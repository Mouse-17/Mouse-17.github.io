<template>
    <main>
        <section class="banner">
            <img class="imgban" src="../../public/img/Banner1.png" alt="">
        </section>
        
        <!-- Menu thể thao -->
        <div class="box">
            <div class="menu-news">
                <a href="#" class="menu-item">Bóng Đá</a>
                <a href="#" class="menu-item">Bóng Chuyền</a>
                <a href="#" class="menu-item">Cầu Lông</a>
                <a href="#" class="menu-item">Pickleball</a>
                <a href="#" class="menu-item">Tennis</a>
                <a href="#" class="menu-item">Bơi Lội</a>
                <a href="#" class="menu-item">E-Sports</a>
                <a href="#" class="menu-item">Bóng đá quốc tế</a>
                <a href="#" class="menu-item">Bóng đá Việt Nam</a>
                <a href="#" class="menu-item">Golf</a>
                <a href="#" class="menu-item">Xem thêm</a>
            </div>
        </div>
        
        <!-- Phần tin nổi bật và tin tức chính -->
        <div class="box">
            <div class="content main-content">
                <!-- Tin chính bên trái -->
                <div class="left-content">
                    <h2 class="section-title">TIN CHÍNH</h2>
                    <div class="featured-news">
                        <img src="../../public/img/Picture1.png" alt="Hình ảnh thể thao" class="feature-image">
                        <div class="news-text">
                            <span class="news-category">Bóng đá Việt Nam</span>
                            <h3 class="news-title">Danh thủ Nguyễn Hồng Sơn NÓI THẲNG VỀ PHONG ĐỘ CỦA FILIP NGUYỄN</h3>
                            <p class="news-description">Danh thủ Dương Hồng Sơn nhận xét thẳng thắn về màn trình diễn của thủ môn Filip Nguyễn ở trận CLB CAHN hòa Hà Tĩnh tại vòng 12 V-League 2024/25</p>
                            <div class="news-meta">
                                <span><i class="bi bi-clock"></i> 2 giờ trước</span>
                                <span><i class="bi bi-chat"></i> 167</span>
                                <span><i class="bi bi-share"></i> 10</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tin nổi bật dạng grid -->
                    <h2 class="section-title mt-4">TIN NỔI BẬT</h2>
                    <div class="news-grid">
                        <div class="news-item">
                            <img src="../../public/img/Picture2.png" alt="Ronaldo">
                            <div class="news-content">
                                <span class="news-category small">Bóng đá quốc tế</span>
                                <p>Ronaldo ghi bàn ở tuổi 41, lập kỷ lục mới</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="../../public/img/Picture3.png" alt="Hoa Khôi bóng chuyền VN">
                            <div class="news-content">
                                <span class="news-category small">Bóng chuyền</span>
                                <p>Hoa Khôi bóng chuyền VN gia nhập LPB Ninh Bình</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="../../public/img/Picture4.png" alt="Thùy Linh">
                            <div class="news-content">
                                <span class="news-category small">Cầu lông</span>
                                <p>Thùy Linh vô địch sau 6 phút ở giải đấu 2024</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="../../public/img/Picture5.png" alt="T1 và Gumayusi">
                            <div class="news-content">
                                <span class="news-category small">E-Sports</span>
                                <p>Bang trở lại giữa lúc T1 không có Gumayusi</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="../../public/img/Picture6.png" alt="Cầu lông thay đổi luật">
                            <div class="news-content">
                                <span class="news-category small">Cầu lông</span>
                                <p>Tiger Woods công bố kế hoạch thi đấu năm 2025</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="../../public/img/Picture7.png" alt="Chọn vợt cầu lông">
                            <div class="news-content">
                                <span class="news-category small">Bóng đá Việt Nam</span>
                                <p>HLV Troussier lên kế hoạch chuẩn bị AFF Cup 2024</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar bên phải -->
                <div class="right-sidebar">
                    <!-- Bảng xếp hạng -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">BẢNG XẾP HẠNG</h3>
                        <div class="rankings-tabs">
                            <button 
                                v-for="league in leagues" 
                                :key="league.id" 
                                @click="selectLeague(league.id)" 
                                :class="['ranking-tab', { active: selectedLeague === league.id }]"
                            >
                                {{ league.name }}
                            </button>
                            <button class="ranking-tab settings-btn" @click="showSettings = !showSettings">
                                <i class="bi bi-gear-fill"></i>
                            </button>
                        </div>
                        
                        <!-- Tùy chỉnh hiển thị -->
                        <div v-if="showSettings" class="rankings-settings">
                            <h4>Tùy chỉnh hiển thị</h4>
                            <div class="settings-options">
                                <label>
                                    <input type="checkbox" v-model="showStats"> Hiện thống kê
                                </label>
                                <label>
                                    <input type="checkbox" v-model="showForm"> Hiện phong độ
                                </label>
                                <div class="select-wrapper">
                                    <span>Số đội hiển thị:</span>
                                    <select v-model="teamsToShow">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">Tất cả</option>
                                    </select>
                                </div>
                            </div>
                            <button class="save-btn" @click="saveSettings">Lưu thiết lập</button>
                        </div>
                        
                        <!-- Loading spinner -->
                        <div v-if="loading" class="loading-spinner">
                            <div class="spinner"></div>
                            <p>Đang tải dữ liệu...</p>
                        </div>
                        
                        <!-- Bảng xếp hạng -->
                        <div v-else-if="currentRankings.length" class="rankings-table">
                            <div class="rankings-header">
                                <span class="rank">#</span>
                                <span class="team-name">Đội</span>
                                <span v-if="showStats" class="team-stats">T-H-B</span>
                                <span v-if="showForm" class="team-form">Phong độ</span>
                                <span class="points">Đ</span>
                            </div>
                            <div 
                                v-for="(team, index) in displayedRankings" 
                                :key="team.id" 
                                class="rankings-row"
                                :class="{ 'user-favorite': userFavorites.includes(team.id) }"
                                @click="toggleFavorite(team.id)"
                            >
                                <span class="rank">{{ index + 1 }}</span>
                                <span class="team-name">
                                    {{ team.name }}
                                    <i v-if="userFavorites.includes(team.id)" class="bi bi-star-fill favorite-icon"></i>
                                </span>
                                <span v-if="showStats" class="team-stats">{{ team.stats }}</span>
                                <span v-if="showForm" class="team-form">
                                    <span 
                                        v-for="(result, i) in team.form" 
                                        :key="i" 
                                        :class="['form-indicator', result]"
                                        :title="getFormTitle(result)"
                                    ></span>
                                </span>
                                <span class="points">{{ team.points }}</span>
                            </div>
                        </div>
                        
                        <!-- Thông báo khi không có dữ liệu -->
                        <div v-else class="no-data">
                            <p>Không có dữ liệu bảng xếp hạng</p>
                            <button @click="fetchRankings" class="refresh-btn">
                                <i class="bi bi-arrow-clockwise"></i> Tải lại
                            </button>
                        </div>
                        
                        <!-- Liên kết xem thêm -->
                        <div class="view-more">
                            <a :href="getLeagueUrl()" class="view-more-link">Xem bảng xếp hạng đầy đủ</a>
                        </div>
                    </div>
                    
                    <!-- Lịch thi đấu -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">LỊCH THI ĐẤU</h3>
                        <div class="schedule-tabs">
                            <button class="tab-btn active">Hôm nay</button>
                            <button class="tab-btn">Ngày mai</button>
                        </div>
                        <div class="schedule-table">
                            <div class="schedule-row">
                                <div class="match-info">
                                    <div class="match-time">19:00</div>
                                    <div class="match-teams">Hà Nội FC vs HAGL</div>
                                </div>
                                <div class="match-meta">
                                    <div class="match-league">V-League</div>
                                    <div class="match-channel">VTV6</div>
                                </div>
                            </div>
                            <div class="schedule-row">
                                <div class="match-info">
                                    <div class="match-time">20:00</div>
                                    <div class="match-teams">Liverpool vs Arsenal</div>
                                </div>
                                <div class="match-meta">
                                    <div class="match-league">Premier League</div>
                                    <div class="match-channel">K+PM</div>
                                </div>
                            </div>
                            <div class="schedule-row">
                                <div class="match-info">
                                    <div class="match-time">21:30</div>
                                    <div class="match-teams">Barcelona vs Real Madrid</div>
                                </div>
                                <div class="match-meta">
                                    <div class="match-league">La Liga</div>
                                    <div class="match-channel">SCTV15</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tin nóng -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">TIN NÓNG</h3>
                        <div class="hot-news">
                            <div class="hot-news-item">
                                <span class="hot-tag">HOT</span>
                                <p>AFF Cup 2024: Trận Việt Nam - Malaysia hút khán giả</p>
                            </div>
                            <div class="hot-news-item">
                                <span class="hot-tag">HOT</span>
                                <p>Võ sĩ MMA Việt Nam giành chiến thắng ấn tượng tại ONE Championship</p>
                            </div>
                            <div class="hot-news-item">
                                <span class="hot-tag">HOT</span>
                                <p>Điểm tin E-Sports: GAM thắng lớn tại VCS Mùa Xuân 2025</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quảng cáo -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">QUẢNG CÁO</h3>
                        <div class="ads-container">
                            <img src="../../public/img/Picture11.png" alt="Quảng cáo 1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Phần video highlight -->
        <div class="box video-section">
            <div class="video-header">
                <div class="header-content">
                    <div class="section-title-wrapper">
                        <h2 class="section-title">VIDEO HIGHLIGHT</h2>
                        <div class="title-decoration"></div>
                    </div>
                    <a href="/videos" class="view-all-link">
                        <span>Xem tất cả</span>
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>
            
            <div class="video-container video-horizontal">
                <div class="video-row">
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="../../public/img/Picture1.png" alt="Video highlight">
                            <div class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <span class="video-duration">9:35</span>
                            <div class="video-overlay"></div>
                        </div>
                        <div class="video-info">
                            <span class="video-category">Bóng đá Việt Nam</span>
                            <h3>Highlight: Hà Nội FC 2-1 Hải Phòng FC</h3>
                        </div>
                    </div>
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="../../public/img/Picture5.png" alt="Video highlight">
                            <div class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <span class="video-duration">12:02</span>
                            <div class="video-overlay"></div>
                        </div>
                        <div class="video-info">
                            <span class="video-category">Cầu lông</span>
                            <h3>Viktor Axelsen vs Lee Zii Jia</h3>
                        </div>
                    </div>
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="../../public/img/Picture3.png" alt="Video highlight">
                            <div class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <span class="video-duration">15:45</span>
                            <div class="video-overlay"></div>
                        </div>
                        <div class="video-info">
                            <span class="video-category">Bóng chuyền</span>
                            <h3>Top 10 pha bóng VLeague 2024</h3>
                        </div>
                    </div>
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="../../public/img/Picture7.png" alt="Video highlight">
                            <div class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <span class="video-duration">8:23</span>
                            <div class="video-overlay"></div>
                        </div>
                        <div class="video-info">
                            <span class="video-category">Pickleball</span>
                            <h3>Giải pickleball Championship 2024</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Phần tin tức khác -->
        <div class="box news-section">
            <div class="news-header">
                <div class="header-content">
                    <div class="section-title-wrapper">
                        <h2 class="section-title">TIN KHÁC</h2>
                        <div class="title-decoration"></div>
                    </div>
                    <a href="/news" class="view-all-link">
                        <span>Xem tất cả</span>
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>
            
            <div class="news-container">
                <div class="news-row">
                    <div class="news-item">
                        <div class="news-thumbnail">
                            <img src="../../public/img/Picture8.png" alt="Nguyễn Thùy Linh">
                            <div class="news-overlay"></div>
                        </div>
                        <div class="news-info">
                            <span class="news-category">Cầu lông</span>
                            <h3>Nguyễn Thùy Linh lọt vào top 20 thế giới</h3>
                        </div>
                    </div>
                    <div class="news-item">
                        <div class="news-thumbnail">
                            <img src="../../public/img/Picture9.png" alt="Asian Cup">
                            <div class="news-overlay"></div>
                        </div>
                        <div class="news-info">
                            <span class="news-category">Bóng đá</span>
                            <h3>Vòng loại Asian Cup khi nào, ở đâu?</h3>
                        </div>
                    </div>
                    <div class="news-item">
                        <div class="news-thumbnail">
                            <img src="../../public/img/Picture10.png" alt="Lịch thi đấu tennis">
                            <div class="news-overlay"></div>
                        </div>
                        <div class="news-info">
                            <span class="news-category">Tennis</span>
                            <h3>Lịch thi đấu tennis 26/2: Zverev ra sân</h3>
                        </div>
                    </div>
                    <div class="news-item">
                        <div class="news-thumbnail">
                            <img src="../../public/img/Picture5.png" alt="VĐV bơi lội">
                            <div class="news-overlay"></div>
                        </div>
                        <div class="news-info">
                            <span class="news-category">Bơi lội</span>
                            <h3>Ánh Viên trở lại thi đấu sau thời gian dài</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="events-section">
            <div class="section-title-wrapper center">
                <h2 class="section-title">SỰ KIỆN SẮP DIỄN RA</h2>
                <div class="title-decoration"></div>
            </div>
            <div class="events-grid">
                <div class="event-item">
                    <div class="event-date">
                        <span class="event-day">15</span>
                        <span class="event-month">Tháng 12</span>
                    </div>
                    <div class="event-info">
                        <h3>AFF Cup 2025</h3>
                        <p>Việt Nam vs Malaysia</p>
                        <span class="event-location"><i class="bi bi-geo-alt-fill"></i> Mỹ Đình, Hà Nội</span>
                    </div>
                </div>
                <div class="event-item">
                    <div class="event-date">
                        <span class="event-day">20</span>
                        <span class="event-month">Tháng 12</span>
                    </div>
                    <div class="event-info">
                        <h3>Giải Cầu lông Toàn quốc 20255</h3>
                        <p>Vòng chung kết</p>
                        <span class="event-location"><i class="bi bi-geo-alt-fill"></i> Bắc Giang</span>
                    </div>
                </div>
                <div class="event-item">
                    <div class="event-date">
                        <span class="event-day">25</span>
                        <span class="event-month">Tháng 12</span>
                    </div>
                    <div class="event-info">
                        <h3>Giải Golf Mùa đông 20255</h3>
                        <p>Vòng loại thứ 3</p>
                        <span class="event-location"><i class="bi bi-geo-alt-fill"></i> Đà Lạt</span>
                    </div>
                </div>
                <div class="event-item">
                    <div class="event-date">
                        <span class="event-day">28</span>
                        <span class="event-month">Tháng 12</span>
                    </div>
                    <div class="event-info">
                        <h3>Giải Tennis Mở rộng 20255</h3>
                        <p>Trận chung kết đơn nam</p>
                        <span class="event-location"><i class="bi bi-geo-alt-fill"></i> TP. Hồ Chí Minh</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            // Danh sách các giải đấu
            leagues: [
                { id: 'v-league', name: 'V-League' },
                { id: 'premier-league', name: 'Premier League' },
                { id: 'la-liga', name: 'La Liga' },
                { id: 'serie-a', name: 'Serie A' }
            ],
            selectedLeague: 'v-league',
            
            // Cài đặt hiển thị
            showSettings: false,
            showStats: false,
            showForm: true,
            teamsToShow: 5,
            userFavorites: [],
            
            // Trạng thái tải
            loading: false,
            
            // Thông tin API
            apiBaseUrl: 'https://api.yoursportsapi.com', // Thay bằng API thực tế của bạn
            apiKey: 'YOUR_API_KEY', // Thay bằng API key của bạn
            
            // Dữ liệu bảng xếp hạng mẫu (sẽ thay thế bằng dữ liệu từ API)
            rankings: {
                'v-league': [
                    { id: 'hanoifc', name: 'Hà Nội FC', points: 30, stats: '9-3-2', form: ['W', 'W', 'D', 'L', 'W'] },
                    { id: 'hagl', name: 'HAGL', points: 28, stats: '8-4-2', form: ['W', 'W', 'W', 'D', 'L'] },
                    { id: 'slna', name: 'SLNA', points: 25, stats: '7-4-3', form: ['D', 'W', 'L', 'W', 'W'] },
                    { id: 'haiphong', name: 'Hải Phòng', points: 24, stats: '7-3-4', form: ['L', 'W', 'W', 'W', 'D'] },
                    { id: 'thanhhoa', name: 'Thanh Hóa', points: 22, stats: '6-4-4', form: ['W', 'D', 'W', 'L', 'D'] },
                    { id: 'binhdinh', name: 'Bình Định', points: 21, stats: '6-3-5', form: ['L', 'W', 'D', 'W', 'L'] },
                    { id: 'viettel', name: 'Viettel', points: 19, stats: '5-4-5', form: ['D', 'L', 'W', 'D', 'W'] },
                    { id: 'hcmc', name: 'TP.HCM', points: 17, stats: '4-5-5', form: ['L', 'L', 'D', 'W', 'D'] },
                    { id: 'namdinh', name: 'Nam Định', points: 16, stats: '4-4-6', form: ['D', 'L', 'L', 'W', 'D'] },
                    { id: 'quangnam', name: 'Quảng Nam', points: 14, stats: '3-5-6', form: ['L', 'D', 'L', 'D', 'W'] }
                ],
                'premier-league': [
                    { id: 'mancity', name: 'Man City', points: 32, stats: '10-2-1', form: ['W', 'W', 'W', 'D', 'W'] },
                    { id: 'liverpool', name: 'Liverpool', points: 31, stats: '10-1-2', form: ['W', 'W', 'L', 'W', 'W'] },
                    { id: 'arsenal', name: 'Arsenal', points: 29, stats: '9-2-2', form: ['D', 'W', 'W', 'W', 'L'] },
                    { id: 'tottenham', name: 'Tottenham', points: 26, stats: '8-2-3', form: ['L', 'W', 'W', 'W', 'D'] },
                    { id: 'villa', name: 'Aston Villa', points: 25, stats: '8-1-4', form: ['W', 'L', 'W', 'L', 'W'] },
                    { id: 'chelsea', name: 'Chelsea', points: 24, stats: '7-3-3', form: ['D', 'W', 'W', 'W', 'L'] },
                    { id: 'newcastle', name: 'Newcastle', points: 22, stats: '7-1-5', form: ['W', 'L', 'W', 'L', 'L'] },
                    { id: 'mufc', name: 'Man United', points: 20, stats: '6-2-5', form: ['W', 'L', 'L', 'W', 'D'] },
                    { id: 'brighton', name: 'Brighton', points: 18, stats: '5-3-5', form: ['D', 'L', 'W', 'D', 'L'] },
                    { id: 'westham', name: 'West Ham', points: 17, stats: '5-2-6', form: ['L', 'D', 'L', 'W', 'L'] }
                ],
                'la-liga': [],
                'serie-a': []
            }
        };
    },
    computed: {
        // Bảng xếp hạng hiện tại dựa trên giải đấu đã chọn
        currentRankings() {
            return this.rankings[this.selectedLeague] || [];
        },
        // Số đội hiển thị theo cài đặt người dùng
        displayedRankings() {
            if (this.teamsToShow === '20') {
                return this.currentRankings;
            } else {
                return this.currentRankings.slice(0, parseInt(this.teamsToShow));
            }
        }
    },
    methods: {
        // Chọn giải đấu
        selectLeague(leagueId) {
            this.selectedLeague = leagueId;
            this.fetchRankings();
        },
        
        // Lấy dữ liệu bảng xếp hạng từ API thực tế
        async fetchRankings() {
            this.loading = true;
            
            try {
                // Hướng dẫn triển khai API thực tế
                // 1. Sử dụng fetch hoặc axios để gọi API
                const endpoint = `${this.apiBaseUrl}/standings/${this.selectedLeague}`;
                
                // 2. Thêm tham số và headers cần thiết
                const params = new URLSearchParams({
                    api_key: this.apiKey,
                    league: this.selectedLeague,
                    season: '2024-2025' // Hoặc season hiện tại
                });
                
                // 3. Tùy chọn sử dụng axios (cần cài đặt trước)
                // const response = await axios.get(`${endpoint}?${params}`);
                // const data = response.data;
                
                // 4. Hoặc sử dụng fetch API (có sẵn trong browser)
                const response = await fetch(`${endpoint}?${params}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.apiKey}` // Tùy thuộc vào API
                    }
                });
                
                // 5. Xử lý dữ liệu trả về
                if (response.ok) {
                    const data = await response.json();
                    
                    // 6. Chuyển đổi định dạng dữ liệu từ API thành định dạng ứng dụng
                    const formattedData = data.standings.map(team => {
                        return {
                            id: team.team_id,
                            name: team.team_name,
                            points: team.points,
                            stats: `${team.wins}-${team.draws}-${team.losses}`,
                            form: this.convertFormArray(team.form) // Chuyển đổi định dạng form
                        };
                    });
                    
                    // 7. Cập nhật state với dữ liệu mới
                    this.rankings[this.selectedLeague] = formattedData;
                }
                
                // MÔ PHỎNG API CALL - Chỉ để demo, xóa khi triển khai thực tế
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Dữ liệu mẫu cho La Liga và Serie A
                if (this.selectedLeague === 'la-liga' && !this.rankings['la-liga'].length) {
                    this.rankings['la-liga'] = [
                        { id: 'realmadrid', name: 'Real Madrid', points: 33, stats: '10-3-0', form: ['W', 'W', 'D', 'W', 'W'] },
                        { id: 'barcelona', name: 'Barcelona', points: 30, stats: '9-3-1', form: ['D', 'W', 'W', 'W', 'D'] },
                        { id: 'atletico', name: 'Atletico Madrid', points: 28, stats: '9-1-3', form: ['W', 'W', 'L', 'W', 'W'] },
                        { id: 'girona', name: 'Girona', points: 24, stats: '7-3-3', form: ['L', 'W', 'D', 'W', 'L'] },
                        { id: 'sociedad', name: 'Real Sociedad', points: 21, stats: '6-3-4', form: ['W', 'D', 'W', 'L', 'D'] }
                    ];
                } else if (this.selectedLeague === 'serie-a' && !this.rankings['serie-a'].length) {
                    this.rankings['serie-a'] = [
                        { id: 'inter', name: 'Inter Milan', points: 29, stats: '9-2-2', form: ['W', 'D', 'W', 'W', 'W'] },
                        { id: 'juventus', name: 'Juventus', points: 28, stats: '8-4-1', form: ['W', 'D', 'D', 'W', 'W'] },
                        { id: 'napoli', name: 'Napoli', points: 27, stats: '8-3-2', form: ['D', 'W', 'W', 'L', 'W'] },
                        { id: 'milan', name: 'AC Milan', points: 25, stats: '8-1-4', form: ['W', 'L', 'W', 'W', 'L'] },
                        { id: 'atalanta', name: 'Atalanta', points: 23, stats: '7-2-4', form: ['W', 'W', 'L', 'W', 'D'] }
                    ];
                }
            } catch (error) {
                console.error('Error fetching rankings:', error);
                // Xử lý lỗi - hiển thị thông báo cho người dùng
                this.handleApiError(error);
            } finally {
                this.loading = false;
            }
        },
        
        // Hàm chuyển đổi dữ liệu "form" từ API thành định dạng ứng dụng
        convertFormArray(formString) {
            // Giả sử API trả về form dạng "WDLWW"
            if (!formString) return Array(5).fill('N'); // N = No data
            
            const formArray = [];
            for (let i = 0; i < Math.min(formString.length, 5); i++) {
                formArray.push(formString[i]);
            }
            
            // Đảm bảo luôn có 5 phần tử
            while (formArray.length < 5) {
                formArray.push('N');
            }
            
            return formArray;
        },
        
        // Xử lý lỗi từ API
        handleApiError(error) {
            // 1. Log lỗi để debug
            console.error('API Error Details:', error);
            
            // 2. Hiển thị thông báo người dùng thân thiện
            // Bạn có thể sử dụng thư viện toast notification hoặc alert
            alert('Không thể tải dữ liệu bảng xếp hạng. Vui lòng thử lại sau.');
            
            // 3. Báo cáo lỗi đến hệ thống monitoring (nếu có)
            // this.reportErrorToMonitoring(error);
            
            // 4. Sử dụng dữ liệu cache nếu có
            if (localStorage.getItem(`rankings_${this.selectedLeague}`)) {
                try {
                    this.rankings[this.selectedLeague] = JSON.parse(
                        localStorage.getItem(`rankings_${this.selectedLeague}`)
                    );
                } catch (e) {
                    // Ignore parse errors
                }
            }
        },
        
        // Lưu dữ liệu vào cache
        saveRankingsToCache() {
            // Lưu dữ liệu hiện tại vào localStorage làm cache
            localStorage.setItem(
                `rankings_${this.selectedLeague}`,
                JSON.stringify(this.rankings[this.selectedLeague])
            );
            
            // Lưu thời gian cache
            localStorage.setItem(
                `rankings_${this.selectedLeague}_timestamp`,
                Date.now().toString()
            );
        },
        
        // Kiểm tra dữ liệu cache có hết hạn chưa
        isCacheExpired(leagueId) {
            const timestamp = localStorage.getItem(`rankings_${leagueId}_timestamp`);
            if (!timestamp) return true;
            
            // Cache hết hạn sau 1 giờ (3600000 ms)
            const expirationTime = 3600000;
            return Date.now() - parseInt(timestamp) > expirationTime;
        },
        
        // Toggle đội yêu thích
        toggleFavorite(teamId) {
            if (this.userFavorites.includes(teamId)) {
                // Nếu đã là yêu thích, xóa khỏi danh sách
                this.userFavorites = this.userFavorites.filter(id => id !== teamId);
            } else {
                // Thêm vào danh sách yêu thích
                this.userFavorites.push(teamId);
            }
            
            // Lưu vào localStorage
            localStorage.setItem('userFavorites', JSON.stringify(this.userFavorites));
        },
        
        // Tiêu đề hiển thị khi hover vào chỉ số phong độ
        getFormTitle(result) {
            switch(result) {
                case 'W': return 'Thắng';
                case 'D': return 'Hòa';
                case 'L': return 'Thua';
                default: return '';
            }
        },
        
        // Lưu cài đặt hiển thị
        saveSettings() {
            // Lưu cài đặt vào localStorage
            localStorage.setItem('rankingsSettings', JSON.stringify({
                showStats: this.showStats,
                showForm: this.showForm,
                teamsToShow: this.teamsToShow
            }));
            
            this.showSettings = false;
        },
        
        // Lấy URL đầy đủ cho bảng xếp hạng
        getLeagueUrl() {
            const leagueUrls = {
                'v-league': '/tournaments/v-league/rankings',
                'premier-league': '/tournaments/premier-league/rankings',
                'la-liga': '/tournaments/la-liga/rankings',
                'serie-a': '/tournaments/serie-a/rankings'
            };
            
            return leagueUrls[this.selectedLeague] || '/tournaments';
        },
        
        // Tải cài đặt từ localStorage
        loadSettings() {
            try {
                // Tải cài đặt
                const settings = JSON.parse(localStorage.getItem('rankingsSettings'));
                if (settings) {
                    this.showStats = settings.showStats;
                    this.showForm = settings.showForm;
                    this.teamsToShow = settings.teamsToShow;
                }
                
                // Tải đội yêu thích
                const favorites = JSON.parse(localStorage.getItem('userFavorites'));
                if (favorites) {
                    this.userFavorites = favorites;
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        },
        
        // Lập lịch cập nhật tự động
        setupAutoRefresh() {
            // Tự động cập nhật dữ liệu mỗi 15 phút
            this.autoRefreshInterval = setInterval(() => {
                this.fetchRankings();
            }, 15 * 60 * 1000); // 15 phút
        }
    },
    created() {
        // Tải cài đặt khi component được tạo
        this.loadSettings();
        
        // Kiểm tra cache trước khi tải dữ liệu mới
        if (this.isCacheExpired(this.selectedLeague)) {
            // Cache hết hạn, tải dữ liệu mới
            this.fetchRankings();
        } else {
            // Sử dụng dữ liệu từ cache
            try {
                this.rankings[this.selectedLeague] = JSON.parse(
                    localStorage.getItem(`rankings_${this.selectedLeague}`)
                );
            } catch (e) {
                // Nếu có lỗi khi đọc cache, tải dữ liệu mới
                this.fetchRankings();
            }
        }
        
        // Thiết lập tự động cập nhật
        this.setupAutoRefresh();
    },
    beforeDestroy() {
        // Dọn dẹp interval khi component bị hủy
        if (this.autoRefreshInterval) {
            clearInterval(this.autoRefreshInterval);
        }
    }
};
</script>

