<script setup lang="ts">
import { ref, onMounted } from "vue";
import { RouterLink } from "vue-router";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";
import axios from "axios";

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
                          :src="userInfo.avatar || '../../../public/img/user.webp'"
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