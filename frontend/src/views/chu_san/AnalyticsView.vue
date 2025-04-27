<script setup lang="ts">
import { RouterLink, useRouter } from "vue-router";
import { onMounted, ref } from "vue";
import Chart from "chart.js/auto";
import { useAuthStore } from "../../stores/auth";
import Sidebar from './partials/Sidebar.vue'

const router = useRouter();
const authStore = useAuthStore();
// Hàm đăng xuất

// biểu đồ doanh thu
const revenueCanvas = ref<HTMLCanvasElement | null>(null);
const BookingByDayCanvas = ref<HTMLCanvasElement | null>(null);
const viewByTimeCanvas = ref<HTMLCanvasElement | null>(null);
const pieViewCanvas = ref<HTMLCanvasElement | null>(null);
const customerCanvas = ref<HTMLCanvasElement | null>(null);
onMounted(() => {
  // Biểu đồ 1
  const dailyRevenue = [
    1000, 1200, 900, 1500, 1300, 1600, 1700, 1800, 2000, 2700, 2800, 2900, 3000,
    3200, 4100, 4200, 4300, 4400, 4300, 3300, 3400, 3500, 3600, 3700, 3800,
    3600, 3500, 2200, 2500, 2400,
  ];
  if (revenueCanvas.value) {
    new Chart(revenueCanvas.value, {
      type: "bar", // Biểu đồ cột
      data: {
        labels: Array.from({ length: 30 }, (_, i) => `Ngày ${i + 1}`),
        datasets: [
          {
            label: "Doanh thu (triệu VND)",
            data: dailyRevenue,
            backgroundColor: "rgba(0, 123, 255, 0.2)",
            borderColor: "rgba(0, 123, 255, 0.5)",
            borderWidth: 1,
            // barThickness: 30
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: "top" },
          title: {
            display: true,
            text: "Biểu đồ doanh thu trong tháng",
            font: { size: 16 },
            color: "#2a2a2a",
          },
        },
      },
    });
  }

  // Biểu đồ 2
  const morningBookings = [30, 25, 40, 35, 50, 45, 60];
  const afternoonBookings = [45, 40, 55, 60, 65, 70, 80];
  const eveningBookings = [75, 80, 65, 70, 85, 80, 90];
  const days = [
    "Thứ 2",
    "Thứ 3",
    "Thứ 4",
    "Thứ 5",
    "Thứ 6",
    "Thứ 7",
    "Chủ nhật",
  ];
  if (BookingByDayCanvas.value) {
    new Chart(BookingByDayCanvas.value, {
      type: "bar", // Biểu đồ cột
      data: {
        labels: days, // Các ngày trong tuần
        datasets: [
          {
            label: "Sáng (6h - 12h)",
            data: morningBookings,
            backgroundColor: "rgba(75, 192, 192, 0.7)",
            borderWidth: 1,
            barThickness: 24,
          },
          {
            label: "Chiều (12h - 16h)",
            data: afternoonBookings,
            backgroundColor: "rgba(202, 155, 39, 0.7)",
            borderWidth: 1,
            barThickness: 24,
          },
          {
            label: "Tối 16h - 23h",
            data: eveningBookings,
            backgroundColor: "rgba(249, 87, 87, 0.7)",
            borderWidth: 1,
            barThickness: 24,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "top",
          },
          title: {
            display: true,
            text: "Tổng lượt đặt sân sáng, chiều và tối trong một tuần",
            font: {
              size: 16,
            },
            color: "#2a2a2a",
          },
        },
        scales: {
          x: {
            title: {
              display: true,
              text: "Ngày trong tuần",
            },
          },
          y: {
            // Trục Y: Số lượt đặt
            title: {
              display: true,
              text: "Số lượt đặt",
            },
            beginAtZero: true, // Bắt đầu trục Y từ 0
          },
        },
      },
    });
  }

  // Biểu đồ 3
  let viewsArr = [
    24, 29, 20, 15, 25, 28, 35, 30, 35, 30, 45, 50, 65, 70, 75, 80, 90, 100,
    101, 102, 103, 104, 105, 81,
  ];
  const viewTimeArr = [
    "00:00",
    "01:00",
    "02:00",
    "03:00",
    "04:00",
    "05:00",
    "06:00",
    "07:00",
    "08:00",
    "09:00",
    "10:00",
    "11:00",
    "12:00",
    "13:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00",
    "22:00",
    "23:00",
  ];
  if (viewByTimeCanvas.value) {
    new Chart(viewByTimeCanvas.value, {
      type: "bar",
      data: {
        labels: viewTimeArr,
        datasets: [
          {
            label: "Số lượt xem sân",
            data: viewsArr,
            backgroundColor: "rgba(75, 192, 192, 0.3)",
            borderColor: "rgba(75, 192, 192, 0.7)",
            borderWidth: 1,
            barThickness: 24,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: "top" },
          title: {
            display: true,
            text: "Lượt xem sân theo giờ",
            font: { size: 16 },
            color: "#2a2a2a",
          },
        },
      },
    });
  }

  // Biểu đồ 4
  const highestValue = Math.max(...viewsArr);
  const lowestValue = Math.min(...viewsArr);
  const highestLabel = viewTimeArr[viewsArr.indexOf(highestValue)];
  const lowestLabel = viewTimeArr[viewsArr.indexOf(lowestValue)];

  if (pieViewCanvas.value) {
    new Chart(pieViewCanvas.value, {
      type: "pie",
      data: {
        labels: [`Cao nhất: ${highestLabel}`, `Thấp nhất: ${lowestLabel}`],
        datasets: [
          {
            data: [highestValue, lowestValue],
            backgroundColor: [
              "rgba(75, 192, 192, 0.7)",
              "rgba(249, 87, 87, 0.7)",
            ],
            borderColor: ["rgba(75, 192, 192, 1)", "rgba(249, 87, 87, 1)"],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: "top" },
          title: {
            display: true,
            text: "Khung Giờ Có Lượt Đặt Cao Nhất và Thấp Nhất",
            font: { size: 14 },
            color: "#2a2a2a",
          },
        },
      },
    });
  }

  // Biểu đồ 5
  const customerData = {
    new: 150, // Số khách hàng mới
    old: 100, // Số khách hàng cũ
  };

  if (customerCanvas.value) {
    new Chart(customerCanvas.value, {
      type: "doughnut",
      data: {
        labels: ["Khách Hàng Mới", "Khách Hàng Cũ"],
        datasets: [
          {
            data: [customerData.new, customerData.old],
            backgroundColor: [
              "rgba(75, 192, 192, 0.7)",
              "rgba(249, 87, 87, 0.7)",
            ],
            borderColor: ["rgba(75, 192, 192, 1)", "rgba(249, 87, 87, 1)"],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: "top" },
          title: {
            display: true,
            text: "Tỷ Lệ Khách Hàng Mới vs Khách Hàng Cũ",
            font: { size: 16 },
            color: "#2a2a2a",
          },
        },
      },
    });
  }
});
</script>

