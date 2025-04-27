<template>
  <main>
    <section class="admin">
      <div class="admin-left">
        <img src="../../../public/img/user.webp" alt="" class="admin-avatar" />
        <h5>Admin</h5>
        <RouterLink to="/admin"
          ><i class="bi bi-palette2"></i
          ><span>Bảng điều khiển</span></RouterLink
        >
        <RouterLink to="/admin/quanlidanhmuc" class="active"
          ><i class="bi bi-inboxes-fill"></i
          ><span>Quản lý danh mục</span></RouterLink
        >
        <RouterLink to="/admin/quanlisanpham"
          ><i class="bi bi-box2-fill"></i
          ><span>Quản lý sản phẩm</span></RouterLink
        >
        <RouterLink to="/admin/quanlinguoidung"
          ><i class="bi bi-people-fill"></i
          ><span>Quản lý người dùng</span></RouterLink
        >
        <RouterLink to="/admin/quanlidonhang"
          ><i class="bi bi-receipt-cutoff"></i
          ><span>Quản lý đơn hàng</span></RouterLink
        >
        <RouterLink to="/admin/quanlibinhluan"
          ><i class="bi bi-chat-fill"></i
          ><span>Quản lý bình luận</span></RouterLink
        >
        <RouterLink to="/admin/quanlibaiviet"
          ><i class="bi bi-book-fill"></i
          ><span>Quản lý bài viết</span></RouterLink
        >
        <RouterLink to="/admin/quanlidanhgia"
          ><i class="bi bi-star-fill"></i
          ><span>Quản lý đánh giá</span></RouterLink
        >
        <div class="tienichadmin-left">
          <a href="#"><i class="bi bi-gear"></i><span>CÀI ĐẶT</span></a>
          <a href="#"
            ><i class="bi bi-question-circle"></i><span>TRỢ GIÚP</span></a
          >
          <a href="#" @click.prevent="logout"
            ><i class="bi bi-box-arrow-right"></i><span>THOÁT</span></a
          >
        </div>
      </div>

      <div class="admin-right">
        <div class="header-container">
          <div class="title-section">
            <h5>Danh mục</h5>
            <p class="text-muted">Xem và tìm kiếm danh mục sản phẩm</p>
          </div>
          <div class="button-wrapper">
            <button class="btn-xuat-admin" @click="openAddCategoryModal">
              <i class="bi bi-plus-lg"></i>
              <span>Thêm danh mục</span>
            </button>
          </div>
        </div>

        <div class="filter-toolbar">
          <select class="filter-select">
            <option>Tác vụ</option>
            <option>Xóa</option>
            <option>Cập nhật</option>
          </select>
          <button class="btn-apply">Áp dụng</button>
          <select class="filter-select">
            <option>Tất cả các ngày</option>
            <option>Tuần này</option>
            <option>Tháng này</option>
          </select>
          <select class="filter-select">
            <option>Chọn danh mục</option>
            <option>Quần áo</option>
            <option>Giày dép</option>
            <option>Phụ kiện</option>
            <option>Mũ</option>
            <option>Gậy golf</option>
            <option>Vợt</option>
          </select>
          <select class="filter-select">
            <option>Toàn bộ sản phẩm</option>
            <option>Đang hoạt động</option>
            <option>Ngưng hoạt động</option>
            <option>Đang cập nhật</option>
          </select>
          <div class="search-box">
            <input
              type="text"
              placeholder="Tìm kiếm sản phẩm..."
              class="search-input"
            />
            <button class="search-btn"><i class="bi bi-search"></i></button>
          </div>
        </div>

        <div class="table-container bg-white rounded-3 shadow-sm">
          <table class="category-table">
            <thead>
              <tr>
                <th width="40px">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" />
                  </div>
                </th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th width="120px">Hình ảnh</th>
                <th>Trạng thái</th>
                <th class="text-center" width="120px">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(category, index) in categories" :key="index">
                <td>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" />
                  </div>
                </td>
                <td class="category-name">{{ category.name }}</td>
                <td class="category-desc">{{ category.description }}</td>
                <td class="category-img">
                  <img
                    :src="
                      category.image || '../../../public/img/placeholder.png'
                    "
                    :alt="category.name"
                    class="category-thumbnail"
                  />
                </td>
                <td>
                  <div class="status-select-wrapper">
                    <div
                      :class="['status-select', category.status]"
                      @click="toggleStatus($event, category)"
                    >
                      <div class="status-border"></div>
                      <span class="status-text">{{
                        getStatusText(category.status)
                      }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="action-buttons">
                    <button
                      class="action-btn view-btn"
                      title="Xem"
                      @click="viewCategory(category)"
                    >
                      <i class="bi bi-eye-fill"></i>
                    </button>
                    <button
                      class="action-btn edit-btn"
                      title="Sửa"
                      @click="editCategory(category)"
                    >
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button
                      class="action-btn delete-btn"
                      title="Xóa"
                      @click="deleteCategory(category)"
                    >
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <div
            class="d-flex justify-content-between align-items-center pt-3 pb-2 px-3 border-top"
          >
            <div class="text-muted">
              Hiển thị 1-10 trong tổng số 50 danh mục
            </div>
            <nav>
              <ul class="pagination mb-0">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item active">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal thêm danh mục -->
    <div
      class="modal-backdrop"
      v-if="showAddCategoryModal"
      @click="closeAddCategoryModal"
    ></div>
    <div class="modal-container" v-if="showAddCategoryModal">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Thêm danh mục mới</h5>
          <button class="close-btn" @click="closeAddCategoryModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="addNewCategory" class="add-category-form">
            <div class="form-group">
              <label for="category-name" class="form-label">Tên danh mục</label>
              <input
                type="text"
                id="category-name"
                class="form-control"
                v-model="newCategory.name"
                required
                placeholder="Nhập tên danh mục"
              />
            </div>

            <div class="form-group">
              <label for="category-desc" class="form-label">Mô tả</label>
              <textarea
                id="category-desc"
                class="form-control description-field"
                v-model="newCategory.description"
                rows="4"
                placeholder="Nhập mô tả (không bắt buộc)"
              ></textarea>
            </div>

            <!-- Image upload section -->
            <div class="form-group">
              <label class="form-label">Hình ảnh</label>
              <div class="upload-container">
                <input
                  type="file"
                  id="category-image"
                  class="d-none"
                  accept="image/*"
                  @change="handleImageUpload"
                />

                <div v-if="!imagePreview" class="image-upload-area">
                  <label for="category-image" class="upload-btn">
                    <div class="upload-inner">
                      <i class="bi bi-cloud-arrow-up upload-icon"></i>
                      <span>Tải hình ảnh lên</span>
                    </div>
                  </label>
                </div>

                <div v-else class="image-preview-container">
                  <img
                    :src="imagePreview"
                    alt="Preview"
                    class="image-preview"
                  />
                  <button
                    type="button"
                    class="remove-image-btn"
                    @click="removeImage"
                  >
                    <i class="bi bi-x-circle-fill"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Status selection -->
            <div class="form-group">
              <label class="form-label">Trạng thái</label>
              <div class="status-radio-group">
                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="available"
                    v-model="newCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle available"></span>
                  <span class="status-radio-text">Đang hoạt động</span>
                </label>

                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="maintenance"
                    v-model="newCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle maintenance"></span>
                  <span class="status-radio-text">Đang bảo trì</span>
                </label>

                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="unavailable"
                    v-model="newCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle unavailable"></span>
                  <span class="status-radio-text">Ngưng hoạt động</span>
                </label>
              </div>
            </div>

            <div class="form-actions">
              <button
                type="button"
                class="btn btn-cancel"
                @click="closeAddCategoryModal"
              >
                Hủy
              </button>
              <button type="submit" class="btn btn-save">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal xem chi tiết danh mục -->
    <div
      class="modal-backdrop"
      v-if="showViewCategoryModal"
      @click="closeViewModal"
    ></div>
    <div class="modal-container" v-if="showViewCategoryModal">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Chi tiết danh mục</h5>
          <button class="close-btn" @click="closeViewModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="category-detail-view">
            <div class="category-image-container">
              <img
                :src="
                  selectedCategory?.image ||
                  '../../../public/img/placeholder.png'
                "
                :alt="selectedCategory?.name"
                class="category-detail-image"
              />
            </div>
            <div class="category-info">
              <h3 class="category-detail-name">{{ selectedCategory?.name }}</h3>
              <div class="status-badge" :class="selectedCategory?.status">
                {{ getStatusText(selectedCategory?.status) }}
              </div>
              <p class="category-detail-desc">
                {{ selectedCategory?.description || "Không có mô tả" }}
              </p>
            </div>
            <div class="action-row">
              <button
                type="button"
                class="btn btn-cancel"
                @click="closeViewModal"
              >
                Đóng
              </button>
              <button
                type="button"
                class="btn btn-save"
                @click="editCategory(selectedCategory)"
              >
                Chỉnh sửa
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal chỉnh sửa danh mục -->
    <div
      class="modal-backdrop"
      v-if="showEditCategoryModal"
      @click="closeEditModal"
    ></div>
    <div class="modal-container" v-if="showEditCategoryModal">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Chỉnh sửa danh mục</h5>
          <button class="close-btn" @click="closeEditModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveEditCategory" class="edit-category-form">
            <div class="form-row">
              <input
                type="text"
                id="category-name"
                class="form-control"
                v-model="selectedCategory.name"
                required
                placeholder="Nhập tên danh mục"
              />
            </div>

            <div class="form-row">
              <textarea
                id="category-desc"
                class="form-control description-field"
                v-model="selectedCategory.description"
                rows="4"
                placeholder="Nhập mô tả (không bắt buộc)"
              ></textarea>
            </div>

            <!-- Image upload section -->
            <div class="form-row upload-row">
              <input
                type="file"
                id="category-image"
                class="d-none"
                accept="image/*"
                @change="handleImageUpload"
              />

              <div class="upload-container">
                <div v-if="!imagePreview" class="image-upload-area">
                  <label for="category-image" class="upload-btn">
                    <div class="upload-inner">
                      <i class="bi bi-cloud-arrow-up upload-icon"></i>
                      <span>Tải hình ảnh lên</span>
                    </div>
                  </label>
                </div>

                <div v-else class="image-preview-container">
                  <img
                    :src="imagePreview"
                    alt="Preview"
                    class="image-preview"
                  />
                  <button
                    type="button"
                    class="remove-image-btn"
                    @click="removeImage"
                  >
                    <i class="bi bi-x-circle-fill"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Status selection -->
            <div class="form-row status-row">
              <div class="status-radio-group">
                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="available"
                    v-model="selectedCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle available"></span>
                </label>

                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="maintenance"
                    v-model="selectedCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle maintenance"></span>
                </label>

                <label class="status-radio">
                  <input
                    type="radio"
                    name="status"
                    value="unavailable"
                    v-model="selectedCategory.status"
                    class="status-radio-input"
                  />
                  <span class="status-radio-circle unavailable"></span>
                </label>
              </div>
            </div>

            <div class="form-row action-row">
              <button
                type="button"
                class="btn btn-cancel"
                @click="closeEditModal"
              >
                Hủy
              </button>
              <button type="submit" class="btn btn-save">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal xác nhận xóa danh mục -->
    <div
      class="modal-backdrop"
      v-if="showDeleteConfirmModal"
      @click="closeDeleteModal"
    ></div>
    <div class="modal-container" v-if="showDeleteConfirmModal">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Xác nhận xóa danh mục</h5>
          <button class="close-btn" @click="closeDeleteModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <p>
            Bạn có chắc chắn muốn xóa danh mục "{{ selectedCategory.name }}"?
          </p>
          <div class="action-row">
            <button
              type="button"
              class="btn btn-cancel"
              @click="closeDeleteModal"
            >
              Hủy
            </button>
            <button
              type="button"
              class="btn btn-save"
              @click="confirmDeleteCategory"
            >
              Xóa
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { onMounted, ref } from "vue";

// Thêm biến để kiểm soát hiển thị modal
const showAddCategoryModal = ref(false);

// Thêm biến cho form thêm danh mục
const newCategory = ref({
  name: "",
  description: "",
  status: "available",
  image: null,
});

// Biến lưu URL hình ảnh preview
const imagePreview = ref("");

// Thêm biến để lưu danh mục được chọn
const selectedCategory = ref(null);
const showViewCategoryModal = ref(false);
const showEditCategoryModal = ref(false);
const showDeleteConfirmModal = ref(false);

// Data for categories
const categories = ref([
  {
    id: 1,
    name: "Áo",
    description: "Các loại áo thể thao",
    image: "../../../public/img/placeholder.png",
    status: "unavailable",
  },
  {
    id: 2,
    name: "Quần",
    description: "Các loại quần thể thao",
    image: "../../../public/img/placeholder.png",
    status: "unavailable",
  },
  {
    id: 3,
    name: "Giày",
    description: "Các loại giày thể thao",
    image: "../../../public/img/placeholder.png",
    status: "unavailable",
  },
  {
    id: 4,
    name: "Vợt",
    description: "Vợt thể thao các loại",
    image: "../../../public/img/placeholder.png",
    status: "available",
  },
  {
    id: 5,
    name: "Gậy golf",
    description: "Gậy golf các loại",
    image: "../../../public/img/placeholder.png",
    status: "available",
  },
  {
    id: 6,
    name: "Mũ",
    description: "Mũ thể thao các loại",
    image: "../../../public/img/placeholder.png",
    status: "available",
  },
  {
    id: 7,
    name: "Phụ kiện tennis",
    description: "Các phụ kiện tennis cao cấp",
    image: "../../../public/img/placeholder.png",
    status: "maintenance",
  },
]);

// Hàm xử lý khi chọn hình ảnh
function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    newCategory.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
}

