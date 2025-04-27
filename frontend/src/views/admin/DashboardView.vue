<template>
  <main>
    <section class="admin">
      <!-- Thanh điều hướng bên trái -->
      <div class="admin-left">
        <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
        <h5>Admin</h5>
        <RouterLink to="/admin" class="admin-menu-item active">
          <i class="bi bi-palette2"></i>
          <span>Bảng điều khiển</span>
        </RouterLink>
        <RouterLink to="/admin/quanlidanhmuc" class="admin-menu-item">
          <i class="bi bi-inboxes-fill"></i>
          <span>Quản lý danh mục</span>
        </RouterLink>
        <RouterLink to="/admin/quanlisanpham" class="admin-menu-item">
          <i class="bi bi-box2-fill"></i>
          <span>Quản lý sản phẩm</span>
        </RouterLink>
        <RouterLink to="/admin/quanlinguoidung" class="admin-menu-item">
          <i class="bi bi-people-fill"></i>
          <span>Quản lý người dùng</span>
        </RouterLink>
        <RouterLink to="/admin/quanlidonhang" class="admin-menu-item">
          <i class="bi bi-receipt-cutoff"></i>
          <span>Quản lý đơn hàng</span>
        </RouterLink>
        <RouterLink to="/admin/quanlibinhluan" class="admin-menu-item">
          <i class="bi bi-chat-fill"></i>
          <span>Quản lý bình luận</span>
        </RouterLink>
        <RouterLink to="/admin/quanlibaiviet" class="admin-menu-item">
          <i class="bi bi-book-fill"></i>
          <span>Quản lý bài viết</span>
        </RouterLink>
        <RouterLink to="/admin/quanlidanhgia" class="admin-menu-item">
          <i class="bi bi-star-fill"></i>
          <span>Quản lý đánh giá</span>
        </RouterLink>
        <div class="admin-menu-separator"></div>
        <div class="admin-menu-item" @click="exportData">
          <i class="bi bi-download"></i>
          <span>Xuất dữ liệu</span>
        </div>
        <div class="admin-menu-item" @click="showHelp">
          <i class="bi bi-question-circle"></i>
          <span>Trợ giúp</span>
        </div>
        <div class="admin-menu-item" @click="logout">
          <i class="bi bi-box-arrow-right"></i>
          <span>Đăng xuất</span>
        </div>
      </div>

      <!-- Phần nội dung chính -->
      <div class="admin-right">
        <div class="header-container">
          <div class="title-section">
            <h5>Bảng điều khiển</h5>
            <p class="text-muted">Tổng quan hoạt động của hệ thống</p>
          </div>
          <div class="button-wrapper">
            <button class="btn-xuat-admin" @click="exportData">
              <i class="bi bi-download"></i>
              <span>Xuất báo cáo</span>
            </button>
          </div>
        </div>

        <div class="dashboard-content">
          <!-- Loading and error state -->
          <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2">Đang tải dữ liệu...</p>
          </div>

          <div v-else-if="error" class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ error }}
          </div>

          <div v-else>
            <!-- Stats Overview Cards -->
            <div class="row g-3 stats-cards mb-4">
              <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="card-icon card-type-users">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <h6 class="card-title">TỔNG NGƯỜI DÙNG</h6>
                    <div class="card-value">{{ stats.user_stats ? Object.values(stats.user_stats).reduce((a, b) => a + b, 0) : 0 }}</div>
                    <div class="card-progress up">
                      <i class="bi bi-arrow-up-short"></i>
                      <span>+5.2% so với tháng trước</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="card-icon card-type-orders">
                      <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <h6 class="card-title">ĐƠN HÀNG</h6>
                    <div class="card-value">{{ stats.order_stats?.total || 0 }}</div>
                    <div class="card-progress up">
                      <i class="bi bi-arrow-up-short"></i>
                      <span>+2.8% so với tháng trước</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="card-icon card-type-revenue">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <h6 class="card-title">DOANH THU</h6>
                    <div class="card-value">{{ formatCurrency(stats.order_stats?.revenue || 0) }}</div>
                    <div class="card-progress up">
                      <i class="bi bi-arrow-up-short"></i>
                      <span>+12.3% so với tháng trước</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="card-icon card-type-products">
                      <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h6 class="card-title">SẢN PHẨM</h6>
                    <div class="card-value">{{ stats.product_stats?.total || 0 }}</div>
                    <div class="card-progress down">
                      <i class="bi bi-arrow-down-short"></i>
                      <span>{{ stats.product_stats?.low_stock || 0 }} sản phẩm sắp hết</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Biểu đồ doanh thu với thiết kế hiện đại -->
            <div class="chart-container">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h5 class="mb-0">Thống kê doanh thu</h5>
                  <p class="text-muted mb-0 mt-1">Doanh thu từ đơn hàng sản phẩm và đặt sân</p>
                </div>
                <div class="chart-filter">
                  <button class="btn" :class="{ active: selectedPeriod === 'week' }" @click="selectedPeriod = 'week'; fetchChartData()">
                    Tuần
                  </button>
                  <button class="btn" :class="{ active: selectedPeriod === 'month' }" @click="selectedPeriod = 'month'; fetchChartData()">
                    Tháng
                  </button>
                  <button class="btn" :class="{ active: selectedPeriod === 'year' }" @click="selectedPeriod = 'year'; fetchChartData()">
                    Năm
                  </button>
                </div>
              </div>
              <div class="chart-wrapper" style="height: 350px; position: relative;">
                <div v-if="chartLoading" class="chart-loading">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                  </div>
                  <p class="mt-2">Đang tải dữ liệu biểu đồ...</p>
                </div>
                <canvas ref="revenueChart"></canvas>
              </div>
              <div class="chart-legend mt-3">
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #4e73df;"></div>
                  <span>Tổng doanh thu</span>
                </div>
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #1cc88a;"></div>
                  <span>Doanh thu đơn hàng</span>
                </div>
                <div class="legend-item">
                  <div class="legend-color" style="background-color: #f6c23e;"></div>
                  <span>Doanh thu đặt sân</span>
                </div>
              </div>
            </div>

            <!-- Bố cục các bảng dữ liệu với thiết kế hiện đại -->
            <div class="row table-cards">
              <div class="col-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn hàng gần đây</h5>
                    <RouterLink to="/admin/quanlidonhang" class="btn btn-primary">
                      Xem tất cả
                    </RouterLink>
                  </div>
                  <div class="card-body p-0">
                    <div v-if="loading" class="text-center py-5">
                      <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                      </div>
                    </div>
                    <div v-else-if="stats.recent_orders && stats.recent_orders.length > 0">
                      <div class="table-responsive">
                        <table class="table table-hover mb-0">
                          <thead>
                            <tr>
                              <th>Mã đơn</th>
                              <th>Khách hàng</th>
                              <th>Ngày đặt</th>
                              <th>Tổng tiền</th>
                              <th>Trạng thái</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="order in stats.recent_orders" :key="order.id">
                              <td><strong>#{{ order.id }}</strong></td>
                              <td>{{ order.user_name || 'Khách vãng lai' }}</td>
                              <td>{{ formatDate(order.created_at) }}</td>
                              <td><strong>{{ formatCurrency(order.total_price) }}</strong></td>
                              <td>
                                <span class="badge-status" :class="'badge-status-' + getOrderStatusClass(order.status)">
                                  {{ getOrderStatusLabel(order.status) }}
                                </span>
                              </td>
                              <td>
                                <RouterLink :to="`/admin/quanlidonhang/${order.id}`" class="btn btn-sm btn-outline-primary">
                                  Chi tiết
                                </RouterLink>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div v-else class="empty-data">
                      <p class="text-muted mb-0">Chưa có đơn hàng nào gần đây</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Người dùng mới</h5>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead>
                          <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày đăng ký</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="user in stats.recent_users" :key="user.id">
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                              <span :class="getRoleBadgeClass(user.role)">
                                {{ getRoleLabel(user.role) }}
                              </span>
                            </td>
                            <td>{{ formatDate(user.created_at) }}</td>
                          </tr>
                          <tr v-if="!stats.recent_users?.length">
                            <td colspan="4" class="text-center py-3">
                              Không có dữ liệu
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Đơn đặt sân gần đây</h5>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead>
                          <tr>
                            <th>Sân</th>
                            <th>Khách hàng</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="booking in stats.recent_bookings"
                            :key="booking.id"
                          >
                            <td>{{ booking.field?.Ten_san || "N/A" }}</td>
                            <td>{{ booking.customer?.name || "N/A" }}</td>
                            <td>
                              <span
                                :class="
                                  getBookingStatusBadgeClass(booking.Trang_thai)
                                "
                              >
                                {{ getBookingStatusLabel(booking.Trang_thai) }}
                              </span>
                            </td>
                            <td>{{ formatDate(booking.Ngay_dat) }}</td>
                          </tr>
                          <tr v-if="!stats.recent_bookings?.length">
                            <td colspan="4" class="text-center py-3">
                              Không có dữ liệu
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sản phẩm bán chạy -->
            <div class="row table-cards">
              <div class="col-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sản phẩm bán chạy</h5>
                    <RouterLink to="/admin/quanlisanpham" class="btn btn-primary">
                      Quản lý sản phẩm
                    </RouterLink>
                  </div>
                  <div class="card-body p-0">
                    <div v-if="loading" class="text-center py-5">
                      <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                      </div>
                    </div>
                    <div v-else-if="stats.top_selling_products && stats.top_selling_products.length > 0">
                      <div class="table-responsive">
                        <table class="table table-hover mb-0">
                          <thead>
                            <tr>
                              <th>Sản phẩm</th>
                              <th>Danh mục</th>
                              <th>Giá</th>
                              <th>Đã bán</th>
                              <th>Đánh giá</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="product in stats.top_selling_products" :key="product.id">
                              <td>
                                <div class="d-flex align-items-center">
                                  <div class="product-image me-3">
                                    <img 
                                      :src="getProductImageUrl(product.Hinh_anh)" 
                                      :alt="product.Ten_SP" 
                                      class="img-fluid"
                                      style="width: 100%; height: 100%; object-fit: cover;"
                                      @error="$event.target.src = '/img/img_sp/d5-bong1.png'"
                                      loading="lazy"
                                    >
                                  </div>
                                  <div class="product-name">
                                    <h6 class="mb-0 fw-semibold">{{ product.Ten_SP }}</h6>
                                    <small class="text-muted">ID: #{{ product.id }}</small>
                                  </div>
                                </div>
                              </td>
                              <td>{{ product.Ten_LSP || 'Không phân loại' }}</td>
                              <td><strong>{{ formatCurrency(product.Gia) }}</strong></td>
                              <td><span class="badge bg-success px-2 py-1">{{ product.sold_count || 0 }}</span></td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <span class="me-2 fw-bold">{{ product.So_sao || 0 }}</span>
                                  <div class="star-ratings">
                                    <i 
                                      v-for="i in 5" 
                                      :key="i" 
                                      class="bi" 
                                      :class="[i <= Math.round(product.So_sao || 0) ? 'bi-star-fill text-warning' : 'bi-star text-muted']"
                                    ></i>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <RouterLink :to="`/admin/quanlisanpham/${product.id}`" class="btn btn-sm btn-outline-primary">
                                  Chi tiết
                                </RouterLink>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div v-else class="empty-data">
                      <p class="text-muted mb-0">Chưa có dữ liệu sản phẩm bán chạy</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add Top Products Section -->
            <div class="row mb-4" v-if="!loadingTopProducts">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Top sản phẩm bán chạy</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th>Lượt bán</th>
                            <th>Đánh giá</th>
                            <th>Giá</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(product, index) in topProducts" :key="product.id">
                            <td>{{ index + 1 }}</td>
                            <td>
                              <div class="d-flex align-items-center">
                                <img 
                                  :src="getProductImageUrl(product.Hinh_anh)" 
                                  class="product-thumbnail me-2"
                                  :alt="product.Ten_SP"
                                  @error="$event.target.src = '/img/img_sp/d5-bong1.png'"
                                  loading="lazy"
                                />
                                <span>{{ product.Ten_SP }}</span>
                              </div>
                            </td>
                            <td>{{ product.so_luong_ban || 0 }}</td>
                            <td>
                              <div class="rating">
                                <i 
                                  v-for="i in 5" 
                                  :key="i" 
                                  class="bi" 
                                  :class="i <= Math.round(product.so_sao || 0) ? 'bi-star-fill text-warning' : 'bi-star'"
                                ></i>
                                <span class="ms-1">({{ product.so_sao ? product.so_sao.toFixed(1) : '0.0' }})</span>
                              </div>
                            </td>
                            <td>{{ formatCurrency(product.Gia) }}</td>
                          </tr>
                          <tr v-if="!topProducts || topProducts.length === 0">
                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row mb-4" v-if="loadingTopProducts">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Top sản phẩm bán chạy</h5>
                  </div>
                  <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Đang tải dữ liệu...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from "vue";
