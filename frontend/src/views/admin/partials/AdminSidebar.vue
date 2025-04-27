<template>
  <div class="admin-left">
    <img src="/public/img/user.webp" alt="" class="admin-avatar">
    <h5>Admin</h5>
    <RouterLink to="/admin" class="admin-menu-item active">
      <i class="bi bi-palette2"></i>
      <span>Bảng điều khiển</span>
    </RouterLink>
    <RouterLink to="/admin/quanlidanhmuc" class="admin-menu-item">
      <i class="bi bi-inboxes-fill"></i>
      <span>Quản lý danh mục</span>
    </RouterLink>
    <RouterLink to="/admin/quanlisanpham" class="admin-menu-item">
      <i class="bi bi-box2-fill"></i>
      <span>Quản lý sản phẩm</span>
    </RouterLink>
    <RouterLink to="/admin/quanlinguoidung" class="admin-menu-item">
      <i class="bi bi-people-fill"></i>
      <span>Quản lý người dùng</span>
    </RouterLink>
    <RouterLink to="/admin/quanlidonhang" class="admin-menu-item">
      <i class="bi bi-receipt-cutoff"></i>
      <span>Quản lý đơn hàng</span>
    </RouterLink>
    <RouterLink to="/admin/quanlibinhluan" class="admin-menu-item">
      <i class="bi bi-chat-fill"></i>
      <span>Quản lý bình luận</span>
    </RouterLink>
    <RouterLink to="/admin/quanlibaiviet" class="admin-menu-item">
      <i class="bi bi-book-fill"></i>
      <span>Quản lý bài viết</span>
    </RouterLink>
    <RouterLink to="/admin/quanlidanhgia" class="admin-menu-item">
      <i class="bi bi-star-fill"></i>
      <span>Quản lý đánh giá</span>
    </RouterLink>
    <div class="admin-menu-separator"></div>
    <a class="admin-menu-item" @click.prevent="exportData">
      <i class="bi bi-download"></i>
      <span>Xuất dữ liệu</span>
    </a>
    <a class="admin-menu-item" @click.prevent="showHelp">
      <i class="bi bi-question-circle"></i>
      <span>Trợ giúp</span>
    </a>
    <a class="admin-menu-item" @click.prevent="logout">
      <i class="bi bi-box-arrow-right"></i>
      <span>Đăng xuất</span>
    </a>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";
const logout = () => {
  authStore.logout();
  toast.success("Đăng xuất thành công!");
  router.push("/login");
};

// Hiển thị trợ giúp
const showHelp = () => {
  toast.info("Chức năng trợ giúp đang được phát triển.");
};

// Xuất dữ liệu báo cáo
const exportData = () => {
  toast.info("Chức năng xuất báo cáo đang được phát triển.");
};
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

.admin-menu-separator {
  height: 1px;
  background-color: rgba(255, 255, 255, 0.1);
  margin: 15px 20px;
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
  padding: 20px 25px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: auto;
}

.title-section {
  padding-left: 0;
}

