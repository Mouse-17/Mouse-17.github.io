<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { RouterLink, useRoute } from "vue-router";
import Chart from "chart.js/auto";
import Sidebar from "@/views/chu_san/partials/Sidebar.vue";

const route = useRoute();
// Xác định đường dẫn hiện tại
const currentPath = computed(() => route.path);

// Các state cho modal xác nhận và thông báo
const showDeleteModal = ref(false);
const customerToDelete = ref(null);
const successMessage = ref("");
const errorMessage = ref("");
const isLoading = ref(false);

// Danh sách khách hàng
const customers = ref([
  {
    id: 1,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Đã booking",
    visits: 12,
  },
  {
    id: 2,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Chỉ xem",
    visits: 3,
  },
  {
    id: 3,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Chỉ xem",
    visits: 3,
  },
  {
    id: 4,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Chỉ xem",
    visits: 3,
  },
  {
    id: 5,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Đã booking",
    visits: 12,
  },
  {
    id: 6,
    name: "Shin",
    avatar: "../../../public/img/user.webp",
    status: "Chỉ xem",
    visits: 3,
  },
]);

// Hiển thị modal xác nhận xóa
function openDeleteModal(customer) {
  customerToDelete.value = customer;
  showDeleteModal.value = true;
}

// Đóng modal xác nhận
function closeDeleteModal() {
  showDeleteModal.value = false;
  customerToDelete.value = null;
}

// Xóa khách hàng
async function deleteCustomer() {
  if (!customerToDelete.value) return;

  isLoading.value = true;

  try {
    // Giả lập API call
    await new Promise((resolve) => setTimeout(resolve, 500));

    // Xóa khách hàng khỏi danh sách
    customers.value = customers.value.filter(
      (c) => c.id !== customerToDelete.value.id
    );

    // Hiển thị thông báo thành công
    successMessage.value = `Đã xóa khách hàng ${customerToDelete.value.name} thành công!`;
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);

    // Đóng modal
    closeDeleteModal();
  } catch (error) {
    console.error("Lỗi khi xóa khách hàng:", error);
    errorMessage.value =
      "Có lỗi xảy ra khi xóa khách hàng. Vui lòng thử lại sau.";
    setTimeout(() => {
      errorMessage.value = "";
    }, 3000);
  } finally {
    isLoading.value = false;
  }
}

const menuItems = [
  { name: "Thống kê", icon: "bi-bar-chart", path: "/chusan" },
  { name: "Lịch sân", icon: "bi-list-check", path: "/lichsan" },
  { name: "Chờ phê duyệt", icon: "bi-hourglass-split", path: "/pheduyet" },
  { name: "Khách hàng thân thiết", icon: "bi-hearts", path: "/khyeuthich" },
  { name: "Cài đặt", icon: "bi-gear", path: "/caidat" },
];