import Chart from "chart.js/auto";
import { useToast } from "vue-toastification";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import axios from "axios";

// Định nghĩa store và router
const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

// Trạng thái biểu đồ
const revenueChart = ref(null);
let revenueChartInstance = null;
const selectedPeriod = ref("week");
const periodLabels = {
  week: "Tuần",
  month: "Tháng",
  year: "Năm",
};
const chartLoading = ref(false);

// Định nghĩa cho dữ liệu top sản phẩm
const topProducts = ref([]);
const loadingTopProducts = ref(true);

// Trạng thái và dữ liệu
const stats = ref({
  total_products: 0,
  total_orders: 0,
  total_users: 0,
  total_revenue: 0,
  recent_orders: [],
  top_selling_products: [],
  user_stats: {},
  booking_stats: {},
  order_stats: {},
  field_stats: {},
  content_stats: {},
  recent_users: [],
  recent_bookings: []
});
const loading = ref(true);
const error = ref(null);

// Format currency
const formatCurrency = (value) => {
  if (!value) return '0 ₫';
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(value);
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Get role badge class
const getRoleBadgeClass = (role) => {
  const classes = {
    admin: "badge bg-danger",
    field_owner: "badge bg-success",
    user: "badge bg-primary",
  };
  return classes[role] || "badge bg-secondary";
};

// Get role label
const getRoleLabel = (role) => {
  const labels = {
    admin: "Quản trị viên",
    field_owner: "Chủ sân",
    user: "Khách hàng",
  };
  return labels[role] || role;
};

// Get booking status badge class
const getBookingStatusBadgeClass = (status) => {
  const classes = {
    0: "badge bg-danger",
    1: "badge bg-warning",
    2: "badge bg-success",
  };
  return classes[status] || "badge bg-secondary";
};

// Get booking status label
const getBookingStatusLabel = (status) => {
  const labels = {
    0: "Đã hủy",
    1: "Chờ xác nhận",
    2: "Đã xác nhận",
  };
  return labels[status] || "Không xác định";
};

// Get order status label
const getOrderStatusLabel = (status) => {
  const statusMap = {
    0: 'Chờ xác nhận',
    1: 'Đã xác nhận',
    2: 'Đang giao hàng',
    3: 'Đã giao hàng',
    4: 'Đã hủy'
  };
  return statusMap[status] || 'Không xác định';
};

// Get order status class
const getOrderStatusClass = (status) => {
  const classMap = {
    0: 'badge bg-warning',
    1: 'badge bg-info',
    2: 'badge bg-primary',
    3: 'badge bg-success',
    4: 'badge bg-danger'
  };
  return classMap[status] || 'badge bg-secondary';
};

// Đăng xuất khỏi hệ thống
const logout = () => {
  authStore.logout();
  toast.success("Đăng xuất thành công!");
  router.push("/login");
};

// Hiển thị trợ giúp
const showHelp = () => {
  toast.info("Chức năng trợ giúp đang được phát triển.");
};

// Xuất dữ liệu báo cáo
const exportData = () => {
  toast.info("Chức năng xuất báo cáo đang được phát triển.");
};

// Fetch dashboard data
const fetchDashboardData = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    try {
      const response = await axios.get('/api/admin/dashboard', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      
      // Check if the response has the expected data structure
      if (response.data && response.data.status !== 'error') {
        // Data is directly available in response.data, not in response.data.data
        stats.value = response.data;
        
        // If there's no recent orders data, fetch it separately
        if (!stats.value.recent_orders || stats.value.recent_orders.length === 0) {
          await fetchRecentOrders();
        }
        
        // If there's no top products data, fetch it separately
        if (!stats.value.top_selling_products || stats.value.top_selling_products.length === 0) {
          await fetchTopProducts();
        }
      } else {
        console.log('Định dạng phản hồi không hợp lệ từ API dashboard, sử dụng dữ liệu mẫu');
        stats.value = getDummyDashboardData();
        await fetchRecentOrders();
        await fetchTopProducts();
      }
    } catch (apiError) {
      console.log('API dashboard chưa được triển khai, sử dụng dữ liệu mẫu:', apiError.message);
      stats.value = getDummyDashboardData();
      await fetchRecentOrders();
      await fetchTopProducts();
    }
  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = 'Không thể tải dữ liệu dashboard. Sử dụng dữ liệu mẫu.';
    
    // Initialize with dummy data
    stats.value = getDummyDashboardData();
    
    // Try to load partial data
    try {
      await fetchRecentOrders();
      await fetchTopProducts();
    } catch (e) {
      console.error('Error fetching partial data:', e);
    }
  } finally {
    loading.value = false;
  }
};

