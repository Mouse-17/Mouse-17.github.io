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
                <RouterLink to="/admin/quanlibinhluan"><i class="bi bi-chat-fill"></i><span>Quản lý bình luận</span></RouterLink>
                <RouterLink to="/admin/quanlibaiviet"><i class="bi bi-book-fill"></i><span>Quản lý bài viết</span></RouterLink>
                <RouterLink to="/admin/quanlidanhgia" class="active"><i class="bi bi-star-fill"></i><span>Quản lý đánh giá</span></RouterLink>
                <div class="tienichadmin-left">
                    <a href="#"><i class="bi bi-gear"></i><span>CÀI ĐẶT</span></a>
                    <a href="#"><i class="bi bi-question-circle"></i><span>TRỢ GIÚP</span></a>
                    <a href="#"><i class="bi bi-info-circle"></i><span>THOÁT</span></a>
                </div>
            </div>

            <div class="admin-right">
                <div class="header-container">
                    <div class="title-section">
                        <h5>Danh sách đánh giá</h5>
                        <p class="text-muted">Xem đánh giá</p>
                    </div>
                </div>                    

                <div class="content-wrapper bg-white rounded-3 shadow-sm p-4">
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Tất cả đánh giá</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Xử lý đánh giá</a>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-funnel me-2"></i>Thêm điều kiện lọc
                        </button>
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Tìm kiếm đánh giá...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40px">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input">
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
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input">
                                        </div>
                                    </td>
                                    <td>1</td>
                                    <td>Nga</td>
                                    <td>Cầu lông</td>
                                    <td>23/02/2025</td>
                                    <td>8:45PM</td>
                                    <td class="rating">
                                        <span class="star-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </span>
                                        <span class="rating-text">5 sao</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm edit-btn" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                            <button class="btn btn-sm delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input">
                                        </div>
                                    </td>
                                    <td>2</td>
                                    <td>Minh</td>
                                    <td>Cầu lông</td>
                                    <td>23/02/2025</td>
                                    <td>8:45PM</td>
                                    <td class="rating">
                                        <span class="star-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star"></i>
                                        </span>
                                        <span class="rating-text">4 sao</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm edit-btn" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                            <button class="btn btn-sm delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input">
                                        </div>
                                    </td>
                                    <td>3</td>
                                    <td>Hân</td>
                                    <td>Cầu lông</td>
                                    <td>23/02/2025</td>
                                    <td>8:45PM</td>
                                    <td class="rating">
                                        <span class="star-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </span>
                                        <span class="rating-text">4.5 sao</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm edit-btn" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                            <button class="btn btn-sm delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input">
                                        </div>
                                    </td>
                                    <td>4</td>
                                    <td>Tuấn</td>
                                    <td>Cầu lông</td>
                                    <td>23/02/2025</td>
                                    <td>8:45PM</td>
                                    <td class="rating">
                                        <span class="star-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star"></i>
                                            <i class="bi bi-star"></i>
                                        </span>
                                        <span class="rating-text">3 sao</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm view-btn" title="Xem"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm edit-btn" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                            <button class="btn btn-sm delete-btn" title="Xóa"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">Hiển thị 1-10 trong tổng số 50 đánh giá</div>
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
            </div>
        </section>
    </main>
</template>

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
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