// Hàm xóa hình ảnh đã chọn
function removeImage() {
  newCategory.value.image = null;
  imagePreview.value = "";
  // Reset input file
  const fileInput = document.getElementById("category-image");
  if (fileInput) fileInput.value = "";
}

// Hàm mở modal form thêm danh mục
function openAddCategoryModal() {
  showAddCategoryModal.value = true;
}

// Hàm đóng modal form
function closeAddCategoryModal() {
  showAddCategoryModal.value = false;
  // Reset form khi đóng
  newCategory.value = {
    name: "",
    description: "",
    status: "available",
    image: null,
  };
  imagePreview.value = "";
}

// Hàm thêm danh mục mới
function addNewCategory() {
  // Xử lý thêm danh mục ở đây
  console.log("Thêm danh mục mới:", newCategory.value);

  // Đóng modal sau khi thêm
  closeAddCategoryModal();
}

// Hàm để lấy text hiển thị theo trạng thái
function getStatusText(status) {
  switch (status) {
    case "available":
      return "Đang hoạt động";
    case "unavailable":
      return "Ngưng hoạt động";
    case "updating":
      return "Đang cập nhật";
    case "maintenance":
      return "Đang bảo trì";
    default:
      return "Không xác định";
  }
}

// Cập nhật hàm toggleStatus để nhận category
function toggleStatus(event, category) {
  event.stopPropagation();

  if (category.status === "available") {
    category.status = "updating";
    console.log("Đã chuyển sang: Đang cập nhật");
  } else if (category.status === "updating") {
    category.status = "maintenance";
    console.log("Đã chuyển sang: Đang bảo trì");
  } else if (category.status === "maintenance") {
    category.status = "unavailable";
    console.log("Đã chuyển sang: Ngưng hoạt động");
  } else {
    category.status = "available";
    console.log("Đã chuyển sang: Đang hoạt động");
  }
}

