<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

interface OrderItem {
  id: number;
  ID_DH: number;
  ID_SP: number;
  user_id: number;
  So_luong: number;
  Gia: number;
  Thanh_tien: number;
  color_id?: number;
  size_id?: number;
  don_gia: number;
  hinh_anh?: string;
  created_at?: string;
  updated_at?: string;
  product?: {
    id?: number;
    Ten_san_pham?: string;
    Anh_dai_dien?: string;
    Gia?: number;
    name?: string;
    image?: string;
    current_price?: number;
    So_sao?: number;
  };
  color?: {
    id: string | number;
    name: string;
    Ten_mau?: string;
  };
  size?: {
    id: string | number;
    name: string;
    Ten_size?: string;
  };
}

interface Order {
  id: number;
  Ma_don_hang?: string;
  order_number?: string;
  ID_KH?: number;
  user_id?: number;
  Trang_thai?: number | string;
  status?: string;
  phuong_thuc_thanh_toan?: number | string;
  payment_method?: string;
  trang_thai_thanh_toan?: number | string;
  payment_status?: string;
  Tong_tien: number;
  total_price?: number;
  ho_ten?: string;
  so_dien_thoai?: string;
  email?: string;
  dia_chi?: string;
  shipping_address?: string;
  shipping_phone?: string;
  shipping_name?: string;
  Ngay_mua?: string;
  created_at: string;
  updated_at: string;
  items: OrderItem[];
  orderItems?: OrderItem[];
}

const orders = ref<Order[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const success = ref<string | null>(null);
const currentOrder = ref<Order | null>(null);

// Kiểm tra order_id từ query parameters
const orderIdFromQuery = computed(() => {
  return route.query.order_id?.toString();
});

const statusFromQuery = computed(() => {
  return route.query.status?.toString();
});

// Hàm truy vấn đơn hàng theo ID cụ thể
const fetchOrderById = async (orderId: string) => {
  loading.value = true;
  error.value = null;

  try {
    // Lấy token xác thực
    const token =
      localStorage.getItem("auth_token") || localStorage.getItem("user_token");

    if (!token) {
      error.value = "Bạn cần đăng nhập để xem đơn hàng";
      loading.value = false;
      return null;
    }

    // Gọi API lấy chi tiết đơn hàng theo ID với timestamp để tránh cache
    const timestamp = new Date().getTime();
    const response = await axios.get(`/api/orders/${orderId}?_t=${timestamp}`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        "Cache-Control": "no-cache, no-store, must-revalidate",
        Pragma: "no-cache",
        Expires: "0",
      },
      withCredentials: true,
    });

    // Kiểm tra trạng thái response
    if (response.status === 401) {
      error.value = "Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại";
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user_token");
      setTimeout(() => {
        router.push("/login");
      }, 2000);
      loading.value = false;
      return null;
    }

    if (response.status === 404) {
      // Không tìm thấy đơn hàng
      console.log(`Không tìm thấy đơn hàng với ID: ${orderId}`);
      return null;
    }

    const result = await response.json();
    console.log(`Kết quả chi tiết đơn hàng ${orderId}:`, result);

    if (result.status === "success" && result.data) {
      // Định dạng dữ liệu phù hợp với interface Order
      return mapOrderData(result.data);
    }

    return null;
  } catch (err) {
    console.error(`Lỗi khi tải đơn hàng ID ${orderId}:`, err);
    return null;
  } finally {
    loading.value = false;
  }
};

