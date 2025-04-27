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