// Hàm tạo dữ liệu mẫu cho dashboard
const getDummyDashboardData = () => {
  return {
    total_products: 256,
    total_orders: 124,
    total_users: 568,
    total_revenue: 12500000,
    user_stats: {
      total: 568,
      active: 430,
      new_this_month: 43,
      new_this_week: 12
    },
    booking_stats: {
      total: 86,
      pending: 12,
      confirmed: 68,
      canceled: 6
    },
    order_stats: {
      total: 124,
      pending: 15,
      processing: 32,
      completed: 68,
      canceled: 9,
      revenue: 12500000
    },
    field_stats: {
      total: 18,
      active: 15,
      maintenance: 3
    },
    content_stats: {
      articles: 46,
      comments: 152,
      ratings: 312
    },
    recent_users: [
      { id: 1, name: 'Nguyễn Văn A', email: 'nguyenvana@example.com', role: 'user', created_at: new Date().toISOString() },
      { id: 2, name: 'Trần Thị B', email: 'tranthib@example.com', role: 'user', created_at: new Date().toISOString() },
      { id: 3, name: 'Lê Văn C', email: 'levanc@example.com', role: 'field_owner', created_at: new Date().toISOString() },
      { id: 4, name: 'Phạm Thị D', email: 'phamthid@example.com', role: 'user', created_at: new Date().toISOString() }
    ],
    recent_bookings: [
      { id: 1, field: { Ten_san: 'Sân bóng Thống Nhất' }, customer: { name: 'Nguyễn Văn A' }, Trang_thai: 1, Ngay_dat: new Date().toISOString() },
      { id: 2, field: { Ten_san: 'Sân tennis Olympic' }, customer: { name: 'Trần Thị B' }, Trang_thai: 2, Ngay_dat: new Date().toISOString() },
      { id: 3, field: { Ten_san: 'Sân cầu lông Hòa Bình' }, customer: { name: 'Lê Văn C' }, Trang_thai: 2, Ngay_dat: new Date().toISOString() },
      { id: 4, field: { Ten_san: 'Sân bóng rổ Phú Thọ' }, customer: { name: 'Phạm Thị D' }, Trang_thai: 0, Ngay_dat: new Date().toISOString() }
    ],
    product_stats: {
      total: 256,
      low_stock: 15,
      out_of_stock: 3
    },
    recent_orders: [],
    top_selling_products: []
  };
};

