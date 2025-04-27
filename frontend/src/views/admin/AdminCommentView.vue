<template>
    <main>
        <section class="admin">
            <div class="admin-left">
                <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                <h5>Admin</h5>
                <RouterLink to="/admin"><i class="bi bi-palette2"></i><span>Bảng điều khiển</span></RouterLink>
                <RouterLink to="/admin/quanlidanhmuc"><i class="bi bi-inboxes-fill"></i><span>Quản lý danh mục</span></RouterLink>
                <RouterLink to="/admin/quanlisanpham"><i class="bi bi-box2-fill"></i><span>Quản lý sản phẩm</span></RouterLink>
                <RouterLink to="/admin/quanlinguoidung"><i class="bi bi-people-fill"></i><span>Quản lý người dùng</span></RouterLink>
                <RouterLink to="/admin/quanlidonhang"><i class="bi bi-receipt-cutoff"></i><span>Quản lý đơn hàng</span></RouterLink>
                <RouterLink to="/admin/quanlibinhluan" class="active"><i class="bi bi-chat-fill"></i><span>Quản lý bình luận</span></RouterLink>
                <RouterLink to="/admin/quanlibaiviet"><i class="bi bi-book-fill"></i><span>Quản lý bài viết</span></RouterLink>
                <RouterLink to="/admin/quanlidanhgia"><i class="bi bi-star-fill"></i><span>Quản lý đánh giá</span></RouterLink>
                <div class="tienichadmin-left">
                    <a href="#"><i class="bi bi-gear"></i><span>CÀI ĐẶT</span></a>
                    <a href="#"><i class="bi bi-question-circle"></i><span>TRỢ GIÚP</span></a>
                    <a href="#"><i class="bi bi-info-circle"></i><span>THOÁT</span></a>
                </div>
            </div>

            <div class="admin-right">
                <div class="header-container">
                    <div class="title-section">
                        <h5>Danh sách bình luận</h5>
                        <p class="text-muted">Xem bình luận</p>
                    </div>
                    <div class="button-wrapper">
                        <button class="btn-xuat-admin">
                            <i class="bi bi-plus-lg"></i>
                            <span>Thêm điều kiện lọc</span>
                        </button>
                    </div>
                </div>

                <div class="content-wrapper">
                    <div class="tabs-container">
                        <ul class="comment-tabs">
                            <li class="tab-item active"><a href="#">Tất cả bình luận</a></li>
                            <li class="tab-item"><a href="#">Chưa đăng</a></li>
                            <li class="tab-item"><a href="#">Xử lý bình luận</a></li>
                        </ul>
                    </div>

                    <div class="filter-toolbar">
                        <div class="filter-group">
                            <select class="filter-select">
                                <option>Tác vụ</option>
                                <option>Duyệt bình luận</option>
                                <option>Xóa bình luận</option>
                            </select>
                            <button class="btn-apply">Áp dụng</button>

                            <select class="filter-select">
                                <option>Sắp xếp theo</option>
                                <option>Mới nhất</option>
                                <option>Cũ nhất</option>
                            </select>
                        </div>
                        
                        <div class="search-group">
                            <div class="search-box">
                                <input type="text" placeholder="Tìm kiếm bình luận..." class="search-input">
                                <button class="search-btn"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="comment-table-container">
                    <table class="comment-table">
                        <thead>
                            <tr>
                                <th class="checkbox-col"><input type="checkbox" class="master-checkbox"></th>
                                <th class="id-col">ID</th>
                                <th class="date-col">Ngày bình luận</th>
                                <th class="time-col">Thời gian</th>
                                <th class="content-col">Nội dung</th>
                                <th class="status-col">Trạng thái</th>
                                <th class="actions-col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(comment, index) in comments" :key="index">
                                <td class="checkbox-cell"><input type="checkbox"></td>
                                <td class="id-cell">{{ comment.id }}</td>
                                <td class="date-cell">{{ comment.date }}</td>
                                <td class="time-cell">{{ comment.time }}</td>
                                <td class="content-cell">{{ comment.content }}</td>
                                <td class="status-cell">
                                    <span 
                                        class="status-badge" 
                                        :class="getStatusClass(comment.status)" 
                                        @click="changeStatus(comment)"
                                        title="Nhấn để thay đổi trạng thái">
                                        {{ getStatusText(comment.status) }}
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <div class="actions">
                                        <button class="action-btn view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                        <button class="action-btn edit-btn" title="Sửa"><i class="bi bi-pencil"></i></button>
                                        <button class="action-btn delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 px-3 border-top">
                    <div class="text-muted">Hiển thị 1-10 trong tổng số 50 bình luận</div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item"><a class="page-link" href="#">«</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">»</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
