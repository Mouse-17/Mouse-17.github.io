<script lang="ts" setup>
import {onMounted, ref} from 'vue';
import {useRoute} from 'vue-router';
import type {Yard} from '../stores/yard';
import axios from "axios";
import Swal from 'sweetalert2';

const route = useRoute();
const yard_store = ref<Yard | null>(null);
const popular_yards = ref<Yard[]>([]);
const quantity = ref(1);
const activeTab = ref('description'); // 'description' or 'reviews'
const ratingCounts = ref<number[]>([0, 0, 0, 0, 0]); // Số lượng đánh giá cho các mức 1-5 sao
const totalRatings = ref(0); // Tổng số đánh giá
const averageRating = ref(0); // Điểm đánh giá trung bình
const timeSlots = ref([]); // Danh sách khung giờ từ CSDL

// Biến để hiển thị form đặt sân
const showBookingForm = ref(false);
const selectedDate = ref('');
const selectedSlot = ref(null);
const customerName = ref('');
const customerEmail = ref('');
const customerPhone = ref('');
const bookingNote = ref('');
const isProcessing = ref(false);
const bookingSuccess = ref(false);
const bookingError = ref('');
const paymentMethod = ref('1'); // Mặc định là thanh toán tại sân

// Đặt ngày mặc định là ngày hiện tại
const today = new Date();
const yyyy = today.getFullYear();
const mm = String(today.getMonth() + 1).padStart(2, '0');
const dd = String(today.getDate()).padStart(2, '0');
selectedDate.value = `${yyyy}-${mm}-${dd}`;

const handleTabClick = (tab: string) => {
  activeTab.value = tab;
};

// Function to determine image source
const getImageSrc = (imagePath: string) => {
  if (!imagePath) return '';
  return imagePath.startsWith('http') ? imagePath : '/public/img/san/' + imagePath;
};

// Format giờ từ chuỗi thời gian
const formatTime = (timeString: string) => {
  if (!timeString) return '';
  const timeParts = timeString.split(':');
  return `${timeParts[0]}:${timeParts[1]}`;
};

// Format giá tiền
const formatPrice = (price: number) => {
  return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
};

const fetchYard = async () => {
  try {
    const response = await axios.get(`/api/san/${route.params.id}`);
    if (response.data.status === 'success') {
      yard_store.value = response.data.data;
      console.log('Sân:', yard_store.value);

      // Get ratings right after receiving the yard info
      await fetchYardRatings(route.params.id);
    }
  } catch (error) {
    console.error('Lỗi khi tải sân:', error);
  }
};

// Thêm hàm riêng để lấy đánh giá sân
const fetchYardRatings = async (yardId) => {
  try {
    // Use axios.get to retrieve yard ratings
    const response = await axios.get(`/api/san/${yardId}/danh-gia`);
    console.log('API đánh giá sân trả về:', response.data);

    if (response.data.status === 'success') {
      // Reset initial values
      ratingCounts.value = [0, 0, 0, 0, 0];
      totalRatings.value = 0;
      averageRating.value = 0;

      // Update average rating if available
      if (response.data.average) {
        averageRating.value = response.data.average;
      }

      // Assign detailed rating data if available
      if (Array.isArray(response.data.data) && response.data.data.length > 0) {
        if (yard_store.value) {
          yard_store.value.danh_gia = response.data.data;
        }
        // Count ratings for each star level
        response.data.data.forEach(rating => {
          const starValue = parseInt(rating.So_sao);
          if (starValue >= 1 && starValue <= 5) {
            ratingCounts.value[starValue - 1]++;
          }
        });
        // Update total ratings
        totalRatings.value = response.data.data.length;
      }
    } else {
      console.warn('API đánh giá sân trả về không thành công:', response.data);
    }
  } catch (error) {
    console.error('Lỗi khi tải đánh giá sân:', error);
    // Ensure UI displays data even if an error occurs
    if (yard_store.value && yard_store.value.diem_danh_gia) {
      averageRating.value = parseFloat(yard_store.value.diem_danh_gia) || 0;
    }
  }
};

// Tính phần trăm cho mỗi mức đánh giá
const getRatingPercentage = (starIndex: number) => {
  if (totalRatings.value === 0) return 0;
  return (ratingCounts.value[starIndex] / totalRatings.value) * 100;
};

// Lấy danh sách khung giờ từ API
const fetchTimeSlots = async () => {
  try {
    const response = await axios.get('/api/khung-gio');
    console.log('API khung giờ trả về:', response.data);
    if (response.data.status === 'success') {
      timeSlots.value = response.data.data;
    }
  } catch (error) {
    console.error('Lỗi khi tải khung giờ:', error);
  }
};
const fetchPopularYards = async () => {
  try {
    // Changed endpoint to '/api/san-pho-bien' as per the comment.
    const response = await axios.get('/api/san-pho-bien');
    if (response.data.status === 'success') {
      popular_yards.value = response.data.data;
      console.log('Sân phổ biến:', popular_yards.value);
    }
  } catch (error) {
    console.error('Lỗi khi tải sân phổ biến:', error);
  }
};

// Xử lý khi chọn một khung giờ
const selectTimeSlot = (slot) => {
  selectedSlot.value = slot;
};

// Hàm mở form đặt sân
const openBookingForm = () => {
  // Kiểm tra nếu người dùng đã chọn khung giờ
  if (!selectedSlot.value) {
    // Nếu chưa chọn, lấy khung giờ đầu tiên từ danh sách
    if (timeSlots.value && timeSlots.value.length > 0) {
      selectedSlot.value = timeSlots.value[0];
    }
  }
  showBookingForm.value = true;
};

// Hàm đóng form đặt sân
const closeBookingForm = () => {
  showBookingForm.value = false;
  bookingSuccess.value = false;
  bookingError.value = '';
};