<style scoped>
.banner {
    width: 100%;
    margin-bottom: 20px;
}

.imgban {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
}

.box {
    max-width: 1200px;
    margin: 0 auto 30px;
    padding: 0 15px;
}

/* Menu navigation */
.menu-news {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    background-color: #0A1E5C;
    border-radius: 5px;
    padding: 12px;
    margin-bottom: 20px;
}

.menu-item {
    color: white;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 600;
    padding: 8px 14px;
    margin: 5px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.menu-item.active {
    background-color: #C69749;
}

.menu-item:hover {
    background-color: #C69749;
    transform: translateY(-2px);
}

/* Main content layout */
.main-content {
    display: flex;
    gap: 30px;
}

.left-content {
    flex: 2;
}

.right-sidebar {
    flex: 1;
    min-width: 300px;
}

/* Section titles */
.section-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0A1E5C;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #C69749;
    transition: all 0.2s ease;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    width: 0%;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #C69749;
    transition: width 0.3s ease;
}

.section-title:hover::after {
    width: 100%;
}

.mt-4 {
    margin-top: 25px;
}

/* Featured news */
.featured-news {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.featured-news:hover {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    border-bottom: 3px solid #C69749;
    transform: translateY(-5px);
}

.featured-news:hover .news-title {
    color: #C69749;
}

.feature-image {
    width: 100%;
    height: auto;
    max-height: 350px;
    object-fit: cover;
}

.news-text {
    padding: 20px;
}

.news-category {
    display: inline-block;
    background-color: #0A1E5C;
    color: white;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 4px;
    margin-bottom: 10px;
    transition: all 0.2s ease;
}

.news-category.small {
    font-size: 0.7rem;
    padding: 3px 8px;
    margin-bottom: 5px;
}

.news-category:hover {
    background-color: #C69749;
}

.news-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0A1E5C;
    margin: 0 0 15px 0;
    line-height: 1.3;
}

