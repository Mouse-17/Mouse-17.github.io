<script setup lang="ts">
    import { RouterLink } from 'vue-router'
    import { onMounted, ref } from 'vue'
    import Chart from 'chart.js/auto'

    // Tham chiếu tới canvas
    const chart1 = ref<HTMLCanvasElement | null>(null);
    const chart2 = ref<HTMLCanvasElement | null>(null);

    onMounted(() => {
        // Biểu đồ cột cho chart1
        if (chart1.value) {
            new Chart(chart1.value, {
                type: 'bar',
                data: {
                    labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
                    datasets: [{
                        label: 'Mua hàng',
                        data: [10, 20, 15, 25, 18, 22, 17],
                        backgroundColor: '#0095FF'
                    }, {
                        label: 'Booking',
                        data: [8, 18, 12, 22, 14, 20, 15],
                        backgroundColor: '#00E096'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        // Biểu đồ line cho chart2
        if (chart2.value) {
            new Chart(chart2.value, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Tháng trước',
                        data: [3000, 3200, 3100, 3004],
                        borderColor: '#0095FF',
                        fill: false,
                        tension: 0.1
                    }, {
                        label: 'Tháng này',
                        data: [4000, 4500, 4200, 4504],
                        borderColor: '#00E096',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
    });
</script>

<template>
    <main>
        <section class="admin">
            <div class="admin-left">
                <img src="../../../public/img/user.webp" alt="" class="admin-avatar">
                <h5>Admin</h5>
                <RouterLink to="/admin" class="active"><i class="bi bi-palette2"></i><span>Bảng điều khiển</span></RouterLink>
                <RouterLink to="/admin/quanlidanhmuc"><i class="bi bi-inboxes-fill"></i><span>Quản lý danh mục</span></RouterLink>
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
                <div class="header-container">
                    <div class="title-section">
                        <h5>Bảng điều khiển</h5>
                        <p class="text-muted">Khuyến mãi mỗi ngày</p>
                    </div>
                    <div class="button-wrapper">
                        <button class="btn-xuat-admin">
                            <i class="bi bi-upload"></i>
                            <span>Xuất</span>
                        </button>
                    </div>
                </div>

                <div class="stat-cards">
                    <div class="stat-card bg-red-admin">
                        <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5>$1k</h5>
                        <p>Tổng chủ sản</p>
                        <small>+8% từ hôm qua</small>
                    </div>
                    <div class="stat-card bg-orange-admin">
                        <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                        <h5>300</h5>
                        <p>Tổng đơn hàng</p>
                        <small>+5% từ hôm qua</small>
                    </div>
                    <div class="stat-card bg-green-admin">
                        <div class="stat-icon"><i class="bi bi-tag-fill"></i></div>
                        <h5>75</h5>
                        <p>Booking</p>
                        <small>+12% từ hôm qua</small>
                    </div>
                    <div class="stat-card bg-purple-admin">
                        <div class="stat-icon"><i class="bi bi-person-fill-add"></i></div>
                        <h5>16</h5>
                        <p>Khách hàng mới</p>
                        <small>0.5% từ hôm qua</small>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="grid-row">
                        <div class="grid-card">
                            <div class="product-card-admin">
                                <h5 class="fw-bold text-center">Tổng kết ngày</h5>
                                <div class="chart-container">
                                    <canvas ref="chart1" id="chart1"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="grid-card">
                            <div class="product-card-admin">
                                <h5 class="fw-bold text-center">Sự hài lòng của khách hàng</h5>
                                <div class="char2-hailong">
                                    <canvas ref="chart2" id="chart2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid-row">
                        <div class="grid-card">
                            <div class="product-card-admin">
                                <h5 class="fw-bold text-center">Sản phẩm nổi bật</h5>
                                <div class="product-list-container">
                                    <div class="product-item">
                                        <div class="product-icon">
                                            <i class="bi bi-circle-fill" style="color: #2196f3;"></i>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">Vợt cầu lông Yonex Astrox</div>
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar-blue" style="width: 45%;"></div>
                                                </div>
                                                <span class="badge-blue">45%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="product-item">
                                        <div class="product-icon">
                                            <i class="bi bi-circle-fill" style="color: #4caf50;"></i>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">Giày pickleball Adidas Pro</div>
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar-green" style="width: 38%;"></div>
                                                </div>
                                                <span class="badge-green">38%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="product-item">
                                        <div class="product-icon">
                                            <i class="bi bi-circle-fill" style="color: #9c27b0;"></i>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">Bộ gậy golf Homa Premium</div>
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar-purple" style="width: 29%;"></div>
                                                </div>
                                                <span class="badge-purple">29%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="product-item">
                                        <div class="product-icon">
                                            <i class="bi bi-circle-fill" style="color: #ff9800;"></i>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">Áo tennis Nike DryFit</div>
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar-orange" style="width: 24%;"></div>
                                                </div>
                                                <span class="badge-orange">24%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="product-item">
                                        <div class="product-icon">
                                            <i class="bi bi-circle-fill" style="color: #f44336;"></i>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">Bóng đá Adidas World Cup</div>
                                            <div class="progress-container">
                                                <div class="progress">
                                                    <div class="progress-bar-red" style="width: 18%;"></div>
                                                </div>
                                                <span class="badge-red">18%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-card">
                            <div class="product-card-admin map-card">
                                <h5 class="fw-bold text-center">Vị trí hoạt động</h5>
                                <div class="image-container">
                                    <div class="map-wrapper">
                                        <div class="vietnam-map">
                                            <svg width="100%" viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M190,30 C200,45 210,60 215,75 C220,90 225,105 220,120 C215,135 210,150 200,165 C190,180 180,195 175,210 C170,225 165,240 160,255 C150,270 140,285 135,300 C130,315 125,330 120,315 C115,300 110,285 105,270 C100,255 95,240 100,225 C105,210 110,195 115,180 C120,165 125,150 120,135 C115,120 110,105 115,90 C120,75 125,60 140,52 C155,45 170,37 185,30 C187,28 188,28 190,30 Z" 
                                                fill="#e3f2fd" stroke="#2196f3" stroke-width="1.5"/>
                                                <circle cx="165" cy="75" r="6" fill="#f44336" class="map-point" data-region="Hà Nội"/>
                                                <circle cx="175" cy="150" r="6" fill="#ff9800" class="map-point" data-region="Đà Nẵng"/>
                                                <circle cx="135" cy="250" r="6" fill="#3f51b5" class="map-point" data-region="TP.HCM"/>
                                                <circle cx="110" cy="210" r="6" fill="#9c27b0" class="map-point" data-region="Cần Thơ"/>
                                                <circle cx="155" cy="120" r="6" fill="#009688" class="map-point" data-region="Huế"/>
                                            </svg>
                                        </div>
                                        <div class="map-stats">
                                            <div class="stat-item">
                                                <div class="stat-value">235</div>
                                                <div class="stat-label">Chi nhánh</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value">45</div>
                                                <div class="stat-label">Tỉnh thành</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value">12k+</div>
                                                <div class="stat-label">Khách hàng</div>
                                            </div>
                                        </div>
                                        <div class="map-legend">
                                            <div class="legend-item">
                                                <span class="legend-color" style="background-color: #f44336;"></span>
                                                <span class="legend-text">Hà Nội</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color" style="background-color: #ff9800;"></span>
                                                <span class="legend-text">Đà Nẵng</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color" style="background-color: #3f51b5;"></span>
                                                <span class="legend-text">TP.HCM</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color" style="background-color: #9c27b0;"></span>
                                                <span class="legend-text">Cần Thơ</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color" style="background-color: #009688;"></span>
                                                <span class="legend-text">Huế</span>
                                            </div>
                                        </div>
                                    </div>
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

.button-wrapper {
    display: flex;
    justify-content: flex-end;
    min-width: 170px;
    margin-right: 25px;
}

.btn-xuat-admin {
    background-color: #1d2a54;
    color: white;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 600;
    font-size: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 150px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-right: 30px;
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

.row.g-3 {
    margin: 0 -8px;
    display: flex;
    flex-wrap: nowrap;
}

.row.g-3 > [class*="col-"] {
    padding: 0 8px;
    flex: 1;
}

.card {
    border: none;
    border-radius: 16px;
    padding: 20px;
    height: 140px;
    transition: all 0.3s ease;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.card i {
    font-size: 20px;
    background: rgba(255, 255, 255, 0.3);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}

.card h5 {
    font-size: 28px;
    font-weight: 600;
    margin: 8px 0;
    line-height: 1;
}

.card p {
    font-size: 14px;
    margin: 4px 0;
    opacity: 0.9;
}

.card small {
    font-size: 12px;
    opacity: 0.8;
    margin-top: 4px;
    display: block;
}

.bg-red-admin {
    background: linear-gradient(135deg, rgba(255, 138, 138, 0.7) 0%, rgba(255, 166, 166, 0.7) 100%);
}

.bg-red-admin i, .bg-red-admin h5, .bg-red-admin p, .bg-red-admin small {
    color: #a93226;
}

.bg-orange-admin {
    background: linear-gradient(135deg, rgba(255, 222, 89, 0.7) 0%, rgba(255, 189, 107, 0.7) 100%);
}

.bg-orange-admin i, .bg-orange-admin h5, .bg-orange-admin p, .bg-orange-admin small {
    color: #d35400;
}

.bg-green-admin {
    background: linear-gradient(135deg, rgba(125, 229, 144, 0.7) 0%, rgba(76, 227, 179, 0.7) 100%);
}

.bg-green-admin i, .bg-green-admin h5, .bg-green-admin p, .bg-green-admin small {
    color: #1e8449;
}

.bg-purple-admin {
    background: linear-gradient(135deg, rgba(228, 149, 252, 0.7) 0%, rgba(200, 105, 229, 0.7) 100%);
}

.bg-purple-admin i, .bg-purple-admin h5, .bg-purple-admin p, .bg-purple-admin small {
    color: #6c3483;
}

.row.mt-4 {
    margin: 0 -10px 20px;
    display: flex;
    flex-wrap: wrap;
}

.row.mt-4.second-row {
    margin-top: 20px;
}

.row.mt-4 > [class*="col-"] {
    padding: 0 10px;
}

.product-card-admin {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04), 0 1px 4px rgba(0, 0, 0, 0.02);
    height: 100%;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.product-card-admin h5 {
    font-size: 18px;
    margin-bottom: 12px;
    color: #1a2942;
    font-weight: 600;
    padding-bottom: 8px;
    border-bottom: 1px solid #f0f0f0;
    letter-spacing: 0.2px;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
}

.progress-bar-blue {
    background: linear-gradient(to right, #4dabf7 0%, #228be6 100%);
    border-radius: 4px;
}

.progress-bar-green {
    background: linear-gradient(to right, #69db7c 0%, #38d9a9 100%);
    border-radius: 4px;
}

.progress-bar-purple {
    background: linear-gradient(to right, #da77f2 0%, #be4bdb 100%);
    border-radius: 4px;
}

.badge-blue, .badge-green, .badge-purple {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-blue {
    background-color: #228be6;
    color: white;
}

.badge-green {
    background-color: #38d9a9;
    color: white;
}

.badge-purple {
    background-color: #be4bdb;
    color: white;
}

.table {
    margin-top: 8px;
    font-size: 14px;
    color: #3c4858;
    width: 100%;
}

.table th {
    padding: 10px 8px;
    font-weight: 600;
    color: #1a2942;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

.table td {
    padding: 10px 8px;
    vertical-align: middle;
    border-bottom: 1px solid #f8f8f8;
}

.char2-hailong {
    width: 100%;
    height: 100%;
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

#chart1, #chart2 {
    width: 100% !important;
    height: 250px !important;
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
    
    .grid-row {
        grid-template-columns: 1fr;
    }
}

.dashboard-grid {
    display: grid;
    grid-gap: 20px;
    margin: 0;
    position: relative;
}

.grid-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    position: relative;
    z-index: 1;
    margin-bottom: 20px;
}

.grid-card {
    min-height: 300px;
    max-height: 400px;
}

.product-card-admin:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.03);
    transform: translateY(-3px);
}

.progress {
    height: 8px;
    background-color: #f4f5f7;
    overflow: hidden;
    border-radius: 10px;
}

.progress-bar-blue, .progress-bar-green, .progress-bar-purple {
    border-radius: 10px;
    transition: width 0.6s ease;
    height: 100%;
}

.badge-blue, .badge-green, .badge-purple {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    text-align: center;
}

@media (max-width: 768px) {
    .grid-row {
        grid-template-columns: 1fr;
        grid-gap: 20px;
    }
    
    .grid-card {
        min-height: auto;
    }
    
    #chart1, #chart2 {
        height: 220px !important;
    }
    
    .dashboard-grid:after {
        display: none;
    }
}

.text-center {
    text-align: center;
}

.table-container {
    width: 100%;
    flex-grow: 1;
}

.chart-container {
    width: 100%;
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.image-container {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
}

.image-container img {
    width: 90%;
    height: auto;
    max-height: 240px;
    object-fit: contain;
}

.map-card {
    position: relative;
    background: white;
}

.map-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}

.vietnam-map {
    width: 100%;
    max-width: 180px;
    margin: 0 auto;
    display: flex;
    justify-content: center;
}

.map-stats {
    display: flex;
    justify-content: space-around;
    width: 100%;
    margin: 10px 0;
}

.stat-item {
    text-align: center;
    padding: 0 5px;
}

.stat-value {
    font-size: 18px;
    font-weight: 600;
    color: #1a2942;
}

.stat-label {
    font-size: 11px;
    color: #6c757d;
}

.map-point {
    transition: all 0.2s ease;
    cursor: pointer;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

.map-point:hover {
    r: 7;
    filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
}

.map-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
    width: 100%;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 11px;
    color: #6c757d;
    margin: 0 2px;
}

.legend-color {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 2px;
    margin-right: 4px;
}

.legend-text {
    font-weight: 500;
}

.product-list-container {
    width: 100%;
    height: 100%;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-y: auto;
    max-height: 340px;
    padding-right: 5px;
}

.product-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.product-item:hover {
    background-color: #f8f9fa;
}

.product-icon {
    margin-right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.product-info {
    flex: 1;
}

.product-name {
    font-size: 13px;
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.progress-container {
    display: flex;
    align-items: center;
}

.progress {
    height: 6px;
    background-color: #f4f5f7;
    overflow: hidden;
    border-radius: 10px;
    flex: 1;
    margin-right: 10px;
}

.progress-bar-blue, .progress-bar-green, .progress-bar-purple, .progress-bar-orange, .progress-bar-red {
    border-radius: 10px;
    transition: width 0.6s ease;
    height: 100%;
}

.progress-bar-blue {
    background: linear-gradient(to right, #42a5f5, #2196f3);
}

.progress-bar-green {
    background: linear-gradient(to right, #66bb6a, #4caf50);
}

.progress-bar-purple {
    background: linear-gradient(to right, #ab47bc, #9c27b0);
}

.progress-bar-orange {
    background: linear-gradient(to right, #ffa726, #ff9800);
}

.progress-bar-red {
    background: linear-gradient(to right, #ef5350, #f44336);
}

.badge-blue, .badge-green, .badge-purple, .badge-orange, .badge-red {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    color: white;
    min-width: 40px;
    text-align: center;
}

.badge-blue {
    background-color: #2196f3;
}

.badge-green {
    background-color: #4caf50;
}

.badge-purple {
    background-color: #9c27b0;
}

.badge-orange {
    background-color: #ff9800;
}

.badge-red {
    background-color: #f44336;
}

.stat-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    color: #1a2942;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 140px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.stat-icon i {
    font-size: 18px;
    color: #1a2942;
}

.stat-card h5 {
    font-size: 30px;
    font-weight: 700;
    margin: 8px 0;
}

.stat-card p {
    font-size: 16px;
    margin: 5px 0;
    opacity: 0.9;
    font-weight: 500;
}

.stat-card small {
    font-size: 14px;
    opacity: 0.8;
    font-weight: 500;
}

@media (max-width: 1200px) {
    .stat-cards {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 992px) {
    .stat-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 576px) {
    .stat-cards {
        grid-template-columns: 1fr;
    }
}

.product-list-container::-webkit-scrollbar {
    width: 4px;
}

.product-list-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.1);
    border-radius: 4px;
}

.product-list-container::-webkit-scrollbar-track {
    background-color: rgba(0,0,0,0.05);
}

</style>
