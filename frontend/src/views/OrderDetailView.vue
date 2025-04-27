<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import axios from "axios";


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

const order = ref<Order | null>(null);
const loading = ref(true);
const error = ref<string | null>(null);

// Lấy id đơn hàng từ URL params
const orderId = route.params.id;

// Hàm lấy chi tiết đơn hàng từ API
const fetchOrderDetail = async () => {
  loading.value = true;
  error.value = null;

  try {
    // Lấy token xác thực
    const token =
      localStorage.getItem("auth_token") || localStorage.getItem("user_token");

    if (!token) {
      error.value = "Bạn cần đăng nhập để xem chi tiết đơn hàng";
      loading.value = false;
      return;
    }

    // Gọi API lấy chi tiết đơn hàng
    const response = await axios.get(`/api/orders/${orderId}`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        "Cache-Control": "no-cache, no-store, must-revalidate",
        Pragma: "no-cache",
        Expires: "0"
      },
      withCredentials: true,
    });

    const result = response.data;

    if (result.status === "success" && response.status === 200) {
      if (result.data) {
        // Xử lý dữ liệu từ API
        const apiData = result.data;
        const mappedOrder: Order = {
          id: apiData.id,
          Ma_don_hang: apiData.Ma_don_hang,
          order_number: apiData.Ma_don_hang || `DH${apiData.id}`,
          ID_KH: apiData.ID_KH,
          user_id: apiData.ID_KH,
          Trang_thai: apiData.Trang_thai,
          status: mapOrderStatus(apiData.Trang_thai),
          phuong_thuc_thanh_toan: apiData.phuong_thuc_thanh_toan,
          payment_method: mapPaymentMethod(apiData.phuong_thuc_thanh_toan),
          trang_thai_thanh_toan: apiData.trang_thai_thanh_toan,
          payment_status: apiData.trang_thai_thanh_toan === 1 ? "completed" : "pending",
          Tong_tien: parseFloat(apiData.Tong_tien || 0),
          total_price: parseFloat(apiData.Tong_tien || 0),
          ho_ten: apiData.ho_ten,
          shipping_name: apiData.ho_ten,
          so_dien_thoai: apiData.so_dien_thoai,
          shipping_phone: apiData.so_dien_thoai,
          email: apiData.email,
          dia_chi: apiData.dia_chi,
          shipping_address: apiData.dia_chi,
          Ngay_mua: apiData.Ngay_mua,
          created_at: apiData.created_at,
          updated_at: apiData.updated_at,
          items: [],
        };

        // Xử lý chi tiết đơn hàng
        let orderItems: any[] = [];
        if (apiData.orderItems && Array.isArray(apiData.orderItems)) {
          orderItems = apiData.orderItems;
        } else if (apiData.order_items && Array.isArray(apiData.order_items)) {
          orderItems = apiData.order_items;
        } else if (apiData.chi_tiet && Array.isArray(apiData.chi_tiet)) {
          orderItems = apiData.chi_tiet;
        }

        if (orderItems && orderItems.length > 0) {
          mappedOrder.items = orderItems.map((item: any) => {
            const productData = item.product || {};
            return {
              id: item.id,
              ID_DH: item.ID_DH,
              ID_SP: item.ID_SP,
              user_id: item.user_id,
              So_luong: item.So_luong || 1,
              Gia: item.Gia || item.don_gia || 0,
              don_gia: item.don_gia || item.Gia || 0,
              Thanh_tien: item.Thanh_tien || (item.So_luong * item.don_gia) || 0,
              color_id: item.color_id,
              size_id: item.size_id,
              hinh_anh: item.hinh_anh,
              product: {
                id: productData.id || item.ID_SP,
                Ten_san_pham: productData.Ten_san_pham || "Sản phẩm không có tên",
                name: productData.Ten_san_pham || "Sản phẩm không có tên",
                Anh_dai_dien: productData.Anh_dai_dien || item.hinh_anh || "default.jpg",
                image: productData.Anh_dai_dien || item.hinh_anh || "default.jpg",
                Gia: item.Gia || item.don_gia || 0,
                current_price: item.Gia || item.don_gia || 0,
              },
              color: item.color ? {
                id: item.color.id || item.color_id || 0,
                name: item.color.Ten_mau || item.color.name || "Không xác định",
                Ten_mau: item.color.Ten_mau || item.color.name || "Không xác định",
              } : undefined,
              size: item.size ? {
                id: item.size.id || item.size_id || 0,
                name: item.size.Ten_size || item.size.name || "Không xác định",
                Ten_size: item.size.Ten_size || item.size.name || "Không xác định",
              } : undefined,
            };
          });
        }

        order.value = mappedOrder;
      } else {
        order.value = null;
      }
    } else {
      error.value = result.message || "Không thể tải thông tin đơn hàng";
    }
  } catch (err) {
    console.error("Lỗi khi tải chi tiết đơn hàng:", err);
    error.value = "Lỗi kết nối khi tải thông tin đơn hàng";
  } finally {
    loading.value = false;
  }
};