.news-description {
    font-size: 1rem;
    line-height: 1.5;
    color: #333;
    margin-bottom: 15px;
}

.news-meta {
    display: flex;
    gap: 20px;
    color: #444;
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 15px;
    padding: 8px 0;
}

.news-meta i {
    margin-right: 8px;
    font-size: 1.4rem;
    color: #0A1E5C;
}

.news-meta span {
    display: flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    transition: all 0.2s ease;
}

.news-meta span:hover {
    color: #C69749;
    background-color: rgba(198, 151, 73, 0.1);
    transform: translateY(-2px);
}

.news-meta span:hover i {
    color: #C69749;
}

/* News grid */
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.news-item {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    height: 100%;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}

.news-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    border-color: rgba(198, 151, 73, 0.2);
}

.news-item img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.news-item:hover img {
    transform: scale(1.08);
}

.news-content {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: linear-gradient(to bottom, #ffffff, #f9f9f9);
}

.news-content p {
    margin: 8px 0 0 0;
    font-size: 1rem;
    font-weight: 600;
    color: #0A1E5C;
    line-height: 1.5;
}

.news-category.small {
    font-size: 0.75rem;
    padding: 4px 10px;
    margin-bottom: 8px;
    display: inline-block;
    background-color: #0A1E5C;
    color: white;
    border-radius: 4px;
    font-weight: 600;
    transition: all 0.2s ease;
    letter-spacing: 0.5px;
}

.news-item:hover .news-category.small {
    background-color: #C69749;
}

/* Sidebar widgets */
.sidebar-widget {
    background-color: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    transition: all 0.2s ease;
}

.sidebar-widget:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-left: 3px solid #C69749;
}