// Hàm xử lý đặt sân
const submitBooking = async () => {
  // Validate form
  if (!customerName.value || !customerPhone.value || !selectedDate.value || !selectedSlot.value) {
    bookingError.value = 'Vui lòng điền đầy đủ thông tin đặt sân';
    return;
  }

  // Bắt đầu xử lý đặt sân
  isProcessing.value = true;
  bookingError.value = '';

  try {
    // Chuẩn bị dữ liệu gửi lên server
    const bookingData = {
      ID_San: yard_store.value?.id,
      ID_KH: 1, // Sử dụng ID người dùng hiện tại nếu đã đăng nhập
      Ten_KH: customerName.value,
      SDT: customerPhone.value,
      Email: customerEmail.value,
      Ngay_dat: selectedDate.value,
      Gio_bat_dau: selectedSlot.value.Gio_bat_dau,
      Gio_ket_thuc: selectedSlot.value.Gio_ket_thuc,
      Tong_tien: selectedSlot.value.Gia_thue,
      Ghi_chu: bookingNote.value,
      phuong_thuc_thanh_toan: parseInt(paymentMethod.value),
      Trang_thai: 1, // Trạng thái chờ phê duyệt (1 = chờ phê duyệt, 2 = đã phê duyệt, 0 = đã hủy)
    };

    console.log('Dữ liệu gửi đi:', bookingData);

    // Lấy token từ localStorage
    const authToken = localStorage.getItem('auth_token');

    // Gọi API để lưu thông tin đặt sân
    const response = await axios.post('/api/bookings', bookingData, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(authToken ? { Authorization: `Bearer ${authToken}` } : {}),
      },
    });

    // Kiểm tra phản hồi từ API
    const data = response.data.booking;
    console.log('Phản hồi API:', data);

    if (response.status === 201) {
      // Nếu thanh toán qua MoMo
      if (data.phuong_thuc_thanh_toan === 3) {
        try {
          // Gọi API MoMo để lấy URL thanh toán
          const momoResponse = await axios.post('https://momo-test.sililink.vn', {
            booking_id: data.booking_id, // ID đặt sân vừa tạo
            amount: data.Tong_tien, // Tổng tiền
            description: `Thanh toán đặt sân ${yard_store.value?.Ten_san} vào ${selectedDate.value}`,
          });

          if (momoResponse.data && momoResponse.data.payUrl) {
            // Redirect đến URL thanh toán MoMo
            window.location.href = momoResponse.data.payUrl;
            return; // Kết thúc xử lý tại đây
          } else {
            throw new Error('Không nhận được URL thanh toán từ MoMo');
          }
        } catch (momoError) {
          console.error('Lỗi khi xử lý thanh toán MoMo:', momoError);
          bookingError.value = 'Có lỗi xảy ra khi xử lý thanh toán MoMo. Vui lòng thử lại sau.';
        }
      } else {
        // Xử lý khi không phải thanh toán qua MoMo
        bookingSuccess.value = true;

        // Reset form fields
        customerName.value = '';
        customerEmail.value = '';
        customerPhone.value = '';
        bookingNote.value = '';

        // Show SweetAlert2 success message
        Swal.fire({
          title: 'Đặt sân thành công!',
          text: 'Bạn đã đặt sân thành công!',
          icon: 'success',
          confirmButtonText: 'OK',
        });
      }
    } else {
      // Xử lý lỗi khác từ server
      bookingError.value = data.message || 'Có lỗi xảy ra khi đặt sân. Vui lòng thử lại sau.';
    }
  } catch (error) {
    // Xử lý lỗi ngoại lệ
    if (axios.isAxiosError(error)) {
      if (error.response) {
        console.error('Lỗi từ server:', error.response.data);
        bookingError.value = error.response.data.message || 'Có lỗi xảy ra từ server. Vui lòng thử lại sau.';
      } else if (error.request) {
        console.error('Không nhận được phản hồi từ server:', error.request);
        bookingError.value = 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.';
      } else {
        console.error('Lỗi khi tạo yêu cầu:', error.message);
        bookingError.value = 'Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.';
      }
    } else {
      console.error('Lỗi không xác định:', error);
      bookingError.value = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
    }
  } finally {
    isProcessing.value = false;
  }
};
// Function to copy text to clipboard
const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text)
      .then(() => {
        alert('Đã sao chép vào clipboard!');
      })
      .catch(err => {
        console.error('Không thể sao chép: ', err);
      });
};

onMounted(() => {
  fetchYard();
  fetchPopularYards();
  fetchTimeSlots();
});
</script>

