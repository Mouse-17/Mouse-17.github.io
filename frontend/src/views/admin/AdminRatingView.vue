<template>
  <main>
    <section class="admin">
      <AdminSidebar />

      <div class="admin-right">
        <div class="header-container">
          <div class="title-section">
            <h5>Danh sách đánh giá</h5>
            <p class="text-muted">Xem đánh giá</p>
          </div>
        </div>

        <div class="content-wrapper bg-white rounded-3 shadow-sm p-4">
          <div v-if="loading" class="text-center py-5">
            <span>Đang tải dữ liệu...</span>
          </div>
          <div v-else-if="error" class="text-center py-5 text-danger">
            <span>{{ error }}</span>
          </div>
          <div v-else>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                <tr>
                  <th width="40px">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" />
                    </div>
                  </th>
                  <th>ID</th>
                  <th>Người đánh giá</th>
                  <th>Sản phẩm</th>
                  <th>Ngày đánh giá</th>
                  <th>Thời gian</th>
                  <th>Số sao</th>
                  <th width="120px">Hành động</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="rating in ratings" :key="rating.id">
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" />
                    </div>
                  </td>
                  <td>{{ rating.id }}</td>
                  <td>{{ rating.user }}</td>
                  <td>{{ rating.product }}</td>
                  <td>{{ rating.date }}</td>
                  <td>{{ rating.time }}</td>
                  <td class="rating">
                      <span class="star-rating">
                        <i v-for="star in renderStars(rating.stars)" :key="star" :class="star"></i>
                      </span>
                    <span class="rating-text">{{ rating.stars }} sao</span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn btn-sm view-btn" title="Xem">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button class="btn btn-sm edit-btn" title="Sửa">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button
                          class="btn btn-sm delete-btn"
                          title="Xóa"
                          @click="deleteRating(rating.id)"
                      >
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
              <div class="text-muted">Hiển thị {{ ratings.length }} đánh giá</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminSidebar from "@/views/admin/partials/AdminSidebar.vue";

// Dữ liệu đánh giá
const ratings = ref([]);
const loading = ref(false);
const error = ref(null);

// Hàm tải danh sách đánh giá
async function fetchRatings() {
  loading.value = true;

  try {
    const response = await axios.get('/api/ratings', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    });

    if (response.data.status === 'success') {
      ratings.value = response.data.data.map(rating => ({
        id: rating.id,
        user: rating.user.name,
        product: rating.product?.name || 'Không xác định',
        date: new Date(rating.created_at).toLocaleDateString(),
        time: new Date(rating.created_at).toLocaleTimeString(),
        stars: rating.So_sao,
        content: rating.Noi_dung || '',
      }));
    }
  } catch (err) {
    console.error("Lỗi khi tải danh sách đánh giá:", err);
    error.value = "Không thể tải danh sách đánh giá. Vui lòng thử lại sau.";
  } finally {
    loading.value = false;
  }
}

// Hàm xóa đánh giá
async function deleteRating(id) {
  try {
    const response = await axios.delete(`/api/ratings/${id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    });

    if (response.data.status === 'success') {
      ratings.value = ratings.value.filter(rating => rating.id !== id);
      alert('Đã xóa đánh giá thành công.');
    }
  } catch (err) {
    console.error("Lỗi khi xóa đánh giá:", err);
    alert('Không thể xóa đánh giá. Vui lòng thử lại sau.');
  }
}

// Hàm hiển thị sao
function renderStars(stars) {
  const fullStars = Math.floor(stars);
  const halfStar = stars % 1 >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;

  return [
    ...Array(fullStars).fill('bi bi-star-fill'),
    ...Array(halfStar).fill('bi bi-star-half'),
    ...Array(emptyStars).fill('bi bi-star'),
  ];
}

// Tải dữ liệu khi component được mount
onMounted(fetchRatings);
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
  padding: 16px 35px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 70px;
}

.title-section {
  padding-left: 10px;
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

.content-wrapper {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  margin-bottom: 20px;
}

.nav-tabs {
  border-bottom: 1px solid #dee2e6;
}

.nav-link {
  color: #6c757d;
  font-weight: 500;
  padding: 10px 15px;
  border: none;
}

.nav-link.active {
  color: #1d2a54;
  font-weight: 600;
  border-bottom: 3px solid #1d2a54;
}

.search-box {
  width: 300px;
}

.search-box .form-control {
  border-right: none;
}

.search-box .input-group-text {
  background-color: white;
  border-left: none;
}

.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: 15px;
}

.table th {
  background-color: #1d2a54;
  font-weight: 600;
  color: #ffffff;
  border-bottom: none;
  padding: 14px 16px;
  font-size: 16px;
}

.table td {
  padding: 14px 16px;
  border-bottom: 1px solid #dde1e6;
  vertical-align: middle;
  font-size: 15px;
  color: #333;
}

.table tr:hover {
  background-color: rgba(0, 0, 0, 0.02);
}

.table tbody tr {
  height: 65px;
  border-bottom: 1px solid #dde1e6;
}

.table tbody tr:not(:last-child) {
  border-bottom: 1px solid #dde1e6;
}

.table thead tr {
  height: 55px;
  background-color: #1d2a54;
}

/* Increase visibility of horizontal separators */
.table-responsive {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 0 0 1px #edf2f7;
}

.table-hover tbody tr {
  transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
  background-color: #f8fafc;
}

/* Ensure the header is more pronounced */
.table thead {
  border-bottom: none;
  background-color: #1d2a54;
}

/* Make sure checkboxes are properly aligned */
.form-check {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
}

/* Action buttons styling */
.action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.action-buttons button {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: all 0.2s;
  border: 1px solid transparent;
}

.action-buttons button:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.action-buttons i {
  font-size: 16px;
}

/* View button - blue */
.view-btn {
  background-color: rgba(33, 150, 243, 0.1) !important;
  color: #1976d2 !important;
  border: 1px solid rgba(33, 150, 243, 0.2) !important;
}

.view-btn:hover {
  background-color: rgba(33, 150, 243, 0.2) !important;
}

/* Edit button - amber/orange */
.edit-btn {
  background-color: rgba(255, 152, 0, 0.1) !important;
  color: #f57c00 !important;
  border: 1px solid rgba(255, 152, 0, 0.2) !important;
}

.edit-btn:hover {
  background-color: rgba(255, 152, 0, 0.2) !important;
}

/* Delete button - red */
.delete-btn {
  background-color: rgba(244, 67, 54, 0.1) !important;
  color: #e53935 !important;
  border: 1px solid rgba(244, 67, 54, 0.2) !important;
}

.delete-btn:hover {
  background-color: rgba(244, 67, 54, 0.2) !important;
}

/* Star rating styling */
.rating {
  display: flex;
  align-items: center;
  gap: 10px;
}

.star-rating {
  color: #FFB400;
  font-size: 16px;
  letter-spacing: 2px;
}

.rating-text {
  color: #555;
  font-size: 14px;
}

/* Larger pagination */
.pagination .page-link {
  font-size: 15px;
  padding: 8px 14px;
}

.pagination .page-item.active .page-link {
  background-color: #1d2a54;
  border-color: #1d2a54;
}

/* Make the table header corners round */
.table-responsive .table thead tr th:first-child {
  border-top-left-radius: 8px;
}

.table-responsive .table thead tr th:last-child {
  border-top-right-radius: 8px;
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

  .search-box {
    width: 200px;
  }
}
</style>