// Hàm lấy dữ liệu đơn hàng gần đây
const fetchRecentOrders = async () => {
  try {
    try {
      const response = await axios.get('/api/admin/orders?limit=5&sort=created_at:desc', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      
      if (response.data && response.data.data) {
        stats.value.recent_orders = response.data.data || [];
        console.log('Đơn hàng gần đây đã được tải:', stats.value.recent_orders.length);
      } else {
        console.log('Định dạng phản hồi không hợp lệ từ API đơn hàng, sử dụng dữ liệu mẫu');
        stats.value.recent_orders = getDummyRecentOrders();
      }
    } catch (apiError) {
      console.log('API đơn hàng chưa được triển khai, sử dụng dữ liệu mẫu:', apiError.message);
      stats.value.recent_orders = getDummyRecentOrders();
    }
  } catch (err) {
    console.error('Error fetching recent orders:', err);
    stats.value.recent_orders = getDummyRecentOrders();
  }
};

// Hàm tạo dữ liệu mẫu cho đơn hàng gần đây
const getDummyRecentOrders = () => {
  const statuses = [0, 1, 2, 3, 4]; // Các trạng thái đơn hàng
  const users = ['Nguyễn Văn A', 'Trần Thị B', 'Lê Văn C', 'Phạm Thị D', 'Hoàng Văn E'];
  
  return Array.from({ length: 5 }, (_, index) => {
    const orderDate = new Date();
    orderDate.setDate(orderDate.getDate() - Math.floor(Math.random() * 7)); // Trong 7 ngày gần đây
    
    return {
      id: 100 + index,
      user_name: users[Math.floor(Math.random() * users.length)],
      created_at: orderDate.toISOString(),
      total_price: Math.floor(Math.random() * 2000000) + 500000, // 500k - 2.5M
      status: statuses[Math.floor(Math.random() * statuses.length)],
      Ma_don_hang: `DH${Math.floor(Math.random() * 10000).toString().padStart(5, '0')}`
    };
  });
};

// Hàm lấy dữ liệu sản phẩm bán chạy
const fetchTopProducts = async () => {
  loadingTopProducts.value = true;
  try {
    try {
      const response = await axios.get('/api/admin/products/top-selling', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      
      if (response.data && (response.data.data || response.data.status === 'success')) {
        // Hỗ trợ cả hai định dạng: data trực tiếp hoặc nằm trong data.data
        const productData = response.data.data || response.data || [];
        
        // Log đường dẫn hình ảnh để debug
        console.log('Dữ liệu hình ảnh từ API:', productData.map(p => p.Hinh_anh));
        
        // Đảm bảo dữ liệu sản phẩm có đầy đủ thông tin cần thiết
        topProducts.value = productData.map(product => {
          // Đảm bảo các trường cần thiết đều tồn tại
          const processedProduct = {
            ...product,
            Hinh_anh: product.Hinh_anh || product.hinh_anh || product.Anh_dai_dien || null,
            Ten_SP: product.Ten_SP || product.Ten_san_pham || 'Sản phẩm không tên',
            Gia: product.Gia || product.don_gia || 0,
            so_luong_ban: product.so_luong_ban || product.sold_count || 0,
            so_sao: product.so_sao || product.So_sao || 0
          };
          
          // Log đường dẫn ảnh đã xử lý
          console.log(`Sản phẩm ${processedProduct.Ten_SP}: ${processedProduct.Hinh_anh} => ${getProductImageUrl(processedProduct.Hinh_anh)}`);
          
          return processedProduct;
        });
        
        console.log('Sản phẩm bán chạy đã được tải:', topProducts.value.length);
      } else {
        console.log('Sử dụng dữ liệu mẫu vì API trả về dữ liệu không hợp lệ');
        topProducts.value = getDummyTopProducts();
      }
    } catch (apiError) {
      console.log('API sản phẩm bán chạy chưa được triển khai, sử dụng dữ liệu mẫu:', apiError.message);
      topProducts.value = getDummyTopProducts();
    }
  } catch (err) {
    console.error('Error fetching top products:', err);
    topProducts.value = getDummyTopProducts();
    toast.error('Không thể tải danh sách sản phẩm bán chạy, sử dụng dữ liệu mẫu');
  } finally {
    loadingTopProducts.value = false;
  }
};

// Hàm tạo dữ liệu mẫu cho sản phẩm bán chạy
const getDummyTopProducts = () => {
  const productNames = [
    'Giày thể thao Nike Air Max',
    'Áo thun Adidas Pro',
    'Quần short Puma Sport',
    'Giày đá bóng Mizuno Wave',
    'Áo khoác thể thao Under Armour',
    'Bộ đồ tập gym Nike Dri-FIT',
    'Giày tennis Asics Gel',
    'Áo bóng đá Việt Nam',
    'Balo thể thao Adidas',
    'Vợt cầu lông Yonex'
  ];
  
  const productCategories = [
    'Giày thể thao',
    'Quần áo',
    'Phụ kiện',
    'Dụng cụ thể thao'
  ];
  
  const productImages = [
    'd1-giay1.png',
    'd1-giay2.png',
    'd2-ao1.png',
    'd2-ao2.png',
    'd3-ao1.png',
    'd4-quan1.png',
    'd5-bong1.png',
    'd5-tennis1.png',
    'd6-bang1.png',
    'd5-vot1.png'
  ];
  
  return productNames.map((name, index) => {
    return {
      id: index + 1,
      Ten_SP: name,
      Ten_LSP: productCategories[Math.floor(Math.random() * productCategories.length)],
      Gia: Math.floor(Math.random() * 1000000) + 500000,
      Hinh_anh: productImages[index],
      so_luong_ban: Math.floor(Math.random() * 100) + 10,
      so_sao: (Math.random() * 3) + 2, // 2-5 sao
      Anh_dai_dien: productImages[index]
    };
  });
};

// Fetch chart data
const fetchChartData = async () => {
  chartLoading.value = true;
  try {
    // Cố gắng gọi API
    try {
      const response = await axios.get(
        `/api/admin/stats/revenue?period=${selectedPeriod.value}`,
        {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        }
      );
      
      if (response.data && response.data.status === 'success') {
        initCharts(response.data.data);
      } else {
        console.log('Sử dụng dữ liệu mẫu vì API trả về dữ liệu không hợp lệ');
        initCharts(getDummyChartData());
      }
    } catch (apiError) {
      console.log('API chưa được triển khai, sử dụng dữ liệu mẫu:', apiError.message);
      initCharts(getDummyChartData());
    }
  } catch (err) {
    console.error("Error fetching chart data:", err);
    toast.error("Không thể tải dữ liệu biểu đồ. Sử dụng dữ liệu mẫu.");
    // Initialize with dummy data
    initCharts(getDummyChartData());
  } finally {
    chartLoading.value = false;
  }
};

// Hàm tạo dữ liệu mẫu cho biểu đồ
const getDummyChartData = () => {
  // Tạo một mảng ngày dựa trên loại thời gian đã chọn
  const dates = [];
  const now = new Date();
  
  if (selectedPeriod.value === 'week') {
    // 7 ngày gần nhất
    for (let i = 6; i >= 0; i--) {
      const date = new Date();
      date.setDate(now.getDate() - i);
      dates.push(date.toISOString().split('T')[0]);
    }
  } else if (selectedPeriod.value === 'month') {
    // 30 ngày gần nhất
    for (let i = 29; i >= 0; i--) {
      const date = new Date();
      date.setDate(now.getDate() - i);
      dates.push(date.toISOString().split('T')[0]);
    }
  } else if (selectedPeriod.value === 'year') {
    // 12 tháng gần nhất
    for (let i = 11; i >= 0; i--) {
      const date = new Date();
      date.setMonth(now.getMonth() - i);
      dates.push(`${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`);
    }
  }
  
  // Tạo dữ liệu mẫu ngẫu nhiên cho doanh thu đơn hàng và đặt sân
  const orderRevenueData = dates.map(date => ({
    date,
    revenue: Math.floor(Math.random() * 10000000) + 5000000 // Doanh thu từ 5-15 triệu
  }));
  
  const bookingRevenueData = dates.map(date => ({
    date,
    revenue: Math.floor(Math.random() * 2000000) + 1000000 // Doanh thu từ 1-3 triệu
  }));
  
  return {
    order_revenue: orderRevenueData,
    booking_revenue: bookingRevenueData
  };
};

// Initialize charts
const initCharts = (chartData) => {
  // Revenue chart
  if (revenueChartInstance) {
    revenueChartInstance.destroy();
  }

  const ctx = revenueChart.value.getContext("2d");

  // Prepare data
  const orderRevenueData = (chartData.order_revenue || []).map((item) => ({
    x: item.date,
    y: item.revenue || 0,
  }));
  
  const bookingRevenueData = (chartData.booking_revenue || []).map((item) => ({
    x: item.date,
    y: item.revenue || 0,
  }));
  
  // Lấy tất cả các ngày
  const allDates = [...new Set([
    ...orderRevenueData.map(item => item.x),
    ...bookingRevenueData.map(item => item.x)
  ])].sort();
  
  // Tạo bộ dữ liệu tổng hợp
  const totalRevenueData = allDates.map(date => {
    const orderItem = orderRevenueData.find(item => item.x === date);
    const bookingItem = bookingRevenueData.find(item => item.x === date);
    
    return {
      x: date,
      y: (orderItem?.y || 0) + (bookingItem?.y || 0)
    };
  });

  // Create chart
  revenueChartInstance = new Chart(ctx, {
    type: "line",
    data: {
      datasets: [
        {
          label: "Tổng doanh thu",
          data: totalRevenueData,
          borderColor: "#4e73df",
          backgroundColor: "rgba(78, 115, 223, 0.05)",
          tension: 0.3,
          fill: true,
          borderWidth: 2
        },
        {
          label: "Doanh thu đơn hàng",
          data: orderRevenueData,
          borderColor: "#1cc88a",
          backgroundColor: "rgba(28, 200, 138, 0.05)",
          tension: 0.3,
          borderDash: [5, 5],
          fill: false,
          borderWidth: 2
        },
        {
          label: "Doanh thu đặt sân",
          data: bookingRevenueData,
          borderColor: "#f6c23e",
          backgroundColor: "rgba(246, 194, 62, 0.05)",
          tension: 0.3,
          borderDash: [3, 3],
          fill: false,
          borderWidth: 2
        }
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += formatCurrency(context.parsed.y);
              }
              return label;
            },
          },
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleFont: {
            size: 13
          },
          bodyFont: {
            size: 12
          },
          padding: 10,
          cornerRadius: 5
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
          ticks: {
            font: {
              size: 11
            }
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return formatCurrency(value).split(" ")[0];
            },
            font: {
              size: 11
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)',
          }
        },
      },
      elements: {
        point: {
          radius: 3,
          hoverRadius: 5
        }
      }
    },
  });

  // Nếu không còn tham chiếu đến biểu đồ sân, xóa phần này
  // if (fieldChartInstance) {
  //   fieldChartInstance.destroy();
  //   fieldChartInstance = null;
  // }
};