.sidebar-widget:hover .sidebar-title {
    color: #C69749;
}

.sidebar-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #0A1E5C;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #C69749;
}

/* Rankings */
.rankings-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}

.ranking-tab {
    background-color: #0A1E5C;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 0 3px;
}

.ranking-tab.active {
    background-color: #0A1E5C;
    border: 1px solid #C69749;
    color: white;
}

.ranking-tab:hover {
    background-color: #C69749;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.rankings-table {
    overflow: hidden;
    border-radius: 5px;
}

.rankings-header {
    display: flex;
    background-color: #0A1E5C;
    color: white;
    padding: 10px 12px;
    font-weight: 600;
    font-size: 1.1rem;
}

.rankings-row {
    display: flex;
    padding: 10px 12px;
    font-size: 1.05rem;
    border-bottom: 1px solid #eaeaea;
}

.rankings-row:last-child {
    border-bottom: none;
}

.rankings-row:nth-child(odd) {
    background-color: rgba(10, 30, 92, 0.05);
}

.rankings-row:hover {
    background-color: rgba(198, 151, 73, 0.1);
}

.rankings-row:hover .team-name {
    color: #C69749;
    font-weight: 600;
}

.rank {
    flex: 0 0 30px;
    font-weight: 700;
}

.team-name {
    flex: 1;
    font-size: 1.05rem;
    padding-left: 10px;
}

