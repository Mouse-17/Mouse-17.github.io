import { defineStore } from 'pinia';
import axios from 'axios';
import { API_URL } from '../main';

interface CartItem {
  id: number;
  product_id: number;
  quantity: number;
  price: number;
  subtotal: number;
  product: {
    name: string;
    image: string;
    current_price: number;
  };
  color?: {
    id: string | number;
    name: string;
  };
  size?: {
    id: string | number;
    name: string;
  };
}

interface CartData {
  id: number;
  items: CartItem[];
  total_price: number;
  total_items: number;
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    cart: null as CartData | null,
    isLoading: false,
    error: null as string | null,
  }),

  getters: {
    cartCount: (state) => state.cart?.total_items || 0,
    cartTotal: (state) => state.cart?.total_price || 0,
    cartItems: (state) => state.cart?.items || [],
  },

  actions: {
    async loadCart() {
      this.isLoading = true;
      this.error = null;

      try {
        // Đảm bảo cookie cart_session đã được khởi tạo trước
        const cartSessionId = this.initCartSession();

        console.log("Cookie giỏ hàng hiện tại:", {
          cartSessionId,
          allCookies: document.cookie,
        });

        // Gọi API để lấy dữ liệu giỏ hàng
        const response = await fetch(`${API_URL}/api/cart?debug=true`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "X-Cart-Session": cartSessionId
          },
          credentials: "include", // Để gửi cookie session
        });

        const result = await response.json();

        // Debug: Hiển thị kết quả từ API
        console.log("Phản hồi API giỏ hàng:", result);

        if (result.status === "success") {
          // Chuyển đổi dữ liệu từ API sang định dạng cần thiết cho frontend
          const items = (result.data?.items || []).map((item: any) => ({
            id: item.id,
            product_id: item.id_sp,
            quantity: item.so_luong,
            price: item.don_gia,
            subtotal: item.don_gia * item.so_luong,
            product: {
              name: item.ten_sp || "Sản phẩm không tên",
              image: item.hinh_anh || "default.jpg",
              current_price: item.don_gia,
            },
            color: item.ten_mau ? {
              id: item.id_mau,
              name: item.ten_mau
            } : null,
            size: item.ten_size ? {
              id: item.id_size,
              name: item.ten_size
            } : null
          }));

          this.cart = {
            id: result.data.cart_id || 0,
            items: items,
            total_price: result.data.total_amount || 0,
            total_items: result.data.item_count || 0
          };

          // Kiểm tra dữ liệu trả về
          if (items.length > 0) {
            console.log("Số sản phẩm trong giỏ hàng:", items.length);
            console.log("Sản phẩm đầu tiên:", items[0]);
          } else {
            console.log("Giỏ hàng trống hoặc không có items");
          }
        } else {
          this.error = result.message || "Không thể tải giỏ hàng";
        }
      } catch (err) {
        console.error("Lỗi khi tải giỏ hàng:", err);
        this.error = "Lỗi kết nối khi tải giỏ hàng";
      } finally {
        this.isLoading = false;
      }
    },

    async addToCart(productData: any) {
      this.isLoading = true;
      this.error = null;

      try {
        // Đảm bảo có cart_session cookie
        const cartSessionId = this.initCartSession();

        // Lấy token từ localStorage nếu có (người dùng đã đăng nhập)
        const token = localStorage.getItem("auth_token") || 
                      localStorage.getItem("token") || 
                      localStorage.getItem("user_token");

        // Chuẩn bị headers
        const headers: HeadersInit = {
          "Content-Type": "application/json",
          "X-Cart-Session": cartSessionId
        };

        // Thêm token nếu có
        if (token) {
          headers["Authorization"] = `Bearer ${token}`;
        }

        // Gọi API thêm vào giỏ hàng
        const response = await fetch(`${API_URL}/api/cart/add`, {
          method: "POST",
          headers: headers,
          body: JSON.stringify(productData),
          credentials: "include", // Để gửi cookie session
        });

        // Kiểm tra status code
        if (!response.ok) {
          const errorData = await response.json();
          console.error("Lỗi " + response.status + " từ server:", errorData);
          throw new Error("Lỗi máy chủ: " + JSON.stringify(errorData));
        }

        const result = await response.json();

        if (result.status === "success") {
          // Cập nhật giỏ hàng từ kết quả API
          if (result.cart) {
            const items = (result.cart.items || []).map((item: any) => ({
              id: item.id,
              product_id: item.id_sp,
              quantity: item.so_luong,
              price: item.don_gia,
              subtotal: item.don_gia * item.so_luong,
              product: {
                name: item.ten_sp || "Sản phẩm không tên",
                image: item.hinh_anh || "default.jpg",
                current_price: item.don_gia,
              },
              color: item.ten_mau ? {
                id: item.id_mau,
                name: item.ten_mau
              } : null,
              size: item.ten_size ? {
                id: item.id_size,
                name: item.ten_size
              } : null
            }));

            this.cart = {
              id: result.cart.cart_id || 0,
              items: items,
              total_price: result.cart.total_amount || 0,
              total_items: result.cart.item_count || 0
            };
          } else {
            // Nếu API không trả về chi tiết giỏ hàng, tải lại giỏ hàng
            await this.loadCart();
          }
          
          return true;
        } else {
          this.error = result.message || "Có lỗi xảy ra khi thêm vào giỏ hàng";
          return false;
        }
      } catch (err) {
        console.error("Lỗi khi thêm vào giỏ hàng:", err);
        this.error = "Có lỗi xảy ra khi thêm vào giỏ hàng";
        return false;
      } finally {
        this.isLoading = false;
      }
    },

    async updateQuantity(itemId: number, newQuantity: number) {
      this.isLoading = true;
      this.error = null;
      
      try {
        if (newQuantity < 1) {
          newQuantity = 1;
        }
        
        const response = await fetch(`${API_URL}/api/cart/update/${itemId}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            "X-Cart-Session": this.getCookie("cart_session") || ""
          },
          credentials: "include",
          body: JSON.stringify({ quantity: newQuantity }),
        });
        
        const result = await response.json();
        
        if (result.status === "success") {
          // Cập nhật giỏ hàng từ server
          await this.loadCart();
          return true;
        } else {
          this.error = result.message || "Không thể cập nhật giỏ hàng";
          return false;
        }
      } catch (err) {
        console.error("Lỗi khi cập nhật giỏ hàng:", err);
        this.error = "Lỗi kết nối khi cập nhật giỏ hàng";
        return false;
      } finally {
        this.isLoading = false;
      }
    },

    async removeItem(itemId: number) {
      this.isLoading = true;
      this.error = null;
      
      try {
        const response = await fetch(`${API_URL}/api/cart/remove/${itemId}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "X-Cart-Session": this.getCookie("cart_session") || ""
          },
          credentials: "include",
        });
        
        const result = await response.json();
        
        if (result.status === "success") {
          // Tải lại giỏ hàng sau khi xóa
          await this.loadCart();
          return true;
        } else {
          this.error = result.message || "Không thể xóa sản phẩm khỏi giỏ hàng";
          return false;
        }
      } catch (err) {
        console.error("Lỗi khi xóa sản phẩm:", err);
        this.error = "Lỗi kết nối khi xóa sản phẩm";
        return false;
      } finally {
        this.isLoading = false;
      }
    },

    async clearCart() {
      this.isLoading = true;
      this.error = null;
      
      try {
        const response = await fetch(`${API_URL}/api/cart/clear`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "X-Cart-Session": this.getCookie("cart_session") || ""
          },
          credentials: "include",
        });
        
        const result = await response.json();
        
        if (result.status === "success") {
          // Tải lại giỏ hàng sau khi xóa
          await this.loadCart();
          return true;
        } else {
          this.error = result.message || "Không thể xóa giỏ hàng";
          return false;
        }
      } catch (err) {
        console.error("Lỗi khi xóa giỏ hàng:", err);
        this.error = "Lỗi kết nối khi xóa giỏ hàng";
        return false;
      } finally {
        this.isLoading = false;
      }
    },
    
    // Helper function to get cookies
    getCookie(name: string): string | null {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop()?.split(';').shift() || null;
      return null;
    },

    // Tạo cookie giỏ hàng nếu chưa có
    initCartSession(): string {
      const cartSessionCookie = this.getCookie("cart_session");
      if (!cartSessionCookie) {
        // Tạo một session ID mới với timestamp để đảm bảo duy nhất
        const sessionId = "cart_" + Date.now() + "_" + Math.random().toString(36).substring(2, 9);
        document.cookie = `cart_session=${sessionId}; path=/; max-age=2592000; SameSite=Lax; domain=localhost`;
        console.log("Đã tạo cookie mới:", sessionId);
        return sessionId;
      }
      return cartSessionCookie;
    }
  }
}); 