.content-section {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  margin-bottom: 20px;
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

.nav-tabs {
  padding: 0;
  margin-bottom: 0;
  border-bottom: 1px solid #dee2e6;
  background-color: white;
  display: flex;
}

.nav-item {
  margin-bottom: -1px;
}

.nav-link {
  font-size: 14px;
  font-weight: 500;
  padding: 16px 25px;
  border: none;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
  color: #6c757d;
  background-color: transparent;
  white-space: nowrap;
  position: relative;
}

.nav-link:hover {
  color: #1d2a54;
}

.nav-link.active {
  color: #1d2a54;
  font-weight: 600;
  border-bottom: none;
  background-color: transparent;
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  height: 2px;
  background-color: #1d2a54;
}

.filter-search-container {
  background-color: white;
  padding: 15px 20px;
  border-bottom: 1px solid #f0f0f0;
  margin-top: 0 !important;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.filter-options {
  display: flex;
  align-items: center;
  gap: 15px;
}

.filter-pills {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.filter-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 12px;
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 20px;
  font-size: 13px;
  color: #495057;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-pill.active {
  background-color: rgba(29, 42, 84, 0.08);
  border-color: rgba(29, 42, 84, 0.2);
  color: #1d2a54;
}

.filter-pill:hover {
  background-color: #e9ecef;
}

.filter-pill i {
  font-size: 11px;
}

.remove-filter {
  margin-left: 2px;
  opacity: 0.6;
  transition: all 0.2s;
}

.remove-filter:hover {
  opacity: 1;
  color: #dc3545;
}

.btn-add-filter {
  background-color: transparent;
  border: 1px dashed #ced4da;
  color: #6c757d;
  padding: 5px 12px;
  font-size: 13px;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: all 0.2s;
}

.btn-add-filter:hover {
  background-color: #f8f9fa;
  color: #495057;
  border-color: #adb5bd;
}

.btn-filter {
  background-color: #1d2a54;
  color: white;
  font-size: 14px;
  padding: 8px 16px;
  border: none;
  display: flex;
  align-items: center;
  gap: 8px;
  border-radius: 6px;
  transition: all 0.2s;
}

.btn-filter:hover {
  background-color: #283773;
}

.btn-filter i {
  color: white;
}

.search-box {
  width: 280px;
  display: flex;
  border: 1px solid #dee2e6;
  border-radius: 20px;
  overflow: hidden;
  height: 38px;
  background-color: #f8f9fa;
  transition: all 0.2s;
}

.search-box:hover, .search-box:focus-within {
  border-color: #adb5bd;
  background-color: #ffffff;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.search-box .form-control {
  border: none;
  padding: 8px 15px;
  font-size: 14px;
  outline: none;
  background-color: transparent;
  width: 100%;
}

.search-box .form-control::placeholder {
  color: #adb5bd;
  font-size: 13px;
}

.search-btn {
  background-color: transparent;
  border: none;
  padding: 0 15px;
  color: #6c757d;
  cursor: pointer;
  transition: all 0.2s;
}

.search-btn:hover {
  color: #1d2a54;
}

.table {
  margin-bottom: 0;
  box-shadow: none;
  border-radius: 0;
  border-collapse: collapse;
}

.table-dark {
  background-color: #1d2a54;
  color: white;
}

.table thead th {
  padding: 12px 14px;
  font-size: 14px;
  font-weight: 600;
  vertical-align: middle;
  border-bottom: none;
}

.table tbody td {
  padding: 12px 14px;
  vertical-align: middle;
  font-size: 14px;
  color: #495057;
  border-top: 1px solid #f0f0f0;
  border-bottom: 1px solid #f0f0f0;
}

.order-row {
  background-color: white;
  transition: all 0.2s;
}

.order-row:hover {
  background-color: rgba(248, 249, 250, 0.7);
}

.order-id {
  font-weight: 600;
  color: #1d2a54;
  font-size: 14px;
}

.price {
  font-weight: 500;
  color: #212529;
}

.input-head {
  width: 40px;
  text-align: center;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  white-space: nowrap;
  gap: 6px;
  cursor: pointer;
  transition: all 0.2s;
  user-select: none;
  position: relative;
}

.status-badge:hover {
  transform: translateY(-2px);
  box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.status-badge:active {
  transform: translateY(0);
}

.status-badge::after {
  content: '';
  position: absolute;
  bottom: 0;
  right: 4px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: rgba(0,0,0,0.2);
  opacity: 0;
  transition: opacity 0.2s;
}

.status-badge:hover::after {
  opacity: 1;
}

.status-badge i {
  font-size: 12px;
}

.status-badge.shipping {
  background-color: rgba(25, 118, 210, 0.1);
  color: #1976D2;
  border: 1px solid rgba(25, 118, 210, 0.3);
}

.status-badge.delivered {
  background-color: rgba(56, 142, 60, 0.1);
  color: #388E3C;
  border: 1px solid rgba(56, 142, 60, 0.3);
}

.status-badge.pending {
  background-color: rgba(255, 152, 0, 0.1);
  color: #FF9800;
  border: 1px solid rgba(255, 152, 0, 0.3);
}

.date-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.date-main {
  font-weight: 500;
  font-size: 14px;
}

.date-time {
  font-size: 12px;
}

.customer-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.customer-name {
  font-weight: 500;
  color: #333;
  font-size: 14px;
}

.customer-detail {
  font-size: 12px;
  color: #6c757d;
}

.price-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.price-value {
  font-weight: 600;
  color: #212529;
  font-size: 14px;
}

.price-items {
  font-size: 12px;
}

.payment-method {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 10px;
  background-color: #f8f9fa;
  border-radius: 4px;
  font-size: 13px;
  color: #495057;
}

.payment-method i {
  color: #6c757d;
  font-size: 14px;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  padding: 12px 16px;
  background-color: white;
}

.stat-card {
  background-color: white;
  border-radius: 10px;
  padding: 12px 15px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  border: 1px solid #f0f0f0;
  position: relative;
  transition: all 0.3s ease;
  overflow: hidden;
  height: 75px;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background-color: #1d2a54;
  border-radius: 4px 0 0 4px;
}

.stat-card:nth-child(2)::before {
  background-color: #FF9800;
}

.stat-card:nth-child(3)::before {
  background-color: #388E3C;
}

.stat-card:nth-child(4)::before {
  background-color: #1976D2;
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  background-color: rgba(29, 42, 84, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
}

.stat-icon::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 15px;
  background: linear-gradient(135deg, rgba(29, 42, 84, 0.15) 0%, rgba(29, 42, 84, 0.05) 100%);
  z-index: -1;
}

.stat-icon.orders::after {
  background: linear-gradient(135deg, rgba(29, 42, 84, 0.15) 0%, rgba(29, 42, 84, 0.05) 100%);
}

.stat-icon.pending::after {
  background: linear-gradient(135deg, rgba(255, 152, 0, 0.15) 0%, rgba(255, 152, 0, 0.05) 100%);
}

.stat-icon.completed::after {
  background: linear-gradient(135deg, rgba(56, 142, 60, 0.15) 0%, rgba(56, 142, 60, 0.05) 100%);
}

.stat-icon.revenue::after {
  background: linear-gradient(135deg, rgba(25, 118, 210, 0.15) 0%, rgba(25, 118, 210, 0.05) 100%);
}

.stat-icon i {
  font-size: 20px;
  color: #1d2a54;
  text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.stat-icon.pending i {
  color: #FF9800;
}

.stat-icon.completed i {
  color: #388E3C;
}

.stat-icon.revenue i {
  color: #1976D2;
}

.stat-icon.orders i {
  color: #1d2a54;
}

.stat-card:hover .stat-icon {
  transform: scale(1.1);
}

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 22px;
  font-weight: 700;
  color: #212529;
  line-height: 1.2;
}

.stat-label {
  font-size: 13px;
  color: #6c757d;
  margin-top: 2px;
}

.stat-trend {
  position: absolute;
  top: 10px;
  right: 12px;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 3px;
  background-color: rgba(76, 175, 80, 0.15);
  padding: 3px 8px;
  border-radius: 15px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0,0,0,0.06);
  letter-spacing: 0.3px;
}

.stat-trend:hover {
  transform: scale(1.08);
  box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

.stat-trend i {
  font-size: 12px;
  margin-right: 1px;
}

.stat-trend.up {
  color: #2E7D32;
  background-color: rgba(76, 175, 80, 0.2);
}

.stat-trend.down {
  color: #C62828;
  background-color: rgba(244, 67, 54, 0.2);
}

.stat-trend span {
  font-weight: 700;
}

@media (max-width: 1200px) {
  .stats-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
}

.pagination-wrapper {
  padding: 15px 0;
  background-color: white;
  border-top: 1px solid #f0f0f0;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
}

.pagination-container {
  padding-right: 20px;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 4px;
  margin-bottom: 0;
}

.page-item {
  list-style: none;
}

.page-link {
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
  font-size: 14px;
  transition: all 0.2s;
}

.page-link:hover {
  background-color: #e9ecef;
  text-decoration: none;
}

.page-item.active .page-link {
  background-color: #1d2a54;
  color: white;
  border-color: #1d2a54;
}
</style>