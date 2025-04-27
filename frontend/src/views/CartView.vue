<script lang="ts" setup>
import {computed, onMounted, ref} from "vue";
import {useAuthStore} from "../stores/auth";
import {useCartStore} from "../stores/cart";
import axios from "axios";

// Hàm tiện ích xử lý đường dẫn hình ảnh từ database
const getImageUrl = (imagePath: string): string => {
  // Kiểm tra xem đường dẫn đã có dạng URL đầy đủ chưa
  if (!imagePath) return "";

  // Nếu đường dẫn bắt đầu bằng http hoặc https, trả về nguyên đường dẫn
  if (imagePath.startsWith("http")) {
    return imagePath;
  }

  // Nếu không, thêm tiền tố đường dẫn tới thư mục img_sp
  return `/public/img/img_sp/${imagePath}`;
};

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

const authStore = useAuthStore();
const cartStore = useCartStore();

// Replace local state with store computed properties
const cartData = computed(() => cartStore.cart);
const isLoading = computed(() => cartStore.isLoading);
const error = computed(() => cartStore.error);
const updateLoading = ref<number | null>(null);
const successMessage = ref<string | null>(null);
const showPaymentModal = ref(false);
const paymentProcessing = ref(false);
const paymentSuccess = ref(false);
const paymentError = ref(null);
const paymentMethod = ref("cod"); // Default payment method
const paymentInstructions = ref<string | null>(null);

// Tổng tiền giỏ hàng
const cartTotal = computed(() => {
  return cartStore.cartTotal;
});

// Số lượng sản phẩm
const itemCount = computed(() => {
  return cartStore.cartCount;
});

// Thông tin phương thức thanh toán
interface PaymentMethodInfo {
  id: string;
  value: number;
  name: string;
  description: string;
  icon: string;
  instructions?: string;
}

// Danh sách phương thức thanh toán
const paymentMethods = ref<PaymentMethodInfo[]>([
  {
    id: "cod",
    value: 1,
    name: "Thanh toán khi nhận hàng (COD)",
    description: "Thanh toán bằng tiền mặt khi nhận hàng",
    icon: "bi-cash-coin",
    instructions: "Bạn sẽ thanh toán cho nhân viên giao hàng khi nhận được sản phẩm."
  },
  {
    id: "bank_transfer",
    value: 2,
    name: "Chuyển khoản ngân hàng",
    description: "Thanh toán bằng cách chuyển khoản qua ngân hàng",
    icon: "bi-bank",
    instructions: "Vui lòng chuyển khoản theo thông tin:\n- Ngân hàng: Vietcombank\n- Số tài khoản: 1234567890\n- Tên: CÔNG TY TNHH KEYSPORT\n- Nội dung: Thanh toán đơn hàng [SỐ ĐIỆN THOẠI]"
  }
]);

// Replace loadCart function with call to cart store
const loadCart = async () => {
  await cartStore.loadCart();
};

// Thêm hàm debug để kiểm tra quá trình tải giỏ hàng
const debugCart = () => {
  console.log("Dữ liệu giỏ hàng hiện tại:", cartData.value);
  if (!cartData.value) {
    console.log("Giỏ hàng trống hoặc chưa được tải");
  }
};

// Hiển thị thông báo thành công
const showSuccess = (message: string) => {
  successMessage.value = message;
  setTimeout(() => {
    successMessage.value = null;
  }, 3000);
};

// Hiển thị thông báo lỗi
const showError = (message: string) => {
  error.value = message;
  setTimeout(() => {
    error.value = null;
  }, 5000);
};

// Replace updateQuantity function with call to cart store
const updateQuantity = async (itemId, newQuantity) => {
  // Đảm bảo số lượng không nhỏ hơn 1
  if (newQuantity < 1) {
    newQuantity = 1;
  }

  updateLoading.value = itemId;

  try {
    const result = await cartStore.updateQuantity(itemId, newQuantity);

    if (!result) {
      showError(cartStore.error || "Không thể cập nhật giỏ hàng");
    }
  } catch (err) {
    console.error("Lỗi khi cập nhật giỏ hàng:", err);
    showError("Lỗi kết nối khi cập nhật giỏ hàng");
  } finally {
    updateLoading.value = null;
  }
};

// Replace removeItem function with call to cart store
const removeItem = async (itemId) => {
  if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?")) {
    return;
  }

  updateLoading.value = itemId;

  try {
    const result = await cartStore.removeItem(itemId);

    if (result) {
      showSuccess("Đã xóa sản phẩm khỏi giỏ hàng");
    } else {
      showError(cartStore.error || "Không thể xóa sản phẩm khỏi giỏ hàng");
    }
  } catch (err) {
    console.error("Lỗi khi xóa sản phẩm:", err);
    showError("Lỗi kết nối khi xóa sản phẩm");
  } finally {
    updateLoading.value = null;
  }
};

// Replace clearCart function with call to cart store
const clearCart = async () => {
  if (!confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?")) {
    return;
  }

  updateLoading.value = -1; // Using -1 to indicate "all items"

  try {
    const result = await cartStore.clearCart();

    if (result) {
      showSuccess("Đã xóa toàn bộ giỏ hàng");
    } else {
      showError(cartStore.error || "Không thể xóa giỏ hàng");
    }
  } catch (err) {
    console.error("Lỗi khi xóa giỏ hàng:", err);
    showError("Lỗi kết nối khi xóa giỏ hàng");
  } finally {
    updateLoading.value = null;
  }
};

// Tiến hành thanh toán
const checkout = () => {
  // Kiểm tra đăng nhập bằng authStore
  if (!authStore.isAuthenticated && !authStore.isUserLoggedIn()) {
    // Thông báo lỗi
    error.value = "Vui lòng đăng nhập để thanh toán!";

    // Hiển thị modal thông báo có nút chuyển tới trang đăng nhập
    if (
        confirm("Bạn cần đăng nhập để thanh toán. Chuyển đến trang đăng nhập?")
    ) {
      // Chuyển đến trang đăng nhập
      window.location.href = "/dangnhap";
    }
    return;
  }

  // Nếu đã đăng nhập, hiển thị modal thanh toán
  showPaymentModal.value = true;
};

