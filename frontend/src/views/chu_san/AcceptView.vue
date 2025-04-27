<script setup lang="ts">
import { ref, onMounted, computed, watch } from "vue";
import { RouterLink } from "vue-router";
import axios from "axios";
import { API_URL } from "../../main";
import { useAuthStore } from "../../stores/auth";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

const authStore = useAuthStore();
const selectedItem = ref(2);
const activeTab = ref("pending");
const isLoading = ref(false);
const successMessage = ref("");
const errorMessage = ref("");
const isShowingConfirmModal = ref(false);
const currentBookingId = ref(null);
const actionType = ref("");

// Biến để kiểm tra token
const isTokenValid = ref(true);

// Biến để kiểm tra và lưu lỗi cơ sở dữ liệu
const databaseError = ref(false);
const databaseErrorMessage = ref("");

const menuItems = ref([
  { text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan" },
  { text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan" },
  { text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet" },
  { text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich" },
  { text: "Cài đặt", icon: "bi bi-gear", link: "/caidat" },
]);

// Danh sách đơn đặt sân
const bookings = ref([]);

// Lọc đơn đặt sân theo tab hiện tại
const filteredBookings = computed(() => {
  return bookings.value.filter((booking) => {
    if (activeTab.value === "pending") return booking.Trang_thai === 1;
    if (activeTab.value === "accepted") return booking.Trang_thai === 2;
    if (activeTab.value === "rejected") return booking.Trang_thai === 0;
    return false;
  });
});

// Hiển thị modal xác nhận
function showConfirmModal(bookingId, type) {
  currentBookingId.value = bookingId;
  actionType.value = type;
  isShowingConfirmModal.value = true;
}

// Đóng modal xác nhận
function closeConfirmModal() {
  isShowingConfirmModal.value = false;
  currentBookingId.value = null;
  actionType.value = "";
}

// Hàm xác nhận hành động
function confirmAction() {
  if (actionType.value === "accept") {
    acceptBooking(currentBookingId.value);
  } else if (actionType.value === "reject") {
    rejectBooking(currentBookingId.value);
  }
  closeConfirmModal();
}

// Formatters
function formatTime(time) {
  if (!time) return '';
  return time;
}

function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('vi-VN');
}

// Nhận đơn
async function acceptBooking(bookingId) {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    const token = localStorage.getItem('token');
    if (!token) {
      throw new Error('Vui lòng đăng nhập để thực hiện chức năng này');
    }

    const response = await axios.post(
      `${API_URL}/api/bookings/${bookingId}/update-status`, 
      { Trang_thai: 2 }, // 2 = đã phê duyệt
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        },
      }
    );

    if (response.data && response.data.booking) {
      // Cập nhật trạng thái đơn đặt sân trong danh sách
      const bookingIndex = bookings.value.findIndex((b) => b.id === bookingId);
      if (bookingIndex !== -1) {
        bookings.value[bookingIndex].Trang_thai = 2;

        // Hiển thị thông báo thành công
        successMessage.value = `Đã nhận đơn đặt sân thành công!`;
        setTimeout(() => {
          successMessage.value = "";
        }, 3000);
      }
    } else {
      throw new Error('Không thể cập nhật trạng thái đơn');
    }
  } catch (error) {
    console.error("Lỗi khi nhận đơn:", error);
    errorMessage.value = error.response?.data?.message || "Có lỗi xảy ra khi nhận đơn. Vui lòng thử lại sau.";
    setTimeout(() => {
      errorMessage.value = "";
    }, 3000);
  } finally {
    isLoading.value = false;
  }
}

