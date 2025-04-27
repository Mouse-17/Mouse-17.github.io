<script setup lang="ts">
    import { ref, computed } from 'vue'
    import Editor from '@tinymce/tinymce-vue'
    import { RouterLink, useRoute } from 'vue-router'

    const content = ref('') // Biến lưu nội dung từ TinyMCE

    // Hàm xử lý khi thay đổi nội dung
    const handleEditorChange = (newContent:string) => {
        content.value = newContent
    }

    // Hàm lưu dữ liệu vào database (có thể gọi API backend)
    const saveToDatabase = async () => {
        try {
            const response = await fetch('https://ov7q7a587gh1evvwf2jarxnmfk8ycc7wfv69v5jsdvh0huew.com/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content: content.value })
            })
            const result = await response.json()
            console.log('Lưu thành công:', result)
        } catch (error) {
            console.error('Lỗi khi lưu:', error)
        }
    }

    const route = useRoute()
    // Xác định đường dẫn hiện tại
    const currentPath = computed(() => route.path)

    // Danh sách các mục menu
    const menuItems = [
        { name: 'Thống kê', icon: 'bi-bar-chart', path: '/chusan' },
        { name: 'Lịch sân', icon: 'bi-list-check', path: '/lichsan' },
        { name: 'Chờ phê duyệt', icon: 'bi-hourglass-split', path: '/pheduyet' },
        { name: 'Khách hàng thân thiết', icon: 'bi-hearts', path: '/khyeuthich' },
        { name: 'Cài đặt', icon: 'bi-gear', path: '/caidat' }
    ]

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
                        <RouterLink to="/themsanmoi" class="btn-booknow my-3 mb-4" style="color: var(--white) !important;margin-left: 12px;">Thêm sân mới</RouterLink>
                        <ul class="p-0 forboss">
                            <li v-for="item in menuItems" :key="item.path">
                                <RouterLink :to="item.path" class="d-flex align-items-center gap-3 boss-item-link" 
                                :class="{ 'router-link-exact-active': currentPath === item.path }">
                                    <i :class="`bi ${item.icon} fs-4`"></i>
                                    <p class="m-0">{{ item.name }}</p>
                                </RouterLink>
                            </li>
                        </ul>

                    </aside>
                </div>
                <div class="col-10">
                    <div class="bg-white p-5" style="box-shadow: 0 0 18px var(--shadow2); height: 667px; border-radius: 20px 0 0 20px;">
                        <h3 class="px-3 m-0 fs-1 fw-bold text-start" style="color: var(--colortext1);">Thêm sân mới</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row gx-0">
                                <div class="col-5 p-0">
                                    <div class="px-3">
                                        <div class="px-2">
                                            <label for="inputNameYard" class="mt-5 mb-3" style="font-size: 1.6rem;">Tên sân</label>
                                            <input type="text" class="d-block w-100 form-date inputBorder" id="inputNameYard" placeholder="VD: Tada D2">
                                        </div>
                                        <div class="px-2">
                                            <label for="inputAddress" class="mt-5 mb-3" style="font-size: 1.6rem;">Địa chỉ</label>
                                            <input type="text" class="d-block w-100 form-date inputBorder" id="inputAddress" placeholder="VD: 57 Ung Văn Khiêm, Bình Thạnh,...">
                                        </div>
                                        <div class="row justify-content-between align-items-center gx-0">
                                            <div class="col-6">
                                                <div class="px-2">
                                                    <label for="inputEmail" class="d-block mt-5 mb-3" style="font-size: 1.6rem;">Sân</label>
                                                    <select name="" id="" class="form-date inputBorder d-block w-100">
                                                        <option value="" selected>Bóng đá</option>
                                                        <option value="">Bóng rổ</option>
                                                        <option value="">Tennis</option>
                                                        <option value="">Cầu lông</option>
                                                        <option value="">Pickkleball</option>
                                                        <option value="">Bóng chuyền</option>
                                                        <option value="">Golf</option>
                                                        <option value="">Hồ bơi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="px-2">
                                                    <label for="inputEmail" class="d-block mt-5 mb-3" style="font-size: 1.6rem;">Loại sân</label>
                                                    <select name="" id="" class="form-date inputBorder d-block w-100">
                                                        <option value="" selected>Sân 5</option>
                                                        <option value="">Sân 7</option>
                                                        <option value="">Sân 11</option>
                                                        <option value="">Sân ngoài trời</option>
                                                        <option value="">Sân trong nhà</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="px-2">
                                                    <label for="inputEmail" class="d-block mt-5 mb-3" style="font-size: 1.6rem;">Mở cửa</label>
                                                    <select name="" id="" class="form-date inputBorder d-block w-100">
                                                        <option value="" selected>24/24</option>
                                                        <option value="">05:00 - 22:00</option>
                                                        <option value="">05:00 - 23:00</option>
                                                        <option value="">05:00 - 00:00</option>
                                                        <option value="">06:00 - 22:00</option>
                                                        <option value="">06:00 - 23:00</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="px-2">
                                                    <label for="inputEmail" class="d-block mt-5 mb-3" style="font-size: 1.6rem;">Tổng số sân</label>
                                                    <input type="number" name="" id="" value="1" min="1" max="11" style="padding: 13px 8px;" class="form-date inputBorder d-block w-100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-0">
                                            <div class="col-6 px-0">
                                                <div class="px-2">
                                                    <label for="inputUpMain" class="mt-5 mb-3" style="font-size: 1.6rem;">Ảnh đại diện</label>
                                                    <input type="file" class="d-block w-100 form-date inputBorder" id="inputUpMain">
                                                    <p class="mt-3 fs-4 fw-light text-center fst-italic" style="color: var(--colortext3);">Chọn 1 ảnh</p>
                                                </div>
                                            </div>
                                            <div class="col-6 px-0">
                                                <div class="px-2">
                                                    <label for="inputUpThumnail" class="mt-5 mb-3" style="font-size: 1.6rem;">Ảnh phụ</label>
                                                    <input type="file" multiple class="d-block w-100 form-date inputBorder" id="inputUpThumnail">
                                                    <p class="mt-3 fs-4 fw-light text-center fst-italic" style="color: var(--colortext3);">Chọn nhiều ảnh</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7 p-0">
                                    <div class="ps-3">
                                        <label for="textareaDesc" class="mt-5 mb-3" style="font-size: 1.6rem;">Mô tả</label>
                                        <div>
                                            <Editor
                                                api-key="ov7q7a587gh1evvwf2jarxnmfk8ycc7wfv69v5jsdvh0huew"
                                                v-model="content"
                                                :init="{
                                                    display: 'block',
                                                    width: '100%',
                                                    height: '460px',
                                                    plugins: 'lists link image table code help wordcount',
                                                    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
                                                }"
                                                @input="handleEditorChange"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-booknow">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>