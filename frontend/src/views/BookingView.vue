<script lang="ts" setup>
import {onMounted, ref, watch} from 'vue';
import {useRoute} from 'vue-router';
import type {Yard} from '../stores/yard';
import axios from "axios";

const route = useRoute();
const yard_store = ref<Yard[]>([]);
const popular_yards = ref<Yard[]>([]);
const currentPage = ref(1);
const lastPage = ref(1);
const totalItems = ref(0);
const searchKeyword = ref('');

// Lấy từ khóa tìm kiếm từ URL nếu có
searchKeyword.value = route.query.tukhoa as string || '';

// Các biến lọc
const sortBy = ref('');
const priceSort = ref('');
const selectedYardType = ref('');
const selectedRating = ref(0);
const startTime = ref('');
const endTime = ref('');
const minPrice = ref('');
const maxPrice = ref('');
const selectedDate = ref('');
var yardTypes = ref<{id: number, Ten_loai: string}[]>([]);

const fetchyard = async () => {
  try {
    // Tạo URL với tham số tìm kiếm (nếu có)

    let apiUrl = `/api/san?page=${currentPage.value}`;

    // Thêm các tham số lọc vào URL
    if (searchKeyword.value) {
      apiUrl += `&tukhoa=${encodeURIComponent(searchKeyword.value)}`;
    }
    if (sortBy.value) {
      apiUrl += `&sap_xep=${encodeURIComponent(sortBy.value)}`;
    }
    if (priceSort.value) {
      apiUrl += `&gia=${encodeURIComponent(priceSort.value)}`;
    }
    if (selectedYardType.value) {
      apiUrl += `&loai_san=${encodeURIComponent(selectedYardType.value)}`;
    }
    if (selectedRating.value > 0) {
      apiUrl += `&danh_gia=${encodeURIComponent(selectedRating.value.toString())}`;
    }
    if (startTime.value && endTime.value) {
      apiUrl += `&thoi_gian_bat_dau=${encodeURIComponent(startTime.value)}&thoi_gian_ket_thuc=${encodeURIComponent(endTime.value)}`;
    }
    if (minPrice.value) {
      apiUrl += `&gia_min=${encodeURIComponent(minPrice.value)}`;
    }
    if (maxPrice.value) {
      apiUrl += `&gia_max=${encodeURIComponent(maxPrice.value)}`;
    }
    if (selectedDate.value) {
      apiUrl += `&ngay=${encodeURIComponent(selectedDate.value)}`;
    }

    console.log(apiUrl);

    const response = await axios.get(apiUrl);
    if (response.status !== 200) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const datas = response.data;
    if (datas.status == 'success') {
      yard_store.value = datas.data;
      currentPage.value = datas.pagination.current_page;
      lastPage.value = datas.pagination.last_page;
      totalItems.value = datas.pagination.total;
    }
  } catch (error) {
    console.error('Lỗi khi tải sân:', error);
  }
};

const fetchPopularYards = async () => {
  try {
    const response = await axios.get('/api/san-pho-bien');
    if (response.data.status === 'success') {
      popular_yards.value = response.data.data;
      console.log('Sân phổ biến:', popular_yards.value);
    }
  } catch (error) {
    console.error('Lỗi khi tải sân phổ biến:', error);
  }
};

const changePage = (page: number) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page;
    fetchyard();
  }
};

// Function to determine image source
const getImageSrc = (imagePath: string) => {
  if (!imagePath) return '';
  return imagePath.startsWith('http') ? imagePath : '/public/img/san/' + imagePath;
};

// Lọc theo thứ tự - không gọi API ngay
const handleSortChange = (event: Event) => {
  const target = event.target as HTMLSelectElement;
  sortBy.value = target.value;
};

// Lọc theo giá - không gọi API ngay
const handlePriceChange = (direction: string) => {
  priceSort.value = direction;
};

// Lọc theo loại sân - không gọi API ngay
const handleYardTypeChange = (type: string) => {
  selectedYardType.value = type;
};

// Lọc theo đánh giá - không gọi API ngay
const handleRatingChange = (rating: number) => {
  selectedRating.value = rating;
};

