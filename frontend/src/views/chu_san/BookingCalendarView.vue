<script lang="ts" setup>
import {computed, onMounted, ref} from "vue";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

const selectedItem = ref(1);
const currentDate = ref(new Date().toISOString().slice(0, 10));
const searchTerm = ref("");
const showBookingModal = ref(false);
const isLoading = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

// Thông tin đặt sân mới
const newBooking = ref({
  field: "",
  time: "",
  customer: "",
  phone: "",
  price: 140000,
  date: new Date().toISOString().slice(0, 10),
});

// Danh sách các sân
const fields = ref([
  {id: 1, name: "Sân 1"},
  {id: 2, name: "Sân 2"},
  {id: 3, name: "Sân 3"},
  {id: 4, name: "Sân 4"},
  {id: 5, name: "Sân 5"},
  {id: 7, name: "Sân 7"},
  {id: 8, name: "Sân 8"},
  {id: 9, name: "Sân 9"},
]);

// Danh sách khung giờ
const timeSlots = ref([
  {id: 1, time: "6:00", display: "6:00"},
  {id: 2, time: "8:00", display: "8:00"},
  {id: 3, time: "10:00", display: "10:00"},
  {id: 4, time: "12:00", display: "12:00"},
  {id: 5, time: "14:00", display: "14:00"},
  {id: 6, time: "16:00", display: "16:00"},
  {id: 7, time: "18:00", display: "18:00"},
  {id: 8, time: "20:00", display: "20:00"},
]);

// Danh sách đặt sân (mẫu)
const bookings = ref([
  {
    id: "BK001",
    field: 1,
    time: "6:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK002",
    field: 2,
    time: "6:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK003",
    field: 3,
    time: "6:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK004",
    field: 4,
    time: "8:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK005",
    field: 9,
    time: "8:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK007",
    field: 1,
    time: "10:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK008",
    field: 2,
    time: "10:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK009",
    field: 4,
    time: "10:00",
    customer: "Đạt",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK010",
    field: 5,
    time: "10:00",
    customer: "Đạt",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK011",
    field: 8,
    time: "12:00",
    customer: "Đạt",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK012",
    field: 9,
    time: "12:00",
    customer: "Đạt",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK013",
    field: 1,
    time: "14:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK014",
    field: 2,
    time: "14:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK015",
    field: 3,
    time: "14:00",
    customer: "Thủy Nga",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK016",
    field: 4,
    time: "14:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK017",
    field: 5,
    time: "14:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK018",
    field: 9,
    time: "14:00",
    customer: "Đạt",
    phone: "0123456789",
    price: 140000,
    date: "2025-04-19",
  },
  {
    id: "BK020",
    field: 1,
    time: "16:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK021",
    field: 2,
    time: "16:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK022",
    field: 5,
    time: "16:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK023",
    field: 7,
    time: "16:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK020",
    field: 3,
    time: "18:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK021",
    field: 4,
    time: "18:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK022",
    field: 9,
    time: "18:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK020",
    field: 1,
    time: "20:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK021",
    field: 2,
    time: "20:00",
    customer: "Hòa",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK022",
    field: 5,
    time: "20:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
  {
    id: "BK023",
    field: 7,
    time: "20:00",
    customer: "Tuấn",
    phone: "0123456789",
    price: 240000,
    date: "2025-04-19",
  },
]);

const menuItems = ref([
  {text: "Thống kê", icon: "bi bi-bar-chart", link: "/chusan"},
  {text: "Lịch sân", icon: "bi bi-list-check", link: "/lichsan"},
  {text: "Chờ phê duyệt", icon: "bi bi-hourglass-split", link: "/pheduyet"},
  {text: "Khách hàng thân thiết", icon: "bi bi-hearts", link: "/khyeuthich"},
  {text: "Cài đặt", icon: "bi bi-gear", link: "/caidat"},
]);

// Lọc đặt sân theo ngày
const filteredBookings = computed(() => {
  const searchDate = currentDate.value.replace(/-/g, "/");
  return bookings.value.filter((b) => {
    const bookingDate = new Date(b.date)
        .toLocaleDateString("en-CA")
        .replace(/-/g, "/");
    return bookingDate === searchDate;
  });
});

// Lấy đặt sân theo sân và giờ
const getBooking = (fieldId, time) => {
  return filteredBookings.value.find(
      (b) => b.field === fieldId && b.time === time
  );
};

// Mở modal đặt sân mới
const openBookingModal = (fieldId, time) => {
  newBooking.value = {
    field: fieldId,
    time: time,
    customer: "",
    phone: "",
    price: time.startsWith("1") ? 140000 : 240000, // Giá theo giờ
    date: currentDate.value,
  };
  showBookingModal.value = true;
};

// Đóng modal đặt sân
const closeBookingModal = () => {
  showBookingModal.value = false;
};

// Lưu đặt sân mới
const saveBooking = () => {
  isLoading.value = true;

  // Validate input
  if (!newBooking.value.customer || !newBooking.value.phone) {
    errorMessage.value = "Vui lòng nhập đầy đủ thông tin người đặt";
    setTimeout(() => {
      errorMessage.value = "";
    }, 3000);
    isLoading.value = false;
    return;
  }

  // Giả lập API call
  setTimeout(() => {
    // Tạo ID đặt sân mới
    const bookingId =
        "BK" + (bookings.value.length + 1).toString().padStart(3, "0");

    // Thêm đặt sân mới vào danh sách
    bookings.value.push({
      id: bookingId,
      field: newBooking.value.field,
      time: newBooking.value.time,
      customer: newBooking.value.customer,
      phone: newBooking.value.phone,
      price: newBooking.value.price,
      date: newBooking.value.date,
    });

    // Hiển thị thông báo thành công
    successMessage.value = "Đặt sân thành công!";
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);

    // Đóng modal và reset form
    closeBookingModal();
    isLoading.value = false;
  }, 1000);
};

