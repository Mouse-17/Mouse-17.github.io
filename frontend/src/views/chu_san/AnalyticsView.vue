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
              height: 667px;
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
.accept {
  min-height: 100vh;
}

aside {
  background-color: white;
  box-shadow: 0 0 18px var(--shadow2);
  height: 100%;
  border-radius: 0 20px 20px 0;
}

.boss-img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
}

.btn-booknow {
  display: inline-block;
  padding: 10px 30px;
  border-radius: 50px;
  background-color: var(--yellow);
  color: var(--white);
  font-size: 16px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
}

.btn-booknow:hover {
  background-color: var(--yellow);
  color: white;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  transform: translateY(-2px);
}

.boss-item-link {
  display: block;
  padding: 10px 15px;
  margin-bottom: 10px;
  border-radius: 8px;
  color: var(--colortext2);
  text-decoration: none;
  transition: all 0.3s;
}

.boss-item-link:hover {
  background-color: rgba(255, 187, 0, 0.1);
  color: var(--yellow);
}

.boss-link-active {
  background-color: rgba(255, 187, 0, 0.1);
  color: var(--yellow) !important;
  border-left: 4px solid var(--yellow);
}

.logout-btn {
  margin-top: 20px;
  transition: all 0.3s ease;
}

.logout-btn:hover {
  background-color: rgba(220, 53, 69, 0.1);
  transform: translateY(-2px);
}
</style>