// Từ chối đơn
async function rejectBooking(bookingId) {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    const token = localStorage.getItem('token');
    if (!token) {
      throw new Error('Vui lòng đăng nhập để thực hiện chức năng này');
    }

    const response = await axios.post(
      `${API_URL}/api/bookings/${bookingId}/update-status`, 
      { Trang_thai: 0 }, // 0 = đã hủy/từ chối
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        },
      }
    );

    if (response.data && response.data.booking) {
      // Cập nhật trạng thái đơn đặt sân trong danh sách
      const bookingIndex = bookings.value.findIndex((b) => b.id === bookingId);
      if (bookingIndex !== -1) {
        bookings.value[bookingIndex].Trang_thai = 0;

        // Hiển thị thông báo thành công
        successMessage.value = `Đã từ chối đơn đặt sân thành công!`;
        setTimeout(() => {
          successMessage.value = "";
        }, 3000);
      }
    } else {
      throw new Error('Không thể cập nhật trạng thái đơn');
    }
  } catch (error) {
    console.error("Lỗi khi từ chối đơn:", error);
    errorMessage.value = error.response?.data?.message || "Có lỗi xảy ra khi từ chối đơn. Vui lòng thử lại sau.";
    setTimeout(() => {
      errorMessage.value = "";
    }, 3000);
  } finally {
    isLoading.value = false;
  }
}

// Chuyển tab
function changeTab(tab) {
  activeTab.value = tab;
}

// Tải dữ liệu đơn đặt sân
async function fetchBookings() {
  isLoading.value = true;
  errorMessage.value = "";
  databaseError.value = false;
  databaseErrorMessage.value = "";

  try {
    // Kiểm tra trạng thái đăng nhập trước
    if (!authStore.isAuthenticated) {
      // Thử kiểm tra token trong localStorage
      await authStore.checkAuth();
      
      // Nếu vẫn chưa xác thực được
      if (!authStore.isAuthenticated) {
        isTokenValid.value = false;
        errorMessage.value = "Vui lòng đăng nhập để xem danh sách đơn đặt sân";
        setTimeout(() => {
          window.location.href = '/dangnhap?redirect=/pheduyet&role=field_owner';
        }, 2000);
        return;
      }
    }

    const token = localStorage.getItem('token') || authStore.token;
    if (!token) {
      isTokenValid.value = false;
      throw new Error('Vui lòng đăng nhập để xem danh sách đơn đặt sân');
    }

    console.log("Gửi request API với token:", token.substring(0, 15) + "...");

    // Gửi request API với token
    const response = await axios.get(`${API_URL}/api/owner/bookings`, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
      },
      // Thêm timeout để tránh chờ quá lâu
      timeout: 10000
    });

    console.log("API Response:", response.data);

    if (response.data && response.data.status === 'success') {
      bookings.value = response.data.bookings || [];
      if (bookings.value.length === 0) {
        console.log("Không có đơn đặt sân nào");
      }
    } else if (response.data && response.data.status === 'warning') {
      bookings.value = [];
      errorMessage.value = response.data.message || "Chưa có đơn đặt sân nào";
      setTimeout(() => {
        errorMessage.value = "";
      }, 5000);
    } else {
      bookings.value = [];
      throw new Error(response.data?.message || 'Không thể tải dữ liệu đơn đặt sân');
    }
  } catch (error) {
    console.error("Lỗi khi tải dữ liệu:", error);
    
    // Log chi tiết để debug
    if (error.response) {
      console.error("Status code:", error.response.status);
      console.error("Response data:", error.response.data);
      console.error("Response headers:", error.response.headers);
    } else if (error.request) {
      console.error("Request đã gửi nhưng không nhận được response");
      console.error(error.request);
    } else {
      console.error("Lỗi cấu hình request:", error.message);
    }
    
    if (error.response && error.response.status === 401) {
      // Lỗi xác thực
      isTokenValid.value = false;
      errorMessage.value = "Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.";
      setTimeout(() => {
        handleAuthError();
      }, 2000);
    } else if (error.response && error.response.status === 500) {
      // Lỗi server
      isTokenValid.value = true; // Giữ token vẫn hợp lệ
      
      // Kiểm tra xem đây có phải là lỗi về cột user_id không tồn tại
      if (error.response.data && error.response.data.message && 
          error.response.data.message.includes("Column not found") && 
          error.response.data.message.includes("user_id")) {
        
        // Đánh dấu lỗi cơ sở dữ liệu
        databaseError.value = true;
        databaseErrorMessage.value = "Cơ sở dữ liệu có vấn đề với cấu trúc bảng 'san'. Cột 'user_id' chưa được tạo trong bảng này.";
        
        errorMessage.value = "Cấu trúc cơ sở dữ liệu cần được cập nhật. Vui lòng liên hệ quản trị viên.";
      } else {
        errorMessage.value = "Máy chủ đang gặp sự cố. Vui lòng thử lại sau hoặc liên hệ quản trị viên.";
      }
      
      // Để bảo đảm an toàn, lưu biến token tại đây để sử dụng bên dưới
      const currentToken = token;
      
      // Log thêm thông tin về token để debug
      console.log("Token hiện tại:", currentToken ? "Có token" : "Không có token");
      console.log("User data:", authStore.user);
      
      // Thử kiểm tra token
      try {
        await authStore.checkAuth();
        if (!authStore.isAuthenticated) {
          // Nếu phát hiện token không hợp lệ khi kiểm tra lại
          setTimeout(() => {
            handleAuthError();
          }, 2000);
        }
      } catch (authError) {
        console.error("Lỗi khi kiểm tra xác thực:", authError);
      }
    } else {
      errorMessage.value = error.response?.data?.message || error.message || "Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại sau.";
    }
    
    setTimeout(() => {
      if (!errorMessage.value.includes("Máy chủ đang gặp sự cố") && 
          !errorMessage.value.includes("Cấu trúc cơ sở dữ liệu")) {
        errorMessage.value = "";
      }
    }, 5000);
  } finally {
    isLoading.value = false;
  }
}

