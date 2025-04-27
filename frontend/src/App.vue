<script setup lang="ts">
import { ref, watch, onMounted, computed, onBeforeUnmount, nextTick } from "vue";
import { onClickOutside } from "@vueuse/core";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "./stores/auth";
import { useCartStore } from "./stores/cart";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore(); // Cart store for managing cart state

// Authentication computed properties
const isAuthenticated = computed(() => authStore.isAuthenticated);
const isAdmin = computed(() => authStore.isAdmin);
const isFieldOwner = computed(() => authStore.isFieldOwner);
const userFullName = computed(() => authStore.userFullName);
const userAvatar = computed(() => authStore.userAvatar);

// Cart computed properties
const cartCount = computed(() => cartStore.cartCount);

// Computed tạo chữ cái đầu từ tên người dùng
const userInitials = computed(() => {
  if (!userFullName.value) return '';
  
  // Chia tên đầy đủ thành các từ
  const nameParts = userFullName.value.trim().split(' ');
  
  if (nameParts.length === 0) return '';
  
  if (nameParts.length === 1) {
    // Nếu chỉ có một từ, lấy chữ cái đầu tiên
    return nameParts[0].charAt(0).toUpperCase();
  } else {
    // Nếu có nhiều từ, lấy chữ cái đầu tiên của từ đầu tiên và từ cuối cùng
    const firstInitial = nameParts[0].charAt(0);
    const lastInitial = nameParts[nameParts.length - 1].charAt(0);
    return (firstInitial + lastInitial).toUpperCase();
  }
});

// Hàm tạo màu nền cho avatar dựa trên tên người dùng
const getUserAvatarColor = (name) => {
  if (!name) return '#CA9C27'; // Màu mặc định nếu không có tên
  
  // Tạo mã hash đơn giản từ tên
  let hash = 0;
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  
  // Chuyển hash thành màu, giới hạn trong các tông màu hợp lý
  const hue = Math.abs(hash % 360);
  // Sử dụng độ bão hòa và độ sáng cố định để có màu đẹp
  return `hsl(${hue}, 70%, 40%)`;
};

// Thông báo
const notifications = ref<any[]>([]);
const unreadCount = computed(() => notifications.value.filter(n => !n.da_xem).length);
const showNotifications = ref(false);
const notificationRef = ref<HTMLElement | null>(null);

// Lấy danh sách thông báo
const fetchNotifications = async () => {
  try {
    // Kiểm tra xem user đã đăng nhập chưa
    if (!isAuthenticated.value) {
      console.log('User chưa đăng nhập, không fetch thông báo');
      return; // Không gọi API nếu chưa đăng nhập
    }
    
    // Get token from authStore first, then fall back to localStorage
    const userToken = authStore.token || localStorage.getItem('auth_token') || localStorage.getItem('token');
    if (!userToken) {
      console.log('Không tìm thấy token, không fetch thông báo');
      return;
    }
    
    const response = await axios.get('http://localhost:8000/api/thong-bao', {
      headers: {
        'Authorization': `Bearer ${userToken}`,
        'Accept': 'application/json'
      }
    });
    
    if (response.data.status === 'success') {
      notifications.value = response.data.data;
    }
  } catch (error) {
    console.error('Lỗi khi tải thông báo:', error);
    if (error.response && error.response.status === 401) {
      console.log('Token không hợp lệ, nhưng không tự động đăng xuất');
      // For 401 errors in notifications, don't automatically log out
      // Just clear notifications
      notifications.value = [];
    } else {
      // For other errors, just log and continue
      console.error('Error fetching notifications:', error);
      notifications.value = [];
    }
  }
};

