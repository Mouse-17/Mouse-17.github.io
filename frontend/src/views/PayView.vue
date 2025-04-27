<template>
  <main>
    <section class="pay py-5" style="background-color: var(--bg3)">
      <div class="container">
        <div class="row justify-content-between gx-0">
          <div class="col-12 col-lg-4 custom-position-sticky">
            <h1 class="text-start m-0 pb-4 fs-1 mx-2">Đơn hàng</h1>
            <div class="mx-2">
              <div
                v-for="(item, index) in cartItems"
                :key="index"
                class="py-4"
                style="border-top: 1px solid var(--colortext3)"
              >
                <div class="row gx-0 justify-content-between">
                  <div class="col-3">
                    <div class="pe-2">
                      <img
                        :src="item.hinh_anh || '../../public/img/p1.png'"
                        alt=""
                        class="img-fluid"
                      />
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="px-2">
                      <p
                        class="title-product fs-4 m-0"
                        style="color: var(--colortext1)"
                      >
                        {{ item.ten_sp }}
                      </p>
                      <div
                        class="d-flex m-0 py-1 fs-5"
                        style="color: var(--colortext3)"
                      >
                        <p class="fs-5" v-if="item.ten_size">
                          Size({{ item.ten_size }})
                        </p>
                        <i
                          class="bi bi-dot fs-4"
                          v-if="item.ten_size && item.ten_mau"
                        ></i>
                        <p class="fs-5" v-if="item.ten_mau">
                          Màu({{ item.ten_mau }})
                        </p>
                      </div>
                      <p
                        class="my-3"
                        style="font-size: 1.4rem; color: var(--colortext2)"
                      >
                        Số lượng: {{ item.so_luong }}
                      </p>
                    </div>
                  </div>
                  <div class="col-3">
                    <p
                      class="text-end fs-4 fw-bold"
                      style="color: var(--accent)"
                    >
                      {{ formatPrice(item.don_gia) }}đ
                    </p>
                  </div>
                </div>
              </div>
              <div class="py-4" style="border-top: 1px solid var(--colortext3)">
                <div
                  class="d-flex align-items-baseline justify-content-between gap-4"
                >
                  <p class="my-2 fs-4" style="color: var(--colortext3)">
                    Phí vận chuyển
                  </p>
                  <p class="my-2 fs-4 fw-bold" style="color: var(--colortext1)">
                    {{ formatPrice(phiVanChuyen) }}đ
                  </p>
                </div>
              </div>
              <div class="py-4" style="border-top: 1px solid var(--colortext3)">
                <div
                  class="d-flex align-items-baseline justify-content-between gap-4"
                >
                  <p class="my-2 fs-3" style="color: var(--accent)">
                    Tổng thành tiền
                  </p>
                  <p class="my-2 fs-3 fw-bold" style="color: var(--accent)">
                    {{ formatPrice(tongThanhToan) }}đ
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-7">
            <h1 class="text-start mx-2 m-0 pb-4 fs-1">Thông tin thanh toán</h1>
            <form @submit.prevent="submitOrder" method="post">
              <h3
                class="fs-3 fw-semibold text-start mx-2 pt-4"
                style="border-top: 1px solid var(--colortext3)"
              >
                Phương thức thanh toán
              </h3>
              <div class="pb-3 mx-2">
                <div class="form-check my-3 d-flex align-items-center gap-2">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="payment"
                    id="momo"
                    value="3"
                    v-model="phuongThucThanhToan"
                  />
                  <label class="form-check-label fs-4 pt-1" for="momo"
                    >Ví MoMo</label
                  >
                </div>
                <div class="form-check my-3 d-flex align-items-center gap-2">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="payment"
                    id="ebank"
                    value="2"
                    v-model="phuongThucThanhToan"
                  />
                  <label class="form-check-label fs-4 pt-1" for="ebank"
                    >Thanh toán ngân hàng</label
                  >
                </div>
                <div class="form-check my-3 d-flex align-items-center gap-2">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="payment"
                    id="off"
                    value="1"
                    v-model="phuongThucThanhToan"
                    checked
                  />
                  <label class="form-check-label fs-4 pt-1" for="off"
                    >Thanh toán khi nhận hàng</label
                  >
                </div>
              </div>
              <div class="px-2">
                <input
                  type="text"
                  class="form-date d-block w-100 inputBorderPay"
                  placeholder="Lời nhắn cho shop"
                  v-model="ghiChu"
                />
              </div>
              <div class="">
                <h3 class="fs-3 fw-semibold text-start mx-2 mt-5">
                  Thông tin người nhận
                </h3>
                <div class="row justify-content-between gx-0">
                  <div class="col-12 col-lg-6 my-3">
                    <div class="px-2">
                      <input
                        type="text"
                        class="form-date d-block w-100 inputBorderPay"
                        placeholder="Họ tên"
                        v-model="hoTen"
                        required
                      />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 my-3">
                    <div class="px-2">
                      <input
                        type="text"
                        class="form-date d-block w-100 inputBorderPay"
                        placeholder="Số điện thoại"
                        v-model="soDienThoai"
                        required
                      />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 my-3">
                    <div class="px-2">
                      <input
                        type="email"
                        class="form-date d-block w-100 inputBorderPay"
                        placeholder="Email"
                        v-model="email"
                        required
                      />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 my-3">
                    <div class="px-2">
                      <input
                        type="text"
                        class="form-date d-block w-100 inputBorderPay"
                        placeholder="Địa chỉ"
                        v-model="diaChi"
                        required
                      />
                    </div>
                  </div>
                </div>
              </div>
              <button
                type="submit"
                class="btn-find px-5 mt-4 fw-semibold mx-2"
                style="width: fit-content; font-size: 1.7rem"
                :disabled="isProcessing"
              >
                {{ isProcessing ? "Đang xử lý..." : "Thanh toán" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";

export default {
  name: "PayView",
  setup() {
    const router = useRouter();
    const cartItems = ref([]);
    const phuongThucThanhToan = ref("1");
    const ghiChu = ref("");
    const hoTen = ref("");
    const soDienThoai = ref("");
    const email = ref("");
    const diaChi = ref("");
    const phiVanChuyen = ref(0);
    const isProcessing = ref(false);

    const tongThanhToan = computed(() => {
      const tongTienHang = cartItems.value.reduce((total, item) => {
        return total + item.don_gia * item.so_luong;
      }, 0);
      return tongTienHang + phiVanChuyen.value;
    });

    const formatPrice = (price) => {
      return new Intl.NumberFormat("vi-VN").format(price);
    };

    // Lấy thông tin giỏ hàng từ server
    const fetchCartItems = async () => {
      try {
        const response = await axios.get("/api/cart");
        console.log("Dữ liệu giỏ hàng:", response.data);
        cartItems.value = response.data.items || [];
      } catch (error) {
        console.error("Lỗi khi lấy dữ liệu giỏ hàng:", error);
      }
    };

    // Gửi đơn hàng lên server
    const submitOrder = async () => {
      if (!hoTen.value || !soDienThoai.value || !email.value || !diaChi.value) {
        alert("Vui lòng điền đầy đủ thông tin người nhận");
        return;
      }

      isProcessing.value = true;

      try {
        const orderData = {
          ho_ten: hoTen.value,
          so_dien_thoai: soDienThoai.value,
          email: email.value,
          dia_chi: diaChi.value,
          ghi_chu: ghiChu.value,
          phuong_thuc_thanh_toan: parseInt(phuongThucThanhToan.value),
          tong_tien: tongThanhToan.value,
        };

        console.log("Dữ liệu đơn hàng gửi đi:", orderData);

        const response = await axios.post("/api/orders", orderData);
        console.log("Kết quả từ server:", response.data);

        // Nếu thanh toán online (MoMo hoặc ngân hàng)
        if (
          phuongThucThanhToan.value === "2" ||
          phuongThucThanhToan.value === "3"
        ) {
          // Chuyển đến trang thanh toán bên thứ 3
          if (response.data.payment_url) {
            window.location.href = response.data.payment_url;
          } else {
            router.push({
              name: "order-detail",
              params: { id: response.data.order_id },
            });
          }
        } else {
          // Nếu thanh toán khi nhận hàng, chuyển đến trang chi tiết đơn hàng
          router.push({
            name: "order-detail",
            params: { id: response.data.order_id },
          });
        }
      } catch (error) {
        console.error("Lỗi khi tạo đơn hàng:", error);
        console.error(
          "Chi tiết lỗi:",
          error.response ? error.response.data : error.message
        );
        alert("Đã có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.");
      } finally {
        isProcessing.value = false;
      }
    };

    onMounted(() => {
      fetchCartItems();
    });

    return {
      cartItems,
      phuongThucThanhToan,
      ghiChu,
      hoTen,
      soDienThoai,
      email,
      diaChi,
      phiVanChuyen,
      tongThanhToan,
      formatPrice,
      submitOrder,
      isProcessing,
    };
  },
};
</script>