// Hàm xem chi tiết danh mục
function viewCategory(category) {
  selectedCategory.value = category;
  showViewCategoryModal.value = true;
  console.log("Xem chi tiết danh mục:", category);
}

// Hàm mở modal chỉnh sửa danh mục
function editCategory(category) {
  selectedCategory.value = category;
  // Đặt giá trị ban đầu cho form chỉnh sửa
  newCategory.value = {
    name: category.name,
    description: category.description,
    status: category.status,
    image: category.image,
  };
  imagePreview.value = category.image; // Hiển thị ảnh hiện tại nếu có
  showEditCategoryModal.value = true;
  console.log("Mở form chỉnh sửa danh mục:", category);
}

// Hàm lưu chỉnh sửa danh mục
function saveEditCategory() {
  // Xử lý lưu chỉnh sửa ở đây
  console.log("Lưu chỉnh sửa danh mục:", newCategory.value);

  // Đóng modal sau khi lưu
  showEditCategoryModal.value = false;
}

// Hàm mở xác nhận xóa danh mục
function deleteCategory(category) {
  selectedCategory.value = category;
  showDeleteConfirmModal.value = true;
  console.log("Mở xác nhận xóa danh mục:", category);
}

// Hàm xóa danh mục
function confirmDeleteCategory() {
  // Xử lý xóa danh mục ở đây
  console.log("Xóa danh mục:", selectedCategory.value);

  // Đóng modal xác nhận sau khi xóa
  showDeleteConfirmModal.value = false;
}

