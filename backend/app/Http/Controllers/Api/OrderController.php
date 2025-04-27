<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SanP;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Tạo đơn hàng mới từ giỏ hàng
     */
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'ho_ten' => 'required|string|max:255',
                'so_dien_thoai' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'dia_chi' => 'required|string',
                'phuong_thuc_thanh_toan' => 'required|integer|in:1,2',
                'tong_tien' => 'required|numeric',
            ]);

            DB::beginTransaction();

            // Lấy thông tin user nếu đã đăng nhập
            $userId = Auth::id();

            // Lấy session ID từ cookie hoặc header
            $cartSessionId = $request->cookie('cart_session'); // Thử lấy từ cookie
            if (!$cartSessionId) {
                $cartSessionId = $request->header('X-Cart-Session'); // Thử lấy từ header
            }

            // Nếu không có cart_session, lấy session Laravel
            $sessionId = $cartSessionId ?: session()->getId();

            // Debug thông tin session
            \Log::info('Order Creation - Session Info', [
                'cartSessionId' => $cartSessionId,
                'sessionId' => $sessionId,
                'userId' => $userId,
                'all_cookies' => $request->cookie(),
                'headers' => $request->headers->all()
            ]);

            // Truy vấn cart với điều kiện có thể linh hoạt hơn
            $cart = Cart::where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('id_kh', $userId);
                }
                if ($sessionId) {
                    $query->orWhere('session_id', $sessionId);
                }
            })->first();

            // Nếu không tìm thấy cart, trả về lỗi
            if (!$cart) {
                \Log::error('Cart not found', [
                    'userId' => $userId,
                    'sessionId' => $sessionId
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy giỏ hàng',
                    'debug_info' => [
                        'userId' => $userId,
                        'sessionId' => $sessionId
                    ]
                ], 400);
            }

            // Kiểm tra sản phẩm trong giỏ hàng
            $cartItems = CartItem::where('cart_id', $cart->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Giỏ hàng trống, không có sản phẩm nào'
                ], 400);
            }

            // Ghi log thông tin đặt hàng
            \Log::info('Thông tin đặt hàng:', [
                'request' => $request->all(),
                'user_id' => $cart->id_kh,
                'cart_items' => $cartItems->count()
            ]);

            // Tạo đơn hàng mới
            $order = new Order();
            $order->ID_KH = $userId;
            $order->ho_ten = $request->ho_ten;
            $order->so_dien_thoai = $request->so_dien_thoai;
            $order->email = $request->email;
            $order->dia_chi = $request->dia_chi;
            $order->ghi_chu = $request->ghi_chu;
            $order->phuong_thuc_thanh_toan = $request->phuong_thuc_thanh_toan;
            $order->Trang_thai = 1; // Đơn hàng mới
            $order->Tong_tien = $request->tong_tien;
            $order->Ma_don_hang = 'DH' . Str::random(8);
            $order->Ngay_mua = now();

            // Thiết lập trạng thái thanh toán dựa trên phương thức thanh toán
            if ($request->phuong_thuc_thanh_toan == 1) {
                // Thanh toán khi nhận hàng
                $order->trang_thai_thanh_toan = 0; // Chưa thanh toán
            } else if ($request->phuong_thuc_thanh_toan == 2) {
                // Chuyển khoản ngân hàng
                $order->trang_thai_thanh_toan = 1; // Đang chờ xác nhận
            }

            try {
                $order->save();

                // Tạo sản phẩm đầu tiên làm sản phẩm chính của đơn hàng
                if ($cartItems->count() > 0) {
                    $firstItem = $cartItems->first();
                    $mainProduct = SanP::find($firstItem->id_sp);
                    if ($mainProduct) {
                        $order->id_san_pham = $mainProduct->id;
                        $order->ten_san_pham = $mainProduct->Ten_san_pham;
                        $order->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to save order', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // Thêm chi tiết đơn hàng
            foreach ($cartItems as $item) {
                try {
                    $product = SanP::find($item->id_sp);
                    $productName = $product ? $product->Ten_san_pham : 'Unknown Product';

                    $orderItem = new OrderItem();
                    $orderItem->ID_DH = $order->id;
                    $orderItem->user_id = $userId;
                    $orderItem->ID_SP = $item->id_sp;
                    $orderItem->color_id = $item->id_mau;
                    $orderItem->size_id = $item->id_size;
                    $orderItem->So_luong = $item->so_luong;
                    $orderItem->Gia = $item->don_gia;
                    $orderItem->don_gia = $item->don_gia;
                    $orderItem->Thanh_tien = $item->don_gia * $item->so_luong;
                    $orderItem->hinh_anh = $product ? $product->Anh_dai_dien : null;
                    $orderItem->save();

                    \Log::info('Added order item', [
                        'order_id' => $order->id,
                        'product_id' => $item->id_sp,
                        'product_name' => $productName,
                        'quantity' => $item->so_luong,
                        'price' => $item->don_gia
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to add order item', [
                        'error' => $e->getMessage(),
                        'item' => $item,
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // Xử lý phương thức thanh toán
            $paymentUrl = null;

            if ($request->phuong_thuc_thanh_toan == 2) {
                // Xử lý thanh toán qua ngân hàng (chuyển khoản)
                // Trường hợp này có thể chỉ hiển thị thông tin chuyển khoản
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            try {
                CartItem::where('cart_id', $cart->id)->delete();
                \Log::info('Cleared cart items', ['cart_id' => $cart->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to clear cart', [
                    'error' => $e->getMessage(),
                    'cart_id' => $cart->id
                ]);
                // Không throw exception ở đây vì đơn hàng đã được tạo thành công
            }

            DB::commit();

            $responseData = [
                'status' => 'success',
                'message' => 'Đặt hàng thành công',
                'order_id' => $order->id,
                'ma_don_hang' => $order->Ma_don_hang
            ];

            if ($paymentUrl) {
                $responseData['payment_url'] = $paymentUrl;
            }

            // Thêm header để ngăn cache
            return response()->json($responseData, 201)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi đặt hàng: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(),
                'error_details' => env('APP_DEBUG', false) ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString())
                ] : null
            ], 500);
        }
    }

    /**
     * Lấy danh sách đơn hàng của người dùng
     */
    public function getOrdersByUser()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Bạn cần đăng nhập để xem đơn hàng'], 401);
        }

        // Ghi log để debug
        \Log::info('Fetching orders for user', [
            'user_id' => $userId,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        $orders = Order::where('ID_KH', $userId)
                      ->orderBy('created_at', 'desc')
                      ->with([
                        'orderItems.product',
                        'orderItems.color',
                        'orderItems.size'
                      ])
                      ->get();

        // Log số lượng đơn hàng tìm thấy
        \Log::info('Orders found', [
            'count' => $orders->count(),
            'order_ids' => $orders->pluck('id')->toArray()
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
            'timestamp' => now()->timestamp // Thêm timestamp để tránh cache
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function getOrderDetail($id)
    {
        $userId = Auth::id();

        $order = Order::where('id', $id)
                     ->with([
                        'orderItems.product',
                        'orderItems.color',
                        'orderItems.size'
                     ])
                     ->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng'], 404);
        }

        // Kiểm tra quyền truy cập
        if ($userId && $order->ID_KH != $userId) {
            return response()->json(['error' => 'Bạn không có quyền xem đơn hàng này'], 403);
        }

        // Log để debug
        \Log::info('Order details fetched successfully', [
            'order_id' => $id,
            'user_id' => $userId,
            'items_count' => $order->orderItems->count()
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $order,
            'timestamp' => now()->timestamp // Thêm timestamp để tránh cache
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'trang_thai' => 'required|integer|min:1|max:5',
            ]);

            $order = Order::findOrFail($id);
            $order->Trang_thai = $request->trang_thai;

            if ($request->trang_thai == 5) {
                $order->ngay_hoan_thanh = now();
            }

            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Đặt hàng trực tiếp không qua giỏ hàng
     * Được sử dụng khi frontend gặp lỗi với giỏ hàng
     */
    public function directOrder(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'ho_ten' => 'required|string|max:255',
                'so_dien_thoai' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'dia_chi' => 'required|string',
                'phuong_thuc_thanh_toan' => 'required|integer|in:1,2',
                'tong_tien' => 'required|numeric',
                'direct_items' => 'required|array',
                'direct_items.*.ID_SP' => 'required|exists:san_pham,id',
                'direct_items.*.So_luong' => 'required|integer|min:1'
            ]);

            // Log yêu cầu đặt hàng trực tiếp
            \Log::info('Yêu cầu đặt hàng trực tiếp guest', [
                'user_id' => Auth::id(),
                'items_count' => count($request->direct_items),
                'request_data' => $request->except(['direct_items'])
            ]);

            DB::beginTransaction();

            // Lấy thông tin user nếu đã đăng nhập, có thể null nếu chưa đăng nhập
            $userId = Auth::id();

            // Tạo đơn hàng mới
            $order = new Order();
            $order->ID_KH = $userId; // Có thể null
            $order->ho_ten = $request->ho_ten;
            $order->so_dien_thoai = $request->so_dien_thoai;
            $order->email = $request->email;
            $order->dia_chi = $request->dia_chi;
            $order->ghi_chu = $request->ghi_chu ?? '';
            $order->phuong_thuc_thanh_toan = $request->phuong_thuc_thanh_toan;
            $order->Trang_thai = 1; // Đơn hàng mới
            $order->Tong_tien = $request->tong_tien;
            $order->Ma_don_hang = 'DH' . Str::random(8);
            $order->Ngay_mua = now();

            // Thiết lập trạng thái thanh toán dựa trên phương thức thanh toán
            if ($request->phuong_thuc_thanh_toan == 1) {
                // Thanh toán khi nhận hàng
                $order->trang_thai_thanh_toan = 0; // Chưa thanh toán
            } else if ($request->phuong_thuc_thanh_toan == 2) {
                // Chuyển khoản ngân hàng
                $order->trang_thai_thanh_toan = 1; // Đang chờ xác nhận
            }

            $order->save();

            // Thêm chi tiết đơn hàng
            $tongTien = 0;
            foreach ($request->direct_items as $item) {
                $sanPham = SanP::find($item['ID_SP']);
                if (!$sanPham) continue;

                $donGia = $item['don_gia'] ?? $sanPham->Gia ?? 0;
                $thanhTien = $donGia * $item['So_luong'];

                $orderItem = new OrderItem();
                $orderItem->ID_DH = $order->id;
                $orderItem->user_id = $userId;
                $orderItem->ID_SP = $item['ID_SP'];
                $orderItem->color_id = $item['id_mau'] ?? null;
                $orderItem->size_id = $item['id_size'] ?? null;
                $orderItem->So_luong = $item['So_luong'];
                $orderItem->Gia = $donGia;
                $orderItem->Thanh_tien = $thanhTien;
                $orderItem->don_gia = $donGia;
                $orderItem->hinh_anh = $item['hinh_anh'] ?? ($sanPham->Anh_dai_dien ?? null);
                $orderItem->save();

                $tongTien += $thanhTien;
            }

            // Cập nhật tổng tiền nếu có sự khác biệt
            if ($tongTien != $order->Tong_tien) {
                $order->Tong_tien = $tongTien;
                $order->save();
            }

            // Xử lý phương thức thanh toán
            $paymentUrl = null;

            DB::commit();

            $responseData = [
                'status' => 'success',
                'message' => 'Đặt hàng thành công',
                'order_id' => $order->id,
                'ma_don_hang' => $order->Ma_don_hang
            ];

            if ($paymentUrl) {
                $responseData['payment_url'] = $paymentUrl;
            }

            // Thêm header để ngăn cache
            return response()->json($responseData, 201)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi đặt hàng trực tiếp: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