// Change period
const changePeriod = async (period) => {
  selectedPeriod.value = period;
  await fetchChartData();
};

// Function to handle product image paths
function getProductImageUrl(imagePath) {
  // Đường dẫn hình ảnh mặc định (sử dụng một trong những ảnh có sẵn)
  const defaultImagePath = '/img/img_sp/d5-bong1.png';
  
  // If no image path provided, return default image
  if (!imagePath) {
    return defaultImagePath;
  }
  
  // If the image path contains comma, it's multiple images - take the first one
  if (imagePath.includes(',')) {
    imagePath = imagePath.split(',')[0].trim();
  }
  
  // If path already includes http/https, return as is
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  // If path starts with a slash, it's likely a server path
  if (imagePath.startsWith('/')) {
    // For server paths
    return `http://localhost:8000${imagePath}`;
  }
  
  // For paths stored as relative paths in database without img/img_sp prefix
  // Check if path already includes img/img_sp
  if (imagePath.includes('img/img_sp/') || imagePath.includes('img\\img_sp\\')) {
    return `/${imagePath}`;
  }
  
  // Default case: add the img/img_sp/ prefix
  return `/img/img_sp/${imagePath}`;
}

// Hook lifecycle
onMounted(() => {
  console.log('Dashboard mounted');
  fetchDashboardData();
  fetchChartData();
  // Khởi tạo với mảng rỗng để tránh lỗi
  topProducts.value = [];
});
</script>

