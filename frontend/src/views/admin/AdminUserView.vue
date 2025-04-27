<template>
    <main>
        <section class="admin">
            <div class="admin-left">
                <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                <h5>Admin</h5>
                <RouterLink to="/admin"><i class="bi bi-palette2"></i><span>Bảng điều khiển</span></RouterLink>
                <RouterLink to="/admin/quanlidanhmuc"><i class="bi bi-inboxes-fill"></i><span>Quản lý danh mục</span></RouterLink>
                <RouterLink to="/admin/quanlisanpham"><i class="bi bi-box2-fill"></i><span>Quản lý sản phẩm</span></RouterLink>
                <RouterLink to="/admin/quanlinguoidung" class="active"><i class="bi bi-people-fill"></i><span>Quản lý người dùng</span></RouterLink>
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
                        <h5>Danh sách đăng nhập</h5>
                        <p class="text-muted">Xem và tìm kiếm người dùng</p>
                    </div>
                    <div class="button-wrapper">
                        <button class="btn-add-user">
                            <i class="bi bi-plus-lg"></i>
                            <span>Thêm người dùng</span>
                        </button>
                    </div>
                </div>

                <div class="filter-toolbar">
                    <select class="filter-select">
                        <option>All</option>
                    </select>
                    <select class="filter-select">
                        <option>All Departments</option>
                    </select>
                    <button class="btn-delete">Delete <i class="bi bi-trash"></i></button>
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm..." class="search-input">
                        <button class="search-btn"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>sđt</th>
                                <th>Địa chỉ</th>
                                <th>Vai trò</th>
                                <th class="action-col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </td>
                                <td>Minh</td>
                                <td>minh123@gmail.com</td>
                                <td>0123456789</td>
                                <td>Bình thuận</td>
                                <td>Khách hàng</td>
                                <td class="action-col">
                                    <div class="action-buttons">
                                        <a href="#" class="action-icon view-icon" title="Xem">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="action-icon edit-icon" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class="action-icon delete-icon" title="Xóa">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </td>
                                <td>Nga</td>
                                <td>nga123@gmail.com</td>
                                <td>0123456789</td>
                                <td>Bình thuận</td>
                                <td>Admin</td>
                                <td class="action-col">
                                    <div class="action-buttons">
                                        <a href="#" class="action-icon view-icon" title="Xem">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="action-icon edit-icon" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class="action-icon delete-icon" title="Xóa">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </td>
                                <td>Hân</td>
                                <td>han123@gmail.com</td>
                                <td>0123456789</td>
                                <td>Bình thuận</td>
                                <td>Khách hàng</td>
                                <td class="action-col">
                                    <div class="action-buttons">
                                        <a href="#" class="action-icon view-icon" title="Xem">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="action-icon edit-icon" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class="action-icon delete-icon" title="Xóa">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </td>
                                <td>Tuấn</td>
                                <td>tuan123@gmail.com</td>
                                <td>0123456789</td>
                                <td>Bình thuận</td>
                                <td>Khách hàng</td>
                                <td class="action-col">
                                    <div class="action-buttons">
                                        <a href="#" class="action-icon view-icon" title="Xem">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="action-icon edit-icon" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class="action-icon delete-icon" title="Xóa">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="checkbox-col">
                                    <input type="checkbox" class="table-checkbox">
                                </td>
                                <td>Đạt</td>
                                <td>dat123@gmail.com</td>
                                <td>0123456789</td>
                                <td>Bình thuận</td>
                                <td>Khách hàng</td>
                                <td class="action-col">
                                    <div class="action-buttons">
                                        <a href="#" class="action-icon view-icon" title="Xem">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="#" class="action-icon edit-icon" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class="action-icon delete-icon" title="Xóa">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
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
</style>0
