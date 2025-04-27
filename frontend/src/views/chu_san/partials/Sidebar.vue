<template>
  <div class="col-2">
    <aside class="py-5">
      <div
          class="d-flex align-items-center gap-3 mb-5"
          style="margin-left: 12px"
      >
        <div class="boss-img">
          <img
              src="/public/img/user.webp"
              class="img-fluid"
              alt=""
          />
        </div>
        <div>
          <p
              class="m-0 fs-4 fw-regular my-1"
              style="color: var(--colortext3)"
          >
            Xin chào,
          </p>
          <p
              class="m-0 fs-3 fw-semibold my-1"
              style="color: var(--colortext1)"
          >
            Shin
          </p>
        </div>
      </div>
      <RouterLink
          to="/themsanmoi"
          @click="selectedItem = -1"
          class="btn-booknow my-3 mb-4"
          style="color: var(--white) !important; margin-left: 12px"
      >Thêm sân mới</RouterLink>
      <ul class="p-0">
        <li v-for="(item, index) in menuItems" :key="index">
          <RouterLink
              :to="item.link"
              class="d-flex align-items-center gap-3 boss-item-link"
              :class="{ 'boss-link-active': selectedItem === index }"
              @click="selectedItem = index"
          >
            <i :class="item.icon + ' fs-4'"></i>
            <p class="m-0">{{ item.text }}</p>
          </RouterLink>
        </li>
        <!-- Nút đăng xuất -->
        <li class="mt-4">
          <button
              @click="handleLogout"
              class="d-flex align-items-center gap-3 boss-item-link w-100 border-0 bg-transparent text-start"
              style="color: #dc3545"
          >
            <i class="bi bi-box-arrow-right fs-4"></i>
            <p class="m-0">Đăng xuất</p>
          </button>
        </li>
      </ul>
    </aside>
  </div>
</template>
<script setup lang="ts">
import {ref} from "vue";
import {RouterLink} from "vue-router";

const selectedItem = ref(0);
const menuItems = ref([
  { text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan" },
  { text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan" },
  { text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet" },
  { text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich" },
  { text: "Cài đặt", icon: "bi bi-gear", link: "/caidat" },
]);

</script>