// Hàm map order từ API sang model Order
function mapOrderData(apiData) {
  if (!apiData) return null;

  try {
    console.log("Dữ liệu API đơn hàng gốc:", apiData);
    
    const order = {
      id: apiData.id,
      ID_KH: apiData.ID_KH,
      user_id: apiData.ID_KH,
      Ma_don_hang: apiData.Ma_don_hang,
      order_number: apiData.Ma_don_hang || apiData.id,
      Trang_thai: apiData.Trang_thai,
      status: mapOrderStatus(apiData.Trang_thai),
      Tong_tien: parseFloat(apiData.Tong_tien || 0),
      total_price: parseFloat(apiData.Tong_tien || 0),
      created_at: apiData.Ngay_mua || apiData.created_at,
      updated_at: apiData.updated_at,
      phuong_thuc_thanh_toan: apiData.phuong_thuc_thanh_toan,
      payment_method: mapPaymentMethod(apiData.phuong_thuc_thanh_toan),
      trang_thai_thanh_toan: apiData.trang_thai_thanh_toan,
      payment_status: apiData.trang_thai_thanh_toan === 1 ? "paid" : "pending",
      ho_ten: apiData.ho_ten,
      shipping_name: apiData.ho_ten,
      so_dien_thoai: apiData.so_dien_thoai,
      shipping_phone: apiData.so_dien_thoai,
      email: apiData.email,
      dia_chi: apiData.dia_chi || "Không có thông tin",
      shipping_address: apiData.dia_chi || "Không có thông tin",
      Ngay_mua: apiData.Ngay_mua,
      items: []
    };

    // Debugging thông tin chi tiết đơn hàng
    console.log("Kiểm tra các thuộc tính chi tiết đơn hàng:");
    console.log("orderItems:", apiData.orderItems);
    console.log("order_items:", apiData.order_items);
    console.log("chi_tiet:", apiData.chi_tiet);

    // Xử lý chi tiết đơn hàng - kiểm tra tất cả các tên thuộc tính có thể có
    let orderItems = [];
    if (apiData.orderItems && Array.isArray(apiData.orderItems)) {
      orderItems = apiData.orderItems;
    } else if (apiData.order_items && Array.isArray(apiData.order_items)) {
      orderItems = apiData.order_items;
    } else if (apiData.chi_tiet && Array.isArray(apiData.chi_tiet)) {
      orderItems = apiData.chi_tiet;
    } else if (apiData.don_hang_chi_tiet && Array.isArray(apiData.don_hang_chi_tiet)) {
      orderItems = apiData.don_hang_chi_tiet;
    }

    console.log("Danh sách sản phẩm trong đơn hàng được tìm thấy:", orderItems);

    // Xử lý các mục sản phẩm
    if (orderItems && orderItems.length > 0) {
      order.items = orderItems.map((item) => {
        console.log("Chi tiết item:", item);
        
        // Xác định dữ liệu sản phẩm từ các trường có thể có
        const productData = item.product || item.san_pham || {};
        const productId = item.ID_SP || item.product_id || (productData ? productData.id : 0);
        const productName = productData.Ten_san_pham || productData.name || productData.Ten_sp || "Sản phẩm không có tên";
        const productImage = productData.Hinh_anh || productData.image || productData.Anh_dai_dien || item.hinh_anh || "default.jpg";
        const productRating = productData.So_sao || productData.rating || 0;
        
        // Xác định đơn giá và số lượng
        const quantity = item.So_luong || item.quantity || 1;
        const price = item.Gia || item.price || item.don_gia || 0;
        
        const orderItem = {
          id: item.id,
          ID_DH: item.ID_DH || item.order_id,
          ID_SP: productId,
          user_id: item.user_id || order.ID_KH,
          So_luong: quantity,
          Gia: price,
          don_gia: item.don_gia || price,
          Thanh_tien: item.Thanh_tien || (quantity * price),
          color_id: item.color_id,
          size_id: item.size_id,
          hinh_anh: item.hinh_anh || productImage,
          product: {
            id: productId,
            Ten_san_pham: productName,
            name: productName,
            Anh_dai_dien: productImage,
            image: productImage,
            Gia: price,
            current_price: price,
            So_sao: productRating
          }
        };

        // Xử lý thông tin màu sắc
        if (item.mau_sac || item.color) {
          const colorData = item.mau_sac || item.color || {};
          orderItem.color = {
            id: item.color_id || colorData.id || 0,
            name: colorData.Ten_mau || colorData.name || "Không có thông tin",
            Ten_mau: colorData.Ten_mau || colorData.name || "Không có thông tin"
          };
        }

        // Xử lý thông tin kích thước
        if (item.size) {
          const sizeData = item.size;
          orderItem.size = {
            id: item.size_id || sizeData.id || 0,
            name: sizeData.Ten_size || sizeData.name || "Không có thông tin",
            Ten_size: sizeData.Ten_size || sizeData.name || "Không có thông tin"
          };
        }

        return orderItem;
      });
    }

    console.log("Dữ liệu đơn hàng sau khi xử lý:", order);
    return order;
  } catch (error) {
    console.error("Lỗi khi chuyển đổi dữ liệu đơn hàng:", error);
    return null;
  }
}

// Hàm mapping trạng thái đơn hàng
function mapOrderStatus(status) {
  if (typeof status === "number") {
    switch (status) {
      case 0:
        return "cancelled";
      case 1:
        return "pending";
      case 2:
        return "processing";
      case 3:
        return "shipped";
      case 4:
        return "delivered";
      default:
        return "pending";
    }
  }

  return status || "pending";
}

// Hàm mapping phương thức thanh toán
function mapPaymentMethod(method) {
  if (typeof method === "number") {
    switch (method) {
      case 1:
        return "cod";
      case 2:
        return "bank_transfer";
      case 3:
        return "momo";
      default:
        return "cod";
    }
  }
  return method || "cod";
}