// Đánh dấu thông báo đã đọc
const markAsRead = async (notificationId: number) => {
  try {
    const userToken = authStore.token || localStorage.getItem('auth_token') || localStorage.getItem('token');
    if (!userToken) return;
    
    const response = await axios.post(
      `http://localhost:8000/api/thong-bao/danh-dau-da-doc/${notificationId}`,
      {},
      {
        headers: {
          'Authorization': `Bearer ${userToken}`,
          'Accept': 'application/json'
        }
      }
    );
    
    if (response.data.status === 'success') {
      // Cập nhật trạng thái đã đọc trong state
      const notification = notifications.value.find(n => n.id === notificationId);
      if (notification) {
        notification.da_xem = 1;
      }
    }
  } catch (error) {
    console.error('Lỗi khi đánh dấu thông báo đã đọc:', error);
    // Don't clear auth on error here
  }
};

// Mở thông báo
const openNotification = (notification: any) => {
  markAsRead(notification.id);
  if (notification.url) {
    router.push(notification.url);
  }
  showNotifications.value = false;
};

// Đánh dấu tất cả đã đọc
const markAllAsRead = async () => {
  try {
    const userToken = authStore.token || localStorage.getItem('auth_token') || localStorage.getItem('token');
    if (!userToken) return;
    
    const response = await axios.post(
      'http://localhost:8000/api/thong-bao/danh-dau-tat-ca-da-doc',
      {},
      {
        headers: {
          'Authorization': `Bearer ${userToken}`,
          'Accept': 'application/json'
        }
      }
    );
    
    if (response.data.status === 'success') {
      notifications.value.forEach(notification => {
        notification.da_xem = 1;
      });
    }
  } catch (error) {
    console.error('Lỗi khi đánh dấu tất cả thông báo đã đọc:', error);
    // Don't clear auth on error here
  }
};

// Bật/tắt panel thông báo
const toggleNotifications = (event: Event) => {
  event.preventDefault();
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    fetchNotifications();
  }
};

// Đóng panel thông báo khi click bên ngoài
onClickOutside(notificationRef, () => {
  showNotifications.value = false;
});

// Handle click outside for other components
const handleClickOutside = (event: MouseEvent) => {
  // Add any additional click outside handling logic here if needed
  // This function is referenced in onMounted but was missing
};

// Handle window resize
const handleResize = () => {
  // Add resize handling logic here if needed
  // This function is referenced in onMounted but was missing
};

// Logout method
const logout = async () => {
  await authStore.logout();
  router.push("/");
};

// Khai báo hàm xử lý sự kiện notification-update
const handleNotificationUpdate = async () => {
  if (authStore.isAuthenticated) {
    await fetchNotifications();
  }
};

onMounted(async () => {
  document.addEventListener('click', handleClickOutside);
  window.addEventListener('resize', handleResize);
  
  // Thêm event listener cho sự kiện notification-update
  window.addEventListener('notification-update', handleNotificationUpdate);
  
  // Luôn tải giỏ hàng ngay cả khi không đăng nhập
  await loadCart();
  
  if (authStore.isAuthenticated) {
    await fetchNotifications();
  }
});

// Hủy event listener khi component unmounted để tránh memory leak
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
  window.removeEventListener('resize', handleResize);
  window.removeEventListener('notification-update', handleNotificationUpdate);
});

const btnSearch = ref<HTMLElement | null>(null);
const showSearch = ref<HTMLElement | null>(null);
const showSearchBox = ref(false);
const searchType = ref("products");

const clickSearch = (event: Event) => {
  event.preventDefault();
  showSearchBox.value = true;
};

const closeSearch = () => {
  showSearchBox.value = false;
};

// Sử dụng onClickOutside để theo dõi cả nút search và ô tìm kiếm
onClickOutside(showSearch, closeSearch);

const tukhoa = ref("");
const products = ref([]);

// Hàm để thay đổi loại tìm kiếm
const changeSearchType = (type: string) => {
  searchType.value = type;
};

