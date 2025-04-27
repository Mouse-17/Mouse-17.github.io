<template>
    <main>
        <section class="banner">
            <img class="imgban" src="../../public/img/liên hệ.png" alt="">
        </section>
        
        <section class="contact-form">
            <div class="container">
                <h2 class="form-title">GỬI THÔNG TIN CHO CHÚNG TÔI</h2>
                <p class="form-subtitle">Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất</p>
                <form>
                    <div class="form-group">
                        <label for="name">Tên của bạn</label>
                        <div class="input-container">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" id="name" placeholder="Vui lòng nhập tên bạn">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Địa chỉ mail</label>
                        <div class="input-container">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" id="email" placeholder="Vui lòng nhập mail">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <div class="input-container">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="tel" id="phone" placeholder="Vui lòng nhập số điện thoại">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Nội dung</label>
                        <div class="input-container">
                            <i class="bi bi-chat-left-text-fill"></i>
                            <textarea id="message" placeholder="Vui lòng nhập nội dung"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-send-fill"></i>
                        Gửi
                    </button>
                </form>
            </div>
        </section>

        <section class="contact-info-section">
            <div class="container">
                <h1 class="text-center mb-5">THÔNG TIN LIÊN HỆ</h1>
                <div class="contact-boxes">
                    <!-- Box thông tin liên hệ -->
                    <div class="contact-box info-box">
                        <div class="content">
                            <div class="info-content">
                                <div class="info-item">
                                    <i class="bi bi-telephone-fill"></i>
                                    <h2 class="text-center mb-4">1900.5678</h2>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-envelope-fill"></i>
                                    <p class="text-center mb-4">support@keysport.com</p>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <p class="text-center mb-4">Công Viên Phần Mềm Quang Trung, đường Tô Ký, Quận 12, TP.HCM</p>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-people-fill"></i>
                                    <p class="text-center mb-4">Team 5Key</p>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-globe"></i>
                                    <p class="text-center">www.keysport.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Box tải app -->
                    <div class="contact-box download-box">
                        <div class="content">
                            <div class="download-content">
                                <div class="download-title">
                                    <i class="bi bi-download"></i>
                                    <h2 class="text-center mb-4">TẢI NGAY</h2>
                                </div>
                                <div class="store-buttons">
                                    <a href="#" class="store-button">
                                        <img src="../../public/img/ggplay.png" alt="Google Play">
                                    </a>
                                    <a href="#" class="store-button">
                                        <img src="../../public/img/appstore.png" alt="App Store">
                                    </a>
                                </div>
                                <div class="follow-title">
                                    <i class="bi bi-bell-fill"></i>
                                    <h3 class="text-center mt-4">THEO DÕI NGAY</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped>
.contact-banner {
    background-color: var(--bg1);
    padding: 3rem 0;
    text-align: center;
    margin-bottom: 2rem;
}

.contact-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    color: var(--colortext1);
    margin: 0;
}

/* Form styles */
.contact-form {
    padding: 3.5rem 0;
    background-color: #f5f5f5;
}

.contact-form .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 20px;
}

.form-title {
    text-align: center;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--colortext1);
    margin-bottom: 0.7rem;
}

.form-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2.2rem;
}

.form-group {
    margin-bottom: 1.8rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--colortext1);
    margin-bottom: 0.6rem;
    font-size: 1.1rem;
}

.input-container {
    display: flex;
    align-items: flex-start;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: white;
    overflow: hidden;
    transition: all 0.2s ease;
}

.input-container:focus-within {
    border-color: #0A1E5C;
    box-shadow: 0 0 0 2px rgba(10, 30, 92, 0.1);
}

.input-container i {
    padding: 15px;
    font-size: 1.3rem;
    color: #0A1E5C;
    background-color: rgba(10, 30, 92, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
}

.input-container input,
.input-container textarea {
    flex: 1;
    padding: 14px 16px;
    border: none;
    outline: none;
    font-size: 1.1rem;
}

.input-container textarea {
    min-height: 120px;
    resize: vertical;
    line-height: 1.5;
}

.submit-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    background-color: #FFC107;
    color: #333;
    border: none;
    border-radius: 8px;
    font-size: 1.15rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.submit-btn:hover {
    background-color: #C69749;
    color: white;
}

.submit-btn i {
    font-size: 1.15rem;
}

/* Responsive form */
@media (max-width: 768px) {
    .contact-form {
        padding: 2.5rem 0;
    }
    
    .form-title {
        font-size: 2rem;
    }
    
    .input-container i {
        padding: 13px;
        font-size: 1.2rem;
    }
    
    .input-container input,
    .input-container textarea {
        padding: 13px;
        font-size: 1rem;
    }
    
    .submit-btn {
        padding: 13px;
        font-size: 1.1rem;
    }
}

/* Contact info section styles */
.contact-info-section {
    padding: 60px 0;
    background-color: var(--bg3);
}

.contact-boxes {
    display: flex;
    gap: 20px;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 15px;
}

.contact-box {
    flex: 1;
    padding: 25px;
    border-radius: 10px;
    min-height: 280px;
    display: flex;
    align-items: center;
}

.info-box {
    background-color: #0A1E5C;
    color: white;
}

.download-box {
    background-color: #C69749;
    color: white;
}

.content {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-content, .download-content {
    width: 100%;
}

.info-box h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 25px;
}

.info-box p {
    font-size: 1.2rem;
    line-height: 1.4;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 15px;
}

.download-box h2 {
    font-size: 2.2rem;
    font-weight: bold;
    color: white;
    margin-bottom: 25px;
}

.download-box h3 {
    font-size: 1.8rem;
    font-weight: bold;
    color: white;
    margin-top: 25px;
}

.store-buttons {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin: 25px 0;
}

.store-button img {
    height: 35px;
    transition: transform 0.2s ease;
}

.store-button:hover img {
    transform: scale(1.05);
}

h1 {
    color: var(--colortext1);
    font-size: 2.5rem;
    font-weight: bold;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
    padding: 0 20px;
}

.info-item i {
    font-size: 1.5rem;
    color: white;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.info-item:first-child i {
    font-size: 1.8rem;
}

.info-item h2, .info-item p {
    margin: 0;
    flex: 1;
    text-align: left;
}

.info-item h2 {
    font-size: 2.2rem;
    line-height: 1.2;
}

.info-item p {
    font-size: 1.2rem;
    line-height: 1.4;
    color: rgba(255, 255, 255, 0.9);
}

.download-title, .follow-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 20px;
}

.download-title i, .follow-title i {
    font-size: 1.8rem;
    color: white;
}

.download-box h2, .download-box h3 {
    margin: 0;
    font-size: 2rem;
}

@media (max-width: 768px) {
    .contact-boxes {
        flex-direction: column;
    }
    
    .contact-box {
        min-height: 250px;
    }

    .info-item {
        padding: 0 10px;
        gap: 12px;
    }
    
    .info-item i {
        font-size: 1.3rem;
        width: 20px;
    }
    
    .info-item:first-child i {
        font-size: 1.6rem;
    }
    
    .info-item h2 {
        font-size: 2rem;
    }
    
    .info-item p {
        font-size: 1.1rem;
    }
}
</style>