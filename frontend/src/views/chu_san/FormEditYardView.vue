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