const submitSearch = async () => {
  if (!tukhoa.value.trim()) return;

  try {
    if (searchType.value === "products") {
      // Tìm kiếm sản phẩm - sử dụng phương thức GET
      const response = await axios.get(
        "http://localhost:8000/api/sanpham",
        {
          params: {
            tukhoa: tukhoa.value,
            trang: 1
          },
          headers: {
            "Accept": "application/json",
          },
        }
      );
      console.log("Kết quả tìm kiếm sản phẩm:", response.data);

      if (response.data.status === "success") {
        // Chuyển hướng đến trang sản phẩm với từ khóa tìm kiếm
        router.push({
          path: "/sanpham",
          query: {
            trang: 1,
            tukhoa: tukhoa.value,
          },
        });
        // Đóng form tìm kiếm
        showSearchBox.value = false;
      } else {
        console.error("Lỗi tìm kiếm sản phẩm:", response.data.message);
        alert("Không tìm thấy sản phẩm phù hợp");
      }
    } else {
      // Tìm kiếm sân - chuyển trực tiếp đến trang booking với tham số tìm kiếm
      router.push({
        path: "/booking",
        query: {
          tukhoa: tukhoa.value,
        },
      });
      // Đóng form tìm kiếm
      showSearchBox.value = false;
    }
  } catch (error) {
    console.error("Lỗi khi tìm kiếm:", error);
    alert("Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.");
  }
};

// Missing loadCart function
const loadCart = async () => {
  try {
    await cartStore.loadCart();
  } catch (error) {
    console.error("Error loading cart:", error);
  }
};
</script>