// Thử lại tải dữ liệu
async function retryFetchBookings() {
  // Kiểm tra xác thực trước
  await authStore.checkAuth();
  
  if (authStore.isAuthenticated) {
    fetchBookings();
  } else {
    isTokenValid.value = false;
    errorMessage.value = "Vui lòng đăng nhập lại để tiếp tục";
  }
}

// Thử lại khi gặp lỗi xác thực
function handleAuthError() {
  localStorage.removeItem('token');
  authStore.logout();
  window.location.href = '/dangnhap?redirect=/pheduyet&role=field_owner';
}

// Tìm kiếm đơn đặt sân
const searchTerm = ref("");
const searchResults = computed(() => {
  if (!searchTerm.value) return filteredBookings.value;

  const term = searchTerm.value.toLowerCase();
  return filteredBookings.value.filter(
    (booking) =>
      booking.Ten_KH?.toLowerCase().includes(term) ||
      booking.SDT?.includes(term) ||
      booking.Ngay_dat?.includes(term) ||
      booking.timeSlot?.Gio_bat_dau?.includes(term) ||
      booking.timeSlot?.Gio_ket_thuc?.includes(term)
  );
});

// Theo dõi sự thay đổi của tab để cập nhật giao diện
watch(activeTab, () => {
  // Có thể thêm xử lý ở đây nếu cần
});

onMounted(() => {
  // Kiểm tra vai trò người dùng - Thêm await để đảm bảo xác thực hoàn tất
  const checkAuth = async () => {
    if (!authStore.isAuthenticated) {
      try {
        await authStore.checkAuth();
      } catch (error) {
        console.error("Lỗi kiểm tra xác thực:", error);
      }
      
      if (!authStore.isAuthenticated) {
        errorMessage.value = "Bạn cần đăng nhập để truy cập trang này";
        setTimeout(() => {
          window.location.href = '/dangnhap?redirect=/pheduyet&role=field_owner';
        }, 2000);
        return;
      }
    }
    
    // Kiểm tra vai trò field_owner trước khi tải dữ liệu
    if (!authStore.isFieldOwner && !authStore.isAdmin) {
      errorMessage.value = "Bạn không có quyền truy cập trang này. Chỉ chủ sân mới có thể truy cập.";
      setTimeout(() => {
        window.location.href = '/dangnhap?redirect=/pheduyet&role=field_owner';
      }, 2000);
      return;
    }
    
    // Tải dữ liệu đơn đặt sân
    fetchBookings();
  };
  
  checkAuth();
});
</script>