.points {
    flex: 0 0 40px;
    text-align: right;
    font-weight: 700;
    font-size: 1.1rem;
}

/* Schedule */
.schedule-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}

.tab-btn {
    background-color: #0A1E5C;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 0 3px;
}

.tab-btn.active {
    background-color: #0A1E5C;
    border: 1px solid #C69749;
    color: white;
}

.tab-btn:hover {
    background-color: #C69749;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.schedule-row {
    padding: 10px;
    border-bottom: 1px solid #eaeaea;
}

.schedule-row:last-child {
    border-bottom: none;
}

.schedule-row:hover {
    background-color: rgba(198, 151, 73, 0.1);
}

.schedule-row:hover .match-teams {
    color: #C69749;
}

.match-info {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.match-time {
    font-weight: 800;
    color: #0A1E5C;
    margin-right: 15px;
    font-size: 1.1rem;
}

.match-teams {
    font-weight: 700;
    font-size: 1.05rem;
}

.match-meta {
    display: flex;
    justify-content: space-between;
    font-size: 1rem;
    color: #444;
    font-weight: 600;
    margin-top: 5px;
}

.match-league {
    font-weight: 700;
    color: #0A1E5C;
}

.match-channel {
    background-color: #C69749;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 700;
}

/* Hot news */
.hot-news {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.hot-news-item {
    background-color: #f5f5f5;
    border-radius: 5px;
    padding: 10px;
    position: relative;
    border-left: 3px solid #FF3A3A;
}

.hot-tag {
    background-color: #FF3A3A;
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    display: inline-block;
    margin-bottom: 5px;
}

.hot-news-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
    border-left-color: #C69749;
}

.hot-news-item:hover p {
    color: #C69749;
}

.hot-news-item p {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.4;
}

/* Ads */
.ads-container img {
    width: 100%;
    border-radius: 5px;
}

/* Video section */
.video-section {
    padding: 25px 20px;
    background-color: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    max-width: 1000px;
    margin: 0 auto 30px;
}

.video-header {
    margin-bottom: 25px;
    text-align: center;
}

.header-content {
    display: inline-block;
    position: relative;
}

.section-title-wrapper {
    position: relative;
    margin-bottom: 12px;
    text-align: center;
}

.section-title {
    margin-bottom: 8px;
    border-bottom: none;
    padding-bottom: 0;
    display: inline-block;
}

.title-decoration {
    height: 3px;
    background-color: #C69749;
    width: 80%;
    margin: 0 auto;
}

.view-all-link {
    color: #0A1E5C;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.4rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    padding: 8px 20px;
    border-radius: 20px;
    background-color: rgba(198, 151, 73, 0.1);
    border: 1px solid rgba(198, 151, 73, 0.3);
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    white-space: nowrap;
}

.view-all-link i {
    font-size: 1.5rem;
    margin-left: 8px;
}

.view-all-link:hover {
    background-color: #C69749;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(198, 151, 73, 0.3);
}

.view-all-link span {
    margin-right: 6px;
}

.video-container {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 0 45px;
}

.video-row {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin: 0 auto;
    width: 100%;
    max-width: 850px;
}

.video-item {
    flex: 1;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin: 0;
    width: 100%;
    max-width: none;
    position: relative;
}

.video-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.video-thumbnail {
    position: relative;
    cursor: pointer;
    overflow: hidden;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 60%);
    transition: all 0.3s ease;
}

