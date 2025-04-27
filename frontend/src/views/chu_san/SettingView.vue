<script setup lang="ts">
    import { ref } from 'vue'
    import { RouterLink } from 'vue-router'
    const selectedItem = ref(0);
    const isEditing = ref(false);
    const menuItems = ref([
        { text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan"},
        { text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan"},
        { text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet"},
        { text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich"},
        { text: "Cài đặt", icon: "bi bi-gear", link: "/caidat"}
    ]);

    const toggleEdit = () => {
        isEditing.value = !isEditing.value;
    };
</script>
<template>
    <main>
        <section class="accept">
            <div class="row gx-0">
                <div class="col-2">
                    <aside class="py-5">
                        <div class="d-flex align-items-center gap-3 mb-5" style="margin-left: 12px;">
                            <div class="boss-img"><img src="../../../public/img/user.webp" class="img-fluid" alt=""></div>
                            <div>
                                <p class="m-0 fs-4 fw-regular my-1" style="color: var(--colortext3);">Xin chào,</p>
                                <p class="m-0 fs-3 fw-semibold my-1" style="color: var(--colortext1);">Shin</p>
                            </div>
                        </div>
                        <RouterLink to="/themsanmoi" @click="selectedItem = -1" class="btn-booknow my-3 mb-4" style="color: var(--white) !important; margin-left: 12px;">Thêm sân mới</RouterLink>
                        <ul class="p-0">
                            <li v-for="(item, index) in menuItems" :key="index">
                                <RouterLink :to="item.link" 
                                class="d-flex align-items-center gap-3 boss-item-link"
                                :class="{ 'boss-link-active': selectedItem === index }"
                                @click="selectedItem = index">
                                    <i :class="item.icon + ' fs-4'"></i>
                                    <p class="m-0">{{ item.text }}</p>
                                </RouterLink>
                            </li>
                        </ul>
                    </aside>
                </div>
                <div class="col-10">
                    <div class="bg-white p-5" style="box-shadow: 0 0 18px var(--shadow2); height: 667px; border-radius: 20px 0 0 20px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="m-0 fs-2 fw-bold text-start" style="color: var(--colortext1);">Cài đặt</h3>
                        </div>
                        <div class="mt-4">
                            <div class="row gx-0">
                                <div class="col-4 p-0">
                                    <div class="pb-2 pe-3">
                                        <div class="profile-card">
                                            <div class="text-center mt-5">
                                                <div class="profile-img mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden;">
                                                    <img src="../../../public/img/user.webp" class="img-fluid w-100 h-100 object-fit-cover" alt="Profile Image">
                                                </div>
                                                <h4 class="m-0 fs-3 fw-bold text-center" style="color: var(--white);">Shin</h4>
                                                <p class="m-0 mt-1 text-center" style="color: var(--colortext3);">Chủ sân bóng đá</p>
                                                <RouterLink to="/suasan" class="btn-booknow d-block mx-auto mt-4">Chỉnh sửa thông tin sân</RouterLink>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8 p-0">
                                    <div class="form-profile">
                                        <form action="" class="">
                                            <div class="text-start">
                                                <div class="mb-3">
                                                    <label for="bossname" class="form-label fs-4 mt-2" style="color: var(--colortext1);">Họ và tên</label>
                                                    <input type="text" class="inputBorder d-block" style="width: calc(98% - 3px);" id="bossname" value="Shin" placeholder="Họ tên" :disabled="!isEditing">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bossemail" class="form-label fs-4 mt-2" style="color: var(--colortext1);">Email</label>
                                                    <input type="email" class="inputBorder d-block" style="width: calc(98% - 3px);" value="hantruong@gmail.com" placeholder="Email" :disabled="!isEditing">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bossphone" class="form-label fs-4 mt-2" style="color: var(--colortext1);">Số điện thoại</label>
                                                    <input type="tel" class="inputBorder d-block" style="width: calc(98% - 3px);" id="bossphone" value="0987654321" placeholder="Số điện thoại" :disabled="!isEditing">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bossaddress" class="form-label fs-4 mt-2" style="color: var(--colortext1);">Địa chỉ</label>
                                                    <input type="text" class="inputBorder d-block" style="width: calc(98% - 3px);" value="123 Đường ABC, Quận 1, TP.HCM" placeholder="Địa chỉ" :disabled="!isEditing">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn-booknow px-4 mt-3" @click="toggleEdit">
                                                    <i :class="isEditing ? 'bi bi-check-circle-fill text-white fs-2' : 'bi bi-pencil-square text-white fs-2'" class="fs-4" style="color: var(--primary);"></i>
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