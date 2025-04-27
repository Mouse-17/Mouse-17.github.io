<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\SanP;
use App\Models\MauSac;
use App\Models\Size;

class CartController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:san_pham,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'nullable',
            'size_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Lấy thông tin user nếu đã đăng nhập
        $user_id = null;
        if (Auth::check()) {
            $user_id = Auth::id();
        }

        // Tạo hoặc lấy session_id cho người dùng chưa đăng nhập
        $session_id = $request->cookie('cart_session');
        if (!$session_id) {
            $session_id = Str::uuid()->toString();
            // Cookie sẽ được set trong response
        }

        // Debug info nếu có request debug=true
        $debug_info = [];
        if ($request->has('debug')) {
            $debug_info = [
                'user_id' => $user_id,
                'session_id' => $session_id,
                'all_cookies' => $request->cookie(),
                'headers' => $request->headers->all()
            ];
        }

        try {
            // Tìm hoặc tạo giỏ hàng
            $cart = null;
            if ($user_id) {
                $cart = Cart::where('id_kh', $user_id)->first();
            } else {
                $cart = Cart::where('session_id', $session_id)->first();
            }

            if (!$cart) {
                // Tạo giỏ hàng mới
                $cart = new Cart();
                $cart->id_kh = $user_id;
                $cart->session_id = $user_id ? null : $session_id;
                $cart->save();

                if ($request->has('debug')) {
                    $debug_info['cart_created'] = [
                        'id' => $cart->id,
                        'id_kh' => $cart->id_kh,
                        'session_id' => $cart->session_id
                    ];
                }
            } else if ($request->has('debug')) {
                $debug_info['cart_found'] = [
                    'id' => $cart->id,
                    'id_kh' => $cart->id_kh,
                    'session_id' => $cart->session_id
                ];
            }

            // Lấy thông tin sản phẩm
            $product = SanP::find($request->product_id);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm',
                    'debug' => $debug_info
                ], 404);
            }

            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $cart_item = CartItem::where([
                'cart_id' => $cart->id,
                'id_sp' => $request->product_id,
                'id_mau' => $request->color_id,
                'id_size' => $request->size_id,
            ])->first();

            if ($cart_item) {
                // Cập nhật số lượng nếu sản phẩm đã tồn tại
                $cart_item->so_luong += $request->quantity;
                $cart_item->save();

                if ($request->has('debug')) {
                    $debug_info['cart_item_updated'] = [
                        'id' => $cart_item->id,
                        'quantity' => $cart_item->so_luong
                    ];
                }
            } else {
                // Thêm sản phẩm mới vào giỏ hàng
                $cart_item = new CartItem();
                $cart_item->cart_id = $cart->id;
                $cart_item->id_sp = $request->product_id;
                $cart_item->so_luong = $request->quantity;
                $cart_item->id_mau = $request->color_id;
                $cart_item->id_size = $request->size_id;
                $cart_item->don_gia = $product->Gia;
                $cart_item->save();

                if ($request->has('debug')) {
                    $debug_info['cart_item_created'] = [
                        'id' => $cart_item->id,
                        'product_id' => $cart_item->id_sp,
                        'quantity' => $cart_item->so_luong,
                        'price' => $cart_item->don_gia
                    ];
                }
            }

            // Format dữ liệu giỏ hàng để trả về
            $result = $this->formatCartData($cart);

            $response = response()->json([
                'status' => 'success',
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => $result['total_items'],
                'cart' => $result,
                'debug' => $debug_info
            ]);

            // Thêm cookie nếu cần
            if (!$user_id && (!$request->cookie('cart_session') || $request->cookie('cart_session') !== $session_id)) {
                // Đặt cookie với SameSite=None và Secure=true để hoạt động trên mọi môi trường
                $response->cookie('cart_session', $session_id, 60 * 24 * 30, '/', null, false, false);

                if ($request->has('debug')) {
                    $debug_info['cookie_set'] = [
                        'name' => 'cart_session',
                        'value' => $session_id,
                        'expiry' => '30 days'
                    ];
                }
            }

            return $response;

        } catch (\Exception $e) {
            if ($request->has('debug')) {
                $debug_info['error'] = $e->getMessage();
                $debug_info['trace'] = $e->getTraceAsString();
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'debug' => $debug_info
            ], 500);
        }
    }

    /**
     * Format dữ liệu giỏ hàng
     */
    private function formatCartData($cart)
    {
        // Lấy các sản phẩm trong giỏ hàng với eager loading
        $items = CartItem::with(['product', 'color', 'size'])
            ->where('cart_id', $cart->id)
            ->get();

        $total_price = 0;
        $total_items = 0;

        $formatted_items = $items->map(function ($item) use (&$total_price, &$total_items) {
            $total_price += $item->don_gia * $item->so_luong;
            $total_items += $item->so_luong;

            // Lấy thông tin sản phẩm
            $product = $item->product;
            $product_data = [
                'name' => 'Sản phẩm không tồn tại',
                'image' => null,
                'current_price' => 0,
            ];

            if ($product) {
                $product_data = [
                    'name' => $product->Ten_san_pham,
                    'image' => $product->Anh_dai_dien,
                    'current_price' => (float)$product->Gia,
                ];
            }

            // Lấy thông tin màu sắc
            $color_data = null;
            if ($item->id_mau && $item->color) {
                $color_data = [
                    'id' => $item->id_mau,
                    'name' => $item->color->Ten_mau ?? 'Màu không xác định'
                ];
            }

            // Lấy thông tin kích thước
            $size_data = null;
            if ($item->id_size && $item->size) {
                $size_data = [
                    'id' => $item->id_size,
                    'name' => $item->size->Ten_size ?? 'Size không xác định'
                ];
            }

            return [
                'id' => $item->id,
                'product_id' => $item->id_sp,
                'quantity' => $item->so_luong,
                'price' => (float)$item->don_gia,
                'subtotal' => (float)($item->don_gia * $item->so_luong),
                'product' => $product_data,
                'color' => $color_data,
                'size' => $size_data
            ];
        });

        return [
            'id' => $cart->id,
            'items' => $formatted_items,
            'total_price' => (float)$total_price,
            'total_items' => $total_items
        ];
    }

    /**
     * Lấy danh sách sản phẩm trong giỏ hàng
     */
    public function getCart(Request $request)
    {
        // Lấy thông tin user nếu đã đăng nhập
        $user_id = null;
        if (Auth::check()) {
            $user_id = Auth::id();
        }

        // Lấy session_id từ cookie
        $session_id = $request->cookie('cart_session');

        // Debug info nếu có request debug=true
        $debug_info = [];
        if ($request->has('debug')) {
            $debug_info = [
                'user_id' => $user_id,
                'session_id' => $session_id,
                'all_cookies' => $request->cookie(),
                'headers' => $request->headers->all()
            ];
        }

        try {
            // Tìm giỏ hàng
            $cart = null;
            if ($user_id) {
                $cart = Cart::where('id_kh', $user_id)->first();
                if ($request->has('debug')) {
                    $debug_info['cart_query'] = 'Tìm giỏ hàng theo user_id: ' . $user_id;
                }
            } elseif ($session_id) {
                $cart = Cart::where('session_id', $session_id)->first();
                if ($request->has('debug')) {
                    $debug_info['cart_query'] = 'Tìm giỏ hàng theo session_id: ' . $session_id;
                }
            }

            if (!$cart) {
                if ($request->has('debug')) {
                    $debug_info['cart_status'] = 'Không tìm thấy giỏ hàng';
                    $debug_info['all_carts'] = Cart::all(['id', 'id_kh', 'session_id'])->toArray();
                    $debug_info['all_cart_items'] = CartItem::all(['id', 'cart_id', 'id_sp', 'so_luong'])->toArray();
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng trống',
                    'data' => [
                        'id' => 0,
                        'items' => [],
                        'total_price' => 0,
                        'total_items' => 0
                    ],
                    'debug' => $debug_info
                ]);
            }

            // Format dữ liệu giỏ hàng
            $result = $this->formatCartData($cart);

            if ($request->has('debug')) {
                $debug_info['cart_found'] = [
                    'id' => $cart->id,
                    'id_kh' => $cart->id_kh,
                    'session_id' => $cart->session_id
                ];
                $debug_info['cart_items_count'] = $cart->items()->count();
            }

            return response()->json([
                'status' => 'success',
                'data' => $result,
                'debug' => $debug_info
            ]);

        } catch (\Exception $e) {
            if ($request->has('debug')) {
                $debug_info['error'] = $e->getMessage();
                $debug_info['trace'] = $e->getTraceAsString();
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'debug' => $debug_info
            ], 500);
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCartItem(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cập nhật số lượng
            $cartItem = CartItem::find($request->cart_item_id);
            if (!$cartItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ], 404);
            }

            $cartItem->so_luong = $request->quantity;
            $cartItem->save();

            // Lấy giỏ hàng để format và trả về dữ liệu mới nhất
            $cart = Cart::find($cartItem->cart_id);
            $result = $this->formatCartData($cart);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật số lượng sản phẩm',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeCartItem(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Xóa sản phẩm
            $cartItem = CartItem::find($request->cart_item_id);
            if (!$cartItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ], 404);
            }

            $cart_id = $cartItem->cart_id;
            $cartItem->delete();

            // Lấy giỏ hàng để format và trả về dữ liệu mới nhất
            $cart = Cart::find($cart_id);
            $result = $this->formatCartData($cart);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart(Request $request)
    {
        // Lấy thông tin user nếu đã đăng nhập
        $user_id = null;
        if (Auth::check()) {
            $user_id = Auth::id();
        }

        // Lấy session_id từ cookie
        $session_id = $request->cookie('cart_session');

        try {
            // Tìm giỏ hàng
            $cart = null;
            if ($user_id) {
                $cart = Cart::where('id_kh', $user_id)->first();
            } elseif ($session_id) {
                $cart = Cart::where('session_id', $session_id)->first();
            }

            if (!$cart) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng đã trống',
                    'data' => [
                        'id' => 0,
                        'items' => [],
                        'total_price' => 0,
                        'total_items' => 0
                    ]
                ]);
            }

            // Xóa tất cả sản phẩm trong giỏ hàng
            CartItem::where('cart_id', $cart->id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa toàn bộ giỏ hàng',
                'data' => [
                    'id' => $cart->id,
                    'items' => [],
                    'total_price' => 0,
                    'total_items' => 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