const chartUser1 = ref<HTMLCanvasElement | null>(null);
const chartUser2 = ref<HTMLCanvasElement | null>(null);
onMounted(() => {
  const customerData = {
    new: 150, // Số khách hàng mới
    old: 100, // Số khách hàng cũ
  };

  if (chartUser1.value) {
    new Chart(chartUser1.value, {
      type: "doughnut",
      data: {
        labels: ["Truy cập mới", "Khách Cũ"],
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
            text: "Tỷ lệ khách mới và khách cũ trong tháng",
            font: { size: 16 },
            color: "#2a2a2a",
          },
        },
      },
    });
  }

  const customerBooking = {
    bookingSucess: 28,
    view: 210,
  };
  if (chartUser2.value) {
    new Chart(chartUser2.value, {
      type: "doughnut",
      data: {
        labels: ["Xem sân", "Đã booking"],
        datasets: [
          {
            data: [customerBooking.view, customerBooking.bookingSucess],
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
            text: "Tỷ Lệ xem sân và booking trong tháng",
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

    <!-- Modal xác nhận xóa -->
    <div
      v-if="showDeleteModal"
      class="confirm-modal-backdrop"
      @click="closeDeleteModal"
    >
      <div class="confirm-modal" @click.stop>
        <div class="confirm-modal-header">
          <h5 class="m-0">Xác nhận xóa</h5>
          <button class="close-btn" @click="closeDeleteModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="confirm-modal-body">
          <p>
            Bạn có chắc chắn muốn xóa khách hàng
            <strong>{{ customerToDelete?.name }}</strong
            >?
          </p>
          <p class="text-muted small">Hành động này không thể hoàn tác.</p>
        </div>
        <div class="confirm-modal-footer">
          <button class="btn-cancel" @click="closeDeleteModal">Hủy</button>
          <button
            class="btn-refuse"
            @click="deleteCustomer"
            :disabled="isLoading"
          >
            <span
              v-if="isLoading"
              class="spinner-border spinner-border-sm me-2"
              role="status"
              aria-hidden="true"
            ></span>
            Xác nhận xóa
          </button>
        </div>
      </div>
    </div>

    <section class="accept">
      <div class="row gx-0">
        <Sidebar />
        <div class="col-10">
          <div
            class="bg-white p-5"
            style="
              box-shadow: 0 0 18px var(--shadow2);
              min-height: 667px;
              border-radius: 20px 0 0 20px;
            "
          >
            <div class="row">
              <div class="col-12">
                <h3
                  class="m-0 fs-2 fw-bold text-start"
                  style="color: var(--colortext1)"
                >
                  Khách hàng
                </h3>
              </div>
              <div class="row mt-3">
                <div class="col-8">
                  <div>
                    <form action="">
                      <h3
                        class="m-0 mb-3 fs-3 fw-bold text-start"
                        style="color: var(--colortext1)"
                      >
                        Khách hàng đã truy cập
                      </h3>
                      <div class="d-flex align-items-center gap-3">
                        <select
                          name=""
                          id=""
                          class="form-date inputBorder d-block"
                        >
                          <option value="">Tăng dần theo tên</option>
                          <option value="">Giảm dần theo tên</option>
                        </select>
                        <div>
                          <input
                            type="text"
                            class="d-block inputBorder"
                            style="width: 320px"
                            placeholder="Tìm kiếm"
                          />
                        </div>
                      </div>
                    </form>
                    <div>
                      <div class="boss-text bg-white mt-3">
                        <p
                          class="fs-4 text-start m-0 fw-semibold"
                          style="color: var(--colortext1)"
                        >
                          Người dùng
                        </p>
                        <p
                          class="fs-4 text-start m-0 fw-semibold"
                          style="color: var(--colortext1)"
                        >
                          Trạng thái
                        </p>
                        <p
                          class="fs-4 text-start m-0 fw-semibold"
                          style="color: var(--colortext1)"
                        >
                          Truy cập
                        </p>
                        <p
                          class="fs-4 text-start m-0 fw-semibold"
                          style="color: var(--colortext1)"
                        >
                          Tùy chỉnh
                        </p>
                      </div>
                      <div class="booking-item-accept" style="height: 500px">
                        <!-- Danh sách khách hàng -->
                        <div
                          v-for="customer in customers"
                          :key="customer.id"
                          class="boss-text my-2"
                        >
                          <div class="d-flex align-items-center gap-3">
                            <div class="boss-img">
                              <img :src="customer.avatar" alt="" />
                            </div>
                            <p
                              class="fs-4 text-start m-0 fw-semibold"
                              style="color: var(--colortext1)"
                            >
                              {{ customer.name }}
                            </p>
                          </div>
                          <p
                            class="fs-4 text-start m-0"
                            style="color: var(--colortext1)"
                          >
                            {{ customer.status }}
                          </p>
                          <p
                            class="fs-4 text-start m-0"
                            style="color: var(--colortext1)"
                          >
                            {{ customer.visits }} lần
                          </p>
                          <div class="d-flex align-items-center gap-2">
                            <button
                              class="d-flex justify-content-center align-items-center btn-refuse mt-0 px-4"
                              @click="openDeleteModal(customer)"
                            >
                              <i class="bi bi-trash fs-3"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4"></div>
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
