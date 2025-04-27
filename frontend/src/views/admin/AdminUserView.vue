<template>
  <main>
    <section class="admin">
      <AdminSidebar />
      <div class="admin-right">
        <div class="header-container">
          <div class="title-section">
            <h5>Danh sách đăng nhập</h5>
            <p class="text-muted">Xem và tìm kiếm người dùng</p>
          </div>
          <div class="button-wrapper">
            <!-- Điều hướng đến view Thêm mới -->
            <button class="btn-add-user" @click="addUser">
              <i class="bi bi-plus-lg"></i>
              <span>Thêm người dùng</span>
            </button>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <span>Đang tải dữ liệu...</span>
        </div>

        <div v-else-if="error" class="text-center py-5 text-danger">
          <span>{{ error }}</span>
        </div>

        <div v-else class="user-table-container">
          <table class="user-table">
            <thead>
            <tr>
              <th>Họ tên</th>
              <th>Email</th>
              <th>sđt</th>
              <th>Địa chỉ</th>
              <th>Vai trò</th>
              <th class="action-col">Hành động</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.address }}</td>
              <td>{{ translateRole(user.role) }}</td>
              <td class="action-col">
                <div class="action-buttons">
                  <!-- Điều hướng đến view sửa -->
                  <a class="action-icon edit-icon" href="#" @click.prevent="editUser(user.id)" title="Sửa">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a class="action-icon delete-icon" href="#" @click.prevent="deleteUser(user.id)" title="Xóa">
                    <i class="bi bi-trash3-fill"></i>
                  </a>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router"; // Sử dụng router để điều hướng
import axios from "axios";
import AdminSidebar from "@/views/admin/partials/AdminSidebar.vue";

// Router
const router = useRouter();

// Danh sách người dùng
const users = ref([]);
const loading = ref(false);
const error = ref<string | null>(null);

// Tải dữ liệu người dùng từ API
const fetchUsers = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.get("/api/admin/users", {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
      },
    });
    users.value = response.data;
  } catch (err) {
    console.error("Lỗi khi tải danh sách người dùng:", err);
    error.value = "Không thể tải danh sách người dùng. Vui lòng thử lại sau.";
  } finally {
    loading.value = false;
  }
};

// Hàm dịch vai trò sang tiếng Việt
const translateRole = (role: string): string => {
  switch (role) {
    case "field_owner":
      return "Chủ sân";
    case "user":
      return "Người dùng";
    case "admin":
      return "Quản trị viên";
    default:
      return "Không xác định";
  }
};

// Hàm điều hướng đến view Thêm mới
const addUser = () => {
  router.push("/admin/quanlinguoidung/them");
};

// Hàm điều hướng đến view Sửa
const editUser = (userId: number) => {
  router.push(`/admin/quanlinguoidung/sua/${userId}`);
};

// Hàm xóa người dùng
const deleteUser = async (userId: number) => {
  if (confirm("Bạn có chắc chắn muốn xóa người dùng này?")) {
    try {
      await axios.delete(`/api/admin/users/${userId}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
        },
      });
      users.value = users.value.filter((user: any) => user.id !== userId);
      alert("Xóa người dùng thành công!");
    } catch (err) {
      console.error("Lỗi khi xóa người dùng:", err);
      alert("Không thể xóa người dùng. Vui lòng thử lại sau.");
    }
  }
};

// Tải danh sách người dùng khi component được mount
onMounted(fetchUsers);
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
  content: '';
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
  border-radius: 8px;
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
  font-size: 22px;
  font-weight: 600;
  color: #2c3e50;
  margin: 0;
}

.title-section p {
  font-size: 14px;
  color: #6c757d;
  margin: 4px 0 0 0;
}

.button-wrapper {
  display: flex;
  justify-content: flex-end;
  min-width: 170px;
  margin-right: 25px;
}

.btn-add-user {
  background-color: #1d2a54;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  font-size: 16px;
  cursor: pointer;
  margin-right: 20px;
  transition: all 0.2s;
}

.btn-add-user:hover {
  background-color: #2c3e50;
  transform: translateY(-2px);
}

.filter-toolbar {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 15px;
  background: white;
  padding: 12px;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.filter-select {
  padding: 6px 10px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 15px;
  color: #495057;
  height: 38px;
  width: auto;
  min-width: 100px;
  max-width: 160px;
}

.btn-delete {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-delete:hover {
  background-color: #c82333;
}

.search-box {
  display: flex;
  margin-left: auto;
  max-width: 250px;
  flex-grow: 1;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  overflow: hidden;
}

.search-input {
  flex: 1;
  padding: 8px 12px;
  border: none;
  outline: none;
  font-size: 15px;
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

.user-table-container {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 16px;
}

.user-table th {
  background-color: #1d2a54;
  color: white;
  font-weight: 600;
  text-align: left;
  padding: 14px 15px;
  font-size: 16px;
  white-space: nowrap;
}

.user-table td {
  padding: 12px 15px;
  border-top: 1px solid #f0f0f0;
  vertical-align: middle;
  font-size: 16px;
}

.user-table tr:hover {
  background-color: #f8f9fa;
}

.checkbox-col {
  width: 40px;
  text-align: center;
}

.table-checkbox {
  width: 16px;
  height: 16px;
  cursor: pointer;
}

.action-col {
  width: 120px;
  text-align: center;
}

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 14px;
}

.action-icon {
  color: inherit;
  text-decoration: none;
  font-size: 20px;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.view-icon {
  color: #17a2b8;
}

.view-icon:hover {
  color: #138496;
}

.edit-icon {
  color: #2196F3;
}

.edit-icon:hover {
  color: #0d87e9;
}

.delete-icon {
  color: #f44336;
}

.delete-icon:hover {
  color: #d32f2f;
}

.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
  padding: 10px 0;
}

.page-info {
  color: #6c757d;
  font-size: 15px;
}

.pagination {
  display: flex;
  gap: 4px;
}

.page-nav, .page-num {
  min-width: 34px;
  height: 34px;
  font-size: 15px;
}

.page-nav, .page-num {
  min-width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #495057;
  background-color: #ffffff;
  border: 1px solid #dee2e6;
  border-radius: 4px;
}

.page-num {
  padding: 0 12px;
}

.page-nav {
  font-weight: bold;
}

.page-num:hover, .page-nav:hover {
  background-color: #e9ecef;
  text-decoration: none;
}

.page-num.active {
  background-color: #1d2a54;
  color: white;
  border-color: #1d2a54;
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

  .filter-toolbar {
    flex-wrap: wrap;
  }

  .search-box {
    margin-top: 8px;
    margin-left: 0;
    width: 100%;
    max-width: 100%;
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

  .admin-left img, .admin-left h5, .tienichadmin-left {
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
</style>