<template>
  <main v-if="yard_store">
    <section class="booking-detail">
      <div class="container">
        <div class="row gx-0">
          <div class="col-12 col-lg-6 p-0">
            <div class="px-lg-2 px-3">
              <div class="main-image-container">
                <img :alt="yard_store.Ten_san" :src="getImageSrc(yard_store.Hinh_anh)" class="rounded-4">
              </div>
              <div class="d-flex align-items-center gap-2 mt-2 overflow-hidden" style="width: 100%;">
                <div class="custom-thumnail__box">
                  <img :alt="yard_store.Ten_san" :src="getImageSrc(yard_store.Hinh_anh)" class="img-fluid">
                </div>
                <div class="custom-thumnail__box">
                  <img :alt="yard_store.Ten_san" :src="getImageSrc(yard_store.Hinh_anh)" class="img-fluid">
                </div>
                <div class="custom-thumnail__box">
                  <img :alt="yard_store.Ten_san" :src="getImageSrc(yard_store.Hinh_anh)" class="img-fluid">
                </div>
                <div class="custom-thumnail__box">
                  <img :alt="yard_store.Ten_san" :src="getImageSrc(yard_store.Hinh_anh)" class="img-fluid">
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-6 p-0">
            <div class="padding-custom ps-lg-5">
              <a class="category-bkdetail__link mt-2" href="#">SÂN CẦU LÔNG</a>
              <h1 class="title-section my-1" style="text-transform: none;">{{ yard_store.Ten_san }}</h1>
              <div class="d-flex align-items-center gap-2 mt-2 mb-4">
                <template v-for="star in 5" :key="star">
                  <i
                      :class="star <= averageRating ? 'color-star' : 'color-star-gray'"
                      class="bi bi-star-fill"
                  ></i>
                </template>
                <p class="m-0 me-1 fs-3 fw-bold" style="color: var(--colortext1);">{{ averageRating }}</p>
                <i class="bi bi-dot fs-2" style="color: var(--colortext2);"></i>
                <a class="m-0 fs-4 fw-regular fst-italic" href="#" style="color: var(--colortext3);"
                   @click.prevent="handleTabClick('reviews')">Đánh giá ({{ totalRatings }})</a>
              </div>
              <div class="d-flex align-items-center gap-4 my-2">
                <i class="bi bi-aspect-ratio fs-3 text-success"></i>
                <p class="m-0 fs-4 fw-regular" style="color: var(--colortext2);">
                  Quy mô:
                  <span class="fs-4 fw-regular"
                        style="color: var(--colortext1); line-height: 2.2rem;">{{ yard_store.So_luong }} sân</span>
                </p>
              </div>
              <div class="d-flex align-items-center gap-4 my-2">
                <i class="bi bi-door-open fs-3 text-success"></i>
                <p class="m-0 fs-4 fw-regular" style="color: var(--colortext2);">
                  Mở cửa:
                  <span class="fs-4 fw-regular" style="color: var(--colortext1); line-height: 2.2rem;">{{
                      yard_store.Trang_thai === 1 ? 'Đang mở cửa' : 'Đóng cửa'
                    }}</span>
                </p>
              </div>
              <div class="d-flex align-items-center gap-4 my-2">
                <i class="bi bi-geo-alt fs-3 text-success"></i>
                <p class="m-0 fs-4 fw-regular" style="color: var(--colortext2);">
                  Địa chỉ:
                  <span class="fs-4 fw-regular" style="color: var(--colortext1); line-height: 2.2rem;">
                                        {{ yard_store.Dia_chi }}
                                    </span>
                </p>
              </div>
              <hr class="my-4" style="color: var(--colortext3);">
              <div class="d-flex align-items-center gap-2">
                <div class="">
                  <label class="bk-detail__label" for="form-date">Ngày chơi</label>
                  <input id="form-date" v-model="selectedDate" :min="selectedDate" class="form-control form-date"
                         type="date">
                </div>
                <div>
                  <label class="bk-detail__label" for="form-timestart">Giờ bắt đầu</label>
                  <input id="" class="form-control form-date" disabled name="" placeholder="Giờ bắt đầu"
                         type="time" value="06:00:00">
                </div>
                <div>
                  <label class="bk-detail__label" for="form-timestart">Giờ kết thúc</label>
                  <input id="" class="form-control form-date" disabled name="" placeholder="Giờ bắt đầu"
                         type="time" value="08:00:00">
                </div>
              </div>
              <div class="mt-4 d-flex align-items-center gap-3">
                <i class="bi bi-tags fs-2 text-success"></i>
                <p class="m-0 fs-2 fw-bold" style="color: var(--colortext1);">Giá</p>
              </div>
              <div class="ps-5 pt-2 mb-5">
                <!-- Hiển thị giá theo khung giờ từ API -->
                <div v-if="timeSlots.length > 0">
                  <div v-for="slot in timeSlots" :key="slot.id"
                       :class="{ 'selected-slot': selectedSlot && selectedSlot.id === slot.id }"
                       class="d-flex align-items-center gap-5 my-3 timeslot-item"
                       @click="selectTimeSlot(slot)">
                                        <span class="fs-4 fw-regular" style="color: var(--colortext1);">
                                            {{ formatTime(slot.Gio_bat_dau) }} - {{ formatTime(slot.Gio_ket_thuc) }}
                                        </span>
                    <span class="fs-3 fw-bold" style="color: var(--accent);">{{ formatPrice(slot.Gia_thue) }}</span>
                  </div>
                </div>
                <!-- Hiển thị giá mặc định khi chưa có dữ liệu từ API -->
                <div v-else>
                  <div class="d-flex align-items-center gap-5 my-3">
                    <span class="fs-4 fw-regular" style="color: var(--colortext1);">06:00 - 16:00</span>
                    <span class="fs-3 fw-bold" style="color: var(--accent);">70.000đ</span>
                  </div>
                  <div class="d-flex align-items-center gap-5 my-3">
                    <span class="fs-4 fw-regular" style="color: var(--colortext1);">16:00 - 00:00</span>
                    <span class="fs-3 fw-bold" style="color: var(--accent);">170.000đ</span>
                  </div>
                  <div class="d-flex align-items-center gap-5 my-3">
                    <span class="fs-4 fw-regular" style="color: var(--colortext1);">00:00 - 06:00</span>
                    <span class="fs-3 fw-bold" style="color: var(--accent);">190.000đ</span>
                  </div>
                </div>
              </div>
              <button class="d-block btn-find" style="width: 32%; padding: 15px 0 17px 0;" @click="openBookingForm">Đặt
                ngay
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Form đặt sân -->
    <div v-if="showBookingForm" class="booking-form-modal">
      <div class="booking-form-container">
        <div class="booking-form-header">
          <h2 class="form-title">Đặt sân {{ yard_store.Ten_san }}</h2>
          <button class="close-btn" @click="closeBookingForm">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <div v-if="!bookingSuccess" class="booking-form-content">
          <div class="booking-form-info">
            <div class="booking-info-card">
              <div class="info-header">
                <i class="bi bi-calendar-check"></i>
                <span>Thông tin đặt sân</span>
              </div>
              <div class="info-content">
                <div class="info-item">
                  <i class="bi bi-geo-alt-fill"></i>
                  <div>
                    <strong>Sân:</strong>
                    <span>{{ yard_store.Ten_san }}</span>
                  </div>
                </div>
                <div class="info-item">
                  <i class="bi bi-calendar-date"></i>
                  <div>
                    <strong>Ngày đặt:</strong>
                    <span>{{ selectedDate }}</span>
                  </div>
                </div>
                <div v-if="selectedSlot" class="info-item">
                  <i class="bi bi-clock"></i>
                  <div>
                    <strong>Giờ chơi:</strong>
                    <span>{{ formatTime(selectedSlot.Gio_bat_dau) }} - {{ formatTime(selectedSlot.Gio_ket_thuc) }}</span>
                  </div>
                </div>
                <div v-if="selectedSlot" class="price-tag">
                  {{ formatPrice(selectedSlot.Gia_thue) }}
                </div>
              </div>
            </div>
          </div>

          <div v-if="bookingError" class="alert custom-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ bookingError }}
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="bi bi-person-fill"></i>
              Họ tên <span class="required">*</span>
            </label>
            <input v-model="customerName" class="form-control custom-input" placeholder="Nhập họ tên của bạn" type="text" />
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="bi bi-telephone-fill"></i>
              Số điện thoại <span class="required">*</span>
            </label>
            <input v-model="customerPhone" class="form-control custom-input" placeholder="Nhập số điện thoại" type="tel" />
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="bi bi-envelope-fill"></i>
              Email
            </label>
            <input v-model="customerEmail" class="form-control custom-input" placeholder="Nhập email của bạn" type="email" />
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="bi bi-sticky-fill"></i>
              Ghi chú
            </label>
            <textarea v-model="bookingNote" class="form-control custom-textarea" placeholder="Thêm ghi chú (nếu có)" rows="3"></textarea>
          </div>

          <div class="form-group payment-section">
            <label class="form-section-label">
              <i class="bi bi-credit-card-fill"></i>
              Phương thức thanh toán
            </label>

            <div class="payment-options">
              <div :class="{ 'active': paymentMethod == 2 }" class="payment-option" @click="paymentMethod = '2'">
                <input id="payment2" v-model="paymentMethod" class="form-check-input" name="payment" type="radio" value="2" />
                <div class="payment-icon">
                  <i class="bi bi-bank"></i>
                </div>
                <div class="payment-text">
                  <label class="payment-label" for="payment2">Chuyển khoản ngân hàng</label>
                  <span class="payment-description">Chuyển khoản trước để giữ sân</span>
                </div>
              </div>

              <div :class="{ 'active': paymentMethod == 3 }" class="payment-option" @click="paymentMethod = '3'">
                <input id="payment3" v-model="paymentMethod" class="form-check-input" name="payment" type="radio" value="3" />
                <div class="payment-icon">
                  <i class="bi bi-wallet2"></i>
                </div>
                <div class="payment-text">
                  <label class="payment-label" for="payment3">Thanh toán qua MoMo</label>
                  <span class="payment-description">Thanh toán nhanh qua ví MoMo</span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button class="cancel-btn" @click="closeBookingForm">Hủy</button>
            <button :disabled="isProcessing" class="confirm-btn" @click="submitBooking">
              <span v-if="isProcessing">
                <i class="bi bi-arrow-repeat"></i> Đang xử lý...
              </span>
                      <span v-else>
                <i class="bi bi-check-circle"></i> Xác nhận đặt sân
              </span>
            </button>
          </div>
        </div>

        <div v-else class="booking-success-content">
          <div class="success-animation">
            <div class="checkmark-circle">
              <div class="hourglass-animation">
                <i class="bi bi-hourglass-split"></i>
              </div>
            </div>
          </div>

          <div class="success-content">
            <h3 class="success-title">Đặt sân đang chờ phê duyệt!</h3>
            <p class="success-message">Cảm ơn bạn đã đặt sân. Thông tin đặt sân đã được gửi đến hệ thống.</p>
            <p class="contact-message">Chúng tôi sẽ liên hệ với bạn qua số điện thoại để xác nhận đơn đặt sân trong thời
              gian sớm nhất.</p>
          </div>

          <div class="booking-summary">
            <div class="summary-header">
              <i class="bi bi-journal-check"></i>
              <span>Chi tiết đặt sân</span>
            </div>

            <div class="summary-item">
              <div class="summary-icon">
                <i class="bi bi-geo-alt-fill"></i>
              </div>
              <div class="summary-content">
                <div class="summary-label">Sân</div>
                <div class="summary-value">{{ yard_store.Ten_san }}</div>
              </div>
            </div>
            <div class="summary-item">
              <div class="summary-icon">
                <i class="bi bi-calendar-date-fill"></i>
              </div>
              <div class="summary-content">
                <div class="summary-label">Ngày</div>
                <div class="summary-value">{{ selectedDate }}</div>
              </div>
            </div>
            <div v-if="selectedSlot" class="summary-item">
              <div class="summary-icon">
                <i class="bi bi-clock-fill"></i>
              </div>
              <div class="summary-content">
                <div class="summary-label">Thời gian</div>
                <div class="summary-value">{{ formatTime(selectedSlot.Gio_bat_dau) }} -
                  {{ formatTime(selectedSlot.Gio_ket_thuc) }}
                </div>
              </div>
            </div>
            <div v-if="selectedSlot" class="summary-item price-item">
              <div class="summary-icon">
                <i class="bi bi-cash-coin"></i>
              </div>
              <div class="summary-content">
                <div class="summary-label">Giá</div>
                <div class="summary-value price-value">{{ formatPrice(selectedSlot.Gia_thue) }}</div>
              </div>
            </div>

            <div class="next-steps">
              <div class="step">
                <div class="step-icon">1</div>
                <div class="step-content">
                  <h5 class="step-title">Chờ phê duyệt</h5>
                  <p class="step-description">Chủ sân sẽ xác nhận đơn đặt sân của bạn</p>
                </div>
              </div>
              <div class="step">
                <div class="step-icon">2</div>
                <div class="step-content">
                  <h5 class="step-title">Thanh toán</h5>
                  <p class="step-description">Thanh toán theo phương thức đã chọn</p>
                </div>
              </div>
              <div class="step">
                <div class="step-icon">3</div>
                <div class="step-content">
                  <h5 class="step-title">Đến sân</h5>
                  <p class="step-description">Đến sân đúng giờ và tận hưởng trận đấu</p>
                </div>
              </div>
            </div>
          </div>

          <button class="close-success-btn" @click="closeBookingForm">
            <i class="bi bi-check-circle"></i> Đóng
          </button>
        </div>
      </div>
    </div>

    <section class="information-detail">
      <div class="container">
        <div class="row gx-0">
          <div class="col-12 col-lg-7 p-lg-0">
            <div class="padding-custom">
              <h2 class="fs-1 text-start my-3">Thông tin chi tiết</h2>
              <div>
                <h3 class="fs-3 fw-semibold text-start">1. Giới thiệu {{ yard_store.Ten_san }}</h3>
                <ul class="mt-3">
                  <li style="color: var(--colortext2); font-size: 1.5rem; line-height: 2.4rem; text-align: justify;">
                    {{ yard_store.Mo_ta }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5 p-lg-0">
            <div class="bkdt__box-rating m-lg-5 m-2 me-lg-0 p-5">
              <div class="d-flex align-items-center justify-content-between gap-3">
                <h1 class="m-0 fs-1 fw-semibold">{{ averageRating }}</h1>
                <div>
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <template v-for="star in 5" :key="star">
                      <i :class="star <= averageRating ? 'color-star' : 'color-star-gray'"
                         class="bi bi-star-fill fs-4"></i>
                    </template>
                  </div>
                  <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3);">
                    {{ totalRatings }} đánh giá
                  </p>
                </div>
              </div>
              <hr style="color: var(--colortext3);">
              <div class="d-flex align-items-center justify-content-between my-1">
                <h3 style="width:3%;">5</h3>
                <i class="bi bi-star-fill fs-4 color-star"></i>
                <div class="bkdt__percent-rating">
                  <div :style="{ width: getRatingPercentage(4) + '%' }"
                       class="bkdt__percent-rating-child"></div>
                </div>
                <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3); width: 76px;">
                  {{ ratingCounts[4] }} đánh giá
                </p>
              </div>
              <div class="d-flex align-items-center justify-content-between my-1">
                <h3 style="width:3%;">4</h3>
                <i class="bi bi-star-fill fs-4 color-star"></i>
                <div class="bkdt__percent-rating">
                  <div :style="{ width: getRatingPercentage(3) + '%' }"
                       class="bkdt__percent-rating-child"></div>
                </div>
                <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3); width: 76px;">
                  {{ ratingCounts[3] }} đánh giá
                </p>
              </div>
              <div class="d-flex align-items-center justify-content-between my-1">
                <h3 style="width:3%;">3</h3>
                <i class="bi bi-star-fill fs-4 color-star"></i>
                <div class="bkdt__percent-rating">
                  <div :style="{ width: getRatingPercentage(2) + '%' }"
                       class="bkdt__percent-rating-child"></div>
                </div>
                <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3); width: 76px;">
                  {{ ratingCounts[2] }} đánh giá
                </p>
              </div>
              <div class="d-flex align-items-center justify-content-between my-1">
                <h3 style="width:3%;">2</h3>
                <i class="bi bi-star-fill fs-4 color-star"></i>
                <div class="bkdt__percent-rating">
                  <div :style="{ width: getRatingPercentage(1) + '%' }"
                       class="bkdt__percent-rating-child"></div>
                </div>
                <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3); width: 76px;">
                  {{ ratingCounts[1] }} đánh giá
                </p>
              </div>
              <div class="d-flex align-items-center justify-content-between my-1">
                <h3 style="width:3%;">1</h3>
                <i class="bi bi-star-fill fs-4 color-star"></i>
                <div class="bkdt__percent-rating">
                  <div :style="{ width: getRatingPercentage(0) + '%' }"
                       class="bkdt__percent-rating-child"></div>
                </div>
                <p class="fs-5 fst-italic m-0 text-end" style="color: var(--colortext3); width: 76px;">
                  {{ ratingCounts[0] }} đánh giá
                </p>
              </div>

              <div class="bkdt__comment">
                <a class="bkdt__comment-link" href="#">Tất cả bình luận</a>
              </div>

              <!-- Hiển thị danh sách bình luận nếu có dữ liệu -->
              <div v-if="yard_store.danh_gia && yard_store.danh_gia.length > 0">
                <div v-for="(review, index) in yard_store.danh_gia" :key="index">
                  <div class="d-flex gap-4 flex-fill">
                    <div class="bkdt__comment-user">
                      <img :src="review.user?.avatar || '../img/user1.jpg'" alt="" class="img-fluid rounded-circle">
                    </div>
                    <div>
                      <div class="">
                        <h3 class="fs-3 text-start m-0">{{ review.user?.name || 'Người dùng' }}</h3>
                        <div class="d-flex align-items-center gap-3">
                          <div class="d-flex align-items-center gap-1 mb-1">
                            <template v-for="star in 5" :key="star">
                              <i :class="star <= review.So_sao ? 'color-star' : 'color-star-gray'"
                                 class="bi bi-star-fill fs-5"></i>
                            </template>
                          </div>
                          <i class="bi bi-dot fs-5" style="color: var(--colortext2);"></i>
                          <p class="m-0 fs-5 hidden-text" style="color: var(--colortext3);">
                            {{ new Date(review.created_at).toLocaleDateString('vi-VN') }}
                          </p>
                          <div class="flex-fill">
                            <i class="bi bi-hand-thumbs-up-fill fs-3 color-star"></i>
                          </div>
                        </div>
                      </div>
                      <div>
                        <p class="m-0 mt-2" style="color: var(--colortext1); font-size: 1.4rem; line-height: 2rem;">
                          {{ review.Noi_dung || 'Không có nội dung đánh giá' }}
                        </p>
                      </div>
                    </div>
                  </div>
                  <hr style="color: var(--colortext3); margin: 24px 0 20px 0;">
                </div>
              </div>
              <!-- Hiển thị thông báo khi không có bình luận -->
              <div v-else>
                <p class="text-center fs-4 mt-3">Chưa có đánh giá nào cho sân này</p>
              </div>

              <form action="">
                <input class="form-date d-block w-100" placeholder="Viết bình luận..." type="text">
              </form>
            </div>
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
                         :class="star <= (yard.diem_danh_gia ?? 0) ? 'color-star' : 'color-star-gray'"
                         class="bi bi-star-fill">
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
.main-image-container {
  overflow: hidden;
  border-radius: 0.8rem;
  margin-bottom: 12px;
}

