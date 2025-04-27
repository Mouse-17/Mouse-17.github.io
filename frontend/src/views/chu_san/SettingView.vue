<script lang="ts" setup>
import {ref} from 'vue'
import {RouterLink} from 'vue-router'
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

const selectedItem = ref(0);
const isEditing = ref(false);
const menuItems = ref([
  {text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan"},
  {text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan"},
  {text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet"},
  {text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich"},
  {text: "Cài đặt", icon: "bi bi-gear", link: "/caidat"}
]);

const toggleEdit = () => {
  isEditing.value = !isEditing.value;
};
</script>
<template>
  <main>
    <section class="accept">
      <div class="row gx-0">
        <Sidebar/>
        <div class="col-10">
          <div class="bg-white p-5"
               style="box-shadow: 0 0 18px var(--shadow2); height: 667px; border-radius: 20px 0 0 20px;">
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="m-0 fs-2 fw-bold text-start" style="color: var(--colortext1);">Cài đặt</h3>
            </div>
            <div class="mt-4">
              <div class="row gx-0">
                <div class="col-4 p-0">
                  <div class="pb-2 pe-3">
                    <div class="profile-card">
                      <div class="text-center mt-5">
                        <div class="profile-img mx-auto mb-3"
                             style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden;">
                          <img alt="Profile Image" class="img-fluid w-100 h-100 object-fit-cover"
                               src="../../../public/img/user.webp">
                        </div>
                        <h4 class="m-0 fs-3 fw-bold text-center" style="color: var(--white);">Shin</h4>
                        <p class="m-0 mt-1 text-center" style="color: var(--colortext3);">Chủ sân bóng đá</p>
                        <RouterLink class="btn-booknow d-block mx-auto mt-4" to="/suasan">Chỉnh sửa thông tin sân
                        </RouterLink>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-8 p-0">
                  <div class="form-profile">
                    <form action="" class="">
                      <div class="text-start">
                        <div class="mb-3">
                          <label class="form-label fs-4 mt-2" for="bossname" style="color: var(--colortext1);">Họ và
                            tên</label>
                          <input id="bossname" :disabled="!isEditing" class="inputBorder d-block" placeholder="Họ tên"
                                 style="width: calc(98% - 3px);" type="text" value="Shin">
                        </div>
                        <div class="mb-3">
                          <label class="form-label fs-4 mt-2" for="bossemail"
                                 style="color: var(--colortext1);">Email</label>
                          <input :disabled="!isEditing" class="inputBorder d-block" placeholder="Email"
                                 style="width: calc(98% - 3px);" type="email" value="hantruong@gmail.com">
                        </div>
                        <div class="mb-3">
                          <label class="form-label fs-4 mt-2" for="bossphone" style="color: var(--colortext1);">Số điện
                            thoại</label>
                          <input id="bossphone" :disabled="!isEditing" class="inputBorder d-block" placeholder="Số điện thoại"
                                 style="width: calc(98% - 3px);" type="tel" value="0987654321">
                        </div>
                        <div class="mb-3">
                          <label class="form-label fs-4 mt-2" for="bossaddress" style="color: var(--colortext1);">Địa
                            chỉ</label>
                          <input :disabled="!isEditing" class="inputBorder d-block" placeholder="Địa chỉ"
                                 style="width: calc(98% - 3px);" type="text" value="123 Đường ABC, Quận 1, TP.HCM">
                        </div>
                      </div>
                      <div class="d-flex justify-content-end">
                        <button class="btn-booknow px-4 mt-3" type="button" @click="toggleEdit">
                          <i :class="isEditing ? 'bi bi-check-circle-fill text-white fs-2' : 'bi bi-pencil-square text-white fs-2'"
                             class="fs-4" style="color: var(--primary);"></i>
                        </button>
                      </div>
                    </form>
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