// Hiển thị hướng dẫn thanh toán
const showPaymentInstructions = (methodId: string) => {
  const method = paymentMethods.value.find(m => m.id === methodId);
  paymentInstructions.value = method?.instructions || null;
};

// Thêm hàm kiểm tra giỏ hàng
const checkCartNotEmpty = () => {
  if (!cartData.value || !cartData.value.items || cartData.value.items.length === 0) {
    error.value = "Giỏ hàng trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.";
    return false;
  }
  return true;
};

// Hàm ánh xạ thông tin người dùng từ user data
const mapUserToOrderInfo = (userData) => {
  console.log("Mapping user data:", userData); // Thêm log để debug

  return {
    ID_KH: userData.id,
    ho_ten: userData.name || "",
    email: userData.email || "",
    so_dien_thoai: userData.phone || "",
    dia_chi: userData.address || "",
    thanh_pho: userData.city || "",
    phuong_xa: userData.ward || userData.district || ""
  };
};

// Thêm hàm xử lý thanh toán
const processPayment = async () => {
  if (paymentProcessing.value) return;

  paymentProcessing.value = true;
  paymentError.value = null;

  try {
    // Kiểm tra giỏ hàng có sản phẩm không
    if (!cartData.value || !cartData.value.items || cartData.value.items.length === 0) {
      paymentError.value = "Giỏ hàng trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.";
      paymentProcessing.value = false;
      return;
    }

    // Kiểm tra số lượng sản phẩm trong giỏ hàng
    console.log("Kiểm tra giỏ hàng:", {
      cartItems: cartData.value?.items,
      itemCount: cartData.value?.items?.length,
      totalPrice: cartTotal.value
    });

    // Reload giỏ hàng để đảm bảo có dữ liệu mới nhất
    console.log("Bắt đầu reload giỏ hàng...");
    await loadCart();

    // Thêm delay ngắn để đảm bảo dữ liệu đã được cập nhật
    await new Promise(resolve => setTimeout(resolve, 500));

    console.log("Kết quả sau khi reload giỏ hàng:", {
      cartItems: cartData.value?.items,
      itemCount: cartData.value?.items?.length
    });

    // Kiểm tra lại sau khi load
    if (!cartData.value || !cartData.value.items || cartData.value.items.length === 0) {
      paymentError.value = "Không thể tải dữ liệu giỏ hàng. Vui lòng tải lại trang và thử lại.";
      paymentProcessing.value = false;
      return;
    }

    // Xác thực giỏ hàng với server
    try {
      await validateCartBeforePayment();
      console.log("Giỏ hàng đã được xác thực với server, tiếp tục thanh toán...");
    } catch (error) {
      console.error("Lỗi xác thực giỏ hàng:", error);
      paymentError.value = "Giỏ hàng không hợp lệ hoặc trống theo server. Vui lòng làm mới trang và thêm sản phẩm lại.";
      paymentProcessing.value = false;

      if (confirm("Giỏ hàng không hợp lệ theo server. Bạn có muốn tải lại trang để cập nhật không?")) {
        window.location.reload();
      }
      return;
    }

    // Lấy thông tin người dùng từ database thông qua authStore
    if (!authStore.user) {
      await authStore.checkAuth();
    }

    if (!authStore.user) {
      paymentError.value = "Không thể tải thông tin người dùng từ server. Vui lòng đăng nhập lại!";
      paymentProcessing.value = false;
      return;
    }

    // Map thông tin người dùng từ authStore
    const userInfo = mapUserToOrderInfo(authStore.user);

    // Kiểm tra thông tin người dùng có đầy đủ không
    if (!userInfo.ID_KH || !userInfo.ho_ten || !userInfo.so_dien_thoai || !userInfo.dia_chi || !userInfo.thanh_pho || !userInfo.phuong_xa) {
      // Log thông tin debug để kiểm tra
      console.log("Thông tin người dùng không đầy đủ:", {
        id: userInfo.ID_KH ? "OK" : "Missing",
        name: userInfo.ho_ten ? "OK" : "Missing",
        phone: userInfo.so_dien_thoai ? "OK" : "Missing",
        address: userInfo.dia_chi ? "OK" : "Missing",
        city: userInfo.thanh_pho ? "OK" : "Missing",
        ward: userInfo.phuong_xa ? "OK" : "Missing",
        userInfoFull: userInfo
      });

      paymentError.value = "Vui lòng cập nhật đầy đủ thông tin giao hàng (bao gồm thành phố và phường/xã) trước khi thanh toán!";
      paymentProcessing.value = false;

      if (confirm("Bạn cần cập nhật đầy đủ thông tin giao hàng bao gồm địa chỉ, thành phố và phường/xã. Chuyển đến trang hồ sơ cá nhân?")) {
        window.location.href = "/profile";
      }
      return;
    }

    // Chuẩn bị dữ liệu đơn hàng
    const orderData = {
      phuong_thuc_thanh_toan: paymentMethod.value === "cod" ? 1 : 2,
      trang_thai_thanh_toan: paymentMethod.value === "cod" ? 1 : 2,
      ID_KH: Number(userInfo.ID_KH),
      ho_ten: userInfo.ho_ten,
      email: userInfo.email,
      so_dien_thoai: userInfo.so_dien_thoai,
      dia_chi: userInfo.dia_chi,
      thanh_pho: userInfo.thanh_pho,
      phuong_xa: userInfo.phuong_xa,
      Ngay_mua: new Date().toISOString().split('T')[0],
      tong_tien: Number(cartTotal.value),
      Trang_thai: 1,
      ghi_chu: "",
      cart_id: cartData.value.id, // Thêm cart_id để backend có thể truy xuất giỏ hàng
      items: cartData.value.items.map((item) => ({
        ID_SP: Number(item.product_id),
        So_luong: Number(item.quantity),
        Thanh_tien: Number(item.subtotal),
        don_gia: Number(item.price),
        hinh_anh: item.product.image
      })),
    };

    // Debug giá trị
    console.log("Debug giá trị tiền:", {
      cartTotal: cartTotal.value,
      numberCartTotal: Number(cartTotal.value),
      totalPriceFromItems: cartData.value.items.reduce((sum, item) => sum + Number(item.subtotal), 0),
      itemsCount: orderData.items.length
    });

    console.log("Dữ liệu đơn hàng:", orderData);

    // Kiểm tra lần cuối
    if (!orderData.items || orderData.items.length === 0) {
      throw new Error("Giỏ hàng trống, không thể tạo đơn hàng.");
    }

    // Debug chi tiết giỏ hàng trước khi gửi API
    console.log("Chi tiết giỏ hàng trước khi gửi API:", {
      cartDataExists: !!cartData.value,
      cartItems: cartData.value?.items,
      cartItemsLength: cartData.value?.items?.length,
      orderItems: orderData.items,
      orderItemsLength: orderData.items.length,
      cartID: cartData.value?.id
    });

    // Gọi API tạo đơn hàng với thử/bắt lỗi chi tiết hơn
    try {
      // Lấy cart_session cookie
      const cartSessionId = getCookie("cart_session") || "";
      console.log("Cart session ID khi thanh toán:", cartSessionId);

      // Thêm thông tin cart_items trực tiếp vào request để đảm bảo server nhận được dữ liệu
      const requestData = {
        ...orderData,
        cart_session: cartSessionId,
        cart_items: cartData.value.items.map(item => ({
          id: item.id,
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.price,
          subtotal: item.subtotal
        })),
        extra_data: {
          session_id: cartSessionId,
          items_from_frontend: cartData.value.items.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
          })),
          force_create_cart: true,
          using_frontend_data: true
        }
      };

      console.log("Dữ liệu gửi API đặt hàng:", JSON.stringify(requestData));

      const response = await axios.post("/api/orders", requestData, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${authStore.token}`,
          "X-Cart-Session": cartSessionId
        },
        withCredentials: true
      });

      // Kiểm tra response status trước khi parse JSON
      if (response.status !== 200) {
        const errorText = await response.text();
        console.error(`Lỗi HTTP ${response.status}:`, errorText);

        // Thử parse JSON nếu có thể
        try {
          const errorJson = JSON.parse(errorText);

          // Nếu lỗi là "Giỏ hàng trống", thử tạo lại giỏ hàng và gửi lại
          if (errorJson.error && (errorJson.error.includes("trống") || errorJson.error.includes("empty"))) {
            console.log("Phát hiện lỗi giỏ hàng trống, thử làm mới session và gửi lại...");

            // Lưu lại dữ liệu sản phẩm từ frontend
            const currentItems = JSON.parse(JSON.stringify(cartData.value?.items || []));

            // Làm mới giỏ hàng
            if (await createEmptyCart()) {
              console.log("Đã làm mới session giỏ hàng");

              // Nếu sau khi tạo giỏ hàng mới nhưng vẫn có sản phẩm từ trước
              if (currentItems.length > 0) {
                console.log("Dữ liệu sản phẩm trước khi làm mới:", currentItems);

                // Thêm sản phẩm vào giỏ hàng mới thủ công
                for (const item of currentItems) {
                  try {
                    console.log(`Thêm sản phẩm ${item.product_id} vào giỏ hàng mới...`);
                    const addResponse = await axios.post(
                        "/api/cart/add",
                        {
                          product_id: item.product_id,
                          quantity: item.quantity,
                          color_id: item.color?.id,
                          size_id: item.size?.id
                        },
                        {
                          headers: {
                            "Content-Type": "application/json",
                            "X-Cart-Session": getCookie("cart_session") || ""
                          },
                          withCredentials: true
                        }
                    );
                    await addResponse.json();
                  } catch (e) {
                    console.error("Lỗi khi thêm sản phẩm vào giỏ hàng mới:", e);
                  }
                }

                // Tải lại giỏ hàng sau khi thêm xong
                await loadCart();

                // Thử thanh toán lại
                if (cartData.value?.items?.length > 0) {
                  console.log("Giỏ hàng mới đã có sản phẩm, thử thanh toán lại...");
                  // Chờ 1s để đảm bảo server đã cập nhật
                  await new Promise(resolve => setTimeout(resolve, 1000));
                  return processPayment(); // Gọi lại hàm processPayment một lần nữa
                }
              }

              // Nếu tất cả phương pháp trên thất bại, thử đặt hàng trực tiếp không qua giỏ hàng
              if (currentItems.length > 0) {
                console.log("Thử đặt hàng trực tiếp không qua giỏ hàng...");

                // Chuẩn bị dữ liệu đơn hàng mới
                const directOrderData = {
                  phuong_thuc_thanh_toan: paymentMethod.value === "cod" ? 1 : 2,
                  trang_thai_thanh_toan: paymentMethod.value === "cod" ? 1 : 2,
                  ID_KH: Number(userInfo.ID_KH),
                  ho_ten: userInfo.ho_ten,
                  email: userInfo.email,
                  so_dien_thoai: userInfo.so_dien_thoai,
                  dia_chi: userInfo.dia_chi,
                  thanh_pho: userInfo.thanh_pho,
                  phuong_xa: userInfo.phuong_xa,
                  Ngay_mua: new Date().toISOString().split('T')[0],
                  tong_tien: currentItems.reduce((sum, item) => sum + item.quantity * item.price, 0),
                  Trang_thai: 1,
                  ghi_chu: "Đặt hàng trực tiếp không qua giỏ hàng do lỗi session",
                  bypass_cart: true,
                  direct_items: currentItems.map((item) => ({
                    ID_SP: Number(item.product_id),
                    So_luong: Number(item.quantity),
                    Thanh_tien: Number(item.subtotal),
                    don_gia: Number(item.price),
                    hinh_anh: item.product.image
                  })),
                };

                try {
                  const directResponse = await axios.post(
                      "/api/direct-order",
                      directOrderData,
                      {
                        headers: {
                          "Content-Type": "application/json",
                          "Authorization": `Bearer ${authStore.token}`
                        },
                        withCredentials: true
                      }
                  );

                  if (response.status !== 200) {
                    const directResult = await directResponse.data;
                    console.log("Kết quả đặt hàng trực tiếp:", directResult);

                    if (directResult.status === "success") {
                      // Hiển thị thông báo thành công
                      paymentSuccess.value = true;

                      // Xử lý thành công
                      setTimeout(() => {
                        // Đóng modal
                        showPaymentModal.value = false;

                        // Xóa giỏ hàng 
                        cartData.value = {
                          id: 0,
                          items: [],
                          total_price: 0,
                          total_items: 0,
                        };

                        // Hiển thị thông báo thành công
                        showSuccess("Đặt hàng thành công! Đang chuyển đến trang đơn hàng...");

                        // Chuyển đến trang đơn hàng
                        setTimeout(() => {
                          window.location.href = `/don-hang?status=success&payment=${paymentMethod.value}`;
                        }, 1500);
                      }, 2000);

                      return; // Thoát khỏi hàm xử lý
                    }
                  }
                } catch (directError) {
                  console.error("Lỗi khi đặt hàng trực tiếp:", directError);
                }
              }
            }
          }

          throw new Error(`Lỗi server: ${response.status} - ${JSON.stringify(errorJson)}`);
        } catch (parseError) {
          // Nếu không parse được, hiển thị text gốc
          throw new Error(`Lỗi server: ${response.status} - ${errorText}`);
        }
      }

      // Xử lý phản hồi từ API
      const result = await response.json();
      console.log("Kết quả tạo đơn hàng:", result);

      if (result.status !== "success") {
        throw new Error(result.message || "Không thể tạo đơn hàng");
      }

      // Lấy ID đơn hàng từ kết quả API
      const orderId = result.data?.id || result.order_id;

      if (!orderId) {
        throw new Error("Không nhận được ID đơn hàng từ server");
      }

      // Nếu thanh toán bằng chuyển khoản, hiển thị thông tin chuyển khoản
      if (paymentMethod.value === "bank_transfer") {
        await handleBankTransfer(orderId);
      }

      // Hiển thị thông báo thành công
      paymentSuccess.value = true;

      // Reset giỏ hàng sau khi thanh toán thành công
      setTimeout(() => {
        // Đóng modal
        showPaymentModal.value = false;

        // Xóa giỏ hàng
        cartData.value = {
          id: 0,
          items: [],
          total_price: 0,
          total_items: 0,
        };

        // Hiển thị thông báo thành công
        showSuccess("Đặt hàng thành công! Đang chuyển đến trang đơn hàng...");

        // Reset trạng thái thanh toán và chuyển hướng đến trang đơn hàng
        setTimeout(() => {
          paymentSuccess.value = false;
          paymentProcessing.value = false;
          paymentInstructions.value = null;

          // Tạo URL đến trang đơn hàng với các tham số cần thiết
          const orderPageUrl = `/don-hang?order_id=${orderId}&status=success&payment=${paymentMethod.value}`;

          // Thông báo chuyển hướng
          console.log("Chuyển hướng đến trang đơn hàng:", orderPageUrl);

          // Chuyển hướng đến trang đơn hàng
          window.location.href = orderPageUrl;
        }, 1500);
      }, 2000);
    } catch (apiError) {
      console.error("Lỗi API đơn hàng:", apiError);
      throw apiError; // Chuyển tiếp lỗi ra ngoài để xử lý
    }
  } catch (err) {
    console.error("Lỗi khi xử lý thanh toán:", err);
    paymentError.value = typeof err === 'object' && err.message
        ? `Có lỗi xảy ra: ${err.message}`
        : "Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại.";
    paymentProcessing.value = false;
  }
};

// Xử lý thanh toán chuyển khoản ngân hàng
const handleBankTransfer = async (orderId: number | string) => {
  // Hiển thị hướng dẫn chuyển khoản
  showPaymentInstructions("bank_transfer");

  // Cập nhật nội dung hướng dẫn với thông tin đơn hàng cụ thể
  const method = paymentMethods.value.find(m => m.id === "bank_transfer");
  if (method?.instructions) {
    paymentInstructions.value = method.instructions.replace("[SỐ ĐIỆN THOẠI]", "ĐH" + orderId);
  }

  return new Promise(resolve => setTimeout(resolve, 1000));
};

// Helper function to get cookies
const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
  return null;
};

// Tạo cookie giỏ hàng nếu chưa có
const initCartSession = () => {
  const cartSessionCookie = getCookie("cart_session");
  if (!cartSessionCookie) {
    // Tạo một session ID mới với timestamp để đảm bảo duy nhất
    const sessionId = "cart_" + Date.now() + "_" + Math.random().toString(36).substring(2, 9);
    document.cookie = `cart_session=${sessionId}; path=/; max-age=2592000; SameSite=Lax; domain=localhost`;
    console.log("Đã tạo cookie mới:", sessionId);
    return sessionId;
  }
  return cartSessionCookie;
};

// Hàm lấy giá trị số của phương thức thanh toán
const getPaymentMethodValue = (methodId: string): number => {
  const method = paymentMethods.value.find(m => m.id === methodId);
  return method ? method.value : 1; // Mặc định là 1 (COD) nếu không tìm thấy
};

// Thêm hàm để xác thực giỏ hàng từ server
const validateCartBeforePayment = async () => {
  try {
    console.log("Kiểm tra giỏ hàng trước khi thanh toán...");
    const cartSessionId = getCookie("cart_session") || "";
    console.log("Session cookie hiện tại:", cartSessionId);

    // Gọi API cart thông thường để kiểm tra giỏ hàng
    const response = await axios.get("/api/cart?debug=true", {
      headers: {
        "Content-Type": "application/json",
        "X-Cart-Session": cartSessionId
      },
      withCredentials: true
    });

    if (response.status !== 200) {
      throw new Error(`Lỗi khi kiểm tra giỏ hàng: ${response.status}`);
    }

    const result = await response.data;
    console.log("Kết quả kiểm tra giỏ hàng:", result);

    // Kiểm tra xem giỏ hàng có trống không
    if (result.status !== "success" || !result.data || !result.data.items || result.data.items.length === 0) {
      throw new Error("Giỏ hàng trống hoặc không hợp lệ theo server");
    }

    // Cập nhật lại dữ liệu giỏ hàng từ server
    const items = (result.data?.items || []).map((item) => ({
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

    cartData.value = {
      id: result.data.cart_id || 0,
      items: items,
      total_price: result.data.total_amount || 0,
      total_items: result.data.item_count || 0
    };

    return true;
  } catch (error) {
    console.error("Lỗi khi xác thực giỏ hàng:", error);
    throw error;
  }
};

// Hàm làm mới giỏ hàng (giải quyết vấn đề giỏ hàng trống)
const createEmptyCart = async () => {
  try {
    console.log("Làm mới session giỏ hàng...");

    // 1. Xóa cookie cart_session cũ
    document.cookie = "cart_session=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT; domain=localhost;";

    // 2. Tạo cookie mới
    const newSessionId = "cart_" + Date.now() + "_" + Math.random().toString(36).substring(2, 9);
    document.cookie = `cart_session=${newSessionId}; path=/; max-age=2592000; SameSite=Lax; domain=localhost`;
    console.log("Đã tạo cookie cart_session mới:", newSessionId);

    // 3. Ngủ 500ms để đảm bảo cookie được áp dụng
    await new Promise(resolve => setTimeout(resolve, 500));

    // 4. Tải lại giỏ hàng
    await loadCart();

    return true;
  } catch (error) {
    console.error("Lỗi khi làm mới giỏ hàng:", error);
    return false;
  }
};

onMounted(async () => {
  // Kiểm tra xem có phải vừa được chuyển hướng từ trang sản phẩm không
  const urlParams = new URLSearchParams(window.location.search);
  const fromProduct = urlParams.get("from_product");
  const addedProduct = urlParams.get("added");

  if (fromProduct && addedProduct) {
    // Nếu vừa được chuyển từ trang sản phẩm, hiển thị thông báo thành công
    showSuccess("Đã thêm sản phẩm vào giỏ hàng");

    // Xóa tham số query khỏi URL để tránh hiển thị lại thông báo khi refresh
    const url = new URL(window.location.href);
    url.searchParams.delete("from_product");
    url.searchParams.delete("added");
    window.history.replaceState({}, document.title, url.pathname);
  }

  // Tải giỏ hàng
  await cartStore.loadCart();
});

// Biến để hiển thị nút debug
const showDebugButton = ref(false);
</script>

<template>
  <div class="cart-container py-5">
    <h1 class="cart-title mb-4">Giỏ hàng của bạn</h1>

    <!-- Thông báo thành công -->
    <div
        v-if="successMessage"
        class="alert alert-success toast-notification toast-success"
    >
      <div class="toast-icon">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div class="toast-content">
        <p class="toast-message">{{ successMessage }}</p>
      </div>
      <div class="toast-close" @click="successMessage = null">
        <i class="bi bi-x"></i>
      </div>
      <div class="toast-progress-bar"></div>
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="error" class="alert alert-danger toast-notification toast-error">
      <div class="toast-icon">
        <i class="bi bi-exclamation-circle-fill"></i>
      </div>
      <div class="toast-content">
        <h4 class="toast-title">Lỗi</h4>
        <p class="toast-message">{{ error }}</p>
      </div>
      <div class="toast-close" @click="error = null">
        <i class="bi bi-x"></i>
      </div>
      <div class="toast-progress-bar"></div>
    </div>

    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Đang tải...</span>
      </div>
    </div>

    <div
        v-else-if="!cartData || cartData.items.length === 0"
        class="empty-cart text-center py-5"
    >
      <div class="empty-cart-icon mb-4">
        <i class="bi bi-cart-x"></i>
      </div>
      <p class="empty-cart-text">Giỏ hàng của bạn đang trống</p>
      <a class="btn-booknow px-4 py-3 fs-4 mt-3" href="/">Tiếp tục mua sắm</a>

      <!-- Nút tạo giỏ hàng thử nghiệm cho mục đích debug -->
      <div v-if="showDebugButton" class="mt-4 p-3 border rounded bg-light">
        <p class="small text-muted">
          Không thấy dữ liệu giỏ hàng từ database? Thử tạo giỏ hàng mới với sản
          phẩm mẫu:
        </p>
        <button
            class="btn btn-sm btn-outline-secondary"
            @click="createEmptyCart"
        >
          <i class="bi bi-tools me-1"></i>
          Tạo giỏ hàng thử nghiệm
        </button>
      </div>
    </div>

    <div v-else class="cart-content">
      <div class="cart-items">
        <div v-for="item in cartData.items" :key="item.id" class="cart-item">
          <div class="cart-item-details">
            <div class="cart-item-image-container">
              <img
                  :alt="item.product.name"
                  :src="getImageUrl(item.product.image)"
                  class="cart-item-image"
              />
            </div>
            <div class="cart-item-info">
              <h5 class="cart-item-name">{{ item.product.name }}</h5>
              <div class="cart-item-variants">
                <span v-if="item.color" class="variant-tag">{{ item.color.name }}</span>
                <span v-if="item.size" class="variant-tag">Size {{ item.size.name }}</span>
              </div>
              <div class="cart-item-price">
                {{ item.price.toLocaleString("vi-VN") }}đ
              </div>
            </div>
          </div>

          <div class="cart-item-actions">
            <div class="quantity-control">
              <button
                  :disabled="updateLoading === item.id"
                  class="quantity-btn"
                  @click="updateQuantity(item.id, item.quantity - 1)"
              >
                -
              </button>
              <input
                  v-model.number="item.quantity"
                  :disabled="updateLoading === item.id"
                  class="quantity-input"
                  min="1"
                  type="number"
                  @change="updateQuantity(item.id, item.quantity)"
              />
              <button
                  :disabled="updateLoading === item.id"
                  class="quantity-btn"
                  @click="updateQuantity(item.id, item.quantity + 1)"
              >
                +
              </button>
            </div>
            <div v-if="updateLoading === item.id" class="text-center mt-2">
              <div
                  class="spinner-border spinner-border-sm text-primary"
                  role="status"
              >
                <span class="visually-hidden">Đang cập nhật...</span>
              </div>
            </div>

            <div class="cart-item-subtotal">
              {{ item.subtotal.toLocaleString("vi-VN") }}đ
            </div>

            <button
                :disabled="updateLoading === item.id"
                class="btn-remove"
                @click="removeItem(item.id)"
            >
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="cart-footer">
        <div class="cart-actions">
          <button
              :disabled="isLoading"
              class="btn-outline"
              @click="clearCart"
          >
            <i class="bi bi-trash me-2"></i>Xóa giỏ hàng
          </button>
          <a class="btn-outline" href="/">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
          </a>
        </div>
        <div class="cart-summary">
          <div class="cart-summary-row">
            <span>Số lượng sản phẩm:</span>
            <span>{{ itemCount }}</span>
          </div>
          <div class="cart-summary-row cart-summary-total">
            <span>Tổng tiền:</span>
            <span>{{ cartTotal.toLocaleString("vi-VN") }}đ</span>
          </div>
          <button class="btn-booknow px-4 py-3 fs-4 w-100" @click="checkout">
            <i class="bi bi-credit-card me-2"></i>Thanh toán
          </button>
        </div>
      </div>
    </div>

    <!-- Modal thanh toán -->
    <div v-if="showPaymentModal" class="payment-modal-overlay">
      <div class="payment-modal">
        <div class="payment-modal-header">
          <h3 class="payment-title">Xác nhận thanh toán</h3>
          <button
              :disabled="paymentProcessing"
              class="close-btn"
              @click="showPaymentModal = false"
          >
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <div v-if="paymentSuccess" class="payment-success">
          <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <h4>Thanh toán thành công!</h4>
          <p>Cảm ơn bạn đã mua sắm tại KeySport.</p>
          <p>Đơn hàng của bạn đã được xác nhận.</p>
          <p class="mt-3">Đang chuyển đến trang đơn hàng...</p>
          <div class="loading-spinner mt-2">
            <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Đang chuyển hướng...</span>
            </div>
          </div>
        </div>

        <div v-else class="payment-modal-content">
          <div class="order-summary">
            <h4>Thông tin đơn hàng</h4>

            <div class="order-items">
              <div
                  v-for="item in cartData?.items"
                  :key="item.id"
                  class="order-item"
              >
                <div class="item-image">
                  <img
                      :alt="item.product.name"
                      :src="getImageUrl(item.product.image)"
                  />
                </div>
                <div class="item-details">
                  <h5>{{ item.product.name }}</h5>
                  <div class="item-variants">
                    <span v-if="item.color" class="variant-tag">Màu: {{ item.color.name }}</span>
                    <span v-if="item.size" class="variant-tag">Size: {{ item.size.name }}</span>
                  </div>
                  <div class="item-quantity-price">
                    <span>SL: {{ item.quantity }}</span>
                    <span>{{ item.price.toLocaleString("vi-VN") }}đ</span>
                  </div>
                </div>
                <div class="item-subtotal">
                  {{ item.subtotal.toLocaleString("vi-VN") }}đ
                </div>
              </div>
            </div>

            <div class="order-total">
              <div class="total-row">
                <span>Tổng tiền sản phẩm:</span>
                <span>{{ cartTotal.toLocaleString("vi-VN") }}đ</span>
              </div>
              <div class="total-row">
                <span>Phí vận chuyển:</span>
                <span>0đ</span>
              </div>
              <div class="total-row grand-total">
                <span>Tổng thanh toán:</span>
                <span>{{ cartTotal.toLocaleString("vi-VN") }}đ</span>
              </div>
            </div>
          </div>

          <div class="payment-methods">
            <h4>Phương thức thanh toán</h4>

            <div class="payment-options">
              <label
                  v-for="method in paymentMethods"
                  :key="method.id"
                  :class="{ 'selected': paymentMethod === method.id }"
                  class="payment-option"
              >
                <input
                    v-model="paymentMethod"
                    :value="method.id"
                    type="radio"
                    @change="showPaymentInstructions(method.id)"
                />
                <span class="radio-custom"></span>
                <div class="option-content">
                  <i :class="'bi ' + method.icon"></i>
                  <div>
                    <h5>{{ method.name }}</h5>
                    <p>{{ method.description }}</p>
                  </div>
                </div>
              </label>
            </div>

            <!-- Hướng dẫn thanh toán -->
            <div v-if="paymentInstructions" class="payment-instructions">
              <h5>Hướng dẫn thanh toán</h5>
              <p v-for="(line, index) in paymentInstructions.split('\n')" :key="index">
                {{ line }}
              </p>
            </div>
          </div>

          <div v-if="paymentError" class="payment-error">
            <i class="bi bi-exclamation-circle"></i>
            {{ paymentError }}
          </div>

          <div class="payment-actions">
            <button
                :disabled="paymentProcessing"
                class="btn-outline"
                @click="showPaymentModal = false"
            >
              Hủy
            </button>
            <button
                :disabled="paymentProcessing"
                class="btn-booknow px-4 py-3"
                @click="processPayment"
            >
              <span v-if="paymentProcessing">
                <i class="spinner bi bi-arrow-repeat"></i> Đang xử lý...
              </span>
              <span v-else>
                <i class="bi bi-credit-card-fill"></i> Thanh toán
                {{ cartTotal.toLocaleString("vi-VN") }}đ
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cart-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.cart-title {
  font-size: 2.4rem;
  color: var(--colortext1);
  font-weight: 600;
  border-bottom: 2px solid var(--colorgrey);
  padding-bottom: 15px;
  margin-bottom: 30px;
}

.empty-cart-icon {
  font-size: 5rem;
  color: var(--colorgrey);
}

.empty-cart-text {
  font-size: 1.8rem;
  color: var(--colortext2);
  margin-bottom: 20px;
}

.cart-items {
  margin-bottom: 40px;
}

.cart-item {
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
  margin-bottom: 20px;
  transition: transform 0.2s, box-shadow 0.2s;
}

.cart-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.cart-item-details {
  display: flex;
  margin-bottom: 15px;
}

.cart-item-image-container {
  width: 120px;
  height: 120px;
  flex-shrink: 0;
  margin-right: 20px;
  border-radius: 8px;
  overflow: hidden;
}

.cart-item-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.5s ease;
}

.cart-item-image-container:hover .cart-item-image {
  transform: scale(1.1);
}

.cart-item-info {
  flex-grow: 1;
}

.cart-item-name {
  font-size: 1.8rem;
  color: var(--colortext1);
  margin-bottom: 10px;
  font-weight: 500;
}

.cart-item-variants {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 10px;
}

.variant-tag {
  background-color: #f0f0f0;
  font-size: 1.2rem;
  padding: 3px 8px;
  border-radius: 4px;
  color: var(--colortext2);
  border: 1px solid #e0e0e0;
}

.cart-item-price {
  font-size: 1.6rem;
  color: var(--colortext1);
  font-weight: 500;
  margin-top: 8px;
}

.cart-item-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--colorgrey);
  padding-top: 15px;
}

.quantity-control {
  display: flex;
  align-items: center;
}

.quantity-btn {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f8f8;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.4rem;
  transition: background-color 0.2s;
}

.quantity-btn:hover {
  background-color: var(--colorgrey);
}

.quantity-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.quantity-input {
  width: 50px;
  height: 32px;
  text-align: center;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  margin: 0 8px;
  font-size: 1.4rem;
}

.cart-item-subtotal {
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--accent);
}

.btn-remove {
  background: none;
  border: none;
  color: #999;
  cursor: pointer;
  font-size: 1.6rem;
  padding: 8px;
  border-radius: 50%;
  transition: all 0.2s;
}

.btn-remove:hover {
  color: #d9534f;
  background-color: #f8f8f8;
}

.btn-remove:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cart-footer {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-actions {
  display: flex;
  gap: 15px;
}

.btn-outline {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 12px 24px;
  border-radius: 8px;
  cursor: pointer;
  text-decoration: none;
  font-size: 1.4rem;
  transition: all 0.3s ease;
  border: 1px solid var(--accent);
  background-color: transparent;
  color: var(--accent);
  font-weight: 500;
}

.btn-outline:hover {
  background-color: rgba(202, 156, 39, 0.1);
}

.btn-booknow {
  background-color: var(--accent) !important;
  color: white !important;
  border: none !important;
  border-radius: 10px !important;
  text-align: center !important;
  font-weight: 500 !important;
  transition: all 0.3s ease !important;
  text-decoration: none !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.btn-booknow:hover {
  background-color: var(--hover2) !important;
}

.cart-summary {
  background-color: #f9f9f9;
  border-radius: 10px;
  padding: 25px;
  border: 1px solid var(--colorgrey);
}

.cart-summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  font-size: 1.5rem;
  color: var(--colortext2);
}

.cart-summary-total {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--colortext1);
  border-top: 1px solid var(--colorgrey);
  padding-top: 15px;
  margin-top: 15px;
  margin-bottom: 20px;
}

.cart-summary-total span:last-child {
  color: var(--accent);
}

/* Toast notification styles */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 350px;
  min-height: 80px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  display: flex;
  padding: 15px;
  z-index: 9999;
  animation: slideIn 0.3s ease-out forwards;
  overflow: hidden;
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-success {
  border-left: 5px solid #34a853;
}

.toast-error {
  border-left: 5px solid #ea4335;
}

.toast-icon {
  width: 32px;
  height: 32px;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
}

.toast-success .toast-icon {
  color: #34a853;
}

.toast-error .toast-icon {
  color: #ea4335;
}

.toast-content {
  flex: 1;
  padding-right: 10px;
}

.toast-title {
  margin: 0 0 5px 0;
  font-size: 1.6rem;
  font-weight: 600;
  color: #333;
}

.toast-message {
  margin: 0;
  font-size: 1.4rem;
  color: #666;
}

.toast-close {
  width: 24px;
  height: 24px;
  font-size: 1.6rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #999;
  cursor: pointer;
  transition: color 0.2s;
}

.toast-close:hover {
  color: #333;
}

.toast-progress-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 100%;
  background-color: #f0f0f0;
}

.toast-success .toast-progress-bar::before {
  content: "";
  position: absolute;
  height: 100%;
  width: 100%;
  background-color: #34a853;
  animation: progress 3s linear forwards;
}

.toast-error .toast-progress-bar::before {
  content: "";
  position: absolute;
  height: 100%;
  width: 100%;
  background-color: #ea4335;
  animation: progress 3s linear forwards;
}

@keyframes progress {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

/* Responsive styles */
@media (min-width: 768px) {
  .cart-item {
    flex-direction: row;
    align-items: center;
  }

  .cart-item-details {
    margin-bottom: 0;
    margin-right: 20px;
    flex: 1;
  }

  .cart-item-actions {
    flex-direction: row;
    border-top: none;
    padding-top: 0;
    width: 300px;
    justify-content: space-between;
  }

  .cart-footer {
    flex-direction: row;
    justify-content: space-between;
  }

  .cart-summary {
    width: 350px;
  }
}

@media (max-width: 767px) {
  .cart-item-actions {
    flex-wrap: wrap;
    gap: 15px;
  }

  .cart-actions {
    flex-direction: column;
    width: 100%;
  }

  .btn-outline {
    width: 100%;
  }
}

/* Payment Modal Styles */
.payment-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.payment-modal {
  background-color: #fff;
  border-radius: 12px;
  width: 100%;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    transform: translateY(50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.payment-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  border-bottom: 1px solid var(--colorgrey);
  background-color: var(--accent);
  border-radius: 12px 12px 0 0;
}

.payment-title {
  font-size: 2rem;
  font-weight: 600;
  color: white;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.8rem;
  color: white;
  cursor: pointer;
  transition: color 0.2s;
}

.close-btn:hover {
  color: var(--colorgrey);
}

.close-btn:disabled {
  color: #ccc;
  cursor: not-allowed;
}

.payment-modal-content {
  padding: 25px;
}

.order-summary h4,
.payment-methods h4 {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--colortext1);
  margin-top: 0;
  margin-bottom: 20px;
}

.order-items {
  margin-bottom: 30px;
  max-height: 300px;
  overflow-y: auto;
  padding-right: 10px;
}

.order-item {
  display: flex;
  padding: 15px;
  border-radius: 8px;
  background-color: #f9f9f9;
  margin-bottom: 12px;
  transition: transform 0.2s ease;
  border: 1px solid var(--colorgrey);
}

.order-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.item-image {
  width: 70px;
  height: 70px;
  border-radius: 6px;
  overflow: hidden;
  margin-right: 15px;
  border: 1px solid var(--colorgrey);
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.item-image:hover img {
  transform: scale(1.1);
}

.item-details {
  flex: 1;
}

.item-details h5 {
  font-size: 1.5rem;
  margin: 0 0 5px 0;
  color: var(--colortext1);
}

.item-variants {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 8px;
}

.item-variants .variant-tag {
  background-color: #f0f0f0;
  font-size: 1.2rem;
  padding: 3px 8px;
  border-radius: 4px;
  color: var(--colortext2);
  border: 1px solid #e0e0e0;
}

.item-quantity-price {
  display: flex;
  font-size: 1.3rem;
  color: var(--colortext2);
}

.item-quantity-price span:first-child {
  margin-right: 15px;
}

.item-subtotal {
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--accent);
  min-width: 120px;
  text-align: right;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.order-total {
  background-color: #f9f9f9;
  border-radius: 8px;
  padding: 20px;
  border: 1px solid var(--colorgrey);
}

.total-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 1.5rem;
  color: var(--colortext2);
}

.total-row:last-child {
  margin-bottom: 0;
}

.grand-total {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--colortext1);
  border-top: 1px solid var(--colorgrey);
  padding-top: 12px;
  margin-top: 12px;
}

.grand-total span:last-child {
  color: var(--accent);
}

.payment-methods {
  margin-top: 30px;
}

.payment-options {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.payment-option {
  display: flex;
  align-items: flex-start;
  padding: 15px;
  border: 1px solid var(--colorgrey);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
}

.payment-option:hover {
  border-color: var(--accent);
  background-color: rgba(202, 156, 39, 0.05);
}

.payment-option.selected {
  border-color: var(--accent);
  background-color: rgba(202, 156, 39, 0.1);
}

.payment-option input[type="radio"] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.radio-custom {
  position: relative;
  top: 2px;
  display: inline-block;
  width: 20px;
  height: 20px;
  margin-right: 15px;
  border: 2px solid var(--colorgrey);
  border-radius: 50%;
  background-color: white;
  flex-shrink: 0;
}

.payment-option input[type="radio"]:checked ~ .radio-custom::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: var(--accent);
}

.payment-option input[type="radio"]:checked ~ .radio-custom {
  border-color: var(--accent);
}

.option-content {
  display: flex;
  align-items: center;
  flex: 1;
}

.option-content i {
  font-size: 2.4rem;
  margin-right: 15px;
  color: var(--colortext2);
}

.option-content div {
  flex: 1;
}

.option-content h5 {
  margin: 0 0 5px 0;
  font-size: 1.5rem;
  color: var(--colortext1);
}

.option-content p {
  margin: 0;
  font-size: 1.3rem;
  color: var(--colortext2);
}

.payment-instructions {
  margin-top: 20px;
  padding: 15px;
  background-color: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid var(--accent);
}

.payment-instructions h5 {
  color: var(--accent);
  margin-top: 0;
  margin-bottom: 10px;
  font-size: 1.5rem;
}

.payment-instructions p {
  margin: 5px 0;
  font-size: 1.4rem;
  color: var(--colortext2);
}

.payment-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
}

.payment-error {
  background-color: #fdeded;
  color: #ea4335;
  padding: 15px;
  border-radius: 8px;
  margin-top: 20px;
  display: flex;
  align-items: center;
  font-size: 1.4rem;
}

.payment-error i {
  margin-right: 10px;
  font-size: 1.8rem;
}

.payment-success {
  text-align: center;
  padding: 40px 20px;
}

.success-icon {
  font-size: 6rem;
  color: var(--accent);
  margin-bottom: 20px;
}

.payment-success h4 {
  font-size: 2.4rem;
  margin-bottom: 15px;
  color: var(--colortext1);
}

.payment-success p {
  font-size: 1.6rem;
  color: var(--colortext2);
  margin: 5px 0;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.spinner {
  animation: spin 1s linear infinite;
  display: inline-block;
}

.loading-spinner {
  display: flex;
  justify-content: center;
  margin-top: 15px;
}

.loading-spinner .spinner-border {
  width: 2rem;
  height: 2rem;
  color: var(--accent) !important;
}

@media (max-width: 576px) {
  .payment-modal {
    max-height: 95vh;
  }

  .order-item {
    flex-direction: column;
  }

  .item-image {
    width: 100%;
    height: 150px;
    margin-right: 0;
    margin-bottom: 10px;
  }

  .item-subtotal {
    margin-top: 10px;
    width: 100%;
    justify-content: flex-start;
  }

  .payment-actions {
    flex-direction: column;
  }

  .payment-actions button {
    width: 100%;
  }
}
</style>