<style scoped>
.admin {
    min-height: 100vh;
    background-color: #f8f9fa;
    display: flex;
    width: 100%;
}

.admin-left {
    background-color: #1d2a54;
    min-height: 100vh;
    height: 100%;
    color: white;
    position: fixed;
    width: 280px;
    max-width: 280px;
    overflow-y: auto;
    padding-bottom: 30px;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.admin-right {
    margin-left: 280px;
    width: calc(100% - 280px);
    padding: 25px 40px 25px 30px;
    background-color: #f8f9fa;
}

.admin-left img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin: 25px auto;
    display: block;
    border: 3px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    object-fit: cover;
    transition: all 0.3s ease;
}

.admin-left img:hover {
    transform: scale(1.05);
    border-color: #ffd700;
}

.admin-left h5 {
    color: #ffffff;
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    letter-spacing: 1px;
    font-size: 24px;
}

.admin-left a, .admin-left .admin-menu-item {
    display: flex;
    align-items: center;
    padding: 14px 22px;
    text-decoration: none;
    color: #ffffff;
    transition: all 0.3s ease;
    gap: 12px;
    border-radius: 8px;
    margin: 0 12px 8px 12px;
    font-size: 18px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.admin-left a::before, .admin-left .admin-menu-item::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    height: 2px;
    width: 0;
    background-color: #ffd700;
    transition: all 0.3s ease;
}

