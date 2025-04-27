<script lang="ts" setup>
import {onMounted, ref} from 'vue';
import type {Product} from '../stores/product';
import type {Yard} from '../stores/yard';
const apiURL = import.meta.env.VITE_API_URL as string;

const product_store = ref<Product[]>([]);
const yard_store = ref<Yard[]>([]);
const yardType = ref('');
const timeRange = ref('');
const playDate = ref('');
import axios from 'axios';


const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/san-pham-pho-bien');
    if (response.data.status === 'success') {
      product_store.value = response.data.data;
    }
  } catch (error) {
    console.error('Lỗi khi tải sản phẩm:', error);
  }
};


onMounted(() => {
  fetchProducts();
});


const fetchyard = async () => {
  try {
    const response = await axios.get('/api/san-pho-bien');
    if (response.data.status === 'success') {
      yard_store.value = response.data.data;
    }
  } catch (error) {
    console.error('Lỗi khi tải sân:', error);
  }
};

const submitForm = () => {
  var times = timeRange.value.split(" - ");
  var thoi_gian_bat_dau = times[0].trim();
  var thoi_gian_ket_thuc = times[1] ? times[1].trim() : "";

  const queryParams = new URLSearchParams({
    loai_san: yardType.value,
    thoi_gian_bat_dau: thoi_gian_bat_dau,
    thoi_gian_ket_thuc: thoi_gian_ket_thuc,
    ngay: playDate.value
  });
  window.location.href = `/booking?${queryParams.toString()}`;
};


onMounted(() => {
  fetchyard();
});


</script>