import { ref } from 'vue';

// Define constants for status types
const STATUS_TYPES = {
    PENDING: 'pending',    // Chưa đăng
    ACTIVE: 'active',      // Đang đăng
    APPROVED: 'approved',  // Đã đăng
    SPAM: 'spam'           // Spam
};

// Sample comment data
const comments = ref([
    {
        id: 1,
        date: '19/2/2025',
        time: '8h45 AM',
        content: 'Cầu lông có thể đổi luật tính điểm...',
        status: STATUS_TYPES.ACTIVE
    },
    {
        id: 2,
        date: '19/2/2025',
        time: '8h45 AM',
        content: 'Cầu lông có thể đổi luật tính điểm...',
        status: STATUS_TYPES.ACTIVE
    },
    {
        id: 3,
        date: '19/2/2025',
        time: '8h45 AM',
        content: 'Cầu lông có thể đổi luật tính điểm...',
        status: STATUS_TYPES.ACTIVE
    },
    {
        id: 4,
        date: '19/2/2025',
        time: '8h45 AM',
        content: 'Cầu lông có thể đổi luật tính điểm...',
        status: STATUS_TYPES.ACTIVE
    }
]);

// Function to cycle through statuses
function changeStatus(comment) {
    // Cycle through statuses: Đang đăng -> Đã đăng -> Chưa đăng -> Spam -> Đang đăng
    switch (comment.status) {
        case STATUS_TYPES.ACTIVE:
            comment.status = STATUS_TYPES.APPROVED;
            break;
        case STATUS_TYPES.APPROVED:
            comment.status = STATUS_TYPES.PENDING;
            break;
        case STATUS_TYPES.PENDING:
            comment.status = STATUS_TYPES.SPAM;
            break;
        case STATUS_TYPES.SPAM:
            comment.status = STATUS_TYPES.ACTIVE;
            break;
        default:
            comment.status = STATUS_TYPES.ACTIVE;
    }
}

// Get appropriate CSS class based on status
function getStatusClass(status) {
    switch (status) {
        case STATUS_TYPES.ACTIVE:
            return 'status-active';
        case STATUS_TYPES.APPROVED:
            return 'status-approved';
        case STATUS_TYPES.PENDING:
            return 'status-pending';
        case STATUS_TYPES.SPAM:
            return 'status-spam';
        default:
            return '';
    }
}

// Get human-readable status text
function getStatusText(status) {
    switch (status) {
        case STATUS_TYPES.ACTIVE:
            return 'Đang đăng';
        case STATUS_TYPES.APPROVED:
            return 'Đã đăng';
        case STATUS_TYPES.PENDING:
            return 'Chưa đăng';
        case STATUS_TYPES.SPAM:
            return 'Spam';
        default:
            return 'Không xác định';
    }
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
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: auto;
}

.title-section {
    padding-left: 0;
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
    min-width: 190px;
    margin-right: 25px;
}

.btn-xuat-admin {
    background-color: #1d2a54;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 180px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-right: 20px;
    text-decoration: none;
}

.btn-xuat-admin i {
    font-size: 20px;
    margin-top: -2px;
}

.btn-xuat-admin span {
    letter-spacing: 0.5px;
}