.admin-left a:hover::before, .admin-left .admin-menu-item:hover::before {
    width: 100%;
}

.admin-left a.active, .admin-left .admin-menu-item.active {
    background-color: rgba(255, 255, 255, 0.15);
    color: #ffd700;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.admin-left a:hover, .admin-left .admin-menu-item:hover {
    color: #ffd700;
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
    letter-spacing: 0.3px;
}

.admin-left a i, .admin-left .admin-menu-item i {
    color: #ffffff;
    font-size: 22px;
    transition: all 0.3s ease;
    width: 28px;
    text-align: center;
}

.admin-left a:hover i, .admin-left .admin-menu-item:hover i,
.admin-left a.active i, .admin-left .admin-menu-item.active i {
    color: #ffd700;
}

.admin-menu-separator {
    height: 1px;
    background-color: rgba(255, 255, 255, 0.1);
    margin: 15px 20px;
}

.header-container {
    background: white;
    padding: 16px 35px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
}

.title-section h5 {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.title-section p {
    font-size: 14px;
    color: #6c757d;
    margin: 4px 0 0 0;
}

.button-wrapper {
    display: flex;
    justify-content: flex-end;
    min-width: 170px;
}

.btn-xuat-admin {
    background-color: #1d2a54;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-xuat-admin:hover {
    background-color: #2c3e50;
    transform: translateY(-2px);
}

@media (max-width: 1200px) {
    .admin-left {
        width: 200px;
        max-width: 200px;
    }
    
    .admin-right {
        margin-left: 200px;
        width: calc(100% - 200px);
    }
}

@media (max-width: 992px) {
    .admin-left {
        width: 90px;
        max-width: 90px;
    }
    
    .admin-right {
        margin-left: 90px;
        width: calc(100% - 90px);
        padding: 20px 35px 20px 25px;
    }
    
    .admin-left a span, .admin-left .admin-menu-item span {
        display: none;
    }
    
    .admin-left h5 {
        display: none;
    }
    
    .admin-left a, .admin-left .admin-menu-item {
        justify-content: center;
        padding: 16px 10px;
    }
    
    .admin-left a i, .admin-left .admin-menu-item i {
        font-size: 26px;
        margin: 0;
    }
    
    .admin-left img {
        width: 60px;
        height: 60px;
        margin: 15px auto;
    }
}

@media (max-width: 768px) {
    .admin-left {
        position: fixed;
        bottom: 0;
        width: 100%;
        max-width: 100%;
        height: 75px;
        min-height: 75px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 0;
        z-index: 1001;
    }
    
    .admin-right {
        margin-left: 0;
        width: 100%;
        padding: 15px 20px;
        padding-bottom: 85px;
    }
    
    .admin-left img, .admin-left h5, .admin-menu-separator {
        display: none;
    }
    
    .admin-left a, .admin-left .admin-menu-item {
        padding: 12px;
        margin: 0;
    }
    
    .admin-left a i, .admin-left .admin-menu-item i {
        font-size: 28px;
    }
}

.stats-cards .card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  overflow: hidden;
}

