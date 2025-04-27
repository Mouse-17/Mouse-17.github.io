<script setup lang="ts">
import { ref, onMounted } from "vue";
import { RouterLink } from "vue-router";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";
import axios from "axios";

const selectedItem = ref(0);
const isEditing = ref(false);
const userInfo = ref({
  name: "",
  email: "",
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
    userInfo.value = response.data;
  } catch (error) {
    alert("Không thể lấy thông tin người dùng.");
    console.error(error);
  }
};

// Hàm toggle chỉnh sửa thông tin
const toggleEdit = async () => {
  if (!isEditing.value) {
    try {
      const formData = new FormData();
      Object.keys(userInfo.value).forEach((key) => {
        if (key === "avatar" && userInfo.value.avatar instanceof File) {
          formData.append(key, userInfo.value.avatar);
        } else {
          formData.append(key, userInfo.value[key]);
        }
      });

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

// Hàm để chuyển đổi key thành nhãn tiếng Việt
const getLabel = (key: string): string => {
  const labels: Record<string, string> = {
    name: "Họ và tên",
    email: "Email",
    phone: "Số điện thoại",
    address: "Địa chỉ",
    city: "Thành phố",
    district: "Quận/Huyện",
    ward: "Phường/Xã",
    shipping_address: "Địa chỉ giao hàng",
    shipping_phone: "Số điện thoại giao hàng",
  };
  return labels[key] || key;
};
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
              <div class="col-md-4">
                <div class="profile-card p-3 bg-light border rounded">
                  <div class="text-center">
                    <div
                        class="profile-img mx-auto mb-3"
                        style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden;"
                    >
                      <img
                          alt="Ảnh đại diện"
                          class="img-fluid w-100 h-100 object-fit-cover"
                          :src="userInfo.avatar || '../../../public/img/default-avatar.webp'"
                      />
                    </div>
                    <h4 class="fw-bold text-dark">{{ userInfo.name }}</h4>
                    <p class="text-muted">Chủ sân bóng đá</p>
                    <RouterLink class="btn btn-primary btn-sm mt-3" to="/suasan">
                      Chỉnh sửa thông tin sân
                    </RouterLink>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-profile bg-white p-4 border rounded">
                  <form>
                    <div class="row">
                      <div class="col-md-6 mb-3" v-for="(value, key) in userInfo" :key="key" v-if="key !== 'avatar'">
                        <label
                            class="form-label fw-bold"
                            :for="key"
                            style="color: var(--colortext1);"
                        >
                          {{ getLabel(key) }}
                        </label>
                        <input
                            :id="key"
                            v-model="userInfo[key]"
                            :disabled="!isEditing"
                            class="form-control"
                            :placeholder="getLabel(key)"
                            type="text"
                        />
                      </div>
                      <div class="col-md-12 mb-3">
                        <label
                            class="form-label fw-bold"
                            for="avatar"
                            style="color: var(--colortext1);"
                        >
                          Ảnh đại diện
                        </label>
                        <input
                            id="avatar"
                            :disabled="!isEditing"
                            class="form-control"
                            type="file"
                            @change="(e) => (userInfo.avatar = e.target.files[0])"
                        />
                      </div>
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