.main-image-container img {
  width: 100%;
  transition: transform 0.5s ease;
}

.main-image-container:hover img {
  transform: scale(1.05);
}

.custom-thumnail__box {
  overflow: hidden;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.custom-thumnail__box:hover {
  box-shadow: 0 0 0 2px var(--accent);
}

.custom-thumnail__box img {
  transition: transform 0.5s ease;
}

.custom-thumnail__box:hover img {
  transform: scale(1.1);
}

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

.color-star {
  color: var(--hover2);
}

.color-star-gray {
  color: #ddd;
}

/* CSS cho thanh đánh giá */
.bkdt__percent-rating {
  width: 60%;
  height: 10px;
  background-color: #E7E7E7;
  border-radius: 8px;
  overflow: hidden;
}

.bkdt__percent-rating-child {
  height: 100%;
  background-color: var(--hover2);
  border-radius: 8px;
}

.percent100 {
  width: 100%;
}

/* CSS cho form đặt sân */
.booking-form-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.booking-form-container {
  background-color: white;
  width: 90%;
  max-width: 600px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  padding: 0;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  color: var(--colortext1);
}

.booking-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #eee;
  background-color: #f8f9fa;
  border-radius: 16px 16px 0 0;
}

.form-title {
  font-size: 2.2rem;
  font-weight: 700;
  margin: 0;
  color: #333;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.8rem;
  cursor: pointer;
  color: #666;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.close-btn:hover {
  background-color: #f0f0f0;
  color: #333;
}