// Xử lý submit form tìm kiếm
const handleSearchSubmit = (event: Event) => {
  event.preventDefault();
  fetchyard();
};

// Áp dụng tất cả các bộ lọc
const applyFilters = () => {
  fetchyard();
};

// Đặt lại tất cả các bộ lọc
const resetFilters = () => {
  sortBy.value = '';
  priceSort.value = '';
  selectedYardType.value = '';
  selectedRating.value = 0;
  startTime.value = '';
  endTime.value = '';
  minPrice.value = '';
  maxPrice.value = '';
  selectedDate.value = '';
  fetchyard();
};

// Theo dõi thay đổi của tham số tìm kiếm trong URL
watch(() => route.query.tukhoa, (newValue) => {
  currentPage.value = parseInt(route.query.trang as string) || 1;
  searchKeyword.value = newValue as string || '';
  selectedYardType.value = (route.query.loai_san as string) || '';
  selectedDate.value = route.query.ngay as string || '';
  startTime.value = route.query.thoi_gian_bat_dau as string || '';
  endTime.value = route.query.thoi_gian_ket_thuc as string || '';

  fetchyard(); // Reset về trang 1 khi tìm kiếm mới
});

const fetchYardTypes = async () => {
  try {
    const response = await axios.get('/api/loai-san');
    if (response.status !== 200) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = response.data;
    if (data.status === 'success') {
      yardTypes.value = data.data;
    }
  } catch (error) {
    console.error('Lỗi khi tải danh sách loại sân:', error);
    // Sử dụng dữ liệu mặc định từ database
    yardTypes.value = [
      {id: 1, Ten_loai: "Sân Bóng Đá"},
      {id: 2, Ten_loai: "Sân Tennis"},
      {id: 3, Ten_loai: "Sân Golf"},
      {id: 4, Ten_loai: "Sân Cầu Lông"},
      {id: 5, Ten_loai: "Sân Bóng Bàn"},
      {id: 6, Ten_loai: "Sân trong nhà"},
      {id: 7, Ten_loai: "Sân ngoài trời"},
      {id: 10, Ten_loai: "Sân Pickleball"}
    ];
  }
};

onMounted(() => {
  currentPage.value = parseInt(route.query.trang as string) || 1;
  selectedYardType.value = (route.query.loai_san as string) || '';
  searchKeyword.value = route.query.tukhoa as string || '';
  selectedDate.value = route.query.ngay as string || '';
  startTime.value = route.query.thoi_gian_bat_dau as string || '';
  endTime.value = route.query.thoi_gian_ket_thuc as string || '';

  fetchyard();
  fetchPopularYards();
  fetchYardTypes();
});
</script>


