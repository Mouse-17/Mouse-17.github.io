<template>
    <main>
        <section class="admin">
            <div class="container">
                <div class="row gx-0">
            <!-- Bên trái -->
                    <div class="col-12 col-lg-3 col-md-6 p-0 mt-2 admin-left">
                        <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                        <h5>Admin</h5>
                        <RouterLink to="/admin" class=""><i class="bi bi-palette2"></i>Bảng điều khiển</RouterLink>
                        <RouterLink to="/admin/quanlidanhmuc"><i class="bi bi-inboxes-fill"></i>Quản lý danh mục</RouterLink>
                        <RouterLink to="/admin/quanlisanpham"><i class="bi bi-box2-fill"></i>Quản lý sản phẩm</RouterLink>
                        <RouterLink to="/admin/quanlinguoidung"><i class="bi bi-people-fill"></i>Quản lý người dùng</RouterLink>
                        <RouterLink to="/admin/quanlidonhang"><i class="bi bi-receipt-cutoff"></i>Quản lý đơn hàng</RouterLink>
                        <RouterLink to="/admin/quanlibinhluan"><i class="bi bi-chat-fill"></i>Quản lý bình luận</RouterLink>
                        <RouterLink to="/admin/quanlibaiviet"><i class="bi bi-book-fill"></i>Quản lý bài viết</RouterLink>
                        <RouterLink to="/admin/quanlidanhgia"><i class="bi bi-star-fill"></i>Quản lý đánh giá</RouterLink>
                        <div class="tienichadmin-left">
                            <a href="#"><i class="bi bi-gear"></i>CÀI ĐẶT</a>
                            <a href="#"><i class="bi bi-question-circle"></i>TRỢ GIÚP</a>
                            <a href="#"><i class="bi bi-info-circle"></i>THOÁT</a>
                        </div>
                    </div>
        <!-- Bên phải -->
                    <div class="col-lg-9 admin-right" style="margin-top: 50px;">
                        <h2>Thêm bài viết</h2>
                        <form action="" method="post">
                            <div class="form-group">
                                <div class="right-left">
                                    <label for="essay-name">Tên bài viết</label>
                                    <input type="text" id="essay-name" placeholder="VD: Cầu lông">
                                    
                                    <label for="essay-title">Tiêu đề</label>
                                    <input type="text" id="essay-title" placeholder="VD: Cầu lông">

                                    <label for="writer-name">Tác giả</label>
                                    <input type="text" id="writer-name" placeholder="VD: Nga">

                                    <label for="status">Trạng thái</label>
                                    <select id="status">
                                        <option>Đang đăng</option>
                                    </select>
                                </div>

                                <div class="right-right">
                                    <label for="description">Mô tả</label>
                                    <textarea id="description" placeholder="Khách hàng"></textarea>
                                </div>
                            </div>
                            <button class="submit-btn">Thêm</button>       
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>