// Hàm lấy danh sách đơn hàng từ API
const fetchOrders = async () => {
  loading.value = true;
  error.value = null;

  try {
    // Lấy token xác thực
    const token =
      localStorage.getItem("auth_token") || localStorage.getItem("user_token");

    if (!token) {
      error.value = "Bạn cần đăng nhập để xem đơn hàng";
      loading.value = false;
      return;
    }

    // Gọi API lấy danh sách đơn hàng với timestamp để tránh cache
    const timestamp = new Date().getTime();
    const response = await axios.get(`/api/orders?_t=${timestamp}`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        "Cache-Control": "no-cache, no-store, must-revalidate",
        Pragma: "no-cache",
        Expires: "0"
      },
      withCredentials: true
    });

    // Kiểm tra trạng thái response
    if (response.status === 401) {
      // Nếu không được xác thực, chuyển hướng người dùng đến trang đăng nhập
      error.value = "Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại";
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user_token");
      setTimeout(() => {
        router.push("/login");
      }, 2000);
      loading.value = false;
      return;
    }

    const result = await response.json();
    console.log("Kết quả API đơn hàng:", result);

    if (result.status === "success") {
      // Chuyển đổi dữ liệu từ API sang định dạng Order
      if (Array.isArray(result.data)) {
        const newOrders = result.data.map((item: any) => mapOrderData(item)).filter(order => order !== null);
        // Kiểm tra nếu danh sách rỗng
        if (newOrders.length === 0) {
          orders.value = [];
        } else {
          // Cập nhật danh sách đơn hàng
          orders.value = newOrders;
        }
      } else {
        orders.value = [];
      }

      // Nếu có order_id từ query param, tìm và hiển thị đơn hàng đó
      if (orderIdFromQuery.value) {
        const foundOrder = orders.value.find(
          (o) =>
            o.id.toString() === orderIdFromQuery.value ||
            o.Ma_don_hang === orderIdFromQuery.value ||
            o.order_number === orderIdFromQuery.value
        );

        if (foundOrder) {
          currentOrder.value = foundOrder;

          // Nếu là đơn hàng mới tạo thành công
          if (statusFromQuery.value === "success") {
            success.value = "Đơn hàng của bạn đã được tạo thành công!";
            setTimeout(() => {
              success.value = null;
            }, 5000);
          }
        } else if (statusFromQuery.value === "success") {
          // Nếu không tìm thấy đơn hàng nhưng có status thành công, thử tải đơn hàng riêng lẻ
          success.value =
            "Đơn hàng của bạn đã được tạo thành công! Đang tải thông tin đơn hàng...";

          // Tải trực tiếp đơn hàng theo ID
          const specificOrder = await fetchOrderById(orderIdFromQuery.value);

          if (specificOrder) {
            // Nếu tìm thấy đơn hàng qua API riêng, hiển thị nó
            currentOrder.value = specificOrder;
            
            // Thêm đơn hàng này vào danh sách nếu chưa có
            if (!orders.value.some(o => o.id === specificOrder.id)) {
              orders.value = [specificOrder, ...orders.value];
            }
            
            success.value = "Đơn hàng của bạn đã được tạo thành công!";
            setTimeout(() => {
              success.value = null;
            }, 5000);
          } else {
            // Vẫn không tìm thấy, thử lại sau 2 giây
            setTimeout(async () => {
              await fetchOrdersWithRetry();
            }, 2000);
          }
        }
      }
    } else {
      error.value = result.message || "Không thể tải danh sách đơn hàng";
    }
  } catch (err) {
    console.error("Lỗi khi tải đơn hàng:", err);
    error.value = "Lỗi kết nối khi tải danh sách đơn hàng";
  } finally {
    loading.value = false;
  }
};

// Hàm tải đơn hàng với cơ chế thử lại
const fetchOrdersWithRetry = async (retries = 3) => {
  loading.value = true;

  try {
    // Lấy token xác thực
    const token =
      localStorage.getItem("auth_token") || localStorage.getItem("user_token");

    if (!token) {
      error.value = "Bạn cần đăng nhập để xem đơn hàng";
      loading.value = false;
      return;
    }

    // Gọi API lấy danh sách đơn hàng với timestamp để tránh cache
    const timestamp = new Date().getTime();
    const response = await axios.get(`/api/orders?_t=${timestamp}`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        "Cache-Control": "no-cache, no-store, must-revalidate",
        Pragma: "no-cache",
        Expires: "0"
      },
      withCredentials: true
    });

    // Kiểm tra trạng thái response
    if (response.status === 401) {
      // Nếu không được xác thực, chuyển hướng người dùng đến trang đăng nhập
      error.value = "Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại";
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user_token");
      setTimeout(() => {
        router.push("/login");
      }, 2000);
      loading.value = false;
      return;
    }

    const result = await response.json();
    console.log("Kết quả API đơn hàng (retry):", result);

    if (result.status === "success") {
      // Chuyển đổi dữ liệu từ API sang định dạng Order
      if (Array.isArray(result.data)) {
        // Cập nhật danh sách đơn hàng thay vì ghi đè toàn bộ
        const newOrders = result.data.map((item: any) => mapOrderData(item));
        // Lưu danh sách đơn hàng hiện tại
        orders.value = newOrders;
      }

      // Nếu có order_id từ query param, tìm và hiển thị đơn hàng đó
      if (orderIdFromQuery.value) {
        const foundOrder = orders.value.find(
          (o) =>
            o.id.toString() === orderIdFromQuery.value ||
            o.Ma_don_hang === orderIdFromQuery.value ||
            o.order_number === orderIdFromQuery.value
        );

        if (foundOrder) {
          currentOrder.value = foundOrder;
          success.value = "Đơn hàng của bạn đã được tạo thành công!";
          setTimeout(() => {
            success.value = null;
          }, 5000);
          return; // Tìm thấy đơn hàng, dừng thử lại
        } else if (retries > 0 && statusFromQuery.value === "success") {
          // Thử tải trực tiếp đơn hàng theo ID
          const specificOrder = await fetchOrderById(orderIdFromQuery.value);

          if (specificOrder) {
            // Nếu tìm thấy đơn hàng qua API riêng, hiển thị nó
            currentOrder.value = specificOrder;
            
            // Thêm đơn hàng này vào danh sách nếu chưa có
            if (!orders.value.some(o => o.id === specificOrder.id)) {
              orders.value = [specificOrder, ...orders.value];
            }
            
            success.value = "Đơn hàng của bạn đã được tạo thành công!";
            setTimeout(() => {
              success.value = null;
            }, 5000);
            return; // Tìm thấy đơn hàng, dừng thử lại
          }

          // Nếu vẫn không tìm thấy và còn lượt thử, thử lại sau 2 giây
          setTimeout(() => {
            fetchOrdersWithRetry(retries - 1);
          }, 2000);
          return;
        }
      }
    } else {
      error.value = result.message || "Không thể tải danh sách đơn hàng";
    }
  } catch (err) {
    console.error("Lỗi khi tải đơn hàng:", err);
    if (retries > 0) {
      // Thử lại nếu bị lỗi kết nối
      setTimeout(() => {
        fetchOrdersWithRetry(retries - 1);
      }, 2000);
    } else {
      error.value = "Lỗi kết nối khi tải danh sách đơn hàng";
    }
  } finally {
    loading.value = false;
  }
};