// Hàm đóng các modal
function closeViewModal() {
  showViewCategoryModal.value = false;
}

function closeEditModal() {
  showEditCategoryModal.value = false;
}

function closeDeleteModal() {
  showDeleteConfirmModal.value = false;
}

// Vẫn giữ onMounted nhưng không cần gọi setupClickOutside
onMounted(() => {
  // Không cần làm gì khi component được mount
});

// Hàm đăng xuất
function logout() {
  // Xóa thông tin đăng nhập từ localStorage
  localStorage.removeItem("adminUser");
  localStorage.removeItem("adminToken");

  // Hiển thị thông báo đăng xuất thành công
  alert("Đăng xuất thành công!");

  // Chuyển hướng về trang đăng nhập
  window.location.href = "/admin/login";
}
</script>

<style scoped>
.admin {
  min-height: 100vh;
  background-color: #f8f9fa;
  display: flex;
  width: 100%;
}

.admin-left {
  background-color: #1d2a54;
  min-height: 100vh;
  height: 100%;
  color: white;
  position: fixed;
  width: 280px;
  max-width: 280px;
  overflow-y: auto;
  padding-bottom: 30px;
  z-index: 1000;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.admin-right {
  margin-left: 280px;
  width: calc(100% - 280px);
  padding: 25px 40px 25px 30px;
  background-color: #f8f9fa;
}

.admin-left img {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  margin: 25px auto;
  display: block;
  border: 3px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  object-fit: cover;
  transition: all 0.3s ease;
}

.admin-left img:hover {
  transform: scale(1.05);
  border-color: #ffd700;
}

.admin-left h5 {
  color: #ffffff;
  text-align: center;
  margin-bottom: 30px;
  font-weight: 600;
  letter-spacing: 1px;
  font-size: 24px;
}

.admin-left a {
  display: flex;
  align-items: center;
  padding: 14px 22px;
  text-decoration: none;
  color: #ffffff;
  transition: all 0.3s ease;
  gap: 12px;
  border-radius: 8px;
  margin: 0 12px 8px 12px;
  font-size: 18px;
  font-weight: 500;
  position: relative;
  overflow: hidden;
}

.admin-left a::before {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 2px;
  width: 0;
  background-color: #ffd700;
  transition: all 0.3s ease;
}

.admin-left a:hover::before {
  width: 100%;
}

.admin-left a.active {
  background-color: rgba(255, 255, 255, 0.15);
  color: #ffd700;
  font-weight: 600;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.admin-left a:hover {
  color: #ffd700;
  background-color: rgba(255, 255, 255, 0.1);
  transform: translateX(5px);
  letter-spacing: 0.3px;
}

.admin-left a i {
  color: #ffffff;
  font-size: 22px;
  transition: all 0.3s ease;
  width: 28px;
  text-align: center;
}

.admin-left a:hover i,
.admin-left a.active i {
  color: #ffd700;
}

.tienichadmin-left {
  margin-top: 35px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 25px;
}

.tienichadmin-left a {
  font-size: 17px;
  letter-spacing: 0.5px;
  font-weight: 600;
}

.tienichadmin-left a i {
  font-size: 20px;
}

.header-container {
  background: white;
  padding: 0;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 70px;
}

.title-section {
  padding-left: 20px;
}

.title-section h5 {
  font-size: 20px;
  font-weight: 600;
  color: #2c3e50;
  margin: 0;
}

.title-section p {
  font-size: 13px;
  color: #6c757d;
  margin: 4px 0 0 0;
}

.button-wrapper {
  display: flex;
  justify-content: flex-end;
  min-width: 170px;
  margin-right: 25px;
}

.btn-xuat-admin {
  background-color: #1d2a54;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-weight: 600;
  font-size: 20px;
  transition: all 0.3s ease;
  cursor: pointer;
  min-width: 180px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-xuat-admin i {
  font-size: 22px;
}

.btn-xuat-admin:hover {
  background-color: #2c3e50;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.table-container {
  padding: 0;
  overflow: hidden;
}

.category-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.category-table thead th {
  padding: 10px 15px;
  background-color: #1d2a54;
  color: white;
  font-weight: 600;
  font-size: 15px;
  border-bottom: none;
}

.category-table tbody tr {
  transition: all 0.2s ease;
}

.category-table tbody tr:hover {
  background-color: #f8f9fa;
}

.highlight-row {
  background-color: #f8f9fa;
}

.category-table tbody td {
  padding: 10px 15px;
  border-bottom: 1px solid #f0f0f0;
  vertical-align: middle;
}

/* Điều chỉnh style cho status-select */
.status-select {
  position: relative;
  z-index: 20;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 6px 12px;
  min-width: 130px;
  max-width: 160px;
  border-radius: 4px;
  background-color: #fff;
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  border: 1px solid #e9ecef;
  transition: all 0.2s ease;
}

.status-select:hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
  transform: translateY(-1px);
}

.status-select:active {
  transform: translateY(0);
  box-shadow: 0 0 0 3px rgba(130, 138, 145, 0.15);
}

.status-border {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  border-radius: 4px 0 0 4px;
  z-index: 5;
}

.status-select span {
  display: block;
  color: inherit;
  font-weight: 500;
  font-size: 14px;
  font-family: Arial, sans-serif;
  visibility: visible !important;
  opacity: 1 !important;
}

/* Add status badge effect */
.status-select.available .status-text::before {
  content: "";
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #20c997;
  margin-right: 6px;
  position: relative;
  top: -1px;
}

.status-select.unavailable .status-text::before {
  content: "";
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #dc3545;
  margin-right: 6px;
  position: relative;
  top: -1px;
}

.status-select.available {
  background-color: rgba(220, 252, 231, 0.6);
}

.status-select.available .status-border {
  background-color: #20c997;
}

.status-select.unavailable {
  background-color: rgba(254, 226, 226, 0.6);
}

.status-select.unavailable .status-border {
  background-color: #dc3545;
}

.status-text {
  margin-left: 4px;
  font-weight: 600;
}

/* Pagination styling */
.pagination {
  display: flex;
  gap: 4px;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 4px;
  color: #555;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
  font-size: 14px;
}

.page-item.active .page-link {
  background-color: #1d2a54;
  color: white;
  border-color: #1d2a54;
}

.page-link:hover {
  background-color: #e9ecef;
  color: #1d2a54;
}

.filter-toolbar {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 15px;
}

.filter-select {
  padding: 6px 10px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  background-color: white;
  min-width: 140px;
  font-size: 14px;
  color: #495057;
  height: 36px;
  outline: none;
}

.btn-apply {
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  height: 36px;
  white-space: nowrap;
}

.btn-apply:hover {
  background-color: #45a049;
}

.search-box {
  display: flex;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  overflow: hidden;
  height: 36px;
  margin-left: auto;
  max-width: 250px;
  flex-grow: 1;
}

.search-input {
  flex: 1;
  padding: 6px 10px;
  border: none;
  outline: none;
  font-size: 14px;
  color: #495057;
}

.search-btn {
  background-color: white;
  border: none;
  border-left: 1px solid #dee2e6;
  padding: 0 12px;
  cursor: pointer;
  color: #6c757d;
}

.search-btn:hover {
  background-color: #f8f9fa;
}

.search-btn i {
  font-size: 14px;
}

@media (max-width: 1200px) {
  .admin-left {
    width: 200px;
    max-width: 200px;
  }

  .admin-right {
    margin-left: 200px;
    width: calc(100% - 200px);
  }
}

@media (max-width: 992px) {
  .admin-left {
    width: 90px;
    max-width: 90px;
  }

  .admin-right {
    margin-left: 90px;
    width: calc(100% - 90px);
    padding: 20px 35px 20px 25px;
  }

  .admin-left a span {
    display: none;
  }

  .admin-left h5 {
    display: none;
  }

  .admin-left a {
    justify-content: center;
    padding: 16px 10px;
  }

  .admin-left a i {
    font-size: 26px;
    margin: 0;
  }

  .admin-left img {
    width: 60px;
    height: 60px;
    margin: 15px auto;
  }
}

@media (max-width: 768px) {
  .admin-left {
    position: fixed;
    bottom: 0;
    width: 100%;
    max-width: 100%;
    height: 75px;
    min-height: 75px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 0;
    z-index: 1001;
  }

  .admin-right {
    margin-left: 0;
    width: 100%;
    padding: 15px 20px;
    padding-bottom: 85px;
  }

  .admin-left img,
  .admin-left h5,
  .tienichadmin-left {
    display: none;
  }

  .admin-left a {
    padding: 12px;
    margin: 0;
  }

  .admin-left a i {
    font-size: 28px;
  }
}

/* Add styles for category name and description */
.category-name,
.category-desc {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

/* Focus style for active dropdown */
.status-select-wrapper:focus-within {
  z-index: 100;
}

/* Add style to dropdown span */
.dropdown-option span {
  display: block;
  padding-left: 5px;
}

.status-select i {
  cursor: pointer;
  font-size: 14px;
  transition: transform 0.2s;
  color: #495057;
  padding: 2px 4px;
  margin-left: 5px;
}

.status-select i:hover {
  transform: translateY(1px);
  color: #212529;
}

/* Action button styling */
.action-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.action-btn {
  width: 30px;
  height: 30px;
  border-radius: 4px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
}

.action-btn i {
  font-size: 14px;
}

.view-btn {
  color: #17a2b8;
}

.view-btn:hover {
  background-color: rgba(23, 162, 184, 0.1);
  color: #138496;
}

.edit-btn {
  color: #007bff;
}

.edit-btn:hover {
  background-color: rgba(0, 123, 255, 0.1);
  color: #0069d9;
}

.delete-btn {
  color: #dc3545;
}

.delete-btn:hover {
  background-color: rgba(220, 53, 69, 0.1);
  color: #c82333;
}

/* Thêm style cho status-select-wrapper */
.status-select-wrapper {
  position: relative;
  z-index: 20;
}

/* Thêm style cho modal */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}

.modal-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1051;
}

.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  width: 550px;
  max-width: 95%;
  max-height: 90vh;
  overflow-y: auto;
  animation: modal-appear 0.25s ease;
}