.booking-form-content,
.booking-success-content {
  padding: 2rem;
}

/* Form input styles */
.form-group {
  margin-bottom: 1.8rem;
}

.form-label {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  display: block;
}

.form-control {
  width: 100%;
  padding: 1rem;
  font-size: 1.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 1.5rem;
}

.form-control:focus, .custom-select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 0.2rem rgba(44, 123, 229, 0.25);
}

.required-field::after {
  content: " *";
  color: #dc3545;
  font-weight: bold;
  font-size: 1.5rem;
}

.btn-booking {
  font-size: 1.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  transition: all 0.2s ease-in-out;
  font-weight: 600;
}

.confirm-btn {
  background-color: var(--accent);
  border: none;
  color: white;
}

.confirm-btn:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.cancel-btn {
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  color: #6c757d;
}

.cancel-btn:hover {
  background-color: #e2e6ea;
  color: #212529;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Booking info card */
.booking-info-card {
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  position: relative;
  border: 1px solid #e0e0e0;
}

.info-header {
  background-color: var(--accent);
  color: white;
  padding: 15px 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  font-size: 1.5rem;
}

.info-content {
  padding: 1.5rem;
  position: relative;
  color: #333;
  background-color: white;
}

.info-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 15px;
  color: #333;
  font-size: 1.4rem;
}