// Hàm map trạng thái đơn hàng
function mapOrderStatus(status: number | string): string {
  if (typeof status === 'number') {
    switch (status) {
      case 0: return "cancelled";
      case 1: return "pending";
      case 2: return "processing";
      case 3: return "shipped";
      case 4: return "delivered";
      default: return "pending";
    }
  }
  return String(status);
}

// Hàm map phương thức thanh toán
function mapPaymentMethod(method: number | string): string {
  if (typeof method === 'number') {
    switch (method) {
      case 1: return "cod";
      case 2: return "bank_transfer";
      case 3: return "momo";
      default: return "cod";
    }
  }
  return String(method);
}

// Hàm tạo đơn hàng mẫu cho trường hợp không có API
const createSampleOrder = () => {
  order.value = {
    id: parseInt(orderId as string),
    order_number: `DH${Date.now().toString().substring(6)}`,
    user_id: 1,
    status: "pending",
    payment_method: "cod",
    payment_status: "pending",
    total_price: 3360000,
    Tong_tien: 3360000,
    shipping_address: "123 Đường ABC, Quận 1, TP. Hồ Chí Minh",
    shipping_phone: "0987654321",
    shipping_name: "Nguyễn Văn A",
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    items: [
      {
        id: 1,
        ID_DH: parseInt(orderId as string),
        ID_SP: 1,
        user_id: 1,
        So_luong: 1,
        Gia: 2800000,
        don_gia: 2800000,
        Thanh_tien: 2800000,
        product: {
          id: 1,
          name: "Quần ba môn Nam Compressport Tri Under Control Short - Đen (Black)",
          image: "quan-ba-mon-nam-1.jpg",
          current_price: 2800000,
        },
      },
      {
        id: 2,
        ID_DH: parseInt(orderId as string),
        ID_SP: 2,
        user_id: 1,
        So_luong: 1,
        Gia: 560000,
        don_gia: 560000,
        Thanh_tien: 560000,
        product: {
          id: 2,
          name: "Tất chạy bộ Compressport Pro Racing Socks v4.0 Run High - Trắng/Đỏ",
          image: "tat-chay-bo-1.jpg",
          current_price: 560000,
        },
      },
    ],
  };

  loading.value = false;
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

// Quay lại trang danh sách đơn hàng
const goBackToOrders = () => {
  router.push("/don-hang");
};

onMounted(async () => {
  if (!orderId) {
    error.value = "Mã đơn hàng không hợp lệ";
    loading.value = false;
    return;
  }

  try {
    await fetchOrderDetail();

    // Nếu không lấy được đơn hàng, tạo đơn hàng mẫu
    if (!order.value) {
      createSampleOrder();
    }
  } catch (err) {
    console.error("Lỗi khi tải trang chi tiết đơn hàng:", err);
    error.value = "Đã xảy ra lỗi khi tải trang chi tiết đơn hàng";
    loading.value = false;
  }
});
</script>

<template>
  <div class="order-detail-container py-5">
    <div class="order-detail-header">
      <button @click="goBackToOrders" class="btn-back">
        <i class="bi bi-arrow-left"></i> Quay lại đơn hàng
      </button>
      <h1 class="order-detail-title">Chi tiết đơn hàng</h1>
    </div>

    <!-- Thông báo lỗi nếu có -->
    <div v-if="error" class="alert-error">
      <div class="alert-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <p>{{ error }}</p>
    </div>

    <!-- Hiển thị loading -->
    <div v-if="loading" class="text-center py-5">
      <div class="loading-spinner">
        <div class="spinner"></div>
      </div>
      <p class="mt-3">Đang tải thông tin đơn hàng...</p>
    </div>

    <!-- Chi tiết đơn hàng -->
    <div v-else-if="order" class="order-detail-content">
      <!-- Thông tin đơn hàng -->
      <div class="order-info">
        <div class="info-header">
          <h2>Đơn hàng #{{ order.Ma_don_hang || order.order_number }}</h2>
          <div :class="['order-status', getStatusClass(order.status || '')]">
            {{ getStatusText(order.status || '') }}
          </div>
        </div>

        <div class="info-date">
          <i class="bi bi-calendar3"></i> Ngày đặt hàng:
          {{ formatDate(order.Ngay_mua || order.created_at) }}
        </div>

        <!-- Thông tin khách hàng và giao hàng -->
        <div class="customer-shipping-info">
          <div class="info-section">
            <h3><i class="bi bi-person"></i> Thông tin khách hàng</h3>
            <div class="info-content">
              <p>
                <strong>Họ tên:</strong>
                {{
                  order.ho_ten ||
                  order.shipping_name ||
                  authStore.userFullName ||
                  "Không có thông tin"
                }}
              </p>
              <p>
                <strong>Email:</strong>
                {{ order.email || authStore.userEmail || "Không có thông tin" }}
              </p>
              <p>
                <strong>Số điện thoại:</strong>
                {{ order.so_dien_thoai || order.shipping_phone || "Không có thông tin" }}
              </p>
            </div>
          </div>

          <div class="info-section">
            <h3><i class="bi bi-geo-alt"></i> Thông tin giao hàng</h3>
            <div class="info-content">
              <p>
                <strong>Địa chỉ:</strong>
                {{ order.dia_chi || order.shipping_address || "Không có thông tin" }}
              </p>
              <p>
                <strong>Phương thức thanh toán:</strong>
                {{
                  (order.payment_method === "cod" || order.phuong_thuc_thanh_toan === 1)
                    ? "Thanh toán khi nhận hàng"
                    : (order.payment_method === "bank_transfer" || order.phuong_thuc_thanh_toan === 2)
                    ? "Chuyển khoản ngân hàng"
                    : (order.payment_method === "momo" || order.phuong_thuc_thanh_toan === 3)
                    ? "Ví MoMo"
                    : "Không xác định"
                }}
              </p>
              <p>
                <strong>Trạng thái thanh toán:</strong>
                {{
                  (order.payment_status === "pending" || order.trang_thai_thanh_toan === 0)
                    ? "Chưa thanh toán"
                    : (order.payment_status === "completed" || order.trang_thai_thanh_toan === 1)
                    ? "Đã thanh toán"
                    : "Đang xử lý"
                }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách sản phẩm -->
      <div class="order-products">
        <h3>Sản phẩm đã đặt</h3>

        <div class="product-list">
          <div v-for="item in order.items" :key="item.id" class="product-item">
            <div class="product-image">
              <img
                :src="'/public/img/img_sp/' + (item.hinh_anh || (item.product ? item.product.image || item.product.Anh_dai_dien : 'default.jpg'))"
                :alt="item.product ? item.product.name || item.product.Ten_san_pham : 'Sản phẩm'"
              />
            </div>
            <div class="product-details">
              <h4 class="product-name">{{ item.product ? item.product.name || item.product.Ten_san_pham : 'Sản phẩm không có tên' }}</h4>
              <div class="product-variants">
                <span v-if="item.color">Màu: {{ item.color.name || item.color.Ten_mau }}</span>
                <span v-if="item.size">Size: {{ item.size.name || item.size.Ten_size }}</span>
              </div>
              <div class="product-price">{{ formatCurrency(item.Gia || item.don_gia || 0) }}</div>
            </div>
            <div class="product-quantity">
              <span>SL: {{ item.So_luong }}</span>
            </div>
            <div class="product-subtotal">
              {{ formatCurrency(item.Thanh_tien || ((item.So_luong || 1) * (item.Gia || item.don_gia || 0))) }}
            </div>
          </div>
        </div>

        <!-- Tổng tiền đơn hàng -->
        <div class="order-summary">
          <div class="summary-row">
            <span>Tổng tiền sản phẩm:</span>
            <span>{{ formatCurrency(order.Tong_tien || order.total_price || 0) }}</span>
          </div>
          <div class="summary-row">
            <span>Phí vận chuyển:</span>
            <span>0đ</span>
          </div>
          <div class="summary-row total">
            <span>Tổng thanh toán:</span>
            <span>{{ formatCurrency(order.Tong_tien || order.total_price || 0) }}</span>
          </div>
        </div>
      </div>

      <!-- Nút thao tác với đơn hàng -->
      <div class="order-actions">
        <a href="/lienhe" class="btn-contact">
          <i class="bi bi-headset"></i> Liên hệ hỗ trợ
        </a>

        <a href="/sanpham" class="btn-continue-shopping">
          <i class="bi bi-cart"></i> Tiếp tục mua sắm
        </a>
      </div>
    </div>

    <!-- Trường hợp không tìm thấy đơn hàng -->
    <div v-else-if="!loading && !error" class="not-found">
      <div class="not-found-icon">
        <i class="bi bi-file-earmark-x"></i>
      </div>
      <h3>Không tìm thấy đơn hàng</h3>
      <p>Đơn hàng bạn tìm kiếm không tồn tại hoặc đã bị xóa</p>
      <button @click="goBackToOrders" class="btn-back-to-orders">
        Quay lại danh sách đơn hàng
      </button>
    </div>
  </div>
</template>

<style scoped>
.order-detail-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 20px;
  background-color: #f5f7fa;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.order-detail-header {
  display: flex;
  align-items: center;
  margin-bottom: 30px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  background-color: transparent;
  color: var(--accent);
  border: none;
  padding: 10px 15px;
  border-radius: 8px;
  font-size: 1.4rem;
  cursor: pointer;
  transition: all 0.2s;
  margin-right: 20px;
}

.btn-back:hover {
  background-color: rgba(202, 156, 39, 0.1);
}

.btn-back i {
  margin-right: 8px;
}

.order-detail-title {
  font-size: 2.4rem;
  color: var(--colortext1);
  font-weight: 600;
  margin: 0;
  text-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.alert-error {
  display: flex;
  align-items: center;
  padding: 16px 20px;
  border-radius: 8px;
  margin-bottom: 25px;
  background-color: #fdeded;
  color: #ea4335;
  border-left: 4px solid #ea4335;
}

.alert-icon {
  font-size: 2rem;
  margin-right: 15px;
}

.loading-spinner {
  display: flex;
  justify-content: center;
  margin: 40px 0;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top-color: var(--accent);
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.order-detail-content {
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.order-info {
  padding: 25px;
  border-bottom: 1px solid var(--colorgrey);
}

.info-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.info-header h2 {
  font-size: 2rem;
  color: var(--colortext1);
  margin: 0;
}

.order-status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 1.4rem;
  font-weight: 500;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-pending {
  background-color: #fff8e1;
  color: #f57c00;
}

.status-processing {
  background-color: #e3f2fd;
  color: #1976d2;
}

.status-shipped {
  background-color: #e8f5e9;
  color: #388e3c;
}

.status-delivered {
  background-color: #e8f5e9;
  color: #388e3c;
}

.status-cancelled {
  background-color: #ffebee;
  color: #d32f2f;
}

.info-date {
  font-size: 1.4rem;
  color: var(--colortext2);
  margin-bottom: 20px;
}

.info-date i {
  margin-right: 5px;
}

.customer-shipping-info {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}

@media (min-width: 768px) {
  .customer-shipping-info {
    grid-template-columns: 1fr 1fr;
  }
}

.info-section {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid var(--colorgrey);
  transition: transform 0.2s ease;
}

.info-section:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.info-section h3 {
  font-size: 1.6rem;
  color: var(--colortext1);
  margin: 0 0 15px 0;
  display: flex;
  align-items: center;
}

.info-section h3 i {
  margin-right: 8px;
  color: var(--accent);
}

.info-content p {
  font-size: 1.4rem;
  color: var(--colortext2);
  margin: 8px 0;
}

.order-products {
  padding: 25px;
}

.order-products h3 {
  font-size: 1.8rem;
  color: var(--colortext1);
  margin: 0 0 20px 0;
}

.product-list {
  margin-bottom: 30px;
}

.product-item {
  display: flex;
  padding: 15px;
  background-color: #f9f9f9;
  margin-bottom: 15px;
  border-radius: 8px;
  align-items: center;
  border: 1px solid var(--colorgrey);
  transition: transform 0.2s ease;
}

.product-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.product-image {
  width: 80px;
  height: 80px;
  margin-right: 15px;
  border-radius: 6px;
  overflow: hidden;
  border: 1px solid var(--colorgrey);
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-image:hover img {
  transform: scale(1.1);
}

.product-details {
  flex: 1;
}

.product-name {
  font-size: 1.6rem;
  margin: 0 0 8px 0;
  color: var(--colortext1);
}

.product-variants {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 8px;
}

.product-variants span {
  background-color: #f0f0f0;
  font-size: 1.2rem;
  padding: 3px 8px;
  border-radius: 4px;
  color: var(--colortext2);
  border: 1px solid #e0e0e0;
}

.product-price {
  font-size: 1.4rem;
  color: var(--colortext2);
}

.product-quantity {
  margin: 0 15px;
  font-size: 1.4rem;
  color: var(--colortext2);
  min-width: 60px;
  text-align: center;
}

.product-subtotal {
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--accent);
  min-width: 120px;
  text-align: right;
}

.order-summary {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
  border: 1px solid var(--colorgrey);
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 1.5rem;
  color: var(--colortext2);
  margin-bottom: 10px;
}

.summary-row.total {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--colortext1);
  padding-top: 10px;
  margin-top: 10px;
  border-top: 1px solid var(--colorgrey);
}

.summary-row.total span:last-child {
  color: var(--accent);
}

.order-actions {
  display: flex;
  justify-content: center;
  gap: 20px;
  padding: 25px;
  border-top: 1px solid var(--colorgrey);
}

.btn-contact,
.btn-continue-shopping {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 1.5rem;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-contact {
  background-color: transparent;
  color: var(--accent);
  border: 1px solid var(--accent);
}

.btn-contact:hover {
  background-color: rgba(202, 156, 39, 0.05);
  transform: translateY(-2px);
}

.btn-continue-shopping {
  background-color: var(--accent);
  color: white;
  border: none;
}

.btn-continue-shopping:hover {
  background-color: #b18725;
  transform: translateY(-2px);
}

.btn-contact i,
.btn-continue-shopping i {
  margin-right: 8px;
}

.not-found {
  text-align: center;
  padding: 60px 20px;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.not-found-icon {
  font-size: 6rem;
  color: #ccc;
  margin-bottom: 20px;
}

.not-found h3 {
  font-size: 2.2rem;
  color: var(--colortext1);
  margin-bottom: 15px;
}

.not-found p {
  font-size: 1.6rem;
  color: var(--colortext2);
  margin-bottom: 30px;
}

.btn-back-to-orders {
  display: inline-block;
  background-color: var(--accent);
  color: white;
  padding: 12px 30px;
  border-radius: 8px;
  font-size: 1.6rem;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-back-to-orders:hover {
  background-color: #b18725;
  transform: translateY(-2px);
}

@media (max-width: 576px) {
  .product-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .product-image {
    width: 100%;
    height: 150px;
    margin-right: 0;
    margin-bottom: 10px;
  }
  
  .product-subtotal {
    margin-top: 10px;
    text-align: left;
  }
  
  .order-actions {
    flex-direction: column;
  }
  
  .btn-contact, 
  .btn-continue-shopping,
  .btn-back-to-orders {
    width: 100%;
  }
}
</style>
