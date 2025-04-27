<script setup lang="ts">
import { ref, onMounted } from "vue";
import { RouterLink } from "vue-router";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";
import axios from "axios";
const apiURL = import.meta.env.VITE_API_URL as string;


const selectedItem = ref(0);
const isEditing = ref(false);
const userInfo = ref({
  name: "",
  phone: "",
  address: "",
  city: "",
  district: "",
  ward: "",
  shipping_address: "",
  shipping_phone: "",
  avatar: "",
});

const menuItems = ref([
  { text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan" },
  { text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan" },
  { text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet" },
  { text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich" },
  { text: "Cài đặt", icon: "bi bi-gear", link: "/caidat" },
]);

// Hàm lấy thông tin người dùng hiện tại
const fetchUserInfo = async () => {
  try {
    const response = await axios.get("/api/user"); // Đổi URL phù hợp với API của bạn
    if (response.data) {
      userInfo.value = { ...userInfo.value, ...response.data }; // Gộp dữ liệu trả về vào userInfo
    } else {
      alert("Không có dữ liệu người dùng.");
    }
  } catch (error) {
    alert("Không thể lấy thông tin người dùng.");
    console.error(error);
  }
};

// Hàm toggle chỉnh sửa thông tin
const toggleEdit = async () => {
  if (isEditing.value == true) {
    try {
      const formData = new FormData();
      if (userInfo.value.avatar instanceof File) {
        formData.append("avatar", userInfo.value.avatar);
      }
      formData.append("name", userInfo.value.name);
      formData.append("phone", userInfo.value.phone);
      formData.append("address", userInfo.value.address);
      formData.append("city", userInfo.value.city);
      formData.append("district", userInfo.value.district);
      formData.append("ward", userInfo.value.ward);
      formData.append("shipping_address", userInfo.value.shipping_address);
      formData.append("shipping_phone", userInfo.value.shipping_phone);

      await axios.post("/api/update-profile", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      // Hiển thị thông báo thành công
      alert("Cập nhật thông tin thành công!");
    } catch (error) {
      // Hiển thị thông báo lỗi
      alert("Đã xảy ra lỗi khi cập nhật thông tin.");
      console.error(error);
    }
  }

  // Đổi trạng thái chỉnh sửa
  isEditing.value = !isEditing.value;
};

// Gọi API để lấy thông tin người dùng khi component được mount
onMounted(fetchUserInfo);
</script>

<template>
  <main>
    <section class="accept">
      <div class="row gx-0">
        <Sidebar />
        <div class="col-10">
          <div
              class="bg-white p-4"
              style="box-shadow: 0 0 18px var(--shadow2); height: 100%; border-radius: 20px 0 0 20px;"
          >
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h3 class="fs-2 fw-bold text-start" style="color: var(--colortext1);">
                Quản lý thông tin
              </h3>
            </div>
            <div class="row gx-4">
              <div class="col-md-3">
                <div class="profile-card p-3 bg-light border rounded">
                  <div class="text-center">
                    <div
                        class="profile-img mx-auto mb-3"
                        style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden;"
                    >
                      <img
                          alt="Ảnh đại diện"
                          class="img-fluid w-100 h-100 object-fit-cover"
                          :src="userInfo.avatar ? apiURL + userInfo.avatar : apiURL + '/public/img/user.webp'"
                      />
                    </div>
                    <h4 class="fw-bold text-dark">{{ userInfo.name }}</h4>
                    <p class="text-muted">Chủ sân bóng đá</p>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-profile bg-white p-4 border rounded">
                  <form>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="name">Họ và tên</label>
                      <input
                          id="name"
                          v-model="userInfo.name"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập họ và tên"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="phone">Số điện thoại</label>
                      <input
                          id="phone"
                          v-model="userInfo.phone"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập số điện thoại"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="address">Địa chỉ</label>
                      <input
                          id="address"
                          v-model="userInfo.address"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập địa chỉ"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="city">Thành phố</label>
                      <input
                          id="city"
                          v-model="userInfo.city"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập thành phố"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="district">Quận/Huyện</label>
                      <input
                          id="district"
                          v-model="userInfo.district"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập quận/huyện"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="ward">Phường/Xã</label>
                      <input
                          id="ward"
                          v-model="userInfo.ward"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập phường/xã"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="shipping_address">Địa chỉ giao hàng</label>
                      <input
                          id="shipping_address"
                          v-model="userInfo.shipping_address"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập địa chỉ giao hàng"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="shipping_phone">Số điện thoại giao hàng</label>
                      <input
                          id="shipping_phone"
                          v-model="userInfo.shipping_phone"
                          :disabled="!isEditing"
                          class="form-control"
                          placeholder="Nhập số điện thoại giao hàng"
                          type="text"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="avatar">Ảnh đại diện</label>
                      <input
                          id="avatar"
                          :disabled="!isEditing"
                          class="form-control"
                          type="file"
                          @change="(e) => (userInfo.avatar = e.target.files[0])"
                      />
                    </div>
                    <div class="d-flex justify-content-end">
                      <button
                          class="btn btn-success px-4"
                          type="button"
                          @click="toggleEdit"
                      >
                        {{ isEditing ? "Lưu" : "Chỉnh sửa" }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
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
