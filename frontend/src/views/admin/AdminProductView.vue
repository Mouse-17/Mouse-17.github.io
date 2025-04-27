<template>
    <main>
        <section class="admin">
            <div class="admin-left">
                <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                <h5>Admin</h5>
                <RouterLink to="/admin"><i class="bi bi-palette2"></i><span>Bảng điều khiển</span></RouterLink>
                <RouterLink to="/admin/quanlidanhmuc"><i class="bi bi-inboxes-fill"></i><span>Quản lý danh mục</span></RouterLink>
                <RouterLink to="/admin/quanlisanpham" class="active"><i class="bi bi-box2-fill"></i><span>Quản lý sản phẩm</span></RouterLink>
                <RouterLink to="/admin/quanlinguoidung"><i class="bi bi-people-fill"></i><span>Quản lý người dùng</span></RouterLink>
                <RouterLink to="/admin/quanlidonhang"><i class="bi bi-receipt-cutoff"></i><span>Quản lý đơn hàng</span></RouterLink>
                <RouterLink to="/admin/quanlibinhluan"><i class="bi bi-chat-fill"></i><span>Quản lý bình luận</span></RouterLink>
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
                        <h5>Sản Phẩm</h5>
                        <p class="text-muted">Xem và tìm kiếm sản phẩm</p>
                    </div>
                    <div class="button-wrapper">
                        <RouterLink class="btn-xuat-admin" to="/admin/themsanpham">
                            <i class="bi bi-plus-lg"></i>
                            <span>Thêm sản phẩm</span>
                        </RouterLink>
                    </div>
                </div>

                <div class="status-filter">
                    <div class="status-links">
                        <a href="#" class="status-link active">Tất cả <span class="count">(100)</span></a>
                        <a href="#" class="status-link">Đã đăng <span class="count">(80)</span></a>
                        <a href="#" class="status-link">Bản nháp <span class="count">(20)</span></a>
                        <a href="#" class="status-link special">Lọc sản phẩm</a>
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
                    </select>
                    <select class="filter-select">
                        <option>Toàn bộ sản phẩm</option>
                        <option>Còn hànghàng</option>
                        <option>Hết hàng</option>
                        <option>Mới về</option>
                        <option>Giảm giá</option>
                        <option>Ngưng kinh doanh</option>
                    </select>
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                        <button class="search-btn"><i class="bi bi-search"></i></button>
                    </div>
                </div>                

                <div class="product-table-container">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th class="checkbox-col"><input type="checkbox" class="master-checkbox"></th>
                                <th class="product-col">Tên sản phẩm</th>
                                <th class="price-col">Giá</th>
                                <th class="description-col">Mô tả</th>
                                <th class="status-col">Trạng thái</th>
                                <th class="actions-col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <td><input type="checkbox"></td>
                                <td class="product-info">
                                    <img :src="product.image" alt="" class="product-thumbnail">
                                    <span class="product-name">{{ product.name }}</span>
                                </td>
                                <td class="price">{{ product.price }}</td>
                                <td class="description">{{ product.description }}</td>
                                <td class="status">
                                    <span 
                                        :class="['status-badge', product.status]" 
                                        @click="changeStatus(product)"
                                        title="Click để thay đổi trạng thái"
                                    >
                                        {{ getStatusName(product.status) }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <button class="action-btn view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                    <button class="action-btn edit-btn" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                    <button class="action-btn delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 px-3 border-top">
                        <div class="text-muted">Hiển thị 1-10 trong tổng số 50 danh mục</div>
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

// Mảng các trạng thái sản phẩm
const statuses = [
    { id: 'in-stock', name: 'Còn hàng' },
    { id: 'out-of-stock', name: 'Hết hàng' },
    { id: 'sale', name: 'Giảm giá' },
    { id: 'new', name: 'Mới về' },
    { id: 'discontinued', name: 'Ngừng kinh doanh' }
];

// Dữ liệu các sản phẩm với trạng thái
const products = ref([
    { 
        id: 1, 
        name: 'Áo thể thao nam', 
        price: '244.000đ', 
        status: 'in-stock', 
        image: '../../../public/img/p1.png',
        description: 'Áo thể thao chất liệu cao cấp, thấm hút mồ hôi tốt'
    },
    { 
        id: 2, 
        name: 'Áo polo', 
        price: '244.000đ', 
        status: 'out-of-stock', 
        image: '../../../public/img/p1.png',
        description: 'Áo polo thiết kế đơn giản, phù hợp nhiều dịp'
    },
    { 
        id: 3, 
        name: 'Áo khoác gió', 
        price: '244.000đ', 
        status: 'sale', 
        image: '../../../public/img/p1.png',
        description: 'Áo khoác chống nước, chống gió tốt cho mùa đông'
    },
    { 
        id: 4, 
        name: 'Áo thun tay dài', 
        price: '244.000đ', 
        status: 'new', 
        image: '../../../public/img/p1.png',
        description: 'Áo thun dài tay chất cotton thoáng mát'
    },
    { 
        id: 5, 
        name: 'Áo thun tay dài', 
        price: '244.000đ', 
        status: 'new', 
        image: '../../../public/img/p1.png',
        description: 'Áo thun dài tay chất cotton thoáng mát'
    }
]);

// Hàm thay đổi trạng thái khi click
function changeStatus(product) {
    const currentIndex = statuses.findIndex(s => s.id === product.status);
    const nextIndex = (currentIndex + 1) % statuses.length;
    product.status = statuses[nextIndex].id;
}

// Hàm lấy tên trạng thái từ id
function getStatusName(statusId) {
    const status = statuses.find(s => s.id === statusId);
    return status ? status.name : '';
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
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-bottom: 15px;
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
    min-width: 190px;
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-right: 30px;
    text-decoration: none;
}

.btn-xuat-admin i {
    font-size: 22px;
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

/* Status filter styling */
.status-filter {
    background: white;
    padding: 0;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.status-links {
    display: flex;
    align-items: center;
    padding: 0 15px;
}

.status-link {
    color: #555;
    text-decoration: none;
    padding: 12px 10px;
    font-size: 15px;
    font-weight: 500;
    position: relative;
    transition: all 0.2s;
}

.status-link:not(:last-child)::after {
    content: '|';
    position: absolute;
    right: -4px;
    color: #ddd;
}

.status-link.active {
    color: #1d2a54;
    font-weight: 600;
}

.status-link:hover {
    color: #1d2a54;
}

.status-link .count {
    margin-left: 2px;
    color: #777;
    font-size: 14px;
}

.status-link.special {
    margin-left: auto;
    color: #1d2a54;
    font-weight: 600;
}

/* Filter toolbar styling */
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

/* Product table styling */
.product-table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 15px;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

.product-table thead {
    background-color: #1d2a54;
    color: white;
}

.product-table th {
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
    font-size: 15px;
}

.product-table td {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.product-table tbody tr:hover {
    background-color: #f8f9fa;
}

.checkbox-col {
    width: 40px;
}

.checkbox-col input[type="checkbox"] {
    width: 20px;
    height: 20px;
}

.product-col {
    width: 20%;
}

.price-col {
    width: 10%;
}

.description-col {
    width: 30%;
}

.status-col {
    width: 15%;
}

.actions-col {
    width: 10%;
}

.product-info {
    display: flex;
    align-items: center;
}

.product-thumbnail {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    object-fit: cover;
    margin-right: 15px;
}

.product-name {
    font-weight: 500;
    color: #333;
    font-size: 15px;
}

.price {
    font-weight: 500;
    color: #1d2a54;
    font-size: 15px;
}

.status {
    color: #2c3e50;
    font-size: 15px;
}

.description {
    color: #555;
    font-size: 14px;
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 100px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.status-badge:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.status-badge.in-stock {
    background-color: rgba(76, 175, 80, 0.15);
    color: #2e7d32;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-badge.out-of-stock {
    background-color: rgba(244, 67, 54, 0.15);
    color: #d32f2f;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.status-badge.sale {
    background-color: rgba(255, 152, 0, 0.15);
    color: #ef6c00;
    border: 1px solid rgba(255, 152, 0, 0.3);
}

.status-badge.new {
    background-color: rgba(33, 150, 243, 0.15);
    color: #1565c0;
    border: 1px solid rgba(33, 150, 243, 0.3);
}

.status-badge.discontinued {
    background-color: rgba(158, 158, 158, 0.15);
    color: #616161;
    border: 1px solid rgba(158, 158, 158, 0.3);
}

.actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.action-btn {
    background: none;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #555;
}

.action-btn i {
    font-size: 15px;
}

.view-btn:hover {
    background-color: #e3f2fd;
    color: #2196f3;
}

.edit-btn:hover {
    background-color: #e8f5e9;
    color: #4caf50;
}

.delete-btn:hover {
    background-color: #ffebee;
    color: #f44336;
}

/* Table footer styling */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
}

.settings-label {
    color: #555;
    font-size: 14px;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 5px;
}

.page-btn {
    background: white;
    border: 1px solid #ddd;
    width: 32px;
    height: 32px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
    color: #555;
}

.page-btn:hover:not(.active) {
    background-color: #f5f5f5;
    border-color: #ccc;
}

.page-btn.active {
    background-color: #1d2a54;
    color: white;
    border-color: #1d2a54;
}

.prev-btn, .next-btn {
    font-weight: bold;
    font-size: 16px;
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
    
    .search-group {
        margin-top: 10px;
        width: 100%;
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
        font-size: 24px;
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
    
    .search-group {
        width: 100%;
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
        font-size: 26px;
    }
    
    .status-links {
        flex-wrap: wrap;
    }
    
    .status-link {
        padding: 12px;
    }
    
    .actions-col, .status-col {
        display: none;
    }
    
    .product-table th, .product-table td {
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
}

@media (max-width: 480px) {
    .product-name {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .header-container {
        flex-direction: column;
        height: auto;
        padding: 18px;
    }
    
    .title-section {
        margin-bottom: 15px;
        text-align: center;
        padding-left: 0;
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
