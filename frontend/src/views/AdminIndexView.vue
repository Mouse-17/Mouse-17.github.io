<template>
    <main>
        <section class="admin">
            <div class="container-fluid">
                <div class="row gx-0">
                    <!-- Bên trái -->
                    <div class="col-12 col-lg-3 col-md-6 p-0 admin-left">
                        <div class="admin-left-content p-4">
                            <img src="../../public/img/user1.jpg" alt="" class="admin-avatar mb-3">
                            <h5 class="mb-4">Admin</h5>
                            <div class="admin-menu">
                                <RouterLink to="/admin" class="menu-item active"><i class="bi bi-palette2"></i>Bảng điều khiển</RouterLink>
                                <RouterLink to="/admin/quanlidanhmuc" class="menu-item"><i class="bi bi-inboxes-fill"></i>Quản lý danh mục</RouterLink>
                                <RouterLink to="/admin/quanlisanpham" class="menu-item"><i class="bi bi-box2-fill"></i>Quản lý sản phẩm</RouterLink>
                                <RouterLink to="/admin/quanlinguoidung" class="menu-item"><i class="bi bi-people-fill"></i>Quản lý người dùng</RouterLink>
                                <RouterLink to="/admin/quanlichusan" class="menu-item"><i class="bi bi-person-vcard"></i>Quản lý chủ sân</RouterLink>
                                <RouterLink to="/admin/quanlidonhang" class="menu-item"><i class="bi bi-receipt-cutoff"></i>Quản lý đơn hàng</RouterLink>
                                <RouterLink to="/admin/quanlibinhluan" class="menu-item"><i class="bi bi-chat-fill"></i>Quản lý bình luận</RouterLink>
                                <RouterLink to="/admin/quanlibaiviet" class="menu-item"><i class="bi bi-book-fill"></i>Quản lý bài viết</RouterLink>
                            </div>
                            <div class="tienichadmin-left mt-4">
                                <a href="#" class="settings-item"><i class="bi bi-gear"></i>CÀI ĐẶT</a>
                                <a href="#" class="settings-item"><i class="bi bi-question-circle"></i>TRỢ GIÚP</a>
                                <a href="#" class="settings-item"><i class="bi bi-box-arrow-right"></i>THOÁT</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 admin-right">
                        <div>
                            <RouterView />
                        </div>
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
}

.admin-left {
    background-color: #1d2a54;
    min-height: 100vh;
    height: 100%;
    color: white;
    position: fixed;
    width: inherit;
    max-width: inherit;
    overflow-y: auto;
    padding-bottom: 30px;
}

.admin-right {
    margin-left: 25%;
    width: 75%;
    padding: 20px;
}

.admin-left-content {
    position: sticky;
    top: 0;
}

.admin-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.2);
}

.admin-menu {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.menu-item {
    color: #ffffff;
    text-decoration: none;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.menu-item:hover, .menu-item.active {
    background-color: rgba(255,255,255,0.15);
    color: #ffd700;
    transform: translateX(5px);
}

.menu-item i {
    color: #ffffff;
    font-size: 1.1rem;
}

.menu-item:hover i {
    color: #ffd700;
}

.tienichadmin-left {
    margin-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 20px;
}

.settings-item {
    color: #ffffff;
    text-decoration: none;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.settings-item:hover {
    color: #ffd700;
    background-color: rgba(255,255,255,0.15);
    border-radius: 0.5rem;
    transform: translateX(5px);
}

.settings-item i {
    color: #ffffff;
}

.settings-item:hover i {
    color: #ffd700;
}

@media (max-width: 992px) {
    .admin-left {
        position: static;
        width: 100%;
        max-width: 100%;
    }
    .admin-right {
        margin-left: 0;
        width: 100%;
    }
}
</style>
