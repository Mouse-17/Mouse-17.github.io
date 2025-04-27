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
                        <h2 class="text-start">Thêm sản phẩm</h2>
                        <form action="">
                            <div class="form-group">
                                <div class="right-left">
                                    <label for="product-name">Tên sản phẩm</label>
                                    <input type="text" id="product-name" placeholder="VD: Áo">
                                    
                                    <label for="status">Giá </label>
                                    <input type="text" id="product-price" placeholder="VD: 244.000đ">
                                </div>

                                <div class="right-right">
                                    <label for="description">Mô tả</label>
                                    <textarea id="description" class="ms-0" placeholder="Khách hàng"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="right-left">
                                    <label for="main-image">Ảnh đại diện</label>
                                    <input type="file" id="main-image">
                                    <span class="note">Chọn 1 ảnh</span>
                                </div>
                        
                                <div class="right-right">
                                    <label for="extra-images">Ảnh phụ</label>
                                    <input type="file" id="extra-images" multiple>
                                    <span class="note">Chọn nhiều ảnh</span>
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

<style>
    .form-group {
        display: flex;
        gap: 20px;
        align-items: stretch;
        font-size: 28px;
    }

    .right-left, .right-right {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    label{
        font-size: 16px;
        margin-bottom: 5px;
    }
    .right-left label{
        margin-bottom: 5px;
    }
    .right-left input{
        margin-bottom: 10px;
    }
    #status, #product-name, #product-price{
        padding: 10px 5px;
    }
    .right-right textarea {
        flex: 1;
        height: 100%;
        padding: 5px;
    }
    h2
    {
        margin-bottom: 20px;
        font-weight: bolder;
        text-align: left;
    }
    .submit-btn {
        display: block;
        margin: 20px auto;
        padding: 15px 25px;
        background-color: #C99928;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 18px;
        border-radius: 8px;
    }
    .note {
        font-size: 14px;
        color: gray;
        margin-top: -5px;
        text-align: center;
    }
    .right-left input, .right-right input, textarea {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: white;
    }
</style>