.btn-xuat-admin:hover {
    background-color: #2c3e50;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Tabs styling */
.tabs-container {
    margin-bottom: 0;
    border-bottom: 1px solid #dee2e6;
}

.comment-tabs {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    background-color: white;
    border-bottom: none;
}

.tab-item {
    padding: 0;
    margin: 0;
    margin-right: 10px;
}

.tab-item a {
    display: block;
    padding: 16px 22px;
    text-decoration: none;
    color: #6c757d;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s;
    position: relative;
    white-space: nowrap;
}

.tab-item.active a {
    color: #1d2a54;
    font-weight: 600;
}

.tab-item.active a::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #1d2a54;
}

.tab-item a:hover {
    color: #1d2a54;
}

/* Filter toolbar styling */
.filter-toolbar {
    background: white;
    padding: 16px 16px;
    border-radius: 0;
    margin-bottom: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f0f0f0;
}

.filter-group {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: white;
    min-width: 150px;
    font-size: 14px;
    color: #444;
    height: 40px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 30px;
}

.btn-apply {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 8px 22px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    height: 40px;
}

.btn-apply:hover {
    background-color: #45a049;
}

.search-group {
    min-width: 300px;
}

.search-box {
    display: flex;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    overflow: hidden;
    height: 40px;
    background-color: #f8f9fa;
    transition: all 0.2s;
}

.search-box:hover, .search-box:focus-within {
    border-color: #adb5bd;
    background-color: #ffffff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.search-input {
    flex: 1;
    padding: 8px 15px;
    border: none;
    outline: none;
    font-size: 14px;
    background-color: transparent;
}

.search-input::placeholder {
    color: #adb5bd;
    font-size: 13px;
}

.search-btn {
    background-color: transparent;
    border: none;
    border-left: none;
    padding: 0 15px;
    cursor: pointer;
    color: #6c757d;
    transition: all 0.2s;
}

.search-btn:hover {
    color: #1d2a54;
}

.search-btn i {
    font-size: 16px;
}

/* Comment table styling */
.comment-table-container {
    background: white;
    overflow: hidden;
    margin-bottom: 0;
}

.comment-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    table-layout: fixed;
}

.comment-table thead {
    background-color: #1d2a54;
    color: white;
}

.comment-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
    font-size: 16px;
}

.comment-table tbody tr {
    border-bottom: 1px solid #edf2f9;
}

.comment-table tbody tr:last-child {
    border-bottom: none;
}

.comment-table td {
    padding: 20px;
    vertical-align: middle;
    font-size: 15px;
}

.comment-table tbody tr:hover {
    background-color: #f9fafc;
}

/* Column widths */
.checkbox-col {
    width: 50px;
    text-align: center;
}

.id-col {
    width: 80px;
}

.date-col {
    width: 140px;
}

.time-col {
    width: 120px;
}

.content-col {
    width: auto;
}

.status-col {
    width: 140px;
}

.actions-col {
    width: 140px;
    text-align: center;
}

/* Cell styling */
.checkbox-cell, 
.id-cell, 
.date-cell, 
.time-cell, 
.content-cell, 
.status-cell, 
.actions-cell {
    padding: 20px;
}

.checkbox-cell {
    text-align: center;
}

.checkbox-cell input[type="checkbox"],
.master-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    border-radius: 4px;
    accent-color: #1d2a54;
}

.id-cell {
    color: #495057;
    font-weight: 500;
    font-size: 15px;
}

.date-cell, .time-cell {
    color: #495057;
    font-weight: normal;
    font-size: 15px;
}

.content-cell {
    font-weight: 400;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 30px;
    font-size: 15px;
}

.status-cell {
    text-align: center;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 500;
    display: inline-block;
    min-width: 110px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    user-select: none;
    position: relative;
}

.status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.status-badge:active {
    transform: translateY(0);
}