.video-item:hover .video-overlay {
    background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 60%);
}

.video-thumbnail img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.video-item:hover .video-thumbnail img {
    transform: scale(1.08);
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(10, 30, 92, 0.7);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.9;
    transition: all 0.3s ease;
    z-index: 2;
}

.video-item:hover .play-button {
    background-color: rgba(198, 151, 73, 0.9);
    transform: translate(-50%, -50%) scale(1.15);
    box-shadow: 0 0 20px rgba(198, 151, 73, 0.6);
}

.play-button i {
    font-size: 24px;
    color: white;
}

.video-duration {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    font-size: 0.8rem;
    padding: 3px 8px;
    border-radius: 4px;
    z-index: 2;
}

.video-info {
    padding: 15px;
    position: relative;
}

.video-category {
    display: inline-block;
    background-color: #0A1E5C;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.video-item:hover .video-category {
    background-color: #C69749;
}

.video-info h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #0A1E5C;
    line-height: 1.4;
    transition: all 0.2s ease;
}

.video-item:hover .video-info h3 {
    color: #C69749;
}

/* Responsive cho layout video */
@media (max-width: 1200px) {
    .video-section {
        max-width: 90%;
    }
    
    .video-row {
        max-width: none;
        flex-wrap: wrap;
    }
    
    .video-item {
        flex: 0 0 calc(50% - 10px);
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .video-section {
        padding: 20px 15px;
    }
    
    .video-container {
        padding: 0 10px;
    }
    
    .video-row {
        flex-direction: column;
    }
    
    .video-item {
        flex: 0 0 100%;
    }
    
    .video-thumbnail img {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .video-section {
        padding: 15px 10px;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .view-all-link {
        font-size: 1.2rem;
        padding: 6px 16px;
        white-space: nowrap;
    }
    
    .view-all-link i {
        font-size: 1.3rem;
    }
    
    .video-thumbnail img {
        height: 180px;
    }
    
    .play-button {
        width: 40px;
        height: 40px;
    }
    
    .play-button i {
        font-size: 20px;
    }
}

.text-center {
    text-align: center;
}

.mt-3 {
    margin-top: 15px;
}

.view-more {
    text-align: center;
    margin-top: 15px;
    font-size: 1.2rem;
}

.view-more-link {
    color: #0A1E5C;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.3rem;
    transition: all 0.2s ease;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    background-color: rgba(198, 151, 73, 0.1);
}

.view-more-link:hover {
    color: #C69749;
    text-decoration: underline;
    background-color: rgba(198, 151, 73, 0.2);
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin: 30px auto;
    max-width: 900px;
}

.event-item {
    display: flex;
    align-items: flex-start;
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.03);
}

.event-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    border-left: 3px solid #C69749;
}

.event-date {
    background-color: #0A1E5C;
    color: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    min-width: 80px;
    margin-right: 20px;
    box-shadow: 0 4px 8px rgba(10, 30, 92, 0.2);
}

.event-day {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
}

.event-month {
    display: block;
    font-size: 0.9rem;
    margin-top: 8px;
    font-weight: 600;
}

.event-info h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #0A1E5C;
    margin: 0 0 8px 0;
}

