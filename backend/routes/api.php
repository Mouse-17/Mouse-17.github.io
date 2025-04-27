<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SanController;
use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\LoaiSanController;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/chusan/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/chusan/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

// Test route for orders
Route::get('/test-orders', function () {
    try {
        // Kiểm tra kết nối và đếm số lượng đơn hàng
        $count = DB::table('don_hang')->count();
        $firstFew = DB::table('don_hang')->take(3)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Kết nối đến bảng đơn hàng thành công',
            'orders_count' => $count,
            'first_few' => $firstFew
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi kết nối đến bảng đơn hàng: ' . $e->getMessage()
        ], 500);
    }
});

// Create sample order
Route::get('/create-sample-order', function () {
    try {
        // Tạo đơn hàng mẫu
        $orderId = DB::table('don_hang')->insertGetId([
            'ID_KH' => 1,
            'Ngay_mua' => now(),
            'Tong_tien' => 990000,
            'Trang_thai' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tạo chi tiết đơn hàng mẫu
        $orderDetailId = DB::table('don_hang_chi_tiet')->insertGetId([
            'ID_DH' => $orderId,
            'ID_SP' => 1,
            'So_luong' => 1,
            'Thanh_tien' => 990000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã tạo đơn hàng mẫu thành công',
            'order_id' => $orderId,
            'order_detail_id' => $orderDetailId
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi khi tạo đơn hàng mẫu: ' . $e->getMessage()
        ], 500);
    }
});

// Contact form
Route::post('/contact', [ContactController::class, 'store']);

// Public field routes
Route::get('/fields', [FieldController::class, 'index']);
Route::get('/fields/{id}', [FieldController::class, 'show']);
Route::get('/fields/{id}/available-slots', [FieldController::class, 'availableSlots']);

// Public product routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/thuong-hieu', [ProductController::class, 'brands']);

// Public post routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('/post-categories', [PostController::class, 'categories']);

// Public rating routes
Route::get('/product/{id}/ratings', [RatingController::class, 'getProductRatings']);

// Public booking route - cho phép đặt sân không cần đăng nhập
Route::post('/bookings', [BookingController::class, 'store']);

// Public cart routes
Route::get('/cart', [ApiCartController::class, 'getCart']);
Route::post('/cart/add', [ApiCartController::class, 'addToCart']);
Route::put('/cart/update/{id}', [ApiCartController::class, 'updateCartItem']);
Route::delete('/cart/remove/{id}', [ApiCartController::class, 'removeCartItem']);
Route::delete('/cart/clear', [ApiCartController::class, 'clearCart']);

// Public order route for testing
Route::post('/guest-order', [ApiOrderController::class, 'directOrder']);

Route::apiResource('products', ApiController::class);
Route::get('/san-pham-pho-bien', [ApiController::class, 'productView']);

Route::get('/san-pho-bien', [ApiController::class, 'popularYard']);

Route::get('/sanpham', [ProductController::class, 'showListProduct']);
Route::get('/sanpham/{id}', [ProductController::class, 'showdetail']);
Route::post('/sanpham/boloc', [ProductController::class, 'sortProduct']);

Route::apiResource('san', SanController::class);
Route::get('/san', [SanController::class, 'showListYard']);
Route::get('/san/{id}', [SanController::class, 'show']);
Route::get('/san/{id}/danh-gia', [SanController::class, 'getSanRatings']);

// Thêm route cho API tìm kiếm sân
Route::get('/timkiemsan', [SanController::class, 'timkiemSan']);

// Route kiểm tra màu sắc và kích thước
Route::get('/check-mau-size', function() {
    $mau = \App\Models\MauSac::all();
    $size = \App\Models\Size::all();
    $sp_mau_size = \App\Models\SP_MauSize::with(['mau', 'size'])->get();

    return response()->json([
        'mau' => $mau,
        'size' => $size,
        'sp_mau_size' => $sp_mau_size
    ]);
});

// API endpoints để lấy thông tin màu và size từ ID
Route::get('/mau/{id}', function($id) {
    $mau = \App\Models\MauSac::find($id);
    return response()->json([
        'status' => 'success',
        'data' => $mau
    ]);
});

Route::get('/size/{id}', function($id) {
    $size = \App\Models\Size::find($id);
    return response()->json([
        'status' => 'success',
        'data' => $size
    ]);
});

// Route for bestseller products
Route::get('/bestseller', [ProductController::class, 'bestseller']);

Route::get('/loai-san', [LoaiSanController::class, 'index']);

// Route để lấy khung giờ và giá
Route::get('/khung-gio', [SanController::class, 'getTimeSlots']);

// Debug route để kiểm tra kết nối đến bảng đơn hàng
Route::get('/debug/don-hang', function() {
    try {
        $schema = DB::getSchemaBuilder()->getColumnListing('don_hang');
        $sampleData = DB::table('don_hang')->limit(3)->get();

        return response()->json([
            'success' => true,
            'schema' => $schema,
            'sample_data' => $sampleData,
            'count' => DB::table('don_hang')->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/cart-test', function() {
    return response()->json(['status' => 'success', 'message' => 'API hoạt động']);
});

Route::get('/check-session', function(Request $request) {
    $cartSession = $request->cookie('cart_session') ?? $request->header('X-Cart-Session');
    $laravelSession = session()->getId();

    return response()->json([
        'cart_session' => $cartSession,
        'laravel_session' => $laravelSession,
        'all_cookies' => $_COOKIE,
        'headers' => $request->headers->all()
    ]);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

    // Owner bookings - di chuyển ra khỏi middleware field.owner để dễ debug
    Route::get('/owner/bookings', [BookingController::class, 'ownerBookings']);
    Route::post('/bookings/{id}/update-status', [BookingController::class, 'updateStatus']);

    // Orders
    Route::post('/orders', [ApiOrderController::class, 'store']);
    Route::post('/direct-order', [ApiOrderController::class, 'directOrder']);
    Route::get('/orders', [ApiOrderController::class, 'getOrdersByUser']);
    Route::get('/orders/{id}', [ApiOrderController::class, 'getOrderDetail']);
    Route::put('/orders/{id}/status', [ApiOrderController::class, 'updateOrderStatus']);

    // Comments
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Ratings
    Route::get('/', [RatingController::class, 'index']); // Lấy danh sách đánh giá
    Route::post('/', [RatingController::class, 'store']); // Thêm đánh giá mới
    Route::get('/{id}', [RatingController::class, 'show']); // Lấy chi tiết đánh giá
    Route::put('/{id}', [RatingController::class, 'update']); // Cập nhật đánh giá
    Route::delete('/{id}', [RatingController::class, 'destroy']); // Xóa đánh giá
    Route::put('/{id}/status', [RatingController::class, 'updateStatus']); // Cập nhật trạng thái đánh giá
    Route::get('/product/{productId}', [RatingController::class, 'getProductRatings']); // Lấy đánh giá cho sản phẩm

    // Field Owner routes
    Route::middleware('field.owner')->group(function () {
        Route::post('/fields', [FieldController::class, 'store']);
        Route::put('/fields/{id}', [FieldController::class, 'update']);
        Route::delete('/fields/{id}', [FieldController::class, 'destroy']);
        Route::get('/owner/fields', [FieldController::class, 'ownerFields']);
        // Đã di chuyển 2 routes này ra ngoài middleware để tránh lỗi xác thực
        // Route::get('/owner/bookings', [BookingController::class, 'ownerBookings']);
        // Route::post('/bookings/{id}/update-status', [BookingController::class, 'updateStatus']);
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
        Route::get('/admin/stats', [DashboardController::class, 'stats']);

        // User management
        Route::get('/admin/users', [AuthController::class, 'index']);
        Route::get('/admin/users/{id}', [AuthController::class, 'show']);
        Route::put('/admin/users/{id}', [AuthController::class, 'update']);
        Route::delete('/admin/users/{id}', [AuthController::class, 'destroy']);

        // Posts management
        Route::get('/admin/posts', [PostController::class, 'index']);
        Route::post('/admin/posts', [PostController::class, 'store']);
        Route::put('/admin/posts/{id}', [PostController::class, 'update']);
        Route::delete('/admin/posts/{id}', [PostController::class, 'destroy']);
        Route::post('/admin/post-categories', [PostController::class, 'storeCategory']);
        Route::put('/admin/post-categories/{id}', [PostController::class, 'updateCategory']);
        Route::delete('/admin/post-categories/{id}', [PostController::class, 'destroyCategory']);

        // Products management
        Route::get('/admin/products', [ProductController::class, 'index']);
        Route::post('/admin/products', [ProductController::class, 'store']);
        Route::put('/admin/products/{id}', [ProductController::class, 'update']);
        Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
        Route::get('/admin/categories', [ProductController::class, 'getCategory']);
        Route::post('/admin/categories', [ProductController::class, 'storeCategory']);
        Route::put('/admin/categories/{id}', [ProductController::class, 'updateCategory']);
        Route::delete('/admin/categories/{id}', [ProductController::class, 'destroyCategory']);
        Route::put('/admin/products/{id}/bestseller', [ProductController::class, 'updateBestseller']);
        Route::get('/admin/products/top', [ProductController::class, 'getTopProducts']);

        // Comments management
        Route::get('/admin/comments', [CommentController::class, 'index']);
        Route::put('/admin/comments/{id}/status', [CommentController::class, 'updateStatus']);

        // Ratings management
        Route::get('/admin/ratings', [RatingController::class, 'index']);
        Route::put('/admin/ratings/{id}/status', [RatingController::class, 'updateStatus']);

        // Orders management
        Route::get('/admin/orders', [OrderController::class, 'adminIndex']);
        Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Contacts management
        Route::get('/admin/contacts', [ContactController::class, 'index']);
        Route::put('/admin/contacts/{id}/status', [ContactController::class, 'updateStatus']);
    });

    // Thông báo
    Route::get('/thong-bao', [ThongBaoController::class, 'index']);
    Route::put('/thong-bao/{id}/read', [ThongBaoController::class, 'markAsRead']);
    Route::post('/thong-bao/danh-dau-da-doc/{id}', [ThongBaoController::class, 'markAsRead']);
    Route::post('/thong-bao/danh-dau-tat-ca-da-doc', [ThongBaoController::class, 'markAllAsRead']);
});




