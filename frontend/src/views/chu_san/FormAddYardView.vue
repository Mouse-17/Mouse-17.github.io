<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';
import Editor from '@tinymce/tinymce-vue';
import { useRoute } from 'vue-router';
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

// Biến lưu nội dung từ TinyMCE và thông tin sân
const content = ref('');
const fieldName = ref('');
const address = ref('');
const fieldCategory = ref('');
const price = ref(0); // Biến lưu giá
const quantity = ref(1);
const mainImage = ref<File | null>(null);

// Trạng thái
const successMessage = ref('');
const errorMessage = ref('');
const isLoading = ref(false);

// Hàm xử lý khi thay đổi nội dung TinyMCE
const handleEditorChange = (newContent: string) => {
  content.value = newContent;
};

// Hàm xử lý khi thay đổi file
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    mainImage.value = target.files[0];
  }
};

// Hàm gửi dữ liệu đến API khi nhấn nút "Thêm"
const addField = async () => {
  try {
    isLoading.value = true;

    // Kiểm tra dữ liệu nhập vào
    if (!fieldName.value || !address.value || !content.value || !fieldCategory.value || !mainImage.value || price.value <= 0) {
      errorMessage.value = 'Vui lòng nhập đầy đủ thông tin và đảm bảo giá lớn hơn 0!';
      return;
    }

    // Chuẩn bị dữ liệu form
    const formData = new FormData();
    formData.append('Ten_san', fieldName.value);
    formData.append('Dia_chi', address.value);
    formData.append('Mo_ta', content.value);
    formData.append('ID_Loai', fieldCategory.value);
    formData.append('Gia', price.value.toString()); // Thêm giá vào form
    formData.append('So_luong', quantity.value.toString());
    formData.append('Hinh_anh', mainImage.value);

    // Gửi API
    const response = await axios.post('/api/fields', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    // Xử lý kết quả
    successMessage.value = 'Thêm sân thành công!';
    console.log('Field added:', response.data);
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Có lỗi xảy ra!';
    console.error('Lỗi khi thêm sân:', error);
  } finally {
    isLoading.value = false;
  }
};

</script>

<template>
  <main>
    <section class="accept">
      <div class="row gx-0">
        <Sidebar />
        <div class="col-10">
          <div class="bg-white p-5"
               style="box-shadow: 0 0 18px var(--shadow2); height: 667px; border-radius: 20px 0 0 20px;">
            <h3 class="px-3 m-0 fs-1 fw-bold text-start" style="color: var(--colortext1);">Thêm sân mới</h3>
            <form @submit.prevent="addField">
              <div class="row gx-0">
                <div class="col-5 p-0">
                  <div class="px-3">
                    <div class="px-2">
                      <label class="mt-5 mb-3" for="inputNameYard" style="font-size: 1.6rem;">Tên sân</label>
                      <input id="inputNameYard" v-model="fieldName" class="d-block w-100 form-date inputBorder" placeholder="VD: Tada D2" type="text">
                    </div>
                    <div class="px-2">
                      <label class="mt-5 mb-3" for="inputAddress" style="font-size: 1.6rem;">Địa chỉ</label>
                      <input id="inputAddress" v-model="address" class="d-block w-100 form-date inputBorder" placeholder="VD: 57 Ung Văn Khiêm, Bình Thạnh,..." type="text">
                    </div>
                    <div class="px-2">
                      <label class="mt-5 mb-3" for="inputPrice" style="font-size: 1.6rem;">Giá</label>
                      <input id="inputPrice" v-model="price" class="d-block w-100 form-date inputBorder" placeholder="VD: 500000" type="number" min="0" />
                    </div>
                    <div class="row justify-content-between align-items-center gx-0">
                      <div class="col-6">
                        <div class="px-2">
                          <label class="d-block mt-5 mb-3" for="inputCategory" style="font-size: 1.6rem;">Loại sân</label>
                          <select id="inputCategory" v-model="fieldCategory" class="form-date inputBorder d-block w-100">
                            <option value="1">Sân 5</option>
                            <option value="2">Sân 7</option>
                            <option value="3">Sân 11</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="px-2">
                          <label class="d-block mt-5 mb-3" for="inputQuantity" style="font-size: 1.6rem;">Tổng số sân</label>
                          <input id="inputQuantity" v-model="quantity" class="form-date inputBorder d-block w-100" type="number" min="1" />
                        </div>
                      </div>
                    </div>
                    <div class="row gx-0">
                      <div class="col-6 px-0">
                        <div class="px-2">
                          <label class="mt-5 mb-3" for="inputUpMain" style="font-size: 1.6rem;">Ảnh đại diện</label>
                          <input id="inputUpMain" type="file" @change="handleFileChange" class="d-block w-100 form-date inputBorder" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-7 p-0">
                  <div class="ps-3">
                    <label class="mt-5 mb-3" for="textareaDesc" style="font-size: 1.6rem;">Mô tả</label>
                    <Editor v-model="content" :init="{ height: 460, plugins: 'lists link image table code', toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code' }" api-key="ov7q7a587gh1evvwf2jarxnmfk8ycc7wfv69v5jsdvh0huew" @input="handleEditorChange" />
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button class="btn-booknow" :disabled="isLoading">
                  <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  Thêm
                </button>
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