.info-item:last-child {
  margin-bottom: 0;
}

.info-item i {
  color: var(--accent);
  font-size: 1.4rem;
  width: 24px;
  text-align: center;
  margin-top: 3px;
}

.info-item div {
  display: flex;
  flex-direction: column;
}

.info-item strong {
  font-weight: 600;
  margin-bottom: 2px;
  color: #222;
  font-size: 1.3rem;
}

.info-item span {
  font-weight: 500;
  color: #444;
  font-size: 1.4rem;
}

.price-tag {
  position: absolute;
  top: 0;
  right: 0;
  background-color: #f39c12;
  color: white;
  padding: 12px 22px;
  font-weight: 700;
  border-radius: 0 0 0 12px;
  font-size: 1.7rem;
  z-index: 5;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Alert styles */
.custom-alert-danger {
  background-color: #fff5f5;
  border-left: 4px solid #f53d3d;
  color: #d42424;
  padding: 18px;
  border-radius: 8px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 1.8rem;
  font-size: 1.6rem;
}

.custom-alert-danger i {
  font-size: 1.7rem;
  margin-top: 2px;
}

/* Payment options */
.form-section-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  margin-bottom: 1.2rem;
  font-size: 1.7rem;
  color: var(--colortext1);
}

.form-section-label i {
  font-size: 1.8rem;
}

