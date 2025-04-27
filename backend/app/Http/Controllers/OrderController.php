<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SanPham;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);
            }

            // Lấy danh sách đơn hàng của người dùng hiện tại
            $orders = Order::with(['orderItems.product', 'orderItems.color', 'orderItems.size'])
                ->where('ID_KH', $user->id)
                ->orderBy('Ngay_mua', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tải danh sách đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để đặt hàng'
            ], 401);
        }

        $user = Auth::user();

        try {
            // Tạo đơn hàng mới
            $order = new Order();
            $order->ID_KH = $user->id;
            $order->Ngay_mua = now();
            $order->Trang_thai = 1; // 1 = Chờ xác nhận
            $order->Tong_tien = 0;
            $order->save();

            $tongTien = 0;

            // Lưu chi tiết đơn hàng
            if (is_array($request->items) && count($request->items) > 0) {
                foreach ($request->items as $item) {
                    $sanPham = SanPham::find($item['product_id']);
                    if (!$sanPham) continue;

                    $orderItem = new OrderItem();
                    $orderItem->ID_DH = $order->id;
                    $orderItem->ID_SP = $item['product_id'];
                    $orderItem->user_id = $user->id;
                    $orderItem->So_luong = $item['quantity'];
                    $orderItem->don_gia = $item['price'];
                    $orderItem->Gia = $item['price'];
                    $orderItem->Thanh_tien = $item['price'] * $item['quantity'];
                    
                    // Lưu thông tin màu và kích thước nếu có
                    if (isset($item['color_id'])) {
                        $orderItem->color_id = $item['color_id'];
                    }
                    
                    if (isset($item['size_id'])) {
                        $orderItem->size_id = $item['size_id'];
                    }
                    
                    // Lưu đường dẫn hình ảnh nếu có
                    if (isset($item['hinh_anh'])) {
                        $orderItem->hinh_anh = $item['hinh_anh'];
                    }
                    
                    $orderItem->save();

                    $tongTien += $orderItem->Thanh_tien;
                }
            }

            // Cập nhật tổng tiền
            $order->Tong_tien = $tongTien;
            $order->save();

            // Xóa giỏ hàng sau khi đặt hàng thành công
            if ($user) {
                $cart = Cart::where('id_kh', $user->id)->first();
                if ($cart) {
                    CartItem::where('cart_id', $cart->id)->delete();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'id' => $order->id,
                    'order_id' => $order->id,
                    'total' => $order->Tong_tien
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);
            }

            // Tìm đơn hàng theo ID
            $order = Order::with(['orderItems.product', 'orderItems.color', 'orderItems.size'])
                ->where(function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->where('ID_KH', $user->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tải chi tiết đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get orders for admin dashboard
     */
    public function adminIndex(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $sort = $request->input('sort', 'created_at:desc');
            list($sortField, $sortDirection) = explode(':', $sort);
            
            $orders = Order::with(['user'])
                ->orderBy($sortField, $sortDirection)
                ->limit($limit)
                ->get()
                ->map(function ($order) {
                    // Mapear para o formato esperado pelo frontend
                    return [
                        'id' => $order->id,
                        'user_id' => $order->ID_KH,
                        'user_name' => $order->user ? $order->user->name : $order->ho_ten,
                        'total_price' => $order->Tong_tien,
                        'status' => $order->Trang_thai,
                        'payment_method' => $order->phuong_thuc_thanh_toan,
                        'payment_status' => $order->trang_thai_thanh_toan,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at
                    ];
                });
                
            return response()->json([
                'status' => 'success',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
