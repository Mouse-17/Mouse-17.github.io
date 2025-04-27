<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        // Log request for debugging
        Log::info('Register request received', ['data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'nullable|string|in:user,field_owner',
        ]);

        if ($validator->fails()) {
            Log::warning('Register validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tạo user object với các trường cơ bản
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => $request->role ?? 'user',
            ];
            
            // Thêm trường status nếu có trong schema
            if (Schema::hasColumn('users', 'status')) {
                $userData['status'] = 'active';
            }
            
            // Chưa đánh dấu email đã xác thực
            $user = User::create($userData);
            
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
            
            try {
                // Gửi OTP xác thực email
                $otp = $this->sendOtpEmail($user);
                
                return response()->json([
                    'message' => 'Đăng ký thành công! Vui lòng kiểm tra email của bạn để xác thực tài khoản.',
                    'user' => $user,
                    'email_verification_required' => true
                ], 201);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email', ['exception' => $e->getMessage()]);
                
                // Nếu không gửi được email, vẫn tạo tài khoản nhưng báo người dùng
                return response()->json([
                    'message' => 'Đăng ký thành công! Tuy nhiên, không thể gửi email xác thực. Vui lòng liên hệ với quản trị viên.',
                    'user' => $user,
                    'email_verification_required' => true,
                    'email_error' => true
                ], 201);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create user', ['exception' => $e->getMessage()]);
            return response()->json([
                'message' => 'Đăng ký thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user and generate token
     */
    public function login(Request $request)
    {
        // Log request for debugging
        Log::info('Login attempt', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Login validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra nếu user tồn tại
        $user = User::where('email', $request->email)->first();
        
        // Nếu không tìm thấy user hoặc mật khẩu không chính xác
        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Failed login attempt: invalid credentials', ['email' => $request->email]);
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác',
                'errors' => [
                    'email' => ['Thông tin đăng nhập không chính xác.']
                ]
            ], 401);
        }
        
        // Kiểm tra trạng thái tài khoản nếu có trường status
        if (Schema::hasColumn('users', 'status') && isset($user->status) && $user->status !== 'active') {
            Log::warning('Login attempt for inactive account', ['email' => $request->email, 'status' => $user->status]);
            return response()->json([
                'message' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.',
                'errors' => [
                    'email' => ['Tài khoản không hoạt động.']
                ]
            ], 403);
        }
        
        // Kiểm tra xem email đã được xác thực chưa
        if (!$user->email_verified_at) {
            Log::warning('Login attempt with unverified email', ['email' => $request->email]);
            
            try {
                // Gửi lại OTP để xác thực email
                $otp = $this->sendOtpEmail($user);
                
                return response()->json([
                    'message' => 'Email chưa được xác thực. Vui lòng kiểm tra email của bạn để lấy mã xác thực.',
                    'email_verified' => false,
                    'email' => $user->email
                ], 403);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP on login', ['email' => $user->email, 'error' => $e->getMessage()]);
                
                return response()->json([
                    'message' => 'Email chưa được xác thực và hệ thống không thể gửi mã xác thực. Vui lòng liên hệ quản trị viên.',
                    'email_verified' => false,
                    'email' => $user->email,
                    'email_error' => true
                ], 403);
            }
        }
        
        // Đăng nhập thành công
        $token = $user->createToken('auth_token')->plainTextToken;
        
        Log::info('Login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Send OTP email for verification
     */
    private function sendOtpEmail(User $user)
    {
        // Generate a 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        // Kiểm tra trường otp có tồn tại trong bảng không
        if (!Schema::hasColumn('users', 'otp') || !Schema::hasColumn('users', 'otp_expires_at')) {
            Log::error('OTP columns do not exist in users table', ['user_id' => $user->id]);
            throw new \Exception('OTP columns not found in database schema');
        }
        
        // Store OTP in user's otp field
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // OTP expires after 10 minutes
        $user->save();
        
        // Chuẩn bị các thông tin gửi mail
        $mailData = [
            'otp' => $otp,
            'user' => $user->name,
            'expiration' => 10 // minutes
        ];
        
        Log::info('Preparing to send OTP email', ['user_id' => $user->id, 'email' => $user->email]);
        
        try {
            // Kiểm tra tệp view có tồn tại không
            if (!view()->exists('emails.otp')) {
                Log::error('OTP email template not found', ['template' => 'emails.otp']);
                throw new \Exception('OTP email template not found');
            }
            
            // Send email with OTP
            Mail::send('emails.otp', ['otp' => $otp], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Mã xác thực tài khoản KeySport');
            });
            
            Log::info('OTP email sent successfully', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'otp' => substr($otp, 0, 2) . '****' // Che một phần OTP trong log để bảo mật
            ]);
        } catch (\Exception $e) {
            // Log error but continue
            Log::error('Failed to send OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw để controller có thể xử lý
            throw $e;
        }
        
        // Return OTP for debugging
        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        // Log request for debugging
        Log::info('OTP verification attempt', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            Log::warning('OTP verification validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('OTP verification for non-existent user', ['email' => $request->email]);
            return response()->json([
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }
        
        // Nếu email đã được xác thực
        if ($user->email_verified_at) {
            Log::info('Email already verified', ['user_id' => $user->id, 'email' => $user->email]);
            
            // Tạo token và trả về để người dùng có thể đăng nhập luôn
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'Email đã được xác thực trước đó',
                'email_verified' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        
        // Check if OTP is expired
        if ($user->otp_expires_at && now()->isAfter($user->otp_expires_at)) {
            Log::warning('OTP verification with expired OTP', ['user_id' => $user->id, 'email' => $user->email]);
            
            // Tạo OTP mới và gửi lại
            try {
                $otp = $this->sendOtpEmail($user);
                
                return response()->json([
                    'message' => 'Mã OTP đã hết hạn. Chúng tôi đã gửi mã mới đến email của bạn.',
                    'otp_expired' => true
                ], 400);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Mã OTP đã hết hạn và không thể gửi mã mới. Vui lòng thử lại sau.',
                    'otp_expired' => true,
                    'email_error' => true
                ], 400);
            }
        }
        
        if ($user->otp !== $request->otp) {
            Log::warning('OTP verification with invalid OTP', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'provided_otp' => substr($request->otp, 0, 1) . '*****'
            ]);
            return response()->json([
                'message' => 'Mã OTP không đúng'
            ], 400);
        }
        
        // Mark email as verified
        $user->email_verified_at = now();
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();
        
        // Tạo token để người dùng có thể đăng nhập ngay
        $token = $user->createToken('auth_token')->plainTextToken;
        
        Log::info('Email verified successfully', ['user_id' => $user->id, 'email' => $user->email]);
        
        return response()->json([
            'message' => 'Xác thực email thành công',
            'email_verified' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Request password reset
     */
    public function forgotPassword(Request $request)
    {
        // Log request for debugging
        Log::info('Forgot password request received', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            Log::warning('Forgot password validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = User::where('email', $request->email)->first();
        
        // Send OTP for password reset
        $otp = $this->sendOtpEmail($user);
        
        Log::info('Password reset OTP sent', ['email' => $user->email, 'otp' => $otp]);
        
        return response()->json([
            'message' => 'Mã xác thực đã được gửi đến email của bạn',
            'email' => $user->email
        ]);
    }

    /**
     * Reset password with OTP
     */
    public function resetPassword(Request $request)
    {
        // Log request for debugging
        Log::info('Reset password request received', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Reset password validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('Reset password attempted for non-existent user', ['email' => $request->email]);
            return response()->json([
                'message' => 'Người dùng không tồn tại'
            ], 404);
        }
        
        // Check if OTP is expired
        if ($user->otp_expires_at && now()->isAfter($user->otp_expires_at)) {
            Log::warning('Reset password attempted with expired OTP', ['email' => $request->email]);
            return response()->json([
                'message' => 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.'
            ], 400);
        }
        
        if ($user->otp !== $request->otp) {
            Log::warning('Reset password attempted with invalid OTP', ['email' => $request->email, 'provided_otp' => $request->otp]);
            return response()->json([
                'message' => 'Mã OTP không đúng'
            ], 400);
        }
        
        // Reset password
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();
        
        Log::info('Password reset successful', ['email' => $user->email]);
        
        return response()->json([
            'message' => 'Đặt lại mật khẩu thành công'
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'district' => 'sometimes|string|max:255',
            'ward' => 'sometimes|string|max:255',
            'shipping_address' => 'sometimes|string|max:255',
            'shipping_phone' => 'sometimes|string|max:15',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->avatar = '/uploads/avatars/' . $filename;
        }
        
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        
        if ($request->has('city')) {
            $user->city = $request->city;
        }
        
        if ($request->has('district')) {
            $user->district = $request->district;
        }
        
        if ($request->has('ward')) {
            $user->ward = $request->ward;
        }
        
        if ($request->has('shipping_address')) {
            $user->shipping_address = $request->shipping_address;
        }
        
        if ($request->has('shipping_phone')) {
            $user->shipping_phone = $request->shipping_phone;
        }
        
        $user->save();
        
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Change user's password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['The provided password does not match our records.']
                ]
            ], 422);
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }
    
    /**
     * Get all users (admin only)
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $users = User::all();
        
        return response()->json($users);
    }
    
    /**
     * Update user (admin only)
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|string|in:admin,field_owner,user',
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Update fields
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        
        if ($request->has('role')) {
            $user->role = $request->role;
        }
        
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        
        $user->save();
        
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
    
    /**
     * Delete user (admin only)
     */
    public function destroy(Request $request, $id)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 422);
        }
        
        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Resend OTP for email verification
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Check if user already verified
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email đã được xác thực trước đó.'
            ], 400);
        }

        // Generate new OTP
        $otp = mt_rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP to email
        try {
            // Send email with OTP
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Mã xác thực tài khoản KeySport');
            });

            return response()->json([
                'message' => 'Mã OTP mới đã được gửi đến email của bạn.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ], 500);
        }
    }
}