<template>
  <main>
    <!-- Thông báo thành công -->
    <div v-if="successMessage" class="success-toast">
      <i class="bi bi-check-circle-fill me-2"></i>
      {{ successMessage }}
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="errorMessage" class="error-toast">
      <i class="bi bi-exclamation-circle-fill me-2"></i>
      {{ errorMessage }}
      <button 
        v-if="!isTokenValid" 
        @click="handleAuthError" 
        class="btn-login ms-2"
      >
        Đăng nhập lại
      </button>
    </div>

    <!-- Thông báo lỗi cơ sở dữ liệu -->
    <div v-if="databaseError" class="database-error-container">
      <div class="database-error-box">
        <i class="bi bi-database-exclamation fs-1 text-warning mb-3"></i>
        <h4>Lỗi cấu trúc cơ sở dữ liệu</h4>
        <p>{{ databaseErrorMessage }}</p>
        <div class="mt-3">
          <p class="fw-bold">Hướng dẫn sửa lỗi cho quản trị viên:</p>
          <ol class="text-start">
            <li>Truy cập vào cơ sở dữ liệu MySQL</li>
            <li>Thêm cột <code>user_id</code> vào bảng <code>san</code>:</li>
            <li>
              <pre class="code-block">
ALTER TABLE san ADD COLUMN user_id BIGINT UNSIGNED NULL;
ALTER TABLE san ADD CONSTRAINT fk_san_user 
    FOREIGN KEY (user_id) REFERENCES users(id);
              </pre>
            </li>
            <li>Liên kết các sân với chủ sân tương ứng bằng cách cập nhật trường user_id:</li>
            <li>
              <pre class="code-block">
-- Ví dụ: Gán sân có id=1 cho người dùng có id=18
UPDATE san SET user_id = 18 WHERE id = 1;

