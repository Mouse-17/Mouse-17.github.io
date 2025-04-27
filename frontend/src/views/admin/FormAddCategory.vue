<template>
    <main>
        <section class="admin">
            <div class="admin-left">
                <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                <h5>Admin</h5>
                <RouterLink to="/admin"><i class="bi bi-palette2"></i><span>Bảng điều khiển</span></RouterLink>
                <RouterLink to="/admin/quanlidanhmuc" class="active"><i class="bi bi-inboxes-fill"></i><span>Quản lý danh mục</span></RouterLink>
                <RouterLink to="/admin/quanlisanpham"><i class="bi bi-box2-fill"></i><span>Quản lý sản phẩm</span></RouterLink>
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
                <div class="form-container">
                    <div class="form-header">
                        <button class="back-btn" @click="goBack">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <h2>Thêm danh mục</h2>
                    </div>
                    
                    <form @submit.prevent="addCategory" class="category-form">
                        <div class="input-container">
                            <input 
                                type="text" 
                                id="category-name" 
                                v-model="category.name"
                                class="form-input"
                                placeholder="Nhập tên danh mục"
                                required
                            >
                        </div>
                        
                        <div class="input-container">
                            <input 
                                type="text" 
                                id="category-description" 
                                v-model="category.description"
                                class="form-input"
                                placeholder="Mô Tả"
                            >
                        </div>

                        <!-- Image upload section -->
                        <div class="input-container">
                            <input 
                                type="file" 
                                id="category-image" 
                                class="d-none"
                                accept="image/*" 
                                ref="imageInput"
                                @change="handleImageUpload"
                            >
                            
                            <div class="image-upload-area" @click="triggerImageUpload">
                                <div v-if="!imagePreview" class="upload-placeholder">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <span>Tải hình ảnh lên</span>
                                </div>
                                
                                <div v-else class="image-preview-wrapper">
                                    <img :src="imagePreview" alt="Preview" class="image-preview">
                                    <button type="button" class="remove-image-btn" @click.stop="removeImage">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="status-container">
                            <div class="status-options">
                                <label class="status-option">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="available"
                                        v-model="category.status"
                                        class="status-radio"
                                    >
                                    <span class="status-circle available"></span>
                                </label>
                                
                                <label class="status-option">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="unavailable"
                                        v-model="category.status"
                                        class="status-radio" 
                                    >
                                    <span class="status-circle unavailable"></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="button-container">
                            <button type="submit" class="submit-btn">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const imageInput = ref(null);
const imagePreview = ref('');

const category = ref({
    name: '',
    description: '',
    status: 'available',
    image: null
});

function triggerImageUpload() {
    imageInput.value.click();
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        category.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function removeImage(event) {
    event.preventDefault();
    category.value.image = null;
    imagePreview.value = '';
    if (imageInput.value) {
        imageInput.value.value = '';
    }
}

function addCategory() {
    // Logic to add the category
    console.log('Adding category:', category.value);
    // Reset form after submission
    category.value = {
        name: '',
        description: '',
        status: 'available',
        image: null
    };
    imagePreview.value = '';
    // Navigate back to category management
    router.push('/admin/quanlidanhmuc');
}

function goBack() {
    router.push('/admin/quanlidanhmuc');
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
    display: flex;
    justify-content: center;
    align-items: flex-start;
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

/* Form container styling */
.form-container {
    width: 100%;
    max-width: 620px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 30px 40px 40px;
    margin-top: 20px;
}

.form-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
    position: relative;
    width: 100%;
}

.form-header h2 {
    font-size: 26px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    text-align: center;
    padding-left: 10px;
}

.back-btn {
    position: absolute;
    left: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background-color: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.back-btn:hover {
    background-color: #e2e8f0;
    color: #1e293b;
}

.back-btn i {
    font-size: 20px;
}

/* Form inputs */
.category-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
    align-items: center;
}

.input-container {
    width: 85%;
    max-width: 480px;
}

.form-input {
    width: 100%;
    padding: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.2s;
    background-color: #fff;
    color: #1e293b;
}

.form-input:focus {
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input::placeholder {
    color: #94a3b8;
}

.description-input {
    min-height: 120px;
    height: 120px;
    resize: none;
    width: 100%;
}

/* Image upload styling */
.image-upload-area {
    width: 100%;
    height: 150px;
    border: 1px dashed #94a3b8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    background-color: #f8fafc;
    overflow: hidden;
}

.image-upload-area:hover {
    border-color: #3b82f6;
    background-color: #f1f5f9;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
}

.upload-icon {
    font-size: 32px;
    color: #64748b;
    margin-bottom: 8px;
}

.upload-placeholder span {
    font-size: 15px;
    color: #64748b;
}

.image-preview-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
}

.image-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.remove-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #ef4444;
    font-size: 18px;
    transition: all 0.2s;
    z-index: 5;
}

.remove-image-btn:hover {
    background-color: white;
    transform: scale(1.1);
}

/* Status selection */
.status-container {
    display: flex;
    justify-content: center;
    margin: 25px 0;
}

.status-options {
    display: flex;
    gap: 20px;
}

.status-option {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.status-radio {
    display: none;
}

.status-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.status-circle.available {
    background-color: #10b981;
}

.status-circle.unavailable {
    background-color: #ef4444;
}

.status-radio:checked + .status-circle {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
}

/* Button container */
.button-container {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.submit-btn {
    background-color: #ca8a04;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 14px 0;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    width: 140px;
    text-align: center;
}

.submit-btn:hover {
    background-color: #a16207;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.d-none {
    display: none;
}

@media (max-width: 768px) {
    .admin-left {
        width: 70px;
        max-width: 70px;
    }
    
    .admin-left a span {
        display: none;
    }
    
    .admin-right {
        margin-left: 70px;
        width: calc(100% - 70px);
        padding: 25px 20px;
    }
    
    .form-container {
        padding: 25px;
    }
}
</style>