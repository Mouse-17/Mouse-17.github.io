<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\SanPham;
use App\Models\SP_MauSize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Lấy thông tin giỏ hàng hiện tại
     */
    public function getCart()
    {
        try {
            // Lấy thông tin user nếu đã đăng nhập
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Lấy hoặc tạo giỏ hàng
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            // Lấy các sản phẩm trong giỏ hàng
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->join('san_pham', 'cart_items.id_sp', '=', 'san_pham.id')
                ->leftJoin('mau', 'cart_items.id_mau', '=', 'mau.id')
                ->leftJoin('size', 'cart_items.id_size', '=', 'size.id')
                ->select(
                    'cart_items.*',
                    'san_pham.Ten_san_pham as ten_sp',
                    'san_pham.Anh_dai_dien as hinh_anh',
                    'mau.Ten_mau as ten_mau',
                    'size.Ten_size as ten_size'
                )
                ->get();
            
            // Tính tổng tiền
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->don_gia * $item->so_luong;
            });
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'cart_id' => $cart->id,
                    'items' => $cartItems,
                    'total_amount' => $totalAmount,
                    'item_count' => $cartItems->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'id_sp' => 'required|exists:san_pham,id',
                'id_mau' => 'nullable|exists:mau,id',
                'id_size' => 'nullable|exists:size,id',
                'so_luong' => 'required|integer|min:1',
            ]);
            
            // Lấy thông tin user nếu đã đăng nhập
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Log thông tin request
            \Log::info('Adding to cart', [
                'request_data' => $request->all(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            
            // Kiểm tra sản phẩm tồn tại
            $sanPham = SanPham::findOrFail($request->id_sp);
            
            if (!$sanPham) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }
            
            // Kiểm tra Gia_ban có null không và xử lý
            $donGia = 0; // Giá mặc định nếu không tìm thấy
            
            // Nếu trường Gia_ban tồn tại và không null thì sử dụng
            if ($sanPham->Gia_ban !== null) {
                $donGia = $sanPham->Gia_ban;
            } else if ($sanPham->Gia !== null) {
                // Thử sử dụng trường Gia nếu có
                $donGia = $sanPham->Gia;
            } else {
                // Sử dụng giá mặc định là 0 nếu cả hai trường đều null
                $donGia = 0;
            }
            
            // Lấy hoặc tạo giỏ hàng
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            if (!$cart || !$cart->id) {
                \Log::error('Failed to create cart', [
                    'user_id' => $userId,
                    'session_id' => $sessionId
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể tạo giỏ hàng'
                ], 500);
            }
            
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('id_sp', $request->id_sp)
                ->where('id_mau', $request->id_mau)
                ->where('id_size', $request->id_size)
                ->first();
            
            DB::beginTransaction();
            
            try {
                if ($cartItem) {
                    // Nếu đã có, tăng số lượng
                    $cartItem->so_luong += $request->so_luong;
                    $cartItem->save();
                } else {
                    // Nếu chưa có, tạo mới
                    $cartItem = new CartItem();
                    $cartItem->cart_id = $cart->id;
                    $cartItem->id_sp = $request->id_sp;
                    $cartItem->id_mau = $request->id_mau;
                    $cartItem->id_size = $request->id_size;
                    $cartItem->so_luong = $request->so_luong;
                    $cartItem->don_gia = $donGia;
                    $cartItem->save();
                }
                
                DB::commit();
                
                // Lấy thông tin chi tiết sau khi thêm
                $cartDetails = $this->getCartDetails($cart->id);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                    'cart_item' => $cartItem,
                    'cart' => $cartDetails
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage(), [
                'exception' => $e,
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
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCartItem(Request $request, $id)
    {
        try {
            $request->validate([
                'so_luong' => 'required|integer|min:1',
            ]);
            
            // Lấy thông tin user
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Lấy cart hiện tại
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            // Tìm cart item cần cập nhật
            $cartItem = CartItem::where('id', $id)
                ->where('cart_id', $cart->id)
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ], 404);
            }
            
            // Kiểm tra số lượng tồn kho
            if ($cartItem->id_mau && $cartItem->id_size) {
                $spMauSize = SP_MauSize::where('ID_SP', $cartItem->id_sp)
                    ->where('ID_Mau', $cartItem->id_mau)
                    ->where('ID_Size', $cartItem->id_size)
                    ->first();
                
                if (!$spMauSize || $spMauSize->So_luong < $request->so_luong) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sản phẩm không đủ số lượng trong kho'
                    ], 400);
                }
            }
            
            // Cập nhật số lượng
            $cartItem->so_luong = $request->so_luong;
            $cartItem->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật số lượng thành công',
                'cart_item' => $cartItem
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeCartItem($id)
    {
        try {
            // Lấy thông tin user
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Lấy cart hiện tại
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            // Tìm và xóa cart item
            $cartItem = CartItem::where('id', $id)
                ->where('cart_id', $cart->id)
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ], 404);
            }
            
            $cartItem->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart()
    {
        try {
            // Lấy thông tin user
            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Lấy cart hiện tại
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            // Xóa tất cả cart items
            CartItem::where('cart_id', $cart->id)->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa toàn bộ giỏ hàng'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy hoặc tạo giỏ hàng cho user
     */
    private function getOrCreateCart($userId, $sessionId)
    {
        // Lấy cookie cart_session từ request nếu có
        $cartSessionId = request()->cookie('cart_session');
        if (!$cartSessionId) {
            // Thử lấy từ header X-Cart-Session
            $cartSessionId = request()->header('X-Cart-Session');
        }
        
        // Nếu có cart_session từ cookie, sử dụng nó thay vì session ID từ Laravel
        if ($cartSessionId) {
            $sessionId = $cartSessionId;
        }
        
        // Log thông tin để debug
        \Log::info('Cart Session Info', [
            'userId' => $userId,
            'sessionId' => $sessionId,
            'cartSessionId' => $cartSessionId,
            'request_cookies' => request()->cookie(),
            'request_headers' => request()->headers->all()
        ]);
        
        $cart = null;
        
        if ($userId) {
            // Nếu đã đăng nhập, tìm giỏ hàng theo user_id
            $cart = Cart::where('id_kh', $userId)->first();
            
            // Nếu có giỏ hàng theo session, hợp nhất vào giỏ hàng user
            if (!$cart) {
                $sessionCart = Cart::where('session_id', $sessionId)->first();
                
                if ($sessionCart) {
                    // Cập nhật giỏ hàng session thành giỏ hàng user
                    $sessionCart->id_kh = $userId;
                    $sessionCart->save();
                    $cart = $sessionCart;
                }
            }
        } else {
            // Nếu chưa đăng nhập, tìm giỏ hàng theo session
            $cart = Cart::where('session_id', $sessionId)->first();
        }
        
        // Nếu chưa có giỏ hàng, tạo mới
        if (!$cart) {
            $cart = new Cart();
            $cart->id_kh = $userId;
            $cart->session_id = $sessionId;
            $cart->save();
            
            \Log::info('Created new cart', [
                'cart_id' => $cart->id,
                'session_id' => $sessionId,
                'user_id' => $userId
            ]);
        }
        
        // Thêm response header để đảm bảo frontend có thông tin session
        if (!headers_sent()) {
            header('X-Cart-Session: ' . $sessionId);
        }
        
        return $cart;
    }
    
    /**
     * Lấy thông tin chi tiết của giỏ hàng
     */
    private function getCartDetails($cartId)
    {
        $cartItems = CartItem::where('cart_id', $cartId)
            ->join('san_pham', 'cart_items.id_sp', '=', 'san_pham.id')
            ->leftJoin('mau', 'cart_items.id_mau', '=', 'mau.id')
            ->leftJoin('size', 'cart_items.id_size', '=', 'size.id')
            ->select(
                'cart_items.*',
                'san_pham.Ten_san_pham as ten_sp',
                'san_pham.Anh_dai_dien as hinh_anh',
                'mau.Ten_mau as ten_mau',
                'size.Ten_size as ten_size'
            )
            ->get();
        
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->don_gia * $item->so_luong;
        });
        
        return [
            'cart_id' => $cartId,
            'items' => $cartItems,
            'total_amount' => $totalAmount,
            'item_count' => $cartItems->count()
        ];
    }
} 