.event-info p {
    font-size: 1rem;
    color: #555;
    margin: 0 0 12px 0;
    font-weight: 500;
}

.event-location {
    font-size: 0.95rem;
    color: #666;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.event-location i {
    margin-right: 8px;
    color: #C69749;
    font-size: 1.1rem;
}

/* Styles cho bảng xếp hạng nâng cao */
.rankings-settings {
    background-color: #f5f5f5;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 15px;
}

.rankings-settings h4 {
    font-size: 1.15rem;
    margin: 0 0 12px 0;
    color: #0A1E5C;
}

.settings-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.settings-options label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
    cursor: pointer;
}

.select-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
}

.select-wrapper select {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.save-btn {
    background-color: #0A1E5C;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.2s ease;
}

.save-btn:hover {
    background-color: #C69749;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.settings-btn {
    min-width: 34px;
    padding: 6px !important;
}

.rankings-table {
    margin-bottom: 10px;
}

.favorite-icon {
    color: #FFC107;
    margin-left: 8px;
    font-size: 0.8rem;
}

.user-favorite {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.user-favorite .team-name {
    font-weight: 700;
}

.team-stats {
    flex: 0 0 80px;
    text-align: center;
    font-size: 0.95rem;
}

.team-form {
    flex: 0 0 80px;
    display: flex;
    justify-content: center;
    gap: 3px;
}

.form-indicator {
    width: 14px;
    height: 14px;
    border-radius: 50%;
}

.form-indicator.W {
    background-color: #4CAF50;
}

.form-indicator.D {
    background-color: #FFC107;
}

.form-indicator.L {
    background-color: #F44336;
}

.loading-spinner {
    text-align: center;
    padding: 20px 0;
}

.spinner {
    width: 30px;
    height: 30px;
    border: 3px solid rgba(10, 30, 92, 0.2);
    border-top-color: #0A1E5C;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.no-data {
    padding: 20px;
    text-align: center;
    color: #666;
}

.refresh-btn {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 12px;
    margin-top: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.refresh-btn:hover {
    background-color: #e5e5e5;
}

.view-more {
    text-align: center;
    margin-top: 15px;
    font-size: 1.2rem;
}

.view-more-link {
    color: #0A1E5C;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.3rem;
    transition: all 0.2s ease;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    background-color: rgba(198, 151, 73, 0.1);
}

.view-more-link:hover {
    color: #C69749;
    text-decoration: underline;
    background-color: rgba(198, 151, 73, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .team-stats, .team-form {
        display: none;
    }
}

/* Media queries cần bổ sung */
@media (max-width: 768px) {
    .menu-item {
        margin: 5px;
        font-size: 0.9rem;
        padding: 6px 12px;
    }
    
    .news-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .news-title {
        font-size: 1.3rem;
    }
    
    .section-title {
        font-size: 1.4rem;
    }
    
    .events-grid {
        grid-template-columns: 1fr;
    }
    
    .team-name {
        font-size: 1rem;
    }
    
    .points {
        font-size: 1rem;
    }
    
    .sidebar-title {
        font-size: 1.3rem;
    }
    
    .play-button {
        width: 40px;
        height: 40px;
    }
    
    .play-button i {
        font-size: 20px;
    }
}

@media (max-width: 576px) {
    .news-grid {
        grid-template-columns: 1fr;
    }
}

/* News Section styling (tương tự video section) */
.news-section {
    padding: 25px 20px;
    background-color: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    max-width: 1000px;
    margin: 0 auto 30px;
}

.news-header {
    margin-bottom: 25px;
    text-align: center;
}

.news-container {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 0 45px;
}

.news-row {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin: 0 auto;
    width: 100%;
    max-width: 850px;
}

.news-item {
    flex: 1;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
}

.news-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.news-thumbnail {
    position: relative;
    cursor: pointer;
    overflow: hidden;
}

.news-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 60%);
    transition: all 0.3s ease;
}

.news-item:hover .news-overlay {
    background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 60%);
}

.news-thumbnail img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.news-item:hover .news-thumbnail img {
    transform: scale(1.08);
}

.news-info {
    padding: 15px;
    position: relative;
}

.news-category {
    display: inline-block;
    background-color: #0A1E5C;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.news-info h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #0A1E5C;
    line-height: 1.4;
    transition: all 0.2s ease;
}

.news-item:hover .news-category {
    background-color: #C69749;
}

.news-item:hover .news-info h3 {
    color: #C69749;
}

/* Responsive */
@media (max-width: 1200px) {
    .news-section {
        max-width: 90%;
    }
    
    .news-row {
        max-width: none;
        flex-wrap: wrap;
    }
    
    .news-item {
        flex: 0 0 calc(50% - 10px);
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .news-section {
        padding: 20px 15px;
    }
    
    .news-container {
        padding: 0 10px;
    }
    
    .news-row {
        flex-direction: column;
    }
    
    .news-item {
        flex: 0 0 100%;
    }
    
    .news-thumbnail img {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .news-section {
        padding: 15px 10px;
    }
}

/* Ẩn grid cũ */
.more-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.news-item img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform 0.5s ease;
    position: relative;
    z-index: 1;
}

.news-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.news-item:hover::before {
    opacity: 1;
}

/* Fix responsive cho more-news-grid */
@media (max-width: 768px) {
    .more-news-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .news-item img {
        height: 140px;
    }
}

@media (max-width: 576px) {
    .more-news-grid {
        grid-template-columns: 1fr;
    }
    
    .news-item img {
        height: 180px;
    }
}

.events-section {
    padding: 25px 20px;
    background-color: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    max-width: 1000px;
    margin: 0 auto 30px;
}

.events-header {
    margin-bottom: 25px;
    text-align: center;
}

.events-container {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 0 45px;
}

.events-row {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin: 0 auto;
    width: 100%;
    max-width: 850px;
}

.events-item {
    flex: 1;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
}

.events-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.events-thumbnail {
    position: relative;
    cursor: pointer;
    overflow: hidden;
}

.events-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 60%);
    transition: all 0.3s ease;
}

