<template>
  <main class="profile-page py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card profile-sidebar">
            <div class="card-body text-center">
              <div class="avatar-container mb-4">
                <img
                  v-if="userAvatar"
                  :src="userAvatar"
                  alt="Profile Avatar"
                  class="rounded-circle img-fluid profile-avatar"
                />
                <div 
                  v-else
                  class="profile-initial-avatar"
                  :style="{ backgroundColor: getUserAvatarColor(user.name) }"
                >
                  {{ getUserInitials(user.name) }}
                </div>
                <div class="avatar-overlay" @click="triggerFileInput">
                  <i class="bi bi-camera"></i>
                </div>
                <input
                  type="file"
                  ref="fileInput"
                  accept="image/*"
                  style="display: none"
                  @change="handleAvatarChange"
                />
              </div>
              <h3 class="mb-1">{{ user.name }}</h3>
              <p class="text-muted">{{ user.email }}</p>
              <p class="badge" :class="roleBadgeClass">{{ roleDisplayName }}</p>
            </div>
            <div class="list-group list-group-flush">
              <button
                class="list-group-item list-group-item-action"
                :class="{ active: activeTab === 'profile' }"
                @click="activeTab = 'profile'"
              >
                <i class="bi bi-person me-2"></i> Thông tin cá nhân
              </button>
              <button
                class="list-group-item list-group-item-action"
                :class="{ active: activeTab === 'password' }"
                @click="activeTab = 'password'"
              >
                <i class="bi bi-lock me-2"></i> Đổi mật khẩu
              </button>
              <button
                class="list-group-item list-group-item-action"
                :class="{ active: activeTab === 'bookings' }"
                @click="activeTab = 'bookings'"
              >
                <i class="bi bi-calendar-check me-2"></i> Lịch sử đặt sân
              </button>
              <button
                class="list-group-item list-group-item-action"
                :class="{ active: activeTab === 'orders' }"
                @click="activeTab = 'orders'"
              >
                <i class="bi bi-bag me-2"></i> Lịch sử mua hàng
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-white">
              <h4 class="mb-0">
                <span v-if="activeTab === 'profile'">Thông tin cá nhân</span>
                <span v-else-if="activeTab === 'password'">Đổi mật khẩu</span>
                <span v-else-if="activeTab === 'bookings'"
                  >Lịch sử đặt sân</span
                >
                <span v-else-if="activeTab === 'orders'">Lịch sử mua hàng</span>
              </h4>
            </div>
            <div class="card-body">
              <!-- Alert Messages -->
              <div
                v-if="message"
                class="alert"
                :class="isError ? 'alert-danger' : 'alert-success'"
                role="alert"
              >
                {{ message }}
              </div>

              <!-- Profile Tab -->
              <div v-if="activeTab === 'profile'">
                <div class="profile-info-container">
                  <form @submit.prevent="updateProfile">
                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Họ tên</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-person-fill"></i
                          ></span>
                          <input
                            type="text"
                            v-model="profileForm.name"
                            class="form-control"
                            placeholder="Nhập họ tên"
                            required
                          />
                        </div>
                        <div v-if="errors.name" class="text-danger mt-1">
                          {{ errors.name[0] }}
                        </div>
                      </div>

                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Email</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-envelope-fill"></i
                          ></span>
                          <input
                            type="email"
                            v-model="user.email"
                            class="form-control"
                            readonly
                            disabled
                          />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-telephone-fill"></i
                          ></span>
                          <input
                            type="tel"
                            v-model="profileForm.phone"
                            class="form-control"
                            placeholder="Nhập số điện thoại"
                          />
                        </div>
                        <div v-if="errors.phone" class="text-danger mt-1">
                          {{ errors.phone[0] }}
                        </div>
                      </div>

                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Thành phố/Tỉnh</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-building"></i
                          ></span>
                          <input
                            type="text"
                            v-model="profileForm.city"
                            class="form-control"
                            placeholder="Nhập thành phố/tỉnh"
                          />
                        </div>
                        <div v-if="errors.city" class="text-danger mt-1">
                          {{ errors.city[0] }}
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Quận/Huyện</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-geo"></i
                          ></span>
                          <input
                            type="text"
                            v-model="profileForm.district"
                            class="form-control"
                            placeholder="Nhập quận/huyện"
                          />
                        </div>
                        <div v-if="errors.district" class="text-danger mt-1">
                          {{ errors.district[0] }}
                        </div>
                      </div>

                      <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Phường/Xã</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-geo-alt"></i
                          ></span>
                          <input
                            type="text"
                            v-model="profileForm.ward"
                            class="form-control"
                            placeholder="Nhập phường/xã"
                          />
                        </div>
                        <div v-if="errors.ward" class="text-danger mt-1">
                          {{ errors.ward[0] }}
                        </div>
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label fw-bold">Địa chỉ</label>
                      <div class="input-group input-group-lg">
                        <span class="input-group-text"
                          ><i class="bi bi-geo-alt-fill"></i
                        ></span>
                        <textarea
                          v-model="profileForm.address"
                          class="form-control"
                          placeholder="Nhập địa chỉ"
                          rows="3"
                        ></textarea>
                      </div>
                      <div v-if="errors.address" class="text-danger mt-1">
                        {{ errors.address[0] }}
                      </div>
                    </div>

                    <div class="shipping-info mt-4 mb-4">
                      <h5 class="mb-3">Thông tin giao hàng (nếu khác với thông tin cá nhân)</h5>
                      
                      <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-truck"></i
                          ></span>
                          <textarea
                            v-model="profileForm.shipping_address"
                            class="form-control"
                            placeholder="Nhập địa chỉ giao hàng (nếu khác)"
                            rows="3"
                          ></textarea>
                        </div>
                        <div v-if="errors.shipping_address" class="text-danger mt-1">
                          {{ errors.shipping_address[0] }}
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại giao hàng</label>
                        <div class="input-group input-group-lg">
                          <span class="input-group-text"
                            ><i class="bi bi-telephone"></i
                          ></span>
                          <input
                            type="tel"
                            v-model="profileForm.shipping_phone"
                            class="form-control"
                            placeholder="Nhập số điện thoại giao hàng (nếu khác)"
                          />
                        </div>
                        <div v-if="errors.shipping_phone" class="text-danger mt-1">
                          {{ errors.shipping_phone[0] }}
                        </div>
                      </div>
                    </div>

                    <div class="d-grid mt-4">
                      <button
                        type="submit"
                        class="btn btn-warning btn-lg"
                        :disabled="loading"
                      >
                        <span
                          v-if="loading"
                          class="spinner-border spinner-border-sm me-2"
                          role="status"
                          aria-hidden="true"
                        ></span>
                        <i class="bi bi-check-circle-fill me-2"></i> Cập nhật
                        thông tin
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Password Tab -->
              <div v-if="activeTab === 'password'">
                <form @submit.prevent="changePassword">
                  <div class="mb-3">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input
                      type="password"
                      v-model="passwordForm.current_password"
                      class="form-control form-control-lg"
                      placeholder="Nhập mật khẩu hiện tại"
                      required
                    />
                    <div
                      v-if="errors.current_password"
                      class="text-danger mt-1"
                    >
                      {{ errors.current_password[0] }}
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input
                      type="password"
                      v-model="passwordForm.password"
                      class="form-control form-control-lg"
                      placeholder="Nhập mật khẩu mới"
                      required
                      minlength="8"
                    />
                    <div v-if="errors.password" class="text-danger mt-1">
                      {{ errors.password[0] }}
                    </div>
                    <small class="text-muted"
                      >Mật khẩu phải có ít nhất 8 ký tự</small
                    >
                  </div>

                  <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input
                      type="password"
                      v-model="passwordForm.password_confirmation"
                      class="form-control form-control-lg"
                      placeholder="Nhập lại mật khẩu mới"
                      required
                      minlength="8"
                    />
                  </div>

                  <div class="d-grid">
                    <button
                      type="submit"
                      class="btn btn-primary btn-lg"
                      :disabled="loading"
                    >
                      <span
                        v-if="loading"
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true"
                      ></span>
                      Đổi mật khẩu
                    </button>
                  </div>
                </form>
              </div>

              <!-- Bookings Tab -->
              <div v-if="activeTab === 'bookings'">
                <div v-if="loadingBookings" class="text-center py-4">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                  </div>
                </div>
                <div v-else-if="bookings.length === 0" class="text-center py-4">
                  <i class="bi bi-calendar-x display-4 text-muted"></i>
                  <p class="mt-3">Bạn chưa có lịch sử đặt sân nào.</p>
                </div>
                <div v-else class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Sân</th>
                        <th>Ngày</th>
                        <th>Khung giờ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="booking in bookings" :key="booking.id">
                        <td>{{ booking.field?.Ten_san || "N/A" }}</td>
                        <td>{{ formatDate(booking.Ngay_dat) }}</td>
                        <td>
                          {{ booking.timeSlot?.Gio_bat_dau }} -
                          {{ booking.timeSlot?.Gio_ket_thuc }}
                        </td>
                        <td>
                          <span
                            class="badge"
                            :class="getBookingStatusClass(booking.Trang_thai)"
                          >
                            {{ getBookingStatusText(booking.Trang_thai) }}
                          </span>
                        </td>
                        <td>
                          <button
                            v-if="canCancelBooking(booking)"
                            @click="cancelBooking(booking.id)"
                            class="btn btn-sm btn-outline-danger"
                            :disabled="cancelingBookingId === booking.id"
                          >
                            <span
                              v-if="cancelingBookingId === booking.id"
                              class="spinner-border spinner-border-sm"
                              role="status"
                              aria-hidden="true"
                            ></span>
                            <span v-else>Hủy</span>
                          </button>
                          <button
                            @click="viewBookingDetail(booking.id)"
                            class="btn btn-sm btn-outline-primary ms-1"
                          >
                            Chi tiết
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Orders Tab -->
              <div v-if="activeTab === 'orders'">
                <div v-if="loadingOrders" class="text-center py-4">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                  </div>
                </div>
                <div v-else-if="orders.length === 0" class="text-center py-4">
                  <i class="bi bi-bag-x display-4 text-muted"></i>
                  <p class="mt-3">Bạn chưa có đơn hàng nào.</p>
                </div>
                <div v-else class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="order in orders" :key="order.id">
                        <td>{{ order.Ma_don_hang }}</td>
                        <td>{{ formatDate(order.created_at) }}</td>
                        <td>{{ formatCurrency(order.Tong_tien) }}</td>
                        <td>
                          <span
                            class="badge"
                            :class="getOrderStatusClass(order.Trang_thai)"
                          >
                            {{ getOrderStatusText(order.Trang_thai) }}
                          </span>
                        </td>
                        <td>
                          <button
                            @click="viewOrderDetail(order.id)"
                            class="btn btn-sm btn-outline-primary"
                          >
                            Chi tiết
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { useAuthStore } from "../stores/auth";
import axios from "axios";