.payment-options {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 1rem;
}

.payment-option {
  border: 2px solid #eaeaea;
  border-radius: 12px;
  padding: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}

.payment-option.active {
  border-color: var(--accent);
  background-color: rgba(0, 128, 0, 0.05);
}

.payment-option input {
  position: absolute;
  top: 15px;
  left: 15px;
}

.payment-icon {
  font-size: 2.6rem;
  color: var(--accent);
  margin-bottom: 12px;
}

.payment-text {
  text-align: center;
}

.payment-label {
  display: block;
  font-weight: 600;
  margin-bottom: 5px;
  color: var(--colortext1);
  font-size: 1.7rem;
}

.payment-description {
  font-size: 1.5rem;
  color: var(--colortext2);
}

/* Bank transfer info */
.bank-transfer-info {
  background-color: white;
  border-radius: 12px;
  margin-top: 1.5rem;
  overflow: hidden;
  color: #333;
  border: 1px solid #e0e0e0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bank-header {
  background-color: #2980b9;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: white;
}

.bank-header h5 {
  margin: 0;
  font-weight: 600;
  color: white;
  font-size: 1.5rem;
}

.bank-header i {
  font-size: 1.4rem;
}

.bank-details {
  padding: 1.5rem;
  background-color: white;
}

.bank-row {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  flex-wrap: wrap;
}

.bank-row:last-child {
  margin-bottom: 0;
}

.bank-label {
  width: 160px;
  font-weight: 600;
  color: #444;
  font-size: 1.7rem !important;
}

.bank-value {
  font-weight: 500;
  color: #222;
  font-size: 1.8rem !important;
}

.message-value {
  max-width: 260px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.copy-btn {
  background: none;
  border: none;
  color: #2980b9;
  cursor: pointer;
  margin-left: 10px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  background-color: rgba(41, 128, 185, 0.1);
}

.copy-btn:hover {
  background-color: rgba(41, 128, 185, 0.2);
}

.transfer-warning {
  margin-top: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #e74c3c;
  font-size: 1.8rem;
  background-color: #ffeeee;
  padding: 12px 18px;
  border-radius: 8px;
  border-left: 3px solid #e74c3c;
}

/* Form buttons */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 20px;
  margin-top: 2.5rem;
}

.cancel-btn,
.confirm-btn {
  padding: 15px 30px;
  border-radius: 12px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  font-size: 1.7rem;
}

.cancel-btn {
  background-color: #f0f0f0;
  color: #666;
}

.cancel-btn:hover {
  background-color: #e0e0e0;
  color: #333;
}

.confirm-btn {
  background-color: var(--accent);
  color: white;
}

.confirm-btn:hover {
  background-color: #006600;
}

.confirm-btn:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

/* Success message styles */
.booking-success-content {
  text-align: center;
  padding: 2rem;
  color: var(--colortext1);
}

.success-animation {
  margin-bottom: 1.5rem;
}

.checkmark-circle {
  width: 100px;
  height: 100px;
  background-color: #f9d776;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.hourglass-animation {
  font-size: 3.5rem;
  color: white;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.success-content {
  margin-bottom: 2rem;
}

.success-title {
  font-size: 3.2rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 1.2rem;
}

.success-message {
  color: #333;
  font-size: 2.2rem;
  margin-bottom: 0.8rem;
}

.contact-message {
  color: #666;
  font-size: 2rem;
}

/* Booking summary */
.booking-summary {
  background-color: white;
  border-radius: 16px;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  border: 1px solid #e0e0e0;
}

.summary-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1.8rem;
  font-weight: 700;
  font-size: 2.2rem;
  color: #333;
  padding-bottom: 1rem;
  border-bottom: 1px solid #eee;
}

.summary-header i {
  color: var(--accent);
  font-size: 2.2rem;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
  padding: 8px 0;
}

.summary-item:last-child {
  margin-bottom: 0;
}

.summary-icon {
  width: 54px;
  height: 54px;
  background-color: rgba(0, 128, 0, 0.08);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--accent);
  font-size: 1.8rem;
  flex-shrink: 0;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.summary-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.summary-label {
  color: #666;
  margin-bottom: 5px;
  font-weight: 500;
  font-size: 1.7rem;
}

.summary-value {
  font-weight: 600;
  color: #333;
  font-size: 1.9rem;
}

.price-item {
  background-color: rgba(220, 53, 69, 0.05);
  border-radius: 10px;
  padding: 15px;
  margin-top: 10px;
}

.price-item .summary-icon {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}

.price-value {
  color: #dc3545;
  font-size: 2rem;
}

/* Next steps */
.next-steps {
  margin-top: 2rem;
  border-top: 1px solid #e5e5e5;
  padding-top: 1.5rem;
}

.step {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  margin-bottom: 1.5rem;
}

.step:last-child {
  margin-bottom: 0;
}

.step-icon {
  width: 36px;
  height: 36px;
  background-color: var(--accent);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.4rem;
}

.step-content {
  flex: 1;
}

.step-title {
  margin: 0 0 6px 0;
  font-size: 1.7rem;
  color: #333;
  font-weight: 600;
}

.step-description {
  margin: 0;
  color: #666;
  font-size: 1.5rem;
}

.close-success-btn {
  background-color: var(--accent);
  color: white;
  border: none;
  padding: 16px 36px;
  border-radius: 30px;
  font-weight: 600;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 10px auto 0;
  cursor: pointer;
  transition: all 0.3s ease;
}

.close-success-btn:hover {
  background-color: #006600;
  transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .booking-form-container {
    width: 95%;
    max-height: 85vh;
  }

  .payment-options {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }

  .cancel-btn, .confirm-btn {
    width: 100%;
  }
}