.stats-cards .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
}

.stats-cards .card-body {
  padding: 1.5rem;
}

.stats-cards .card-title {
  font-size: 1rem;
  color: rgba(0, 0, 0, 0.6);
  font-weight: 500;
  margin-bottom: 0.75rem;
}

.stats-cards .card-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1d2a54;
  margin-bottom: 0.25rem;
}

.stats-cards .card-progress {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.stats-cards .card-progress.up {
  color: #10b981;
}

.stats-cards .card-progress.down {
  color: #ef4444;
}

.stats-cards .card-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  margin-bottom: 1rem;
}

.stats-cards .card-type-users {
  background: linear-gradient(45deg, #4f46e5, #818cf8);
}

.stats-cards .card-type-orders {
  background: linear-gradient(45deg, #0891b2, #22d3ee);
}

.stats-cards .card-type-revenue {
  background: linear-gradient(45deg, #059669, #34d399);
}

.stats-cards .card-type-products {
  background: linear-gradient(45deg, #9333ea, #c084fc);
}

.stats-cards .card-icon i {
  color: white;
}

.chart-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.chart-filter .btn {
  background-color: #f8f9fc;
  border: 1px solid #eaecf4;
  color: #6e707e;
  font-size: 0.85rem;
  padding: 0.35rem 0.75rem;
  margin-left: 5px;
  border-radius: 6px;
  transition: all 0.2s;
}

.chart-filter .btn.active {
  background-color: #4e73df;
  color: white;
  border-color: #4e73df;
}

.chart-filter .btn:hover:not(.active) {
  background-color: #eaecf4;
}

.chart-legend {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.legend-item {
  display: flex;
  align-items: center;
  font-size: 0.85rem;
}

.legend-color {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  margin-right: 8px;
}

.chart-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 8px;
  padding: 20px;
  z-index: 10;
}

.table-cards .card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
  overflow: hidden;
}

.table-cards .card-header {
  background-color: #ffffff;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-cards .card-header h5 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1d2a54;
  margin: 0;
}

.table-cards .card-header .btn-primary {
  background-color: #1d2a54;
  border-color: #1d2a54;
  font-weight: 500;
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  border-radius: 6px;
}

.table-cards .card-header .btn-primary:hover {
  background-color: #15213f;
  border-color: #15213f;
}

.table-cards .table {
  margin-bottom: 0;
}

.table-cards .table thead th {
  border-top: none;
  border-bottom-width: 1px;
  font-weight: 600;
  font-size: 0.9rem;
  color: #495057;
  padding: 1rem 1.5rem;
  background-color: #f8f9fa;
}

.table-cards .table tbody td {
  padding: 1rem 1.5rem;
  vertical-align: middle;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.table-cards .table tbody tr:last-child td {
  border-bottom: none;
}

.product-image {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  overflow: hidden;
  background-color: #f8f9fa;
}

.star-ratings {
  display: flex;
  align-items: center;
}

.star-ratings i {
  font-size: 0.9rem;
}

.badge-status {
  padding: 0.35rem 0.65rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  min-width: 80px;
  text-align: center;
}

.badge-status-success {
  background-color: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.badge-status-pending {
  background-color: rgba(247, 144, 9, 0.1);
  color: #f59e0b;
}

.badge-status-canceled {
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.btn-outline-primary {
  color: #1d2a54;
  border-color: #1d2a54;
}

.btn-outline-primary:hover {
  background-color: #1d2a54;
  color: white;
}

.empty-data {
  padding: 3rem 0;
  text-align: center;
}

.empty-data p {
  color: #6c757d;
  font-size: 1rem;
  margin-bottom: 0;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.stats-cards .card {
  animation: fadeIn 0.5s ease-out forwards;
}

.stats-cards .card:nth-child(1) { animation-delay: 0.1s; }
.stats-cards .card:nth-child(2) { animation-delay: 0.2s; }
.stats-cards .card:nth-child(3) { animation-delay: 0.3s; }
.stats-cards .card:nth-child(4) { animation-delay: 0.4s; }

.chart-container {
  animation: fadeIn 0.5s ease-out forwards;
  animation-delay: 0.5s;
}

.table-cards .card {
  animation: fadeIn 0.5s ease-out forwards;
  animation-delay: 0.6s;
}

.product-thumbnail {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 4px;
}

.rating {
  display: flex;
  align-items: center;
}

.text-warning {
  color: #f6c23e !important;
}
</style>

