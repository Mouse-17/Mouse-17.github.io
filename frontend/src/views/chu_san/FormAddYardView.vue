<script lang="ts" setup>
import {computed, ref} from 'vue'
import Editor from '@tinymce/tinymce-vue'
import {useRoute} from 'vue-router'
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

const content = ref('') // Biến lưu nội dung từ TinyMCE

// Hàm xử lý khi thay đổi nội dung
const handleEditorChange = (newContent: string) => {
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
      body: JSON.stringify({content: content.value})
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
  {name: 'Thống kê', icon: 'bi-bar-chart', path: '/chusan'},
  {name: 'Lịch sân', icon: 'bi-list-check', path: '/lichsan'},
  {name: 'Chờ phê duyệt', icon: 'bi-hourglass-split', path: '/pheduyet'},
  {name: 'Khách hàng thân thiết', icon: 'bi-hearts', path: '/khyeuthich'},
  {name: 'Cài đặt', icon: 'bi-gear', path: '/caidat'}
]

</script>
<template>
  <main>
    <section class="accept">
      <div class="row gx-0">
        <Sidebar/>
        <div class="col-10">
          <div class="bg-white p-5"
               style="box-shadow: 0 0 18px var(--shadow2); height: 667px; border-radius: 20px 0 0 20px;">
            <h3 class="px-3 m-0 fs-1 fw-bold text-start" style="color: var(--colortext1);">Thêm sân mới</h3>
            <form action="" enctype="multipart/form-data" method="post">
              <div class="row gx-0">
                <div class="col-5 p-0">
                  <div class="px-3">
                    <div class="px-2">
                      <label class="mt-5 mb-3" for="inputNameYard" style="font-size: 1.6rem;">Tên sân</label>
                      <input id="inputNameYard" class="d-block w-100 form-date inputBorder" placeholder="VD: Tada D2"
                             type="text">
                    </div>
                    <div class="px-2">
                      <label class="mt-5 mb-3" for="inputAddress" style="font-size: 1.6rem;">Địa chỉ</label>
                      <input id="inputAddress" class="d-block w-100 form-date inputBorder" placeholder="VD: 57 Ung Văn Khiêm, Bình Thạnh,..."
                             type="text">
                    </div>
                    <div class="row justify-content-between align-items-center gx-0">
                      <div class="col-6">
                        <div class="px-2">
                          <label class="d-block mt-5 mb-3" for="inputEmail" style="font-size: 1.6rem;">Sân</label>
                          <select id="" class="form-date inputBorder d-block w-100" name="">
                            <option selected value="">Bóng đá</option>
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
                          <label class="d-block mt-5 mb-3" for="inputEmail" style="font-size: 1.6rem;">Loại sân</label>
                          <select id="" class="form-date inputBorder d-block w-100" name="">
                            <option selected value="">Sân 5</option>
                            <option value="">Sân 7</option>
                            <option value="">Sân 11</option>
                            <option value="">Sân ngoài trời</option>
                            <option value="">Sân trong nhà</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="px-2">
                          <label class="d-block mt-5 mb-3" for="inputEmail" style="font-size: 1.6rem;">Mở cửa</label>
                          <select id="" class="form-date inputBorder d-block w-100" name="">
                            <option selected value="">24/24</option>
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
                          <label class="d-block mt-5 mb-3" for="inputEmail" style="font-size: 1.6rem;">Tổng số
                            sân</label>
                          <input id="" class="form-date inputBorder d-block w-100" max="11" min="1" name="" style="padding: 13px 8px;" type="number"
                                 value="1">
                        </div>
                      </div>
                    </div>
                    <div class="row gx-0">
                      <div class="col-6 px-0">
                        <div class="px-2">
                          <label class="mt-5 mb-3" for="inputUpMain" style="font-size: 1.6rem;">Ảnh đại diện</label>
                          <input id="inputUpMain" class="d-block w-100 form-date inputBorder" type="file">
                          <p class="mt-3 fs-4 fw-light text-center fst-italic" style="color: var(--colortext3);">Chọn 1
                            ảnh</p>
                        </div>
                      </div>
                      <div class="col-6 px-0">
                        <div class="px-2">
                          <label class="mt-5 mb-3" for="inputUpThumnail" style="font-size: 1.6rem;">Ảnh phụ</label>
                          <input id="inputUpThumnail" class="d-block w-100 form-date inputBorder" multiple type="file">
                          <p class="mt-3 fs-4 fw-light text-center fst-italic" style="color: var(--colortext3);">Chọn
                            nhiều ảnh</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-7 p-0">
                  <div class="ps-3">
                    <label class="mt-5 mb-3" for="textareaDesc" style="font-size: 1.6rem;">Mô tả</label>
                    <div>
                      <Editor
                          v-model="content"
                          :init="{
                                                    display: 'block',
                                                    width: '100%',
                                                    height: '460px',
                                                    plugins: 'lists link image table code help wordcount',
                                                    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
                                                }"
                          api-key="ov7q7a587gh1evvwf2jarxnmfk8ycc7wfv69v5jsdvh0huew"
                          @input="handleEditorChange"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button class="btn-booknow" type="button">Thêm</button>
              </div>
            </form>
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