<template>
  <main>
    <section class="banner">
      <div class="bg-fit-width">
        <div class="banner-box px-3 ">
          <div class="container">
            <h1 class="heading1 ">TÌM KIẾM SÂN NHANH - CHƠI NGAY!</h1>
            <div>
              <div class="d-flex justify-content-center gap-2 custom-star py-4">
                <i class="bi bi-star-fill color-star"></i>
                <i class="bi bi-star-fill color-star"></i>
                <i class="bi bi-star-fill color-star"></i>
                <i class="bi bi-star-fill color-star"></i>
                <i class="bi bi-star-fill color-star"></i>
              </div>
            </div>
            <div class="d-flex justify-content-center">
              <p class="text-intro">Hệ thống đặt sân thông minh giúp bạn tìm được sân, đặt sân nhanh chóng với mức giá
                hợp lí.</p>
            </div>
            <div class="d-flex justify-content-center">
              <form class="custom-form d-flex justify-content-center p-5 p-lg-4" @submit.prevent="submitForm">
                <div class="w-100 row justify-content-center">
                  <div class="col-12 col-lg-3 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
                    <select v-model="yardType" aria-label="Default select example" class="form-date d-block w-100">
                      <option value="" disabled selected>Chọn loại sân</option>
                      <option value="1">Sân bóng đá</option>
                      <option value="2">Sân Tennis</option>
                      <option value="3">Sân Golf</option>
                      <option value="4">Sân Cầu Lông</option>
                      <option value="5">Sân Bóng Bàn</option>
                      <option value="6">Sân trong nhà</option>
                      <option value="7">Sân ngoài trời</option>
                      <option value="10">Sân Pickleball</option>
                    </select>
                  </div>
                  <div class="col-12 col-lg-3 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
                    <select v-model="timeRange"
                            aria-label="Default select example"
                            class="form-date d-block w-100">
                      <option value="" disabled selected>Chọn thời gian</option>
                      <option value="00:00 - 02:00">00:00 - 02:00</option>
                      <option value="02:00 - 04:00">02:00 - 04:00</option>
                      <option value="04:00 - 06:00">04:00 - 06:00</option>
                      <option value="06:00 - 08:00">06:00 - 08:00</option>
                      <option value="08:00 - 10:00">08:00 - 10:00</option>
                      <option value="10:00 - 12:00">10:00 - 12:00</option>
                      <option value="12:00 - 14:00">12:00 - 14:00</option>
                      <option value="14:00 - 16:00">14:00 - 16:00</option>
                      <option value="16:00 - 18:00">16:00 - 18:00</option>
                      <option value="18:00 - 20:00">18:00 - 20:00</option>
                      <option value="20:00 - 22:00">20:00 - 22:00</option>
                      <option value="22:00 - 00:00">22:00 - 00:00</option>
                    </select>
                  </div>
                  <div class="col-12 col-lg-3 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
                    <input v-model="playDate" class="d-block w-100 form-date" type="date"/>
                  </div>
                  <div class="d-flex align-items-center col-12 col-lg-2 col-md-12 m-1 m-lg-2 p-2 p-lg-0">
                    <button class="btn-find" type="submit">Tìm kiếm</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="introduce mx-lg-auto mx-2">
      <div>
        <div class="container">
          <div class="custom-intro">
            <div class="row justify-content-center gx-3 d-none d-lg-flex d-md-flex">
              <div class="col-2">
                <img alt="" class="img-fluid img-loa" src="../../public/img/loa.png">
              </div>
              <div class="col-8">
                <p class="pt-4 fs-sm-" style="font-size: 1.6rem; line-height: 2.4rem;">
                  Bạn đã sẵn sàng cho những trận đấu rực lửa nhưng chưa tìm được sân phù hợp? <br>
                  Hay đang tìm kiếm dụng cụ thể thao chất lượng? <br>
                  <b style="font-size: 1.6rem; color: var(--accent);">Keysport</b> là giải pháp hoàn hảo dành cho bạn!
                </p>
              </div>
            </div>
            <div class="d-flex justify-content-center">
              <div class="container-sm row justify-content-center gap-3">
                <div class="col-12 col-lg-3 col-md-4">
                  <a class="custom-intro-box" href="">
                    <div class="pt-1 pb-4 text-center">
                      <img alt="" src="../../public/img/booking.png" style="width: 30%;">
                    </div>
                    <p class="custom-intro-text">Đặt sân</p>
                  </a>
                </div>
                <div class="col-12 col-lg-3 col-md-4">
                  <a class="custom-intro-box" href="">
                    <div class="pt-1 pb-4 text-center">
                      <img alt="" src="../../public/img/giohang.png" style="width: 30%;">
                    </div>
                    <p class="custom-intro-text">Mua sắm</p>
                  </a>
                </div>
                <div class="col-12 col-lg-3 col-md-4">
                  <a class="custom-intro-box" href="">
                    <div class="pt-1 pb-4 text-center">
                      <img alt="" src="../../public/img/chusan.png" style="width: 30%;">
                    </div>
                    <p class="custom-intro-text">Cho chủ sân</p>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section class="typeyard mx-lg-auto mx-2">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section">CÁC SÂN THỂ THAO</h1>
          <a class="seemore" href="">
            <div>Sân khác</div>
            <i class="bi bi-caret-right-fill"></i>
          </a>
        </div>
        <div class="row gx-0">
          <div class="col-12 col-lg-6 p-0">
            <a class="custom-box" href="#">
              <img alt="" class="img-fluid" src="../../public/img/sanbongda.png">
              <p>Sân bóng đá</p>
            </a>
          </div>
          <div class="col-12 col-lg-6 p-0">
            <a class="custom-box" href="#">
              <img alt="" class="img-fluid" src="../../public/img/sancaulong.png">
              <p>Sân cầu lông</p>
            </a>
          </div>
        </div>
        <div class="row row gx-0">
          <div class="col-6 col-lg-4 p-0">
            <a class="custom-box" href="#">
              <img alt="" class="img-fluid" src="../../public/img/santenis.png">
              <p>Sân tennis</p>
            </a>
          </div>
          <div class="col-6 col-lg-4 p-0">
            <a class="custom-box" href="#">
              <img alt="" class="img-fluid" src="../../public/img/sanbongchuyen.png">
              <p>Sân bóng chuyền</p>
            </a>
          </div>
          <div class="col-6 col-lg-4 p-0">
            <a class="custom-box" href="#">
              <img alt="" class="img-fluid" src="../../public/img/sanpickkleball.png">
              <p>Sân pickkleball</p>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="popular-y mx-lg-auto mx-2">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section">SÂN PHỔ BIẾN</h1>
          <RouterLink class="seemore" to="/booking">
            <div>Xem thêm</div>
            <i class="bi bi-caret-right-fill"></i>
          </RouterLink>
        </div>
        <div class="row gx-0 mt-2">
          <template v-if="yard_store.length > 0">
            <div v-for="yard in yard_store" :key="yard.id" class="col-12 col-lg-3 col-md-6 p-0">
              <div class="yard">
                <div>
                  <img :alt="yard.Ten_san" :src="'/public/img/san/' + yard.Hinh_anh" style="width: 100%;">
                </div>
                <div class="yard-infor">
                  <div class="yard-infor-content">
                    <a class="m-0 title-product" href="#">{{ yard.Ten_san }}</a>
                    <div class="d-flex align-items-center gap-2 py-2">
                      <i class="bi bi-star-fill color-star fs-4"></i>
                      <i class="bi bi-star-fill color-star fs-4"></i>
                      <i class="bi bi-star-fill color-star fs-4"></i>
                      <i class="bi bi-star-fill color-star fs-4"></i>
                      <i class="bi bi-star-fill color-star fs-4"></i>
                      <p class="text-rating m-0 ms-3">(4/5)</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-location pt-3 pb-2">
                      <i class="bi bi-geo-alt"></i>
                      <p class="m-0">{{ yard.Dia_chi }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-location">
                      <i class="bi bi-door-open"></i>
                      <p class="custom-open m-0">{{ yard.Trang_thai == 1 ? 'Đang mở cửa' : 'Đóng cửa' }}</p>
                    </div>
                    <a class="btn-booknow" href="#">Đặt ngay</a>
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

    <section class="popular-y mx-lg-auto mx-2">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section">SẢN PHẨM BÁN CHẠY</h1>
          <RouterLink class="seemore" to="/sanpham">
            <div>Xem thêm</div>
            <i class="bi bi-caret-right-fill"></i>
          </RouterLink>
        </div>
        <div class="row gx-0">
          <div v-if="product_store.length > 0" class="row gx-0">
            <div v-for="product in product_store" :key="product.id" class="col-12 col-lg-3 col-md-6 p-0">
              <div class="product my-3">
                <a :href="`/sanpham/${product.id}`" class="link-img-p">
                  <img :alt="product.Ten_san_pham" :src="apiURL + '/' + product.Anh_dai_dien" class="img-fluid">
                </a>
                <div class="product-infor">
                  <a :href="`/sanpham/${product.id}`" class="m-0 title-product fs-3">{{ product.Ten_san_pham }}</a>
                  <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                    <p class="text-rating m-0 ms-3 fs-4">(4/5)</p>
                  </div>
                  <div class="text-location py-2">
                    <p class="m-0">{{ product.Mo_ta }}</p>
                  </div>
                  <div class="d-flex align-items-center gap-2 text-location">
                    <i class="bi bi-eye"></i>
                    <p class="custom-open m-0">Lượt xem: {{ product.view }}</p>
                  </div>
                  <div class="price d-flex align-items-end gap-4">
                    <p class="m-0">{{ product.Gia.toLocaleString('vi-VN') }}đ</p>
                  </div>
                </div>
                <div class="atc-love-box">
                  <a class="atc-love" href="#"><i class="bi bi-heart-fill"></i></a>
                  <a class="atc-love atc-icon" href="#"><i class="bi bi-cart-fill"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="col-12 text-center py-5">
            <p>Đang tải sản phẩm...</p>
          </div>
        </div>
      </div>
    </section>

    <section class="bookingquick mx-lg-auto mx-2">
      <div class="container">
        <h1 class="title-section text-white px-5 pb-3 px-lg-1">ĐẶT SÂN NHANH!</h1>
        <div class="row justify-content-between">
          <div class="col-lg-4 col-12 p-0">
            <div class="yard-infor-map px-5 px-lg-2">
              <div class="map">
                <iframe
                    height="250"
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4929551446676!2d106.67072607480473!3d10.773505889375087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752eded87204d3%3A0x6da06c380a63ab78!2zMjM4IMSQLiAzIFRow6FuZyAyLCBQaMaw4budbmcgMTIsIFF14bqtbiAxMCwgSOG7kyBDaMOtIE1pbmggNzAwMDAsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1740630108057!5m2!1svi!2s" style="border:0; border-radius: 12px;"
                    width="100%"></iframe>
              </div>
              <p class="yardname">Sân cầu lông Kỳ Hòa</p>
              <div class="map-icon d-flex align-items-baseline gap-2 py-1">
                <i class="bi bi-geo-alt"></i>
                <p>Số 238 Đường 3 Tháng 2, Phường 12, Quận 10, Tp.HCM</p>
              </div>
              <div class="map-icon d-flex align-items-baseline gap-2 py-1">
                <i class="bi bi-door-open"></i>
                <p class="fw-medium">Mở cửa: 24/24</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-12 p-0">
            <div class="yard-infor-map px-5 px-lg-2">
              <div class="timeplay d-flex justify-content-between align-items-center">
                <div class="dateplay" style="width: 49%;">
                  <input id="dateplay" class="form-control form-date custom-input-booking py-3"
                         name="dateplay" style="color-scheme: dark;" type="date">
                </div>
                <div class="dateplay" style="width: 49%;">
                  <select id="" class="form-date custom-input-booking" name="timeplay" style="padding: 11px 8px;">
                    <option class="fs-4" value="sancaulong">Sân cầu lông</option>
                    <option class="fs-4" value="bongda">Sân bóng đá</option>
                    <option class="fs-4" value="bongchuyen">Sân bóng chuyển</option>
                    <option class="fs-4" value="bongro">Sân bóng rổ</option>
                    <option class="fs-4" value="tennis">Sân tennis</option>
                    <option class="fs-4" value="pickkleball">Sân pickkleball</option>
                    <option class="fs-4" value="hoboi">Hồ bơi</option>
                  </select>
                </div>
              </div>
              <h3 class="yardname mt-5">Khung giờ</h3>
              <div class="d-flex flex-wrap">
                <button class="btn choose-timeframe" type="button">0:00 - 2:00</button>
                <button class="btn choose-timeframe" type="button">2:00 - 4:00</button>
                <button class="btn choose-timeframe" type="button">4:00 - 6:00</button>
                <button class="btn choose-timeframe" type="button">6:00 - 8:00</button>
                <button class="btn choose-timeframe" type="button">8:00 - 10:00</button>
                <button class="btn choose-timeframe" type="button">10:00 - 12:00</button>
                <button class="btn choose-timeframe" type="button">12:00 - 14:00</button>
                <button class="btn choose-timeframe" type="button">14:00 - 16:00</button>
                <button class="btn choose-timeframe" type="button">16:00 - 18:00</button>
                <button class="btn choose-timeframe" type="button">18:00 - 20:00</button>
                <button class="btn choose-timeframe" type="button">20:00 - 22:00</button>
                <button class="btn choose-timeframe" type="button">22:00 - 00:00</button>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-12 p-0">
            <form class="yard-infor-map px-5 px-lg-2">
              <h3 class="yardname ps-0">Thông tin người đặt</h3>
              <div class="py-2">
                <input class="form-control-lg custom-input-booking" name="name" placeholder="Họ tên" style="width: 93%;"
                       type="text">
              </div>
              <div class="py-2">
                <input class="form-control-lg custom-input-booking" name="phone" placeholder="Số điện thoại" style="width: 93%;"
                       type="text">
              </div>
              <div class="py-4 d-flex justify-content-end">
                <button class="btn-find" style="width: fit-content; padding: 17px 36px;" type="submit">Đặt sân</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section class="news mx-lg-auto mx-2">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section px-3 px-lg-2">TIN TỨC NỔI BẬC</h1>
        </div>
        <div class="row gx-0">
          <div class="col-12 col-lg-8 col-md-8">
            <div class="newsmean px-3 py-2 px-lg-3">
              <div class="newafter">
                <img alt="" class="img-fluid" src="../../public/img/new1.png">
                <div class="new-content">
                  <h3 class="newtitle">Nguyễn Thùy Linh thua số 1 thế giới, dừng bước tại Malaysia Open 2025</h3>
                  <p class="newdesc p-0">
                    (Kết quả cầu lông) - Nguyễn Thùy Linh đã không thể tạo nên bất ngờ trước tay vợt số 1 thế giới,
                    An Se-young ở vòng 1/8 giải cầu lông Super 1000 Malaysia Open 2025. Thùy Linh (29/10/1994) đã thua
                    số 1 thế giới tại Malaysia Open 2025 với điểm thua 95.18%.
                  </p>
                  <a class="btn-booknow" href="#">Đọc tiếp</a>
                </div>
              </div>

            </div>
          </div>
          <div class="col-12 col-lg-4">
            <div class="newmore-scroll">
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="newmore px-3 py-2 px-lg-3">
                <div class="row flex-nowrap">
                  <div class="col-4 pe-0"><img alt="" class="img-fluid newmore-img" src="../../public/img/new2.png">
                  </div>
                  <div class="newmore-content col-7">
                    <h3 class="newmore-title">Trương Vinh Hiển vô địch đơn nam giải pickleball Quảng Ngãi Open 2024</h3>
                    <p class="newmore-desc">
                      Trận chung kết nội dung đơn nam chuyên nghiệp giải OB Pickleball Open 2024 kết thúc chiều 22.12
                      tại Quảng Ngãi,
                      Trương Vinh Hiển đã chiến thắng 2-0 (11-6, 11-0) trước tay vợt số 1 Việt Nam Lý Hoàng Nam để lên
                      ngôi vô địch.
                    </p>
                    <div class="d-flex align-items-center gap-4 pt-2">
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-eye"></i>
                        <p class="custom-open m-0">10</p>
                      </div>
                      <div class="d-flex align-items-center gap-2 text-location pt-2">
                        <i class="bi bi-share"></i>
                        <p class="custom-open m-0">3</p>
                      </div>
                      <a class="btn-newmore" href="#">Xem thêm</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="rating-comment">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
          <h1 class="title-section">ĐÁNH GIÁ</h1>
          <a class="seemore" href="">
            <div>Xem thêm</div>
            <i class="bi bi-caret-right-fill"></i>
          </a>
        </div>
        <div class="row gx-0">
          <div class="col-12 col-lg-4 col-md-3 p-0">
            <div class="px-2">
              <div class="rating-user text-center p-3 py-4 my-2">
                <div class="py-4">
                  <img alt="" src="../../public/img/user1.jpg">
                </div>
                <h2 class="title-product">Han Truong</h2>
                <p class="fs-4" style="color: var(--colortext2);">Kinh doanh</p>
                <div class="d-flex justify-content-center">
                  <p class="w-75 fs-4" style="color: var(--colortext1); line-height: 2.2rem;">
                    "Gói combo tiện lợi, tiết kiệm chi phí. Đặt sân dễ dàng, nước uống luôn có sẵn trong tủ lạnh mát
                    lạnh."
                  </p>
                </div>
                <div class="d-flex justify-content-center">
                  <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                    <p class="text-rating m-0 ms-3 fs-3 fw-semibold" style="color: var(--colortext1);">4.0</p>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4 col-md-3 p-0">
            <div class="px-2">
              <div class="rating-user text-center p-3 py-4 my-2">
                <div class="py-4">
                  <img alt="" src="../../public/img/user1.jpg">
                </div>
                <h2 class="title-product">Han Truong</h2>
                <p class="fs-4" style="color: var(--colortext2);">Kinh doanh</p>
                <div class="d-flex justify-content-center">
                  <p class="w-75 fs-4" style="color: var(--colortext1); line-height: 2.2rem;">
                    "Gói combo tiện lợi, tiết kiệm chi phí. Đặt sân dễ dàng, nước uống luôn có sẵn trong tủ lạnh mát
                    lạnh."
                  </p>
                </div>
                <div class="d-flex justify-content-center">
                  <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                    <p class="text-rating m-0 ms-3 fs-3 fw-semibold" style="color: var(--colortext1);">4.0</p>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4 col-md-3 p-0">
            <div class="px-2">
              <div class="rating-user text-center p-3 py-4 my-2">
                <div class="py-4">
                  <img alt="" src="../../public/img/user1.jpg">
                </div>
                <h2 class="title-product">Han Truong</h2>
                <p class="fs-4" style="color: var(--colortext2);">Kinh doanh</p>
                <div class="d-flex justify-content-center">
                  <p class="w-75 fs-4" style="color: var(--colortext1); line-height: 2.2rem;">
                    "Gói combo tiện lợi, tiết kiệm chi phí. Đặt sân dễ dàng, nước uống luôn có sẵn trong tủ lạnh mát
                    lạnh."
                  </p>
                </div>
                <div class="d-flex justify-content-center">
                  <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill color-star fs-5"></i>
                    <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                    <p class="text-rating m-0 ms-3 fs-3 fw-semibold" style="color: var(--colortext1);">4.0</p>
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