<template>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!-- font inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <!-- bootstrap icon -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <!-- custom css -->
    <link rel="stylesheet" href="../css/styles.css" />
    <title>Trang chủ</title>
  </head>
  <body>
    <header v-if="!route.meta.hideHeaderFooter">
      <div class="container">
        <div
          class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-between py-3"
        >
          <div class="" style="width: 9%; min-width: 110px">
            <RouterLink
              to="/"
              class="d-inline-flex link-body-emphasis text-decoration-none"
            >
              <img src="/img/logo.jpg" alt="" class="img-fluid" />
            </RouterLink>
          </div>

          <ul
            class="nav col-12 col-md-auto justify-content-center mb-md-0 my-lg-0 my-4"
          >
            <li>
              <RouterLink
                to="/booking"
                class="nav-link px-3 px-lg-4 fs-lg-4 fs-md-5"
                >BOOKING</RouterLink
              >
            </li>
            <li>
              <RouterLink
                to="/sanpham?trang=1"
                class="nav-link px-3 px-lg-4 fs-lg-4 fs-md-5"
                >SẢN PHẨM</RouterLink
              >
            </li>
            <li>
              <RouterLink
                to="/gioithieu"
                class="nav-link px-3 px-lg-4 fs-lg-4 fs-md-5"
                >GIỚI THIỆU</RouterLink
              >
            </li>
            <li>
              <RouterLink
                to="/lienhe"
                class="nav-link px-3 px-lg-4 fs-lg-4 fs-md-5"
                >LIÊN HỆ</RouterLink
              >
            </li>
            <li>
              <RouterLink
                to="/tintuc"
                class="nav-link px-3 px-lg-4 fs-lg-4 fs-md-5"
                >TIN TỨC</RouterLink
              >
            </li>
          </ul>

          <div class="d-flex align-items-center justify-content-end header-actions">
            <div class="position-relative mx-2">
              <a
                @click="clickSearch"
                ref="btnSearch"
                href="#"
                class="nav-icon"
                id="header__search-btn"
              >
                <i class="bi bi-search"></i>
              </a>
              <form
                @submit.prevent="submitSearch"
                method="post"
                class="position-absolute search-box"
                v-if="showSearchBox"
                ref="showSearch"
                id="header__search-form"
                style="top: 36px; z-index: 1"
              >
                <div class="search-types mb-3 d-flex justify-content-between">
                  <button 
                    type="button" 
                    class="search-type-btn" 
                    :class="searchType === 'products' ? 'active' : ''"
                    @click="changeSearchType('products')"
                  >
                    <i class="bi bi-bag me-2"></i> Sản phẩm
                  </button>
                  <button 
                    type="button" 
                    class="search-type-btn" 
                    :class="searchType === 'fields' ? 'active' : ''"
                    @click="changeSearchType('fields')"
                  >
                    <i class="bi bi-calendar-check me-2"></i> Đặt sân
                  </button>
                </div>
                <div class="input-group search-input-group">
                  <input
                    type="text"
                    v-model="tukhoa"
                    name="search"
                    :placeholder="searchType === 'products' ? 'Tìm kiếm sản phẩm...' : 'Tìm kiếm sân...'"
                    class="search-input"
                  />
                  <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </form>
            </div>
            <div class="position-relative mx-2">
              <a 
                @click="toggleNotifications" 
                href="#" 
                class="nav-icon position-relative"
              >
                <i class="bi bi-bell"></i>
                <span 
                  v-if="unreadCount > 0" 
                  class="custom-badge position-absolute rounded-circle fs-5 p-1"
                >
                  {{ unreadCount > 9 ? '9+' : unreadCount }}
                </span>
              </a>
              
              <div 
                v-if="showNotifications" 
                ref="notificationRef" 
                class="notifications-dropdown"
              >
                <div class="notifications-header">
                  <h5 class="m-0">Thông báo</h5>
                  <button 
                    v-if="notifications.length > 0 && unreadCount > 0" 
                    @click="markAllAsRead" 
                    class="mark-all-read"
                  >
                    Đánh dấu tất cả đã đọc
                  </button>
                </div>
                
                <div v-if="notifications.length === 0" class="no-notifications">
                  <i class="bi bi-bell-slash"></i>
                  <p>Không có thông báo</p>
                </div>
                
                <div v-else class="notifications-list">
                  <div 
                    v-for="notification in notifications" 
                    :key="notification.id" 
                    class="notification-item"
                    :class="{ 'unread': !notification.da_xem }"
                    @click="openNotification(notification)"
                  >
                    <div class="notification-icon">
                      <i 
                        class="bi" 
                        :class="{
                          'bi-calendar-check': notification.loai === 'booking',
                          'bi-bag-check': notification.loai === 'sanpham',
                          'bi-info-circle': notification.loai === 'hethong'
                        }"
                      ></i>
                    </div>
                    <div class="notification-content">
                      <h6 class="notification-title">{{ notification.tieu_de }}</h6>
                      <p class="notification-text">{{ notification.noi_dung }}</p>
                      <small class="notification-time">
                        {{ new Date(notification.created_at).toLocaleString('vi-VN') }}
                      </small>
                    </div>
                    <div v-if="!notification.da_xem" class="notification-unread-indicator"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mx-2">
              <template v-if="isAuthenticated">
                <div class="dropdown">
                  <a
                    href="#"
                    class="nav-icon"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <img
                      v-if="userAvatar"
                      :src="userAvatar"
                      alt="Profile"
                      class="avatar-img"
                    />
                    <div 
                      v-else 
                      class="user-initial-avatar"
                      :style="{ backgroundColor: getUserAvatarColor(userFullName) }"
                    >
                      {{ userInitials }}
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <span class="dropdown-item-text fw-bold">{{
                        userFullName
                      }}</span>
                    </li>
                    <li><hr class="dropdown-divider" /></li>
                    <li v-if="isAdmin">
                      <RouterLink to="/admin" class="dropdown-item"
                        >Quản trị viên</RouterLink
                      >
                    </li>
                    <li>
                      <RouterLink to="/profile" class="dropdown-item"
                        >Hồ sơ</RouterLink
                      >
                    </li>
                    <li>
                      <RouterLink to="/don-hang" class="dropdown-item">
                        <i class="bi bi-box-seam me-2"></i>Đơn hàng
                      </RouterLink>
                    </li>
                    <li>
                      <a @click.prevent="logout" href="#" class="dropdown-item"
                        >Đăng xuất</a
                      >
                    </li>
                  </ul>
                </div>
              </template>
              <RouterLink v-else to="/dangnhap" class="nav-icon"
                ><i class="bi bi-person"></i
              ></RouterLink>
            </div>
            <div class="position-relative mx-2">
              <RouterLink to="/giohang" class="nav-icon"
                ><i class="bi bi-cart"></i
              ></RouterLink>
              <span
                class="custom-badge position-absolute rounded-circle fs-5 p-1"
                >{{ cartCount }}</span
              >
            </div>
          </div>
        </div>
      </div>
    </header>

    <RouterView />

    <footer class="footer mt-0" v-if="!route.meta.hideHeaderFooter">
      <div class="container">
        <div class="row gx-0">
          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="footer-box px-3">
              <img src="/img/logo.jpg" alt="Logo" class="footer-logo mb-3" />
              <p class="fs-4 footer-infor">
                <i class="bi bi-telephone fs-3"></i> 1900.5678
              </p>
              <p class="fs-4 footer-infor">
                <i class="bi bi-geo-alt fs-3"></i> Công Viên Phần Mềm Quang
                Trung, Tô Ký, Quận 12, TP.HCM
              </p>
              <p class="fs-4 footer-infor">
                <i class="bi bi-envelope fs-3"></i> keysport@gmail.com
              </p>
            </div>
          </div>

          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="footer-box px-3">
              <h5 class="fs-2">Giới thiệu</h5>
              <ul class="list-unstyled">
                <li>
                  <a href="#" class="nav-link-footer"
                    >Giới thiệu Booking KeySport</a
                  >
                </li>
                <li>
                  <a href="#" class="nav-link-footer">Giới thiệu sản phẩm</a>
                </li>
                <li><a href="#" class="nav-link-footer">Địa chỉ</a></li>
                <li><a href="#" class="nav-link-footer">Liên hệ</a></li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="footer-box px-3">
              <h5 class="fs-2">Chính sách</h5>
              <ul class="list-unstyled">
                <li>
                  <a href="#" class="nav-link-footer">Chính sách booking</a>
                </li>
                <li>
                  <a href="#" class="nav-link-footer"
                    >Chính sách mua, bán hàng</a
                  >
                </li>
                <li>
                  <a href="#" class="nav-link-footer"
                    >Chính sách đổi, trả, hoàn tiền</a
                  >
                </li>
                <li>
                  <a href="#" class="nav-link-footer">Chính sách vận chuyển</a>
                </li>
                <li>
                  <a href="#" class="nav-link-footer">Chính sách bảo mật</a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="footer-box px-3">
              <h5 class="fs-2">Theo dõi chúng tôi tại</h5>
              <div class="social-icons d-flex align-items-center gap-1">
                <a href="#" class="nav-link-footer icon-footer"
                  ><i class="bi bi-facebook"></i
                ></a>
                <a href="#" class="nav-link-footer icon-footer"
                  ><i class="bi bi-instagram"></i
                ></a>
                <a href="#" class="nav-link-footer icon-footer"
                  ><i class="bi bi-twitter"></i
                ></a>
                <a href="#" class="nav-link-footer icon-footer"
                  ><i class="bi bi-envelope"></i
                ></a>
              </div>
              <div class="app-links mt-5 d-flex align-items-center gap-1">
                <img src="/img/ggplay.png" alt="Google Play" />
                <img src="/img/appstore.png" alt="App Store" />
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 p-2">
          <hr class="footer-line" />
          <p
            class="m-0 fs-5 fw-regular text-end"
            style="background-color: transparent"
          >
            Bản quyền © 2024 - THUỘC NHÀ SẢN XUẤT
          </p>
        </div>
      </div>
    </footer>
  </body>