// Lấy tên sân từ ID
const getFieldName = (fieldId) => {
  const field = fields.value.find((f) => f.id === fieldId);
  return field ? field.name : "";
};

// Định dạng giá tiền
const formatPrice = (price) => {
  return new Intl.NumberFormat("vi-VN").format(price);
};

onMounted(() => {
  // Khởi tạo ngày hiện tại
  currentDate.value = new Date().toISOString().slice(0, 10);
});
</script>

<template>
  <main>
    <!-- Thông báo thành công -->
    <div v-if="successMessage" class="success-toast">
      <i class="bi bi-check-circle-fill me-2"></i>
      {{ successMessage }}
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="errorMessage" class="error-toast">
      <i class="bi bi-exclamation-circle-fill me-2"></i>
      {{ errorMessage }}
    </div>

    <!-- Modal đặt sân -->
    <div
        v-if="showBookingModal"
        class="booking-modal-backdrop"
        @click="closeBookingModal"
    >
      <div class="booking-modal" @click.stop>
        <div class="booking-modal-header">
          <h5 class="m-0">
            Đặt sân {{ getFieldName(newBooking.field) }} - {{ newBooking.time }}
          </h5>
          <button class="close-btn" @click="closeBookingModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="booking-modal-body">
          <div class="mb-3">
            <label class="form-label">Tên người đặt</label>
            <input
                v-model="newBooking.customer"
                class="form-control"
                placeholder="Nhập tên người đặt"
                type="text"
            />
          </div>
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input
                v-model="newBooking.phone"
                class="form-control"
                placeholder="Nhập số điện thoại"
                type="text"
            />
          </div>
          <div class="mb-3">
            <label class="form-label">Giá tiền (VNĐ)</label>
            <input
                v-model="newBooking.price"
                class="form-control"
                type="number"
            />
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày đặt</label>
            <input
                v-model="newBooking.date"
                class="form-control"
                disabled
                type="date"
            />
          </div>
        </div>
        <div class="booking-modal-footer">
          <button class="btn-cancel" @click="closeBookingModal">Hủy</button>
          <button
              :disabled="isLoading"
              class="btn-booknow"
              @click="saveBooking"
          >
            <span
                v-if="isLoading"
                aria-hidden="true"
                class="spinner-border spinner-border-sm me-2"
                role="status"
            ></span>
            Đặt sân
          </button>
        </div>
      </div>
    </div>

    <section class="accept">
      <div class="row gx-0">
        <Sidebar/>
        <div class="col-10">
          <div
              class="bg-white p-4 p-lg-5"
              style="
            box-shadow: 0 0 18px var(--shadow2);
            min-height: 667px;
            border-radius: 20px 0 0 20px;
          "
          >
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3 class="m-0 fs-2 fw-bold" style="color: var(--colortext1)">
                Quản lý đặt sân
              </h3>
              <div class="d-flex gap-3 align-items-center">
                <input v-model="currentDate" class="form-date" type="date"/>
                <input
                    v-model="searchTerm"
                    class="form-control search-input"
                    placeholder="Tìm kiếm"
                    type="text"
                />
              </div>
            </div>

            <div class="calendar-container">
              <!-- Mốc thời gian bên trái -->
              <div class="time-col">
                <div class="header-cell"></div>
                <div class="time-cell">
                  <h5>6:00 - 6:30</h5>
                </div>
                <div class="time-cell">
                  <h5>6:30 - 7:00</h5>
                </div>
                <div class="time-cell">
                  <h5>7:00 - 7:30</h5>
                </div>
                <div class="time-cell">
                  <h5>7:30 - 8:00</h5>
                </div>
                <div class="time-cell">
                  <h5>8:00 - 8:30</h5>
                </div>
                <div class="time-cell">
                  <h5>8:30 - 9:00</h5>
                </div>
                <div class="time-cell">
                  <h5>9:00 - 9:30</h5>
                </div>
                <div class="time-cell">
                  <h5>9:30 - 10:00</h5>
                </div>
                <div class="time-cell">
                  <h5>10:00 - 10:30</h5>
                </div>
                <div class="time-cell">
                  <h5>10:30 - 11:00</h5>
                </div>
                <div class="time-cell">
                  <h5>11:00 - 11:30</h5>
                </div>
                <div class="time-cell">
                  <h5>11:30 - 12:00</h5>
                </div>
                <div class="time-cell">
                  <h5>12:00 - 12:30</h5>
                </div>
                <div class="time-cell">
                  <h5>12:30 - 1:00</h5>
                </div>
                <div class="time-cell">
                  <h5>1:00 - 1:30</h5>
                </div>
                <div class="time-cell">
                  <h5>1:30 - 2:00</h5>
                </div>
                <div class="time-cell">
                  <h5>2:00 - 2:30</h5>
                </div>
                <div class="time-cell">
                  <h5>2:30 - 3:00</h5>
                </div>
                <div class="time-cell">
                  <h5>3:00 - 3:30</h5>
                </div>
                <div class="time-cell">
                  <h5>3:30 - 4:00</h5>
                </div>
                <div class="time-cell">
                  <h5>4:00 - 4:30</h5>
                </div>
                <div class="time-cell">
                  <h5>4:30 - 5:00</h5>
                </div>
                <div class="time-cell">
                  <h5>5:00 - 5:30</h5>
                </div>
                <div class="time-cell">
                  <h5>5:30 - 6:00</h5>
                </div>
              </div>

              <!-- Các sân -->
              <div v-for="field in fields" :key="field.id" class="field-col">
                <div class="header-cell">Sân {{ field.id }}</div>
                <div class="booking-cell" @click="openBookingModal(field.id, '6:00')">
                  <div v-if="getBooking(field.id, '6:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '6:00').id }} -
                      {{ getBooking(field.id, '6:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '6:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '8:00')">
                  <div v-if="getBooking(field.id, '8:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '8:00').id }} -
                      {{ getBooking(field.id, '8:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '8:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '10:00')">
                  <div v-if="getBooking(field.id, '10:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '10:00').id }} -
                      {{ getBooking(field.id, '10:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '10:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '12:00')">
                  <div v-if="getBooking(field.id, '12:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '12:00').id }} -
                      {{ getBooking(field.id, '12:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '12:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '14:00')">
                  <div v-if="getBooking(field.id, '14:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '14:00').id }} -
                      {{ getBooking(field.id, '14:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '14:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '16:00')">
                  <div v-if="getBooking(field.id, '16:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '16:00').id }} -
                      {{ getBooking(field.id, '16:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '16:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '18:00')">
                  <div v-if="getBooking(field.id, '18:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '18:00').id }} -
                      {{ getBooking(field.id, '18:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '18:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
                </div>
                <div class="booking-cell" @click="openBookingModal(field.id, '20:00')">
                  <div v-if="getBooking(field.id, '20:00')" class="booking-card">
                    <div class="booking-id">
                      {{ getBooking(field.id, '20:00').id }} -
                      {{ getBooking(field.id, '20:00').customer }}
                    </div>
                    <div class="booking-price">
                      {{ formatPrice(getBooking(field.id, '20:00').price) }}đ
                    </div>
                  </div>
                  <div v-else class="empty-slot"><i class="bi bi-plus-circle"></i></div>
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
@import url(\'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap\');

.calendar-container {
  display: flex;
  overflow-x: auto;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
}

.time-col,
.field-col {
  min-width: 150px;
  border-right: 1px solid #e0e0e0;
}

.time-col {
  min-width: 100px;
}

.field-col:last-child {
  border-right: none;
}

.header-cell {
  height: 50px;
  padding: 10px;
  background-color: #f5f5f5;
  font-weight: bold;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.time-cell {
  height: 90px;
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
}

.booking-cell {
  height: 100px;
  border-bottom: 1px solid #e0e0e0;
  padding: 5px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.booking-cell:hover {
  background-color: rgba(0, 0, 0, 0.03);
}

.booking-card {
  height: 100%;
  background-color: #ca8a04;
  border-radius: 6px;
  padding: 10px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.booking-id {
  font-weight: 500;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.booking-price {
  font-weight: 600;
  text-align: right;
}

.form-date {
  border: 1px solid #ced4da;
  border-radius: 6px;
  padding: 8px 12px;
  background-color: white;
}

.search-input {
  width: 250px;
}

.empty-slot {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #aaa;
  font-size: 18px;
  transition: color 0.2s;
}

.booking-cell:hover .empty-slot {
  color: #ca8a04;
}

/* Modal styling */
.booking-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}

.booking-modal {
  background-color: white;
  border-radius: 10px;
  width: 450px;
  max-width: 95%;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.booking-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
}

.booking-modal-body {
  padding: 20px;
}

.booking-modal-footer {
  padding: 15px 20px;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 1rem;
}

.form-label {
  font-weight: 500;
  margin-bottom: 6px;
  display: block;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.btn-cancel {
  padding: 8px 16px;
  background-color: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 5px;
  cursor: pointer;
}

.btn-booknow {
  padding: 8px 16px;
  background-color: #ca8a04;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-booknow:hover {
  background-color: #a16207;
}

.btn-booknow:disabled {
  background-color: #d4b052;
  cursor: not-allowed;
}

/* Toast notifications */
.success-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #28a745;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: slide-in 0.3s ease-out forwards;
}

.error-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #dc3545;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: slide-in 0.3s ease-out forwards;
}

@keyframes slide-in {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.col-2 {
  flex: 0 0 16.66%;
  max-width: 16.66%;
}

.col-10 {
  flex: 0 0 83.33%;
  max-width: 83.33%;
}

.accept {
  min-height: 100vh;
  background: #f4f4f4;
  padding: 20px;
}
</style>