// Hàm định dạng ngày giờ
const formatDate = (dateString: string) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleString("vi-VN", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

// Hàm định dạng tiền tệ
const formatCurrency = (amount: number) => {
  return amount.toLocaleString("vi-VN") + "đ";
};

// Hàm lấy text cho trạng thái đơn hàng
const getStatusText = (status: string) => {
  switch (status) {
    case "pending":
      return "Chờ xác nhận";
    case "processing":
      return "Đang xử lý";
    case "shipped":
      return "Đang giao hàng";
    case "delivered":
      return "Đã giao hàng";
    case "cancelled":
      return "Đã hủy";
    default:
      return status;
  }
};

// Hàm lấy class cho trạng thái đơn hàng
const getStatusClass = (status: string) => {
  switch (status) {
    case "pending":
      return "status-pending";
    case "processing":
      return "status-processing";
    case "shipped":
      return "status-shipped";
    case "delivered":
      return "status-delivered";
    case "cancelled":
      return "status-cancelled";
    default:
      return "";
  }
};

// Hàm xem chi tiết đơn hàng
const viewOrderDetail = (order) => {
  // Không cần tải lại dữ liệu nếu đã có
  currentOrder.value = order;
  
  // Thay đổi URL để có thể lưu trạng thái
  router.push(`/don-hang?order_id=${order.id}`);
};

// Hàm lấy đường dẫn hình ảnh sản phẩm đúng
function getProductImagePath(item) {
  // Lấy tên file hình ảnh từ các nguồn khác nhau
  const imageName = item.hinh_anh || 
                   (item.product ? item.product.image || item.product.Anh_dai_dien : null) || 
                   'default.jpg';
  
  // Kiểm tra xem đường dẫn đã có http:// hoặc https:// chưa
  if (imageName.startsWith('http://') || imageName.startsWith('https://')) {
    return imageName;
  }
  
  // Kiểm tra xem đường dẫn đã có dấu / ở đầu chưa
  if (imageName.startsWith('/')) {
    return imageName;
  }
  
  // Đường dẫn mặc định
  return `/img/img_sp/${imageName}`;
}

// Hàm hiển thị số sao của sản phẩm
function renderStars(rating) {
  if (!rating || rating <= 0) return '';
  
  // Làm tròn rating đến 0.5 gần nhất
  const roundedRating = Math.round(rating * 2) / 2;
  let starsHtml = '';
  
  // Tạo HTML cho số sao đầy đủ
  for (let i = 1; i <= Math.floor(roundedRating); i++) {
    starsHtml += '<i class="bi bi-star-fill"></i>';
  }
  
  // Thêm nửa sao nếu cần
  if (roundedRating % 1 !== 0) {
    starsHtml += '<i class="bi bi-star-half"></i>';
  }
  
  // Thêm sao trống cho đủ 5 sao
  const emptyStars = 5 - Math.ceil(roundedRating);
  for (let i = 0; i < emptyStars; i++) {
    starsHtml += '<i class="bi bi-star"></i>';
  }
  
  return starsHtml;
}

onMounted(async () => {
  try {
    // Luôn tải tất cả đơn hàng để hiển thị danh sách đầy đủ
    await fetchOrders();
    
    // Nếu có order_id trong query, hiển thị chi tiết đơn hàng đó
  if (orderIdFromQuery.value) {
      // Kiểm tra nếu đơn hàng đã có trong danh sách đã tải
      const foundOrder = orders.value.find(
        (o) => o.id.toString() === orderIdFromQuery.value || 
               o.Ma_don_hang === orderIdFromQuery.value || 
               o.order_number === orderIdFromQuery.value
      );
      
      if (foundOrder) {
        // Nếu tìm thấy trong danh sách, hiển thị chi tiết
        currentOrder.value = foundOrder;
        
        // Hiển thị thông báo nếu là đơn hàng mới thanh toán thành công
    if (statusFromQuery.value === "success") {
      success.value = "Đơn hàng của bạn đã được tạo thành công!";
      setTimeout(() => {
        success.value = null;
      }, 5000);
    }
      } else {
        // Nếu không tìm thấy, tải trực tiếp đơn hàng theo ID
        if (statusFromQuery.value === "success") {
          success.value = "Đơn hàng của bạn đã được tạo thành công! Đang tải thông tin...";
        }
        
      const specificOrder = await fetchOrderById(orderIdFromQuery.value);

      if (specificOrder) {
        currentOrder.value = specificOrder;
          
          // Thêm đơn hàng mới này vào danh sách nếu chưa có
          if (!orders.value.some(o => o.id === specificOrder.id)) {
            orders.value = [specificOrder, ...orders.value];
          }
          
          if (statusFromQuery.value === "success") {
        success.value = "Đơn hàng của bạn đã được tạo thành công!";
        setTimeout(() => {
          success.value = null;
        }, 5000);
          }
      } else {
          // Nếu vẫn không tìm thấy, thử lại với cơ chế retry
        await fetchOrdersWithRetry();
      }
      }
    }
  } catch (err) {
    console.error("Lỗi khi tải trang đơn hàng:", err);
    error.value = "Đã xảy ra lỗi khi tải trang đơn hàng";
  }
});
</script>

<template>
  <div class="order-container">
    <div class="order-header-section">
      <h1 class="order-main-title">Đơn hàng của bạn</h1>
      <div class="order-actions-top">
        <button class="btn-refresh" @click="fetchOrdersWithRetry">
          <i class="bi bi-arrow-clockwise"></i> Làm mới
        </button>
      </div>
    </div>

    <!-- Thông báo thành công nếu có -->
    <div v-if="success" class="alert alert-success">
      <i class="bi bi-check-circle-fill alert-icon"></i>
      <p>{{ success }}</p>
      <button class="btn-close" @click="success = null">
        <i class="bi bi-x"></i>
      </button>
    </div>

    <!-- Thông báo lỗi nếu có -->
    <div v-if="error" class="alert alert-error">
      <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
      <p>{{ error }}</p>
      <div class="alert-actions">
        <button class="btn-retry" @click="fetchOrdersWithRetry">
          <i class="bi bi-arrow-repeat"></i> Thử lại
        </button>
        <button class="btn-close" @click="error = null">
          <i class="bi bi-x"></i>
        </button>
      </div>
    </div>

    <!-- Hiển thị loading -->
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Đang tải đơn hàng...</p>
    </div>

    <!-- Hiện đơn hàng hiện tại nếu có -->
    <div v-if="currentOrder" class="order-details-section">
      <div class="order-header-row">
        <h2 class="order-title">Chi tiết đơn hàng #{{ currentOrder.Ma_don_hang || currentOrder.order_number }}</h2>
        <button class="btn-back" @click="currentOrder = null; router.push('/don-hang')">
          <i class="bi bi-arrow-left"></i> Trở lại danh sách
        </button>
      </div>

      <!-- Nội dung chi tiết đơn hàng -->
      <div class="order-info-grid">
        <div class="order-info-card">
          <div class="info-card-title">
            <i class="bi bi-info-circle"></i> Thông tin đơn hàng
          </div>
          <div class="info-card-content">
            <div class="info-item">
              <span class="info-label">Mã đơn hàng:</span>
              {{ currentOrder.Ma_don_hang || currentOrder.order_number }}
            </div>
            <div class="info-item">
              <span class="info-label">Ngày đặt:</span>
              {{ formatDate(currentOrder.Ngay_mua || currentOrder.created_at) }}
            </div>
            <div class="info-item">
              <span class="info-label">Trạng thái:</span>
              <span
                :class="{
                  'status-pending': currentOrder.status === 'pending',
                  'status-processing': currentOrder.status === 'processing',
                  'status-shipped': currentOrder.status === 'shipped',
                  'status-delivered': currentOrder.status === 'delivered',
                  'status-cancelled': currentOrder.status === 'cancelled',
                }"
                class="order-status-badge"
              >
                {{ getStatusText(currentOrder.status) }}
              </span>
            </div>
            <div class="info-item">
              <span class="info-label">Tổng tiền:</span>
              <span class="total-price">{{
                formatCurrency(currentOrder.Tong_tien || currentOrder.total_price || 0)
              }}</span>
            </div>
          </div>
        </div>

        <div class="order-info-card">
          <div class="info-card-title">
            <i class="bi bi-credit-card"></i> Thanh toán
          </div>
          <div class="info-card-content">
            <div class="info-item">
              <span class="info-label">Phương thức:</span>
              {{
                (currentOrder.payment_method === "cod" || currentOrder.phuong_thuc_thanh_toan === 1)
                  ? "Thanh toán khi nhận hàng"
                  : (currentOrder.payment_method === "bank_transfer" || currentOrder.phuong_thuc_thanh_toan === 2)
                  ? "Chuyển khoản ngân hàng"
                  : (currentOrder.payment_method === "momo" || currentOrder.phuong_thuc_thanh_toan === 3)
                  ? "Ví MoMo"
                  : "Không xác định"
              }}
            </div>
            <div class="info-item">
              <span class="info-label">Trạng thái:</span>
              <span
                :class="{
                  'status-pending': currentOrder.payment_status === 'pending',
                  'status-delivered': currentOrder.payment_status === 'paid',
                }"
                class="order-status-badge"
              >
                {{
                  (currentOrder.payment_status === "pending" || currentOrder.trang_thai_thanh_toan === 0)
                    ? "Chưa thanh toán"
                    : "Đã thanh toán"
                }}
              </span>
            </div>
          </div>
        </div>

        <div class="order-info-card">
          <div class="info-card-title">
            <i class="bi bi-geo-alt"></i> Địa chỉ giao hàng
          </div>
          <div class="info-card-content">
            <div class="info-item address-item">
              {{ currentOrder.dia_chi || currentOrder.shipping_address || "Không có thông tin" }}
            </div>
          </div>
        </div>
      </div>

      <div class="order-items-section">
        <h3 class="section-title">Sản phẩm đã đặt</h3>
        <div class="order-items-table">
          <div class="order-items-header">
            <div class="item-col product-col">Sản phẩm</div>
            <div class="item-col price-col">Đơn giá</div>
            <div class="item-col qty-col">Số lượng</div>
            <div class="item-col subtotal-col">Thành tiền</div>
          </div>

          <div
            v-for="item in currentOrder.items"
            :key="item.id"
            class="order-item-row"
          >
            <div class="item-col product-col">
              <div class="product-info">
                <img
                  :src="getProductImagePath(item)"
                  :alt="item.product ? item.product.name || item.product.Ten_san_pham : 'Sản phẩm'"
                  @error="$event.target.src = '/img/default.jpg'"
                />
                <div class="product-details">
                  <h4 class="product-name">{{ item.product ? item.product.name || item.product.Ten_san_pham : 'Sản phẩm không có tên' }}</h4>
                  <div class="product-rating" v-if="item.product && item.product.So_sao > 0" v-html="renderStars(item.product.So_sao)"></div>
                  <div class="product-variants">
                    <span v-if="item.color" class="variant-tag">{{
                      item.color.name || item.color.Ten_mau
                    }}</span>
                    <span v-if="item.size" class="variant-tag"
                      >Size {{ item.size.name || item.size.Ten_size }}</span
                    >
                  </div>
                </div>
              </div>
            </div>
            <div class="item-col price-col">
              {{ formatCurrency(item.Gia || item.don_gia || 0) }}
            </div>
            <div class="item-col qty-col">{{ item.So_luong }}</div>
            <div class="item-col subtotal-col">
              {{ formatCurrency(item.Thanh_tien || ((item.So_luong || 1) * (item.Gia || item.don_gia || 0))) }}
            </div>
          </div>
        </div>

        <div class="order-summary-totals">
          <div class="total-row">
            <span class="total-label">Tổng cộng:</span>
            <span class="total-value">{{
              formatCurrency(currentOrder.Tong_tien || currentOrder.total_price || 0)
            }}</span>
          </div>
        </div>
      </div>

      <div class="order-actions">
        <button class="btn-back" @click="currentOrder = null; router.push('/don-hang')">
          <i class="bi bi-arrow-left"></i> Trở lại danh sách
        </button>
      </div>
    </div>

    <!-- Danh sách tất cả đơn hàng khi không có currentOrder -->
    <div v-else-if="!loading && orders.length > 0" class="orders-list-section">
      <h2 class="section-title">Tất cả đơn hàng</h2>
      <div class="orders-list">
        <div
          v-for="order in orders"
          :key="order.id"
          class="order-card"
          @click="viewOrderDetail(order)"
        >
        <div class="order-card-header">
            <div class="order-number">Đơn hàng #{{ order.Ma_don_hang || order.order_number }}</div>
            <div
              :class="{
                'status-pending': order.status === 'pending',
                'status-processing': order.status === 'processing',
                'status-shipped': order.status === 'shipped',
                'status-delivered': order.status === 'delivered',
                'status-cancelled': order.status === 'cancelled',
              }"
              class="order-status-badge"
            >
            {{ getStatusText(order.status) }}
          </div>
        </div>
          <div class="order-card-body">
            <div class="order-info-row">
              <span class="info-label">Ngày đặt:</span>
              <span>{{ formatDate(order.Ngay_mua || order.created_at) }}</span>
            </div>
            <div class="order-info-row">
              <span class="info-label">Tổng tiền:</span>
              <span class="order-total">{{ formatCurrency(order.Tong_tien || order.total_price || 0) }}</span>
            </div>
            <div class="order-info-row">
              <span class="info-label">Phương thức thanh toán:</span>
              <span>{{
                (order.payment_method === "cod" || order.phuong_thuc_thanh_toan === 1)
                  ? "Thanh toán khi nhận hàng"
                  : (order.payment_method === "bank_transfer" || order.phuong_thuc_thanh_toan === 2)
                  ? "Chuyển khoản ngân hàng"
                  : (order.payment_method === "momo" || order.phuong_thuc_thanh_toan === 3)
                  ? "Ví MoMo"
                  : "Không xác định"
              }}</span>
            </div>
            <div class="order-info-row">
              <span class="info-label">Thanh toán:</span>
              <span
                :class="{
                  'status-pending': order.payment_status === 'pending',
                  'status-delivered': order.payment_status === 'paid',
                }"
                class="payment-status-badge"
              >
                {{
                  (order.payment_status === "pending" || order.trang_thai_thanh_toan === 0)
                    ? "Chưa thanh toán"
                    : "Đã thanh toán"
                }}
              </span>
              </div>
            </div>
          <div class="order-card-items">
            <div class="item-preview" v-if="order.items && order.items.length > 0">
              <div class="item-count" v-if="order.items.length > 1">
                +{{ order.items.length - 1 }} sản phẩm khác
            </div>
              <img
                v-if="order.items[0]?.product?.image || order.items[0]?.product?.Anh_dai_dien || order.items[0]?.hinh_anh"
                :src="getProductImagePath(order.items[0])"
                :alt="order.items[0].product ? order.items[0].product.name || order.items[0].product.Ten_san_pham : 'Sản phẩm'"
                @error="$event.target.src = '/img/default.jpg'"
              />
          </div>
            <div class="order-item-names" v-if="order.items && order.items.length > 0">
              <div class="main-product-name">
                {{ order.items[0].product ? order.items[0].product.name || order.items[0].product.Ten_san_pham : 'Sản phẩm' }}
            </div>
              <div class="product-rating" v-if="order.items[0].product && order.items[0].product.So_sao > 0" v-html="renderStars(order.items[0].product.So_sao)"></div>
              <div class="other-products" v-if="order.items.length > 1">
                <span>+{{ order.items.length - 1 }} sản phẩm khác</span>
            </div>
          </div>
        </div>
        <div class="order-card-footer">
            <button class="btn-view-order">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Hiển thị khi không có đơn hàng nào -->
    <div v-else-if="!loading && orders.length === 0" class="no-orders">
        <div class="no-orders-icon">
        <i class="bi bi-cart-x"></i>
        </div>
      <p class="no-orders-text">Bạn chưa có đơn hàng nào</p>
      <a href="/" class="btn-shop-now">Mua sắm ngay</a>
    </div>
  </div>