/* Status - Đang đăng */
.status-active {
    background-color: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.status-active:hover {
    background-color: #c8e6c9;
}

/* Status - Đã đăng */
.status-approved {
    background-color: #e3f2fd;
    color: #1565c0;
    border: 1px solid #bbdefb;
}

.status-approved:hover {
    background-color: #bbdefb;
}

/* Status - Chưa đăng */
.status-pending {
    background-color: #fff8e1;
    color: #f57f17;
    border: 1px solid #ffecb3;
}

.status-pending:hover {
    background-color: #ffecb3;
}

/* Status - Spam */
.status-spam {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.status-spam:hover {
    background-color: #ffcdd2;
}

/* Add tooltip indication */
.status-badge::after {
    content: '';
    position: absolute;
    bottom: 2px;
    right: 10px;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.5;
}

.actions-cell {
    text-align: center;
}

.actions {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: center;
}

.action-btn {
    background: none;
    border: 1px solid #dee2e6;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #6c757d;
}

.action-btn i {
    font-size: 16px;
}

.view-btn {
    color: #1976D2;
    border-color: #BBDEFB;
    background-color: #E3F2FD;
}

.edit-btn {
    color: #2e7d32;
    border-color: #C8E6C9;
    background-color: #E8F5E9;
}

.delete-btn {
    color: #c62828;
    border-color: #FFCDD2;
    background-color: #FFEBEE;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.view-btn:hover {
    background-color: #BBDEFB;
}

.edit-btn:hover {
    background-color: #C8E6C9;
}

.delete-btn:hover {
    background-color: #FFCDD2;
}

.action-btn:active {
    transform: translateY(0);
}

/* Pagination styling */
.d-flex.justify-content-between.align-items-center {
    background-color: white;
    border-radius: 0 0 12px 12px;
    padding: 16px;
}

.text-muted {
    font-size: 15px;
    color: #6c757d;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 0;
}

.page-item {
    list-style: none;
}

.page-link {
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #495057;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 15px;
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

/* Improvements for table actions */
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.action-btn:active {
    transform: translateY(0);
}

/* Zebra striping for better readability */
.comment-table tbody tr:nth-child(even) {
    background-color: #f9fafc;
}

.comment-table tbody tr:hover {
    background-color: #f0f4f8;
}

@media (max-width: 1200px) {
    .admin-left {
        width: 220px;
        max-width: 220px;
    }
    
    .admin-right {
        margin-left: 220px;
        width: calc(100% - 220px);
    }
    
    .filter-group {
        flex-wrap: wrap;
    }
    
    .content-col {
        width: 25%;
    }
}

@media (max-width: 992px) {
    .admin-left {
        width: 100px;
        max-width: 100px;
    }
    
    .admin-right {
        margin-left: 100px;
        width: calc(100% - 100px);
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
        font-size: 28px;
        margin: 0;
    }
    
    .admin-left img {
        width: 70px;
        height: 70px;
        margin: 15px auto;
    }
    
    .filter-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .filter-group {
        justify-content: space-between;
    }
    
    .search-group {
        width: 100%;
    }
    
    .content-col {
        width: 200px;
    }
    
    .time-col {
        display: none;
    }
}

@media (max-width: 768px) {
    .admin-left {
        position: fixed;
        bottom: 0;
        width: 100%;
        max-width: 100%;
        height: 80px;
        min-height: 80px;
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
        padding-bottom: 90px;
    }
    
    .admin-left img, .admin-left h5, .tienichadmin-left {
        display: none;
    }
    
    .admin-left a {
        padding: 15px;
        margin: 0;
    }
    
    .admin-left a i {
        font-size: 30px;
    }
    
    .tab-item a {
        padding: 15px 15px;
        font-size: 16px;
    }
    
    .actions-col, .status-col, .date-col {
        display: none;
    }
    
    .comment-table th, .comment-table td {
        padding: 12px;
    }
    
    .table-footer {
        flex-direction: column;
        gap: 15px;
    }
    
    .pagination {
        width: 100%;
        justify-content: center;
    }
    
    .comment-content {
        max-width: 150px;
    }
}

@media (max-width: 480px) {
    .admin-left a {
        padding: 10px 8px;
    }
    
    .comment-content {
        max-width: 120px;
    }
    
    .header-container {
        flex-direction: column;
        height: auto;
        padding: 18px;
    }
    
    .title-section {
        padding-left: 0;
        text-align: center;
        margin-bottom: 15px;
    }
    
    .button-wrapper {
        margin-right: 0;
        justify-content: center;
        min-width: 100%;
    }
    
    .btn-xuat-admin {
        margin-right: 0;
        width: 100%;
    }
}
</style>