-- Hoặc sử dụng id_kh từ bảng booking để liên kết:
UPDATE san s
JOIN booking b ON s.id = b.id_san
SET s.user_id = b.id_kh
WHERE b.id_kh IS NOT NULL;
              </pre>
            </li>
          </ol>
        </div>
        <div class="mt-3">
          <button @click="retryFetchBookings" class="btn-retry me-2">
            <i class="bi bi-arrow-clockwise me-2"></i>
            Thử lại
          </button>
          <a href="/chusan" class="btn-secondary">
            <i class="bi bi-house-door me-2"></i>
            Về trang quản lý
          </a>
        </div>
      </div>
    </div>

    <!-- Modal xác nhận -->
    <div
      v-if="isShowingConfirmModal"
      class="confirm-modal-backdrop"
      @click="closeConfirmModal"
    >
      <div class="confirm-modal" @click.stop>
        <div class="confirm-modal-header">
          <h5 class="m-0">Xác nhận</h5>
          <button class="close-btn" @click="closeConfirmModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="confirm-modal-body">
          <p v-if="actionType === 'accept'">
            Bạn có chắc chắn muốn nhận đơn đặt sân này?
          </p>
          <p v-else-if="actionType === 'reject'">
            Bạn có chắc chắn muốn từ chối đơn đặt sân này?
          </p>
        </div>
        <div class="confirm-modal-footer">
          <button class="btn-cancel" @click="closeConfirmModal">Hủy</button>
          <button
            :class="actionType === 'accept' ? 'btn-booknow' : 'btn-refuse'"
            @click="confirmAction"
          >
            {{
              actionType === "accept" ? "Xác nhận nhận đơn" : "Xác nhận từ chối"
            }}
          </button>
        </div>
      </div>
    </div>

    <section v-if="!databaseError" class="accept">
      <div class="row gx-0">
        <Sidebar />
        <div class="col-10">
          <div
            class="bg-white p-5"
            style="
              box-shadow: 0 0 18px var(--shadow2);
              min-height: 667px;
              border-radius: 20px 0 0 20px;
            "
          >
            <h3
              class="m-0 fs-2 fw-bold text-start"
              style="color: var(--colortext1)"
            >
              Chờ phê duyệt
            </h3>
            <div class="d-flex align-items-center justify-content-between mt-4">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a
                    class="boss-nav-link nav-link"
                    :class="{ 'boss-link-active': activeTab === 'pending' }"
                    href="#"
                    @click.prevent="changeTab('pending')"
                  >
                    Chưa xử lí
                  </a>
                </li>
                <li class="nav-item">
                  <a
                    class="boss-nav-link nav-link"
                    :class="{ 'boss-link-active': activeTab === 'accepted' }"
                    href="#"
                    @click.prevent="changeTab('accepted')"
                  >
                    Đã nhận đơn
                  </a>
                </li>
                <li class="nav-item">
                  <a
                    class="boss-nav-link nav-link"
                    :class="{ 'boss-link-active': activeTab === 'rejected' }"
                    href="#"
                    @click.prevent="changeTab('rejected')"
                  >
                    Đã từ chối
                  </a>
                </li>
              </ul>
              <form action="" style="width: 30%">
                <input
                  type="text"
                  v-model="searchTerm"
                  class="form-date d-block w-100"
                  placeholder="Tìm kiếm"
                />
              </form>
            </div>
            <div v-if="isLoading" class="text-center py-5">
              <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Đang tải...</span>
              </div>
              <p class="mt-2">Đang tải dữ liệu...</p>
            </div>
            <div v-else-if="!isTokenValid" class="text-center py-5">
              <i class="bi bi-exclamation-triangle-fill fs-1 text-warning"></i>
              <p class="mt-3 fs-4">Phiên làm việc của bạn đã hết hạn</p>
              <button @click="handleAuthError" class="btn-booknow mt-3">
                Đăng nhập lại
              </button>
            </div>
            <div v-else>
              <div class="boss-text bg-white mt-5">
                <p
                  class="fs-4 text-start m-0 fw-semibold"
                  style="color: var(--colortext1)"
                >
                  Người dùng
                </p>
                <p
                  class="fs-4 text-start m-0 fw-semibold"
                  style="color: var(--colortext1)"
                >
                  Số điện thoại
                </p>
                <p
                  class="fs-4 text-start m-0 fw-semibold"
                  style="color: var(--colortext1)"
                >
                  Ngày đặt
                </p>
                <p
                  class="fs-4 text-start m-0 fw-semibold"
                  style="color: var(--colortext1)"
                >
                  Khung giờ
                </p>
                <p
                  class="fs-4 text-start m-0 fw-semibold"
                  style="color: var(--colortext1)"
                >
                  Tùy chỉnh
                </p>
              </div>
              <div v-if="searchResults.length === 0" class="text-center py-5">
                <i
                  class="bi bi-inbox fs-1"
                  style="color: var(--colortext3)"
                ></i>
                <p class="mt-2" style="color: var(--colortext3)">
                  Không có đơn đặt sân nào.
                </p>
              </div>
              <div v-else class="booking-item-accept">
                <div
                  v-for="booking in searchResults"
                  :key="booking.id"
                  class="boss-text my-2"
                >
                  <div class="d-flex align-items-center gap-3">
                    <div class="boss-img">
                      <img src="../../../public/img/user.webp" alt="user avatar" />
                    </div>
                    <p
                      class="fs-4 text-start m-0 fw-semibold"
                      style="color: var(--colortext1)"
                    >
                      {{ booking.Ten_KH || (booking.customer ? booking.customer.ho_ten : 'Không có tên') }}
                    </p>
                  </div>
                  <p
                    class="fs-4 text-start m-0"
                    style="color: var(--colortext1)"
                  >
                    {{ booking.SDT || 'Không có SĐT' }}
                  </p>
                  <p
                    class="fs-4 text-start m-0"
                    style="color: var(--colortext1)"
                  >
                    {{ formatDate(booking.Ngay_dat) }}
                  </p>
                  <p
                    class="fs-4 text-start m-0"
                    style="color: var(--colortext1)"
                  >
                    {{ booking.timeSlot ? `${booking.timeSlot.Gio_bat_dau} - ${booking.timeSlot.Gio_ket_thuc}` : 'Không có thời gian' }}
                  </p>
                  <div
                    v-if="activeTab === 'pending'"
                    class="d-flex align-items-center gap-2"
                  >
                    <button
                      class="btn-booknow mt-0 px-3"
                      style="width: 80px"
                      @click="showConfirmModal(booking.id, 'accept')"
                    >
                      Nhận đơn
                    </button>
                    <button
                      class="btn-refuse mt-0 px-3"
                      style="width: 80px"
                      @click="showConfirmModal(booking.id, 'reject')"
                    >
                      Từ chối
                    </button>
                  </div>
                  <div
                    v-else-if="activeTab === 'accepted'"
                    class="d-flex align-items-center gap-2"
                  >
                    <span class="status-badge accepted">
                      <i class="bi bi-check-circle-fill me-1"></i>
                      Đã nhận đơn
                    </span>
                  </div>
                  <div v-else class="d-flex align-items-center gap-2">
                    <span class="status-badge rejected">
                      <i class="bi bi-x-circle-fill me-1"></i>
                      Đã từ chối
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thêm nút thử lại tải dữ liệu -->
            <div v-if="bookings.length === 0 && !isLoading" class="text-center my-4">
              <button @click="retryFetchBookings" class="btn-retry">
                <i class="bi bi-arrow-clockwise me-2"></i>
                Thử lại tải dữ liệu
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

