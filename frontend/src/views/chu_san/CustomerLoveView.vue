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
  background-color: white;
  border-radius: 10px;
  width: 400px;
  max-width: 90%;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.confirm-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
}

.confirm-modal-body {
  padding: 20px;
}

.confirm-modal-footer {
  padding: 15px 20px;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
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

.btn-cancel:hover {
  background-color: #e9ecef;
}

.btn-refuse {
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 8px 16px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-refuse:hover {
  background-color: #c82333;
}

.btn-refuse:disabled {
  background-color: #e4606d;
  cursor: not-allowed;
}
</style>