export default {
  name: "UserProfileView",
  data() {
    return {
      activeTab: "profile",
      loading: false,
      message: "",
      isError: false,
      errors: {},
      profileForm: {
        name: "",
        phone: "",
        address: "",
        city: "",
        district: "",
        ward: "",
        shipping_address: "",
        shipping_phone: "",
      },
      passwordForm: {
        current_password: "",
        password: "",
        password_confirmation: "",
      },
      bookings: [],
      loadingBookings: false,
      orders: [],
      loadingOrders: false,
      cancelingBookingId: null,
      userAvatar: null,
    };
  },
  computed: {
    user() {
      return useAuthStore().user || {};
    },
    roleDisplayName() {
      const role = this.user.role;
      if (role === "admin") return "Quản trị viên";
      return "Người dùng";
    },
    roleBadgeClass() {
      const role = this.user.role;
      if (role === "admin") return "bg-danger";
      if (role === "field_owner") return "bg-primary";
      return "bg-success";
    },
  },
  methods: {
    async updateProfile() {
      this.loading = true;
      this.message = "";
      this.isError = false;
      this.errors = {};

      try {
        const formData = new FormData();
        formData.append("name", this.profileForm.name);
        formData.append("phone", this.profileForm.phone);
        formData.append("address", this.profileForm.address);
        formData.append("city", this.profileForm.city);
        formData.append("district", this.profileForm.district);
        formData.append("ward", this.profileForm.ward);
        formData.append("shipping_address", this.profileForm.shipping_address);
        formData.append("shipping_phone", this.profileForm.shipping_phone);

        const authStore = useAuthStore();
        const response = await authStore.updateProfile(formData);

        this.message = "Cập nhật thông tin thành công!";
        this.isError = false;
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
            this.message =
              error.response.data.message ||
              "Vui lòng kiểm tra thông tin cập nhật.";
          } else {
            this.message =
              error.response.data.message ||
              "Đã xảy ra lỗi khi cập nhật thông tin.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ.";
        }
      } finally {
        this.loading = false;
      }
    },

    async changePassword() {
      this.loading = true;
      this.message = "";
      this.isError = false;
      this.errors = {};

      try {
        const response = await axios.post(
          "/api/change-password",
          this.passwordForm
        );

        this.message = "Đổi mật khẩu thành công!";
        this.isError = false;

        // Clear form
        this.passwordForm = {
          current_password: "",
          password: "",
          password_confirmation: "",
        };
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
            this.message =
              error.response.data.message ||
              "Vui lòng kiểm tra thông tin mật khẩu.";
          } else {
            this.message =
              error.response.data.message || "Đã xảy ra lỗi khi đổi mật khẩu.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ.";
        }
      } finally {
        this.loading = false;
      }
    },

    async loadBookings() {
      if (this.bookings.length > 0) return;

      this.loadingBookings = true;

      try {
        const response = await axios.get("/api/bookings");
        this.bookings = response.data;
      } catch (error) {
        console.error("Error loading bookings:", error);
      } finally {
        this.loadingBookings = false;
      }
    },

    async loadOrders() {
      if (this.orders.length > 0) return;

      this.loadingOrders = true;

      try {
        const response = await axios.get("/api/orders");
        this.orders = response.data;
      } catch (error) {
        console.error("Error loading orders:", error);
      } finally {
        this.loadingOrders = false;
      }
    },

    async cancelBooking(id) {
      this.cancelingBookingId = id;

      try {
        await axios.put(`/api/bookings/${id}`, { Trang_thai: 0 });

        // Update booking status in the list
        const index = this.bookings.findIndex((b) => b.id === id);
        if (index !== -1) {
          this.bookings[index].Trang_thai = 0;
        }

        this.message = "Hủy đặt sân thành công!";
        this.isError = false;
      } catch (error) {
        this.isError = true;
        this.message =
          error.response?.data?.message || "Không thể hủy đặt sân.";
      } finally {
        this.cancelingBookingId = null;
      }
    },

    viewBookingDetail(id) {
      this.$router.push(`/booking-detail/${id}`);
    },

    viewOrderDetail(id) {
      this.$router.push(`/orders/${id}`);
    },

    formatDate(dateString) {
      if (!dateString) return "N/A";
      const date = new Date(dateString);
      return date.toLocaleDateString("vi-VN");
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(amount);
    },

    getBookingStatusClass(status) {
      switch (Number(status)) {
        case 0:
          return "bg-danger"; // Cancelled
        case 1:
          return "bg-warning"; // Pending
        case 2:
          return "bg-success"; // Confirmed
        default:
          return "bg-secondary";
      }
    },

    getBookingStatusText(status) {
      switch (Number(status)) {
        case 0:
          return "Đã hủy";
        case 1:
          return "Chờ xác nhận";
        case 2:
          return "Đã xác nhận";
        default:
          return "Không xác định";
      }
    },

    getOrderStatusClass(status) {
      switch (Number(status)) {
        case 0:
          return "bg-danger"; // Cancelled
        case 1:
          return "bg-warning"; // Pending
        case 2:
          return "bg-info"; // Processing
        case 3:
          return "bg-primary"; // Shipped
        case 4:
          return "bg-success"; // Delivered
        default:
          return "bg-secondary";
      }
    },

    getOrderStatusText(status) {
      switch (Number(status)) {
        case 0:
          return "Đã hủy";
        case 1:
          return "Chờ xác nhận";
        case 2:
          return "Đang xử lý";
        case 3:
          return "Đang giao hàng";
        case 4:
          return "Đã giao hàng";
        default:
          return "Không xác định";
      }
    },

    canCancelBooking(booking) {
      return booking.Trang_thai == 1; // Can only cancel pending bookings
    },

    triggerFileInput() {
      this.$refs.fileInput.click();
    },

    async handleAvatarChange(event) {
      const file = event.target.files[0];
      if (!file) return;

      // Preview image
      this.userAvatar = URL.createObjectURL(file);

      // Prepare for upload
      const formData = new FormData();
      formData.append("avatar", file);

      this.loading = true;
      try {
        const authStore = useAuthStore();
        await authStore.updateProfile(formData);
        this.message = "Cập nhật ảnh đại diện thành công!";
        this.isError = false;
      } catch (error) {
        this.isError = true;
        this.message = "Không thể cập nhật ảnh đại diện.";
      } finally {
        this.loading = false;
      }
    },

    // Hàm tạo chữ cái đầu từ tên người dùng
    getUserInitials(name) {
      if (!name) return '';
      
      // Chia tên đầy đủ thành các từ
      const nameParts = name.trim().split(' ');
      
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
    },

    // Hàm tạo màu nền cho avatar dựa trên tên người dùng
    getUserAvatarColor(name) {
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
    },
  },
  watch: {
    activeTab(newTab) {
      if (newTab === "bookings") {
        this.loadBookings();
      } else if (newTab === "orders") {
        this.loadOrders();
      }
    },
  },
  mounted() {
    // Initialize form with current user data
    const user = this.user;
    this.profileForm.name = user.name || "";
    this.profileForm.phone = user.phone || "";
    this.profileForm.address = user.address || "";
    this.profileForm.city = user.city || "";
    this.profileForm.district = user.district || "";
    this.profileForm.ward = user.ward || "";
    this.profileForm.shipping_address = user.shipping_address || "";
    this.profileForm.shipping_phone = user.shipping_phone || "";
    this.userAvatar = user.avatar || null;
  },
};
</script>