@keyframes modal-appear {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 20px;
  border-bottom: 1px solid #e9ecef;
}

.modal-header h5 {
  margin: 0;
  font-size: 22px;
  font-weight: 600;
  color: #1e293b;
}

.close-btn {
  background: none;
  border: none;
  font-size: 22px;
  cursor: pointer;
  color: #64748b;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s;
  margin-right: -8px;
}

.close-btn:hover {
  background-color: #f1f5f9;
  color: #0f172a;
}

.modal-body {
  padding: 24px 30px;
}

/* Updated Form Styling */
.form-group {
  margin-bottom: 20px;
  width: 100%;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #334155;
  font-size: 15px;
}

.status-radio-group {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.status-radio {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 5px 0;
}

.status-radio-circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: inline-block;
  cursor: pointer;
  border: 2px solid #e2e8f0;
  transition: all 0.2s;
  margin-right: 10px;
}

.status-radio-text {
  font-size: 14px;
  color: #334155;
}

.status-radio-input:checked + .status-radio-circle {
  transform: scale(1.2);
  box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
  padding-top: 20px;
}

.upload-container {
  width: 260px;
  height: 100px;
  margin: 0 auto;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.image-upload-area {
  border: 1px dashed #cbd5e1;
  border-radius: 8px;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  background-color: rgba(202, 138, 4, 0.8);
}

.upload-icon {
  font-size: 22px;
  color: #ffffff;
  margin-bottom: 6px;
}

.upload-btn span {
  font-size: 14px;
  font-weight: 500;
  color: #ffffff;
  margin-top: 2px;
}

.image-preview-container {
  width: 100%;
  height: 100%;
  position: relative;
  border-radius: 8px;
  overflow: hidden;
}

.image-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.remove-image-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255, 255, 255, 0.85);
  border: none;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  color: #ef4444;
  padding: 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.remove-image-btn:hover {
  background: #ffffff;
  transform: scale(1.1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Status radio buttons */
.status-row {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 20px 0;
}

.status-radio-group {
  display: flex;
  gap: 20px;
}

.status-radio {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.status-radio-input {
  display: none;
}

.status-radio-circle {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: inline-block;
  cursor: pointer;
  border: 2px solid #e2e8f0;
  transition: all 0.2s;
}

.status-radio-circle.available {
  background-color: #10b981;
}

.status-radio-circle.unavailable {
  background-color: #ef4444;
}

.status-radio-input:checked + .status-radio-circle {
  transform: scale(1.1);
  box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
}

/* Action buttons */
.action-row {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid #f1f5f9;
}

.btn {
  padding: 10px 22px;
  font-size: 18px;
  font-weight: 500;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s;
  min-width: 90px;
  text-align: center;
}

.btn-cancel {
  background-color: #f1f5f9;
  color: #334155;
  border: 1px solid #cbd5e1;
}

.btn-cancel:hover {
  background-color: #e2e8f0;
}

.btn-save {
  background-color: #1e3a8a;
  color: white;
}

.btn-save:hover {
  background-color: #1e40af;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Category thumbnail styling */
.category-img {
  text-align: center;
  padding: 5px !important;
  vertical-align: middle;
}

.category-thumbnail {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #e9ecef;
  background-color: #f8f9fa;
}

/* Add styles for the updating status */
.status-select.updating {
  background-color: rgba(255, 236, 153, 0.6);
}

.status-select.updating .status-border {
  background-color: #ffc107;
}

.status-select.updating .status-text::before {
  content: "";
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #ffc107;
  margin-right: 6px;
  position: relative;
  top: -1px;
}

/* Category detail view styling */
.category-detail-view {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 15px;
}

.category-image-container {
  width: 100%;
  max-width: 200px;
  height: 200px;
  overflow: hidden;
  border-radius: 8px;
  margin-bottom: 20px;
  border: 1px solid #e2e8f0;
}

.category-detail-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.category-info {
  text-align: center;
  width: 100%;
  margin-bottom: 20px;
}

.category-detail-name {
  font-size: 24px;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 10px;
}

.category-detail-desc {
  color: #64748b;
  font-size: 16px;
  line-height: 1.5;
  margin-top: 15px;
}

.status-badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 15px;
}

.status-badge.available {
  background-color: rgba(16, 185, 129, 0.1);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-badge.unavailable {
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-badge.updating {
  background-color: rgba(245, 158, 11, 0.1);
  color: #f59e0b;
  border: 1px solid rgba(245, 158, 11, 0.2);
}

/* Add styles for the maintenance status */
.status-select.maintenance {
  background-color: rgba(253, 224, 71, 0.6);
}

.status-select.maintenance .status-border {
  background-color: #fbbf24;
}

.status-select.maintenance .status-text::before {
  content: "";
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #fbbf24;
  margin-right: 6px;
  position: relative;
  top: -1px;
}

/* Add style for maintenance badge */
.status-badge.maintenance {
  background-color: rgba(253, 224, 71, 0.1);
  color: #fbbf24;
  border: 1px solid rgba(253, 224, 71, 0.3);
}

/* Status radio styles for maintenance */
.status-radio-circle.maintenance {
  background-color: #fbbf24;
}

/* Form control styling */
.form-control {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.2s;
  background-color: #fff;
  color: #1e293b;
}

.form-control:focus {
  border-color: #3b82f6;
  outline: 0;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control::placeholder {
  color: #94a3b8;
}

/* Description field to match category name field width */
.description-field {
  min-height: 100px;
  resize: vertical;
}

/* Add category form specific styling */
.add-category-form {
  display: flex;
  flex-direction: column;
  width: 100%;
}

/* Modal styling improvements */
.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  width: 550px;
  max-width: 95%;
  max-height: 90vh;
  overflow-y: auto;
  animation: modal-appear 0.25s ease;
}

.image-upload-area {
  background-color: rgba(202, 138, 4, 0.8);
}

.image-upload-area:hover {
  border-color: #3b82f6;
  opacity: 0.95;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.upload-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 100%;
  height: 100%;
  padding: 10px;
}

.upload-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