</template>

<style scoped>
.search-box {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  padding: 1.2rem;
  width: 350px;
  right: -140px;
  border: 1px solid rgba(0, 0, 0, 0.05);
  animation: fadeIn 0.2s ease-out;
  transition: all 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.search-type-btn {
  flex: 1;
  padding: 0.7rem 0.5rem;
  font-size: 1rem;
  font-weight: 500;
  border: none;
  background-color: #f5f5f5;
  color: #666;
  transition: all 0.3s ease;
  border-radius: 8px;
  margin: 0 4px;
}

.search-type-btn:first-child {
  margin-left: 0;
}

.search-type-btn:last-child {
  margin-right: 0;
}

.search-type-btn.active {
  background-color: var(--accent);
  color: white;
  font-weight: 600;
  box-shadow: 0 4px 8px rgba(255, 153, 0, 0.2);
}

.search-type-btn:hover:not(.active) {
  background-color: #ebebeb;
}

.search-input-group {
  position: relative;
  display: flex;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  border: 1px solid #eee;
}

.search-input {
  flex: 1;
  border: none;
  padding: 0.9rem 1rem;
  font-size: 1rem;
  outline: none;
  background-color: #f9f9f9;
  transition: all 0.3s ease;
}

.search-input:focus {
  background-color: white;
  box-shadow: inset 0 0 0 2px var(--accent);
}

.search-btn {
  border: none;
  background-color: var(--accent);
  color: white;
  padding: 0 1.5rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.search-btn:hover {
  background-color: #e68a00;
}

.custom-badge {
  top: -3px;
  right: -3px;
  background-color: var(--accent);
  color: white;
  font-size: 0.8rem;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-img {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #fff;
}

.user-initial-avatar {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.9rem;
  border: 2px solid #fff;
  text-transform: uppercase;
}

.dropdown-menu {
  min-width: 200px;
  padding: 8px 0;
  margin-top: 8px;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(0, 0, 0, 0.08);
}

.dropdown-item {
  padding: 8px 16px;
  font-size: 1rem;
}

.dropdown-item:hover,
.dropdown-item:focus {
  background-color: rgba(255, 153, 0, 0.1);
}

.dropdown-item-text {
  padding: 8px 16px;
  font-size: 1rem;
  color: #333;
}

.dropdown-divider {
  margin: 4px 0;
}

/* Notification styles */
.notifications-dropdown {
  position: absolute;
  top: 40px;
  right: -140px;
  width: 350px;
  max-height: 400px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.2s ease-out;
}

.notifications-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
  background-color: #f9f9f9;
}

.notifications-header h5 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #333;
}

.mark-all-read {
  background: none;
  border: none;
  color: var(--accent);
  font-size: 0.9rem;
  cursor: pointer;
  padding: 0;
}

.notifications-list {
  max-height: 350px;
  overflow-y: auto;
}

.notification-item {
  display: flex;
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  transition: background-color 0.2s;
  position: relative;
}

.notification-item:hover {
  background-color: #f5f5f5;
}

.notification-item.unread {
  background-color: rgba(255, 153, 0, 0.05);
}

.notification-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #f0f0f0;
  margin-right: 12px;
  flex-shrink: 0;
}