</template>

<style scoped>
.order-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px 20px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f7fa;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.order-header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  border-bottom: 2px solid var(--colorgrey);
  padding-bottom: 15px;
}

.order-main-title {
  font-size: 2.4rem;
  color: var(--colortext1);
  font-weight: 700;
  margin: 0;
  text-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.order-actions-top {
  display: flex;
  gap: 10px;
}

.btn-refresh {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background-color: #f0f7ff;
  color: var(--accent);
  border: 1px solid var(--accent);
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 1.4rem;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.btn-refresh:hover {
  background-color: rgba(202, 156, 39, 0.1);
  transform: translateY(-2px);
}

.alert {
  display: flex;
  align-items: center;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 25px;
  position: relative;
}

.alert-success {
  background-color: #e6f4ea;
  color: #34a853;
  border-left: 4px solid #34a853;
}

.alert-error {
  background-color: #fdeded;
  color: #ea4335;
  border-left: 4px solid #ea4335;
}

.alert-icon {
  font-size: 2rem;
  margin-right: 15px;
  flex-shrink: 0;
}

.alert p {
  margin: 0;
  flex-grow: 1;
  font-weight: 500;
}

.alert-actions {
  display: flex;
  gap: 8px;
  margin-left: 15px;
}

.btn-retry {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background-color: #ea4335;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 1.3rem;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.btn-retry:hover {
  background-color: #d32f2f;
  transform: translateY(-2px);
}

.btn-close {
  background: transparent;
  border: none;
  color: inherit;
  font-size: 1.6rem;
  cursor: pointer;
  padding: 4px;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.btn-close:hover {
  opacity: 1;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 0;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.loading-spinner {
  width: 48px;
  height: 48px;
  border: 4px solid rgba(202, 156, 39, 0.2);
  border-radius: 50%;
  border-top-color: var(--accent);
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

.loading-container p {
  color: var(--colortext1);
  font-size: 1.5rem;
  font-weight: 500;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Thiết kế chi tiết đơn hàng */
.order-details-section {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  margin-bottom: 30px;
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.order-header-row {
  padding: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--colorgrey);
  background-color: rgba(202, 156, 39, 0.1);
}

.order-title {
  font-size: 2rem;
  color: var(--colortext1);
  margin: 0;
  font-weight: 600;
  text-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: white;
  color: var(--accent);
  border: 1px solid var(--accent);
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 1.4rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-back:hover {
  background-color: rgba(202, 156, 39, 0.1);
  transform: translateY(-2px);
}

.order-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  padding: 24px;
}

.order-info-card {
  background-color: #f9fafb;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  border: 1px solid var(--colorgrey);
  transition: transform 0.2s ease;
}

.order-info-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.info-card-title {
  background-color: rgba(202, 156, 39, 0.1);
  padding: 12px 16px;
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--accent);
  display: flex;
  align-items: center;
  gap: 8px;
  border-bottom: 1px solid var(--colorgrey);
}

.info-card-content {
  padding: 16px;
  background-color: white;
}

.info-item {
  margin-bottom: 10px;
  font-size: 1.4rem;
  color: var(--colortext2);
  display: flex;
  flex-direction: column;
}

.info-item:last-child {
  margin-bottom: 0;
}

.info-label {
  font-weight: 600;
  color: var(--colortext1);
  margin-right: 8px;
  margin-bottom: 4px;
}

.total-price {
  color: var(--accent);
  font-weight: 700;
}

.address-item {
  line-height: 1.5;
}

.order-items-section {
  padding: 24px;
  border-top: 1px solid var(--colorgrey);
  background-color: white;
}

.section-title {
  font-size: 1.8rem;
  color: var(--colortext1);
  margin: 0 0 20px 0;
  font-weight: 600;
  text-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.order-items-table {
  border: 1px solid var(--colorgrey);
  border-radius: 8px;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.order-items-header {
  display: flex;
  background-color: rgba(202, 156, 39, 0.1);
  font-weight: 600;
  color: var(--colortext1);
  border-bottom: 1px solid var(--colorgrey);
}

.item-col {
  padding: 12px 16px;
  font-size: 1.4rem;
}

.product-col {
  flex: 3;
}

.price-col,
.qty-col,
.subtotal-col {
  flex: 1;
  text-align: center;
}

.order-item-row {
  display: flex;
  border-bottom: 1px solid var(--colorgrey);
  align-items: center;
  padding: 16px 0;
  background-color: white;
  transition: background-color 0.2s ease;
}

.order-item-row:hover {
  background-color: #f9fafb;
}

.order-item-row:last-child {
  border-bottom: none;
}

.product-info {
  display: flex;
  align-items: center;
  padding-left: 16px;
}

.product-info img {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid var(--colorgrey);
  margin-right: 16px;
  background-color: #fff;
  transition: transform 0.3s ease;
}

.product-info:hover img {
  transform: scale(1.05);
}

.product-details {
  flex: 1;
}

.product-details h4 {
  font-size: 1.5rem;
  margin: 0 0 8px 0;
  color: var(--colortext1);
  font-weight: 500;
}

.product-variants {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.variant-tag {
  background-color: #f0f0f0;
  font-size: 1.2rem;
  padding: 3px 8px;
  border-radius: 4px;
  color: var(--colortext2);
  border: 1px solid #e0e0e0;
  transition: all 0.2s ease;
}

.variant-tag:hover {
  background-color: rgba(202, 156, 39, 0.1);
  border-color: var(--accent);
}

.order-summary-totals {
  margin-top: 24px;
  border-top: 1px solid var(--colorgrey);
  padding-top: 16px;
  background-color: #f9fafb;
  border-radius: 8px;
  padding: 16px;
  border: 1px solid var(--colorgrey);
}

.total-row {
  display: flex;
  justify-content: flex-end;
  font-size: 1.6rem;
  font-weight: 600;
  align-items: center;
  color: var(--colortext1);
}

.total-label {
  margin-right: 12px;
}

.total-value {
  color: var(--accent);
  font-size: 1.8rem;
  font-weight: 700;
}

.order-actions {
  padding: 16px 24px 24px;
  display: flex;
  justify-content: flex-start;
  background-color: #f9fafb;
  border-top: 1px solid var(--colorgrey);
}

/* Danh sách đơn hàng */
.orders-list-section {
  background-color: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
  animation: fadeIn 0.3s ease-out;
}

.orders-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.order-card {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
  display: flex;
  flex-direction: column;
  border: 1px solid var(--colorgrey);
  position: relative;
  cursor: pointer;
}

.order-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.order-card-header {
  padding: 16px;
  border-bottom: 1px solid var(--colorgrey);
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: rgba(202, 156, 39, 0.05);
}

.order-number {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--colortext1);
}

.order-status-badge {
  padding: 5px 10px;
  border-radius: 100px;
  font-size: 1.3rem;
  font-weight: 500;
  color: white;
  background-color: #666;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-pending {
  background-color: #f9a825; /* Màu vàng cam */
  color: #333;
}

.status-processing {
  background-color: #1976d2; /* Màu xanh dương */
  color: white;
}

.status-shipped {
  background-color: #7e57c2; /* Màu tím */
  color: white;
}

.status-delivered {
  background-color: #43a047; /* Màu xanh lá */
  color: white;
}

.status-cancelled {
  background-color: #e53935; /* Màu đỏ */
  color: white;
}

.payment-status-badge {
  padding: 4px 8px;
  border-radius: 100px;
  font-size: 1.2rem;
  font-weight: 500;
  color: white;
  text-align: center;
}

.order-card-body {
  padding: 16px;
  flex-grow: 1;
  background-color: white;
}

.order-info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  font-size: 1.4rem;
  color: var(--colortext2);
}

.order-info-row:last-child {
  margin-bottom: 0;
}

.order-total {
  color: var(--accent);
  font-weight: 600;
}

.order-card-items {
  padding: 0 16px 16px;
  display: flex;
  align-items: start;
  background-color: white;
  position: relative;
  gap: 12px;
}

.item-preview {
  position: relative;
  flex-shrink: 0;
}

.item-preview img {
  width: 60px;
  height: 60px;
  border-radius: 6px;
  overflow: hidden;
  border: 1px solid var(--colorgrey);
  background-color: white;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.item-preview:hover img {
  transform: scale(1.1);
}

.item-count {
  position: absolute;
  top: -5px;
  right: -5px;
  min-width: 24px;
  height: 24px;
  background-color: var(--accent);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  color: white;
  font-weight: 600;
  padding: 0 5px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.order-card-footer {
  padding: 12px 16px;
  background-color: rgba(202, 156, 39, 0.05);
  border-top: 1px solid var(--colorgrey);
  display: flex;
  justify-content: flex-end;
}

.btn-view-order {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: var(--accent);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 1.4rem;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.btn-view-order:hover {
  background-color: #b18725;
  transform: translateY(-2px);
}

/* Không có đơn hàng */
.no-orders {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
  padding: 60px 20px;
  text-align: center;
}

.no-orders-icon {
  font-size: 6rem;
  color: #aaa;
  margin-bottom: 20px;
}

.no-orders-text {
  font-size: 1.8rem;
  color: var(--colortext1);
  margin-bottom: 24px;
  font-weight: 500;
}

.btn-shop-now {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: var(--accent);
  color: white;
  padding: 12px 30px;
  border-radius: 8px;
  font-size: 1.6rem;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-shop-now:hover {
  background-color: #b18725;
  transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
  .order-info-grid {
    grid-template-columns: 1fr;
  }
  
  .orders-list {
    grid-template-columns: 1fr;
  }
  
  .order-header-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .order-items-table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
  
  .order-items-header, 
  .order-item-row {
    width: 100%;
    min-width: 600px;
  }

  .info-item {
    font-size: 1.3rem;
  }
}

.product-name {
  font-size: 1.6rem;
  margin: 0 0 8px 0;
  color: var(--colortext1);
  font-weight: 600;
  line-height: 1.4;
}

.order-item-names {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.main-product-name {
  font-size: 1.4rem;
  font-weight: 500;
  color: var(--colortext1);
  margin-bottom: 5px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
}

.other-products {
  font-size: 1.2rem;
  color: var(--colortext2);
  font-style: italic;
}

/* Responsive cho tên sản phẩm */
@media (max-width: 576px) {
  .order-card-items {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .order-item-names {
    width: 100%;
    margin-top: 10px;
  }
  
  .main-product-name {
    white-space: normal;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .order-actions-top {
    margin-top: 10px;
    width: 100%;
  }
  
  .btn-refresh {
    width: 100%;
  }
  
  .order-header-section {
    flex-direction: column;
    align-items: flex-start;
  }
}

.product-rating {
  margin-bottom: 8px;
  font-size: 1.3rem;
  display: flex;
  align-items: center;
}

.product-rating i {
  color: #ffc107;
  margin-right: 2px;
}

.product-rating i.bi-star-fill,
.product-rating i.bi-star-half {
  color: #ffc107;
}

.product-rating i.bi-star {
  color: #d1d1d1;
}
</style>