.timeslot-item {
  cursor: pointer;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.timeslot-item:hover {
  background-color: #f5f5f5;
}

.selected-slot {
  background-color: #e6f7ff;
  border: 1px solid #1890ff;
}

/* Spinner animation */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.bi-arrow-repeat {
  animation: spin 1s linear infinite;
  display: inline-block;
}

.booking-section h3 {
  font-size: 2rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 1.5rem;
}

.booking-section .slot-info {
  font-size: 1.4rem;
  margin-bottom: 0.8rem;
}

.booking-section .time-slot-item {
  font-size: 1.4rem;
  padding: 12px 20px;
}

.total-price-section {
  border-top: 1px solid #ddd;
  margin-top: 20px;
  padding-top: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total-price-label {
  font-size: 1.6rem;
  font-weight: 700;
  color: #333;
}

.total-price-amount {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--accent);
}

.booking-success-alert {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
  padding: 20px;
  margin-bottom: 30px;
  border-radius: 8px;
  font-size: 1.5rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.booking-success-icon {
  font-size: 3.3rem;
  margin-bottom: 15px;
  color: #28a745;
}

.yard-name {
  font-size: 2.6rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

.yard-address {
  font-size: 1.8rem;
  color: #666;
  margin-bottom: 1rem;
}

.yard-description {
  font-size: 1.8rem;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.yard-rating {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
  font-size: 1.8rem;
}

.yard-price {
  font-size: 2rem;
  font-weight: 600;
  color: #FF5722;
  margin-bottom: 1rem;
}

.yard-time {
  font-size: 1.5rem;
  color: #333;
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.yard-time i {
  margin-right: 0.5rem;
  color: var(--accent);
  font-size: 1.3rem;
}

.yard-info-section {
  border-bottom: 2px solid #eaeaea;
  padding-bottom: 1.5rem;
  margin-bottom: 1.5rem;
}

.time-slots-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 1.5rem 0 1rem;
  color: #2c3e50;
}

.booking-form .btn-primary {
  font-size: 1.6rem;
  font-weight: 700;
  padding: 1rem 2rem;
  background-color: #4caf50;
  border: none;
  border-radius: 0.5rem;
  color: white;
  width: 100%;
  transition: background-color 0.3s;
}

.booking-form .btn-primary:hover {
  background-color: #3d8b40;
}

.booking-form .btn-secondary {
  font-size: 1.4rem;
  font-weight: 600;
  padding: 0.8rem 1.5rem;
  background-color: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  color: #333;
  transition: background-color 0.3s;
}

.booking-form .btn-secondary:hover {
  background-color: #e0e0e0;
}

.custom-select {
  font-size: 1.3rem;
  padding: 0.8rem 1rem;
  height: auto;
  border-radius: 0.5rem;
}

.payment-methods {
  margin-top: 1.5rem;
  margin-bottom: 2rem;
}

.payment-methods .form-check {
  margin-bottom: 1rem;
}

.payment-methods label {
  font-size: 1.3rem;
  padding-left: 0.5rem;
}

.form-input-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #333;
  font-size: 1.8rem;
}

.form-input-group input,
.form-input-group select,
.form-input-group textarea {
  width: 100%;
  padding: 1rem 1.2rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1.8rem;
  transition: all 0.2s;
}

.alert {
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 20px;
  font-size: 1.6rem;
}

.section-label {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #333;
}

.schedule-date {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.time-slot-item {
  padding: 10px 20px;
  border-radius: 8px;
  border: 1px solid #ddd;
  cursor: pointer;
  font-size: 1.6rem;
}

.booking-info h1 {
  font-size: 2.2rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 10px;
}

.booking-info p {
  font-size: 1.6rem;
  color: #666;
  margin-bottom: 5px;
}

.submit-btn {
  padding: 1.2rem 1.8rem;
  background-color: #FF5722;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
}

.back-btn {
  padding: 1.2rem 1.8rem;
  background-color: #f5f5f5;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
  display: inline-block;
  text-decoration: none;
}

.tab-nav {
  display: flex;
  border-bottom: 1px solid #ddd;
  margin-bottom: 20px;
}

.tab-nav-item {
  padding: 1rem 2rem;
  font-size: 1.8rem;
  cursor: pointer;
  border-bottom: 3px solid transparent;
}

.booking-error {
  color: #ff0000;
  margin-top: 1rem;
  font-size: 1.6rem;
}

.submit-btn {
  width: 100%;
  padding: 1.2rem;
  background-color: var(--primary);
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.8rem;
  font-weight: 600;
  transition: background-color 0.3s;
}

.submit-btn:hover {
  background-color: #3d8b40;
}

.booking-info {
  margin-top: 2rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 1.5rem;
}

.booking-info-title {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.booking-info-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.8rem;
  font-size: 1.6rem;
}

.booking-total {
  font-weight: 600;
  color: #FF5722;
  font-size: 1.8rem;
}

.section-title {
  font-size: 2.4rem;
  font-weight: 600;
  margin-bottom: 2rem;
  color: #333;
}

.review-card {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  background-color: #fff;
}

.review-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.review-author {
  font-size: 1.8rem;
  font-weight: 600;
}

.review-date {
  font-size: 1.5rem;
  color: #777;
}

.review-rating {
  margin-bottom: 1rem;
  font-size: 1.6rem;
}

.review-content {
  font-size: 1.6rem;
  line-height: 1.6;
}

.time-slot-container {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 2rem;
}

.time-slot {
  padding: 0.8rem 1.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.6rem;
  transition: all 0.2s;
}

.time-slot:hover {
  background-color: #f5f5f5;
}

.time-slot.selected {
  background-color: #4CAF50;
  color: white;
  border-color: #4CAF50;
}

.time-slot.unavailable {
  background-color: #f5f5f5;
  color: #aaa;
  cursor: not-allowed;
}
</style>