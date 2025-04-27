<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\San;
use App\Models\KhungGio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for the authenticated user.
     */
    public function index()
    {
        $bookings = Booking::with(['field', 'timeSlot'])
            ->where('id_kh', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_San' => 'required|exists:san,id',
            'Ngay_dat' => 'required|date|after_or_equal:today',
            'Gio_bat_dau' => 'required',
            'Gio_ket_thuc' => 'required',
            'Ten_KH' => 'required|string|max:255',
            'SDT' => 'required|string|max:20',
            'Email' => 'nullable|email|max:255',
            'Tong_tien' => 'required|numeric',
            'Ghi_chu' => 'nullable|string',
            'phuong_thuc_thanh_toan' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thông tin không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Tìm khung giờ phù hợp dựa trên giờ bắt đầu và kết thúc
        $timeSlot = KhungGio::where('Gio_bat_dau', $request->Gio_bat_dau)
                          ->where('Gio_ket_thuc', $request->Gio_ket_thuc)
                          ->first();
        
        if (!$timeSlot) {
            return response()->json([
                'status' => 'error',
                'message' => 'Khung giờ không hợp lệ'
            ], 422);
        }
        
        // Check if the slot is available
        $isSlotBooked = Booking::where('id_san', $request->ID_San)
            ->where('id_kg', $timeSlot->id)
            ->where('Ngay_dat', $request->Ngay_dat)
            ->where('Trang_thai', '!=', 0) // Không tính các đơn đã hủy
            ->exists();
        
        if ($isSlotBooked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Khung giờ này đã được đặt'
            ], 422);
        }
        
        // Create booking
        $booking = new Booking();
        $booking->id_kh = $request->ID_KH ?? null; // Sử dụng ID_KH nếu có, không thì để null
        $booking->id_san = $request->ID_San;
        $booking->id_kg = $timeSlot->id;
        $booking->Ngay_dat = $request->Ngay_dat;
        $booking->Trang_thai = 1; // Trạng thái đang chờ phê duyệt
        $booking->Ten_KH = $request->Ten_KH;
        $booking->SDT = $request->SDT;
        $booking->Email = $request->Email;
        $booking->Ghi_chu = $request->Ghi_chu;
        $booking->Tong_tien = $request->Tong_tien;
        $booking->phuong_thuc_thanh_toan = $request->phuong_thuc_thanh_toan;
        $booking->trang_thai_thanh_toan = 0; // Mặc định là chưa thanh toán
        
        // Nếu thanh toán online và có mã giao dịch
        if ($request->phuong_thuc_thanh_toan == 3 && $request->has('ma_giao_dich')) {
            $booking->ma_giao_dich = $request->ma_giao_dich;
            $booking->thoi_gian_thanh_toan = now();
            $booking->trang_thai_thanh_toan = 2; // Đã thanh toán
        }
        
        $booking->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Đặt sân thành công',
            'booking' => $booking
        ], 201);
    }

    /**
     * Display the specified booking.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['field', 'timeSlot', 'customer'])->findOrFail($id);
        
        // Check if user owns this booking or is admin/field owner
        $user = Auth::user();
        $isAuthorized = $booking->id_kh == $user->id || 
                        $user->role === 'admin' || 
                        ($user->role === 'field_owner' && $booking->field->user_id == $user->id);
        
        if (!$isAuthorized) {
            return response()->json([
                'message' => 'Unauthorized to view this booking'
            ], 403);
        }
        
        return response()->json($booking);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified booking (cancel by user).
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Check if user owns this booking
        if ($booking->id_kh != Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized to update this booking'
            ], 403);
        }
        
        // Users can only cancel their bookings
        $validator = Validator::make($request->all(), [
            'Trang_thai' => 'required|in:0', // 0 = cancelled
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check if booking can be cancelled (not already played)
        if ($booking->Ngay_dat < now()->format('Y-m-d')) {
            return response()->json([
                'message' => 'Cannot cancel past bookings'
            ], 422);
        }
        
        $booking->Trang_thai = 0; // Cancel booking
        $booking->save();
        
        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking
        ]);
    }

    /**
     * Remove the specified booking (only for admins).
     */
    public function destroy(string $id)
    {
        // Only admins can delete bookings completely
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized to delete bookings'
            ], 403);
        }
        
        $booking = Booking::findOrFail($id);
        $booking->delete();
        
        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
    
    /**
     * Get bookings for fields owned by the authenticated user
     */
    public function ownerBookings()
    {
        try {
            // Kiểm tra xác thực người dùng
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập',
                    'bookings' => []
                ], 401);
            }

            // Ghi log để debug
            \Log::info('Owner Bookings API called by: ', [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]);

            // Cho phép cả admin và chủ sân truy cập API này
            if ($user->role !== 'field_owner' && $user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không có quyền truy cập. Chỉ chủ sân mới có thể xem danh sách đặt sân.',
                    'bookings' => []
                ], 403);
            }

            // Nếu là admin, lấy tất cả các đơn đặt sân
            if ($user->role === 'admin') {
                $bookings = Booking::with(['field', 'timeSlot', 'customer'])
                    ->orderBy('Ngay_dat', 'desc')
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'bookings' => $bookings
                ]);
            }

            // Lấy danh sách các sân thuộc quyền sở hữu của người dùng
            $fieldIds = San::where('user_id', $user->id)->pluck('id')->toArray();
            
            \Log::info('Fields owned by user:', [
                'user_id' => $user->id,
                'field_ids' => $fieldIds
            ]);
            
            if (empty($fieldIds)) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Không tìm thấy sân thuộc quyền sở hữu của bạn',
                    'bookings' => []
                ]);
            }
            
            $bookings = Booking::with(['field', 'timeSlot', 'customer'])
                ->whereIn('id_san', $fieldIds)
                ->orderBy('Ngay_dat', 'desc')
                ->get();

            \Log::info('Found bookings for user fields:', [
                'booking_count' => $bookings->count()
            ]);
            
            return response()->json([
                'status' => 'success',
                'bookings' => $bookings
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ownerBookings method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'bookings' => []
            ], 500);
        }
    }
    
    /**
     * Update booking status by field owner
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::with('field')->findOrFail($id);
        
        // Check if user owns the field or is admin
        $user = Auth::user();
        $isAuthorized = $user->role === 'admin' || 
                        ($user->role === 'field_owner' && $booking->field->user_id == $user->id);
        
        if (!$isAuthorized) {
            return response()->json([
                'message' => 'Unauthorized to update this booking status'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'Trang_thai' => 'required|in:0,1,2', // 0 = đã hủy, 1 = đang chờ phê duyệt, 2 = đã phê duyệt
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $booking->Trang_thai = $request->Trang_thai;
        $booking->save();
        
        // Tạo thông báo cho người dùng
        if ($booking->id_kh) {
            $message = '';
            $url = '/booking-details/' . $booking->id;
            
            if ($request->Trang_thai == 2) {
                $message = 'Đơn đặt sân của bạn đã được chấp nhận.';
            } elseif ($request->Trang_thai == 0) {
                $message = 'Đơn đặt sân của bạn đã bị từ chối.';
            }
            
            if ($message) {
                // Thêm thông báo vào bảng thông báo
                \App\Models\ThongBao::create([
                    'user_id' => $booking->id_kh,
                    'tieu_de' => 'Cập nhật trạng thái đặt sân',
                    'noi_dung' => $message,
                    'url' => $url,
                    'loai' => 'booking',
                    'da_xem' => 0
                ]);
            }
        }
        
        return response()->json([
            'message' => 'Booking status updated successfully',
            'booking' => $booking
        ]);
    }

    public function test()
    {
        return response()->json(['status' => 'success', 'message' => 'Test API is working']);
    }
}