:root {
  --primary-color: #2a2a2a;
  --secondary-color: #28a745;
  --error-color: #dc3545;
  --bg-white: #ffffff;
  --shadow: rgba(0, 0, 0, 0.1);
}

* {
  box-sizing: border-box;
}

body {
  font-family: 'Roboto', sans-serif;
  margin: 0;
  background: #f4f4f4;
}

/* Toast notifications */
.success-toast,
.error-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 12px 24px;
  border-radius: 5px;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: slide-in 0.3s ease-out forwards;
  font-size: 16px;
}

.success-toast {
  background-color: var(--secondary-color);
  color: #fff;
}

.error-toast {
  background-color: var(--error-color);
  color: #fff;
}

@keyframes slide-in {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Modal styling */
.confirm-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}

.confirm-modal {
  background: var(--bg-white);
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.confirm-modal-header,
.confirm-modal-footer {
  padding: 16px 20px;
  background: #f8f8f8;
}

.confirm-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
}

.confirm-modal-body {
  padding: 20px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: var(--primary-color);
}

.btn-cancel,
.btn-refuse {
  font-size: 14px;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  border: none;
}

.btn-cancel {
  background: #f1f1f1;
  color: var(--primary-color);
  margin-right: 10px;
}

.btn-cancel:hover {
  background: #e2e2e2;
}

.btn-refuse {
  background: var(--error-color);
  color: #fff;
  transition: background 0.2s;
}

.btn-refuse:hover {
  background: #c82333;
}

.btn-refuse:disabled {
  background: #e4606d;
  cursor: not-allowed;
}

/* Main layout */
.accept {
  min-height: 100vh;
  background: #f4f4f4;
  padding: 20px;
}

.row {
  display: flex;
  flex-wrap: wrap;
}

.col-2 {
  flex: 0 0 16.66%;
  max-width: 16.66%;
}

.col-10 {
  flex: 0 0 83.33%;
  max-width: 83.33%;
}

.bg-white {
  background: var(--bg-white);
}

.p-5 {
  padding: 2rem;
}

.rounded-container {
  border-radius: 20px 0 0 20px;
}

/* Booking list items */
.booking-item-accept {
  overflow-y: auto;
  max-height: 500px;
  padding-right: 10px;
}

.boss-text {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: var(--bg-white);
  border-radius: 8px;
  margin-bottom: 10px;
  box-shadow: 0 2px 8px var(--shadow);
}

/* Input styles */
.inputBorder {
  border: 1px solid #ddd;
  padding: 8px;
  border-radius: 4px;
  width: 100%;
}

.form-date {
  min-width: 160px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .col-2,
  .col-10 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row {
    flex-direction: column;
  }
}
</style>