.notification-icon i {
  font-size: 1.2rem;
  color: var(--accent);
}

.notification-content {
  flex: 1;
}

.notification-title {
  font-size: 0.95rem;
  font-weight: 600;
  margin: 0 0 5px 0;
  color: #333;
}

.notification-text {
  font-size: 0.85rem;
  color: #666;
  margin: 0 0 5px 0;
  line-height: 1.3;
}

.notification-time {
  font-size: 0.75rem;
  color: #999;
}

.notification-unread-indicator {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: var(--accent);
  position: absolute;
  top: 16px;
  right: 16px;
}

.no-notifications {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px 0;
  color: #999;
}

.no-notifications i {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.no-notifications p {
  font-size: 1rem;
  margin: 0;
}

.d-flex.text-end {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 15px;
}

.header-actions {
  gap: 10px;
}

.position-relative {
  position: relative;
}

.nav-icon {
  color: white;
  font-size: 2.8rem;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  width: 48px;
  height: 48px;
  padding: 0;
  border-radius: 50%;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.nav-icon:hover {
  background-color: rgba(255, 255, 255, 0.2);
  transform: scale(1.08);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.dropdown-toggle::after {
  display: none !important;
}

.avatar-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #fff;
}

.user-initial-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 2rem;
  border: 2px solid #fff;
  text-transform: uppercase;
}

.custom-badge {
  top: -8px;
  right: -8px;
  font-size: 0.8rem !important;
  min-width: 22px;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--accent);
  color: white;
  font-weight: 600;
  border: 1px solid #fff;
  padding: 0 !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}
</style>