<style scoped>
.profile-page {
  background-color: #f8f9fa;
  min-height: 80vh;
}

.card {
  border-radius: 10px;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  margin-bottom: 1.5rem;
}

.profile-sidebar {
  position: sticky;
  top: 1rem;
}

.avatar-container {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto;
}

.profile-avatar {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border: 3px solid #fff;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.avatar-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s;
  cursor: pointer;
}

.avatar-overlay i {
  color: white;
  font-size: 2rem;
}

.avatar-container:hover .avatar-overlay {
  opacity: 1;
}

/* Thêm CSS cho phần trang thông tin cá nhân */
.profile-info-container {
  padding: 0.5rem;
}

.input-group-text {
  background-color: #ff9900;
  color: white;
  border: none;
  font-size: 1.25rem;
  width: 50px;
  display: flex;
  justify-content: center;
}

.form-control {
  border: 1px solid #ddd;
  padding: 0.8rem 1rem;
  font-size: 1.1rem;
}

.form-control:focus {
  border-color: #ff9900;
  box-shadow: 0 0 0 0.25rem rgba(255, 153, 0, 0.25);
}

.form-label {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  color: #333;
}

.btn-warning {
  background-color: #ff9900;
  border-color: #ff9900;
  font-weight: bold;
  font-size: 1.1rem;
  transition: all 0.3s ease;
}

.btn-warning:hover {
  background-color: #e68a00;
  border-color: #e68a00;
  box-shadow: 0 5px 15px rgba(255, 153, 0, 0.3);
  transform: translateY(-2px);
}

.badge {
  font-size: 0.85rem;
  padding: 0.35em 0.65em;
}

.list-group-item {
  border-left: none;
  border-right: none;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
}

.list-group-item.active {
  background-color: #ff9900;
  border-color: #ff9900;
}

.table {
  font-size: 0.95rem;
}

.btn-outline-primary:hover {
  background-color: #ff9900;
  border-color: #ff9900;
}

.btn-outline-danger:hover {
  background-color: #dc3545;
  border-color: #dc3545;
}

.shipping-info {
  background-color: #f8f9fa;
  padding: 1.5rem;
  border-radius: 10px;
  border-left: 4px solid #34a853;
}

.shipping-info h5 {
  color: #34a853;
  font-weight: 600;
}

.profile-initial-avatar {
  width: 128px;
  height: 128px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 3rem;
  text-transform: uppercase;
  margin: 0 auto;
  border: 3px solid #fff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