<template>
  <main>
    <section class="head-sort py-4" style="background-color: var(--bg3);">
      <div class="container">
        <form class="custom-form d-flex justify-content-center w-100" style="box-shadow: 0 0 18px var(--shadow2);"
              @submit="handleSearchSubmit">
          <div class="w-100 row justify-content-center gx-0">
            <div class="col-12 col-lg-2 col-md-12 m-1 m-lg-2 p-2 p-lg-0 py-3">
              <input v-model="selectedDate" class="form-date d-block w-100" style="padding: 18px 12px;" type="date">
            </div>
            <div class="col-12 col-lg-2 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
              <select v-model="selectedYardType" aria-label="Default select example" class="form-select form-date">
                <option selected value="">Tất cả loại sân</option>
                <option value="1">Sân bóng đá</option>
                <option value="4">Sân cầu lông</option>
                <option value="3">Sân golf</option>
                <option value="2">Sân tennis</option>
                <option value="5">Sân bóng bàn</option>
                <option value="10">Sân pickleball</option>
              </select>
            </div>
            <div class="col-12 col-lg-5 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
              <input v-model="searchKeyword" class="d-block w-100 form-date" placeholder="Bạn đang nghĩ gì?"
                     style="padding: 18px 12px;"
                     type="text">
            </div>
            <div class="d-flex align-items-center col-12 col-lg-2 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
              <button class="btn-find" type="submit">Tìm kiếm</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section class="booking">
      <div class="container">
        <div class="form-hidden">
          <h3 class="aside-title text-white text-start p-4 m-0">BỘ LỌC</h3>
          <form class="d-flex justify-content-between gap-3 p-4"
                style="border: 1px solid var(--colortext2); border-radius: 0 0 12px 12px;">
            <select v-model="sortBy" class="form-date w-100 mt-2 select-sort">
              <option value="">Phổ biến</option>
              <option value="moi_nhat">Mới nhất</option>
              <option value="cu_nhat">Cũ nhất</option>
            </select>

            <select v-model="priceSort" class="form-date w-100 mt-2 select-sort">
              <option value="">Giá</option>
              <option value="cao_thap">Cao - Thấp</option>
              <option value="thap_cao">Thấp - Cao</option>
            </select>

            <select v-model="selectedYardType" class="form-date w-100 mt-2 select-sort">
              <option value="">Bộ môn</option>
              <option value="1">Bóng đá</option>
              <option value="4">Cầu lông</option>
              <option value="3">Golf</option>
              <option value="2">Tennis</option>
              <option value="5">Bóng bàn</option>
              <option value="10">Pickleball</option>
            </select>

            <select v-model="selectedRating" class="form-date w-100 mt-2 select-sort">
              <option selected value="0">Đánh giá</option>
              <option value="5">5 &#9733;</option>
              <option value="4">4 &#9733;</option>
              <option value="3">3 &#9733;</option>
              <option value="2">2 &#9733;</option>
              <option value="1">1 &#9733;</option>
            </select>

            <div class="d-flex w-100 gap-2 mt-2">
              <input v-model="startTime" class="form-date w-50" placeholder="Từ" type="time">
              <input v-model="endTime" class="form-date w-50" placeholder="Đến" type="time">
            </div>

            <div class="d-flex w-100 gap-2 mt-2">
              <button class="btn btn-primary w-50" type="button" @click="applyFilters">Áp dụng</button>
              <button class="btn btn-secondary w-50" type="button" @click="resetFilters">Đặt lại</button>
            </div>
          </form>
        </div>
        <div class="row gx-0">
          <aside class="col-12 col-lg-3 col-md-6 p-0 mt-2 sticky-aside">
            <div class="me-3 sticky-aside-div">
              <h3 class="aside-title text-white text-start p-4 m-0">HIỂN THỊ THEO</h3>
              <form class="aside-container p-4">
                <select v-model="sortBy" class="form-date w-100 mt-2 select-sort">
                  <option value="">Phổ biến</option>
                  <option value="moi_nhat">Mới nhất</option>
                  <option value="cu_nhat">Cũ nhất</option>
                  <option value="bestseller">Nổi bật nhất</option>
                </select>

                <hr class="line">
                <h3 class="section-title mt-3 text-start">Giá</h3>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="low-to-high" :checked="priceSort === 'thap_cao'" class="form-check-input" name="price"
                         type="radio" @change="() => handlePriceChange('thap_cao')">
                  <label class="form-check-label" for="low-to-high">Thấp - Cao</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="high-to-low" :checked="priceSort === 'cao_thap'" class="form-check-input" name="price"
                         type="radio" @change="() => handlePriceChange('cao_thap')">
                  <label class="form-check-label" for="high-to-low">Cao - Thấp</label>
                </div>
                <div class="d-flex mt-4">
                  <input v-model="minPrice" class="form-control rounded-4 p-3 me-2" placeholder="Từ"
                         type="text">
                  <input v-model="maxPrice" class="form-control rounded-4 p-3" placeholder="Đến"
                         type="text">
                </div>

                <hr class="line">
                <h3 class="section-title mt-3 text-start">Đánh giá</h3>
                <div class="rating">
                  <div class="form-check d-flex align-items-center gap-3">
                    <input id="rating-5" :checked="selectedRating === 5" class="form-check-input" name="rating"
                           type="radio" @change="() => handleRatingChange(5)">
                    <label class="form-check-label star-label" for="rating-5">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input id="rating-4" :checked="selectedRating === 4" class="form-check-input" name="rating"
                           type="radio" @change="() => handleRatingChange(4)">
                    <label class="form-check-label star-label" for="rating-4">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input id="rating-3" :checked="selectedRating === 3" class="form-check-input" name="rating"
                           type="radio" @change="() => handleRatingChange(3)">
                    <label class="form-check-label star-label" for="rating-3">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input id="rating-2" :checked="selectedRating === 2" class="form-check-input" name="rating"
                           type="radio" @change="() => handleRatingChange(2)">
                    <label class="form-check-label star-label" for="rating-2">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input id="rating-1" :checked="selectedRating === 1" class="form-check-input" name="rating"
                           type="radio" @change="() => handleRatingChange(1)">
                    <label class="form-check-label star-label" for="rating-1">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                    </label>
                  </div>
                </div>

                <hr class="line">
                <h3 class="section-title mt-3 text-start">Bộ môn</h3>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="bongda" :checked="selectedYardType === '1'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('1')">
                  <label class="form-check-label" for="bongda">Bóng đá</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="caulong" :checked="selectedYardType === '4'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('4')">
                  <label class="form-check-label" for="caulong">Cầu lông</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="tennis" :checked="selectedYardType === '2'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('2')">
                  <label class="form-check-label" for="tennis">Tennis</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="golf" :checked="selectedYardType === '3'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('3')">
                  <label class="form-check-label" for="golf">Golf</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="bongban" :checked="selectedYardType === '5'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('5')">
                  <label class="form-check-label" for="bongban">Bóng bàn</label>
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input id="pickleball" :checked="selectedYardType === '10'" class="form-check-input" name="yard"
                         type="radio" @change="() => handleYardTypeChange('10')">
                  <label class="form-check-label" for="pickleball">Pickleball</label>
                </div>

                <hr class="line">
                <h3 class="section-title mt-3 text-start">Khung giờ</h3>
                <div class="d-flex mt-4">
                  <input v-model="startTime" class="form-control displayAPM fs-4 rounded-4 p-3 me-2"
                         placeholder="Bắt đầu" type="time">
                  <input v-model="endTime" class="form-control displayAPM fs-4 rounded-4 p-3"
                         placeholder="Kết thúc" type="time">
                </div>

                <!-- Thêm 2 nút Áp dụng và Đặt lại -->
                <div class="d-flex mt-4 gap-2">
                  <button class="btn btn-primary w-50 py-2 fs-5 rounded-4" type="button" @click="applyFilters">
                    Áp dụng
                  </button>
                  <button class="btn btn-secondary w-50 py-2 fs-5 rounded-4" type="button" @click="resetFilters">
                    Đặt lại
                  </button>
                </div>
              </form>
            </div>
          </aside>
          <div class="col-lg-9">
            <div v-if="searchKeyword" class="alert alert-info fs-4 m-3">
              Kết quả tìm kiếm cho: <strong>{{ searchKeyword }}</strong>
            </div>
            <p class="fs-4 m-3" style="color: var(--colortext2)">Hiển thị {{ yard_store.length }} trong tổng số {{ totalItems }} sản phẩm</p>
            <div class="row gx-0">
              <template v-if="yard_store.length > 0">
                <div v-for="yard in yard_store" :key="yard.id" class="col-12 col-lg-4 col-md-6 p-0">
                  <div class="yard">
                    <a :href="`/san/${yard.id}`" class="link-img-p">
                      <img :alt="yard.Ten_san" :src="getImageSrc(yard.Hinh_anh)"
                           style="width: 100%; border-radius: 12px 12px 0 0;">
                    </a>
                    <div class="yard-infor">
                      <div class="yard-infor-content">
                        <a :href="`/san/${yard.id}`" class="m-0 title-product fs-3">{{ yard.Ten_san }}</a>
                        <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                          <i v-for="star in 5" :key="star"
                             :class="{'text-warning': star <= (yard.diem_danh_gia ?? 0)}"
                             class="bi bi-star-fill color-star fs-5">
                          </i>
                          <p class="text-rating m-0 ms-3">({{ yard.diem_danh_gia ?? 0 }}/5)</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-location pt-3 pb-2">
                          <i class="bi bi-geo-alt"></i>
                          <p class="m-0">{{ yard.Dia_chi }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-location">
                          <i class="bi bi-door-open"></i>
                          <p class="custom-open m-0">{{ yard.Trang_thai === 1 ? 'Đang mở cửa' : 'Đóng cửa' }}</p>
                        </div>
                        <a :href="`/san/${yard.id}`" class="btn-booknow">Đặt ngay</a>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template v-else>
                <div class="col-12 text-center py-5">
                  <p class="fs-4">Không tìm thấy kết quả phù hợp</p>
                </div>
              </template>
            </div>
            <div class="d-flex justify-content-center align-items-end gap-2 mt-4">
              <a class="btn-pagination" href="#" @click.prevent="changePage(currentPage - 1)">
                <i class="bi bi-chevron-left fs-4"></i>
              </a>
              <template v-for="page in lastPage" :key="page">
                <a v-if="page === 1 || page === lastPage || (page >= currentPage - 2 && page <= currentPage + 2)"
                   :class="{ active: page === currentPage }"
                   class="btn-pagination"
                   href="#"
                   @click.prevent="changePage(page)">
                  {{ page }}
                </a>
                <span v-else-if="page === currentPage - 3 || page === currentPage + 3" class="fs-4">...</span>
              </template>
              <a class="btn-pagination" href="#" @click.prevent="changePage(currentPage + 1)">
                <i class="bi bi-chevron-right fs-4"></i>
              </a>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="popular-y mx-lg-auto mx-2">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section">ĐỀ XUẤT CHO BẠN</h1>
          <a class="seemore" href="">
            <div>Xem thêm</div>
            <i class="bi bi-caret-right-fill"></i>
          </a>
        </div>
        <div class="row gx-0 mt-5">
          <template v-if="popular_yards.length > 0">
            <div v-for="yard in popular_yards.slice(0, 4)" :key="yard.id" class="col-12 col-lg-3 col-md-6 p-0">
              <div class="yard">
                <a :href="`/san/${yard.id}`" class="link-img-p">
                  <img :alt="yard.Ten_san" :src="getImageSrc(yard.Hinh_anh)" style="width: 100%;">
                </a>
                <div class="yard-infor">
                  <div class="yard-infor-content">
                    <a :href="`/san/${yard.id}`" class="m-0 title-product fs-3">{{ yard.Ten_san }}</a>
                    <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                      <i v-for="star in 5" :key="star"
                         :class="{'text-warning': star <= (yard.diem_danh_gia ?? 0)}"
                         class="bi bi-star-fill color-star fs-4">
                      </i>
                      <p class="text-rating m-0 ms-3">({{ yard.diem_danh_gia ?? 0 }}/5)</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-location pt-3 pb-2">
                      <i class="bi bi-geo-alt"></i>
                      <p class="m-0">{{ yard.Dia_chi }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-location">
                      <i class="bi bi-door-open"></i>
                      <p class="custom-open m-0">{{ yard.Trang_thai === 1 ? 'Đang mở cửa' : 'Đóng cửa' }}</p>
                    </div>
                    <a :href="`/san/${yard.id}`" class="btn-booknow">Đặt ngay</a>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="col-12 text-center">
              <p>Đang tải dữ liệu...</p>
            </div>
          </template>
        </div>
      </div>
    </section>
  </main>
</template>

<style scoped>
.link-img-p {
  display: block;
  overflow: hidden;
  position: relative;
  border-radius: 12px 12px 0 0;
}

.link-img-p img {
  transition: all 0.5s ease;
  width: 100%;
}

.link-img-p:hover img {
  transform: scale(1.08);
}

.link-img-p::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0);
  transition: all 0.3s ease;
}

.link-img-p:hover::after {
  background: rgba(0, 0, 0, 0.2);
}

.popular-y .link-img-p {
  border-radius: 12px 12px 0 0;
}

.text-warning {
  color: #ffc107 !important;
}
</style>