.events-item:hover .events-overlay {
    background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 60%);
}

.events-thumbnail img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.events-item:hover .events-thumbnail img {
    transform: scale(1.08);
}

.events-info {
    padding: 15px;
    position: relative;
}

.events-category {
    display: inline-block;
    background-color: #0A1E5C;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.events-info h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #0A1E5C;
    line-height: 1.4;
    transition: all 0.2s ease;
}

.events-item:hover .events-category {
    background-color: #C69749;
}

.events-item:hover .events-info h3 {
    color: #C69749;
}

/* Responsive */
@media (max-width: 1200px) {
    .events-section {
        max-width: 90%;
    }
    
    .events-row {
        max-width: none;
        flex-wrap: wrap;
    }
    
    .events-item {
        flex: 0 0 calc(50% - 10px);
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .events-section {
        padding: 20px 15px;
    }
    
    .events-container {
        padding: 0 10px;
    }
    
    .events-row {
        flex-direction: column;
    }
    
    .events-item {
        flex: 0 0 100%;
    }
    
    .events-thumbnail img {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .events-section {
        padding: 15px 10px;
    }
}

/* Ẩn grid cũ */
.more-events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.events-item img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform 0.5s ease;
    position: relative;
    z-index: 1;
}

.events-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.events-item:hover::before {
    opacity: 1;
}

/* Fix responsive cho more-events-grid */
@media (max-width: 768px) {
    .more-events-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .events-item img {
        height: 140px;
    }
}

@media (max-width: 576px) {
    .more-events-grid {
        grid-template-columns: 1fr;
    }
    
    .events-item img {
        height: 180px;
    }
}

.section-title-wrapper.center {
    text-align: center;
}
</style>