<template>
  <main>
    <section class="accept">
      <div class="row gx-0">
        <Sidebar />
        <div class="col-10">
          <div
            class="bg-white p-5"
            style="
              box-shadow: 0 0 18px var(--shadow2);
              border-radius: 20px 0 0 20px;
            "
          >
            <h3
              class="m-0 fs-2 fw-bold text-start"
              style="color: var(--colortext1)"
            >
              Thống kê
            </h3>
            <div class="analytics-box">
              <div class="row gx-0">
                <div class="col-8">
                  <div class="py-4 pe-4">
                    <canvas ref="BookingByDayCanvas"></canvas>
                  </div>
                  <div class="py-4 pe-4">
                    <canvas ref="viewByTimeCanvas"></canvas>
                  </div>
                  <!-- <div class="py-4 pe-4">
                                        <canvas ref="revenueLineCanvas"></canvas>
                                    </div> -->
                </div>
                <div class="col-4">
                  <div
                    class="p-5 ms-5"
                    style="
                      box-shadow: 0 0 28px var(--shadow2);
                      border-radius: 12px;
                    "
                  >
                    <canvas ref="customerCanvas"></canvas>
                  </div>
                  <div
                    class="p-5 ms-5"
                    style="
                      margin-top: 60px;
                      box-shadow: 0 0 28px var(--shadow2);
                      border-radius: 12px;
                    "
                  >
                    <canvas ref="pieViewCanvas"></canvas>
                  </div>
                </div>
              </div>
              <div class="pe-4 mt-5">
                <canvas ref="revenueCanvas"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

:root {
  --primary-color: #2a2a2a;
  --secondary-color: #28a745;
  --error-color: #dc3545;
  --bg-white: #ffffff;
  --shadow: rgba(0, 0, 0, 0.1);
}

* {
  box-sizing: border-box;
}

body {
  font-family: 'Roboto', sans-serif;
  margin: 0;
  background: #f4f4f4;
}

/* Toast notifications */
.success-toast,
.error-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 12px 24px;
  border-radius: 5px;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: slide-in 0.3s ease-out forwards;
  font-size: 16px;
}

.success-toast {
  background-color: var(--secondary-color);
  color: #fff;
}

.error-toast {
  background-color: var(--error-color);
  color: #fff;
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

/* Modal styling */
.confirm-modal-backdrop {
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

.confirm-modal {
  background: var(--bg-white);
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.confirm-modal-header,
.confirm-modal-footer {
  padding: 16px 20px;
  background: #f8f8f8;
}

.confirm-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
}

.confirm-modal-body {
  padding: 20px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: var(--primary-color);
}

.btn-cancel,
.btn-refuse {
  font-size: 14px;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  border: none;
}

.btn-cancel {
  background: #f1f1f1;
  color: var(--primary-color);
  margin-right: 10px;
}

.btn-cancel:hover {
  background: #e2e2e2;
}

.btn-refuse {
  background: var(--error-color);
  color: #fff;
  transition: background 0.2s;
}

.btn-refuse:hover {
  background: #c82333;
}

.btn-refuse:disabled {
  background: #e4606d;
  cursor: not-allowed;
}

/* Main layout */
.accept {
  min-height: 100vh;
  background: #f4f4f4;
  padding: 20px;
}

.row {
  display: flex;
  flex-wrap: wrap;
}

.col-2 {
  flex: 0 0 16.66%;
  max-width: 16.66%;
}

.col-10 {
  flex: 0 0 83.33%;
  max-width: 83.33%;
}

.bg-white {
  background: var(--bg-white);
}

.p-5 {
  padding: 2rem;
}

.rounded-container {
  border-radius: 20px 0 0 20px;
}

/* Booking list items */
.booking-item-accept {
  overflow-y: auto;
  max-height: 500px;
  padding-right: 10px;
}

.boss-text {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: var(--bg-white);
  border-radius: 8px;
  margin-bottom: 10px;
  box-shadow: 0 2px 8px var(--shadow);
}

/* Input styles */
.inputBorder {
  border: 1px solid #ddd;
  padding: 8px;
  border-radius: 4px;
  width: 100%;
}

.form-date {
  min-width: 160px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .col-2,
  .col-10 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row {
    flex-direction: column;
  }
}
</style>
