<script setup lang="ts">
import { onMounted, ref, computed } from "vue";
import { useRoute } from "vue-router";
import type { Product } from "../stores/product";
import { useCartStore } from "../stores/cart";

const route = useRoute();
const product_store = ref<Product | null>(null);
const quantity = ref(1);
const activeTab = ref("description"); // 'description' or 'reviews'
const ratingCounts = ref<number[]>([0, 0, 0, 0, 0]); // Số lượng đánh giá cho các mức 1-5 sao
const totalRatings = ref(0); // Tổng số đánh giá
const averageRating = ref(0); // Điểm đánh giá trung bình
const cart_message = ref(""); // Thông báo sau khi thêm vào giỏ hàng
const show_cart_message = ref(false); // Hiển thị thông báo
const isLoading = ref(false); // Trạng thái loading
const selectedColor = ref(null); // Màu đã chọn
const selectedSize = ref(null); // Size đã chọn

const cartStore = useCartStore();

const handleTabClick = (tab: string) => {
  activeTab.value = tab;
};

// Hàm xử lý đường dẫn hình ảnh
const getImageSrc = (imagePath: string) => {
  if (!imagePath) return "";

  // Nếu đường dẫn bắt đầu bằng http hoặc https, trả về nguyên đường dẫn
  if (imagePath.startsWith("http")) {
    return imagePath;
  }

  // Nếu không, thêm tiền tố đường dẫn tới thư mục img_sp
  return `/public/img/img_sp/${imagePath}`;
};

// Hàm thêm vào giỏ hàng
const addToCart = async () => {
  if (isLoading.value) return;

  // Kiểm tra đã chọn sản phẩm chưa
  if (!product_store.value) {
    showMessage("Không tìm thấy thông tin sản phẩm", "error");
    return;
  }

  // Kiểm tra đã chọn màu và size chưa (nếu sản phẩm có màu và size)
  if (
    product_store.value?.san_pham_mau_size &&
    product_store.value.san_pham_mau_size.length > 0
  ) {
    if (!selectedColor.value) {
      showMessage("Vui lòng chọn màu sắc", "error");
      return;
    }
    if (!selectedSize.value) {
      showMessage("Vui lòng chọn kích thước", "error");
      return;
    }
  }

  // Bắt đầu loading
  isLoading.value = true;

  try {
    // Chuẩn bị dữ liệu
    const cart_data = {
      id_sp: product_store.value.id,
      so_luong: quantity.value,
      id_mau: selectedColor.value,
      id_size: selectedSize.value,
    };

    console.log("Dữ liệu gửi đi:", cart_data);

    // Sử dụng cartStore để thêm sản phẩm vào giỏ hàng
    const result = await cartStore.addToCart(cart_data);

    if (result) {
      showMessage("Đã thêm sản phẩm vào giỏ hàng", "success");
      // Thêm hiệu ứng cho nút
      const cartButton = document.querySelector(".btn-order");
      if (cartButton) {
        cartButton.classList.add("success-pulse");
        setTimeout(() => {
          cartButton.classList.remove("success-pulse");
        }, 1500);
      }

      // Chuyển hướng đến trang giỏ hàng với tham số query để hiển thị thông báo
      setTimeout(() => {
        window.location.href =
          "/giohang?from_product=true&added=" + product_store.value?.id;
      }, 1000);
    } else {
      showMessage(
        cartStore.error || "Có lỗi xảy ra khi thêm vào giỏ hàng",
        "error"
      );
    }
  } catch (error) {
    console.error("Lỗi khi thêm vào giỏ hàng:", error);
    showMessage("Có lỗi xảy ra khi thêm vào giỏ hàng", "error");
  } finally {
    isLoading.value = false;
  }
};

// Hiển thị thông báo
const showMessage = (
  message: string,
  type: "success" | "error" = "success"
) => {
  // Nếu là thông báo thành công, thêm thông tin sản phẩm vào
  if (type === "success" && product_store.value) {
    // Tạo thông báo với thông tin sản phẩm
    const productName = product_store.value.Ten_san_pham;
    const productPrice = product_store.value.Gia.toLocaleString("vi-VN") + "đ";
    const productSize = selectedSize.value ? `Size: ${selectedSize.value}` : "";
    const productColor = selectedColor.value
      ? `Màu: #${selectedColor.value}`
      : "";
    const productQuantity = `Số lượng: ${quantity.value}`;

    // Chuỗi thông tin sản phẩm
    const productInfo = [
      productName,
      productPrice,
      productSize,
      productColor,
      productQuantity,
    ]
      .filter(Boolean)
      .join(" | ");

    cart_message.value = `${message}\n${productInfo}`;
  } else {
    cart_message.value = message;
  }

  show_cart_message.value = true;

  // Tự động ẩn thông báo sau 3 giây
  setTimeout(() => {
    show_cart_message.value = false;
  }, 3000);
};

// Hàm chọn màu
const selectColor = (colorId) => {
  selectedColor.value = colorId;
};

// Hàm chọn size
const selectSize = (sizeId) => {
  selectedSize.value = sizeId;
};

// Lọc màu sắc không trùng lặp
const uniqueColors = computed(() => {
  if (!product_store.value?.san_pham_mau_size) return [];

  // Lấy danh sách ID_Mau không trùng lặp
  const uniqueMauIds = [
    ...new Set(
      product_store.value.san_pham_mau_size.map((item) => item.ID_Mau)
    ),
  ];
  return uniqueMauIds;
});

// Lọc kích thước không trùng lặp
const uniqueSizes = computed(() => {
  if (!product_store.value?.san_pham_mau_size) return [];

  // Lấy danh sách ID_Kichthuoc không trùng lặp
  const uniqueSizeIds = [
    ...new Set(
      product_store.value.san_pham_mau_size.map((item) => item.ID_Kichthuoc)
    ),
  ];
  return uniqueSizeIds;
});

const fetchProduct = async () => {
  try {
    const response = await fetch(
      `http://localhost:8000/api/sanpham/${route.params.id}`
    );
    const data = await response.json();
    if (data.status == "success") {
      product_store.value = data.data;
      console.log("Sản phẩm:", product_store.value);

      // Lấy đánh giá ngay sau khi nhận được thông tin sản phẩm
      await fetchProductRatings(route.params.id);

      // Kiểm tra cấu trúc dữ liệu mau và size
      if (
        product_store.value?.san_pham_mau_size &&
        product_store.value.san_pham_mau_size.length > 0
      ) {
        console.log("Mẫu Màu Size:", product_store.value.san_pham_mau_size);
        product_store.value.san_pham_mau_size.forEach((item, index) => {
          console.log(`Item ${index}:`, item);
          console.log(`Màu ID:`, item.ID_Mau);
          console.log(`Size ID:`, item.ID_Kichthuoc);
          console.log(`Màu:`, item.mau);
          console.log(`Size:`, item.size);
        });
      }
    }
  } catch (error) {
    console.error("Lỗi khi tải sản phẩm:", error);
  }
};

// Thêm hàm riêng để lấy đánh giá
const fetchProductRatings = async (productId) => {
  try {
    console.log("Fetching ratings for product ID:", productId);

    // Sử dụng endpoint chính xác để lấy đánh giá của sản phẩm
    // Route in api.php is: Route::get('/product/{id}/ratings', [RatingController::class, 'getProductRatings']);
    const response = await fetch(
      `http://localhost:8000/api/product/${productId}/ratings`
    );
    const data = await response.json();

    console.log("API đánh giá trả về:", data);

    if (data.status == "success") {
      // Mặc định giá trị ban đầu
      ratingCounts.value = [0, 0, 0, 0, 0];
      totalRatings.value = 0;
      averageRating.value = 0;

      // Cập nhật dữ liệu đánh giá trung bình từ API nếu có
      if (data.average) {
        averageRating.value = data.average;
      }

      // Gán dữ liệu đánh giá chi tiết nếu có
      if (Array.isArray(data.data) && data.data.length > 0) {
        // Gán trực tiếp vào sản phẩm
        if (product_store.value) {
          product_store.value.danh_gia = data.data;
        }

        // Đếm số lượng đánh giá cho mỗi mức sao
        data.data.forEach((rating) => {
          const starValue = parseInt(rating.So_sao);
          if (starValue >= 1 && starValue <= 5) {
            ratingCounts.value[starValue - 1]++;
          }
        });

        // Cập nhật tổng số đánh giá
        totalRatings.value = data.data.length;
      }
    } else {
      console.warn("API đánh giá trả về không thành công:", data);
      console.warn(
        "Đảm bảo rằng route /api/product/{id}/ratings đã được đăng ký"
      );
    }
  } catch (error) {
    console.error("Lỗi khi tải đánh giá:", error);
  }
};

// Tính phần trăm cho mỗi mức đánh giá
const getRatingPercentage = (starIndex: number) => {
  if (totalRatings.value === 0) return 0;
  return (ratingCounts.value[starIndex] / totalRatings.value) * 100;
};

onMounted(() => {
  fetchProduct();
});
</script>
<template>
  <main v-if="product_store">
    <section class="product-detail">
      <div class="container">
        <!-- Thay thế thông báo cũ bằng toast notification đẹp hơn -->
        <div
          v-if="show_cart_message"
          class="toast-notification"
          :class="
            cart_message.includes('lỗi') || cart_message.includes('Vui lòng')
              ? 'toast-error'
              : 'toast-success'
          "
        >
          <div class="toast-icon">
            <i
              v-if="
                cart_message.includes('lỗi') ||
                cart_message.includes('Vui lòng')
              "
              class="bi bi-exclamation-circle-fill"
            ></i>
            <i v-else class="bi bi-check-circle-fill"></i>
          </div>
          <div class="toast-content">
            <h4 class="toast-title">
              {{
                cart_message.includes("lỗi") ||
                cart_message.includes("Vui lòng")
                  ? "Thông báo lỗi"
                  : "Thêm vào giỏ hàng thành công"
              }}
            </h4>
            <p class="toast-message">
              <!-- Hiển thị phần đầu của thông báo (trước \n) -->
              {{ cart_message.split("\n")[0] }}
            </p>

            <!-- Hiển thị thông tin sản phẩm nếu có (phần sau \n) -->
            <div
              v-if="cart_message.split('\n').length > 1"
              class="product-info-toast"
            >
              <!-- Hiển thị thông tin sản phẩm với định dạng đẹp hơn -->
              <div
                v-for="(info, index) in cart_message
                  .split('\n')[1]
                  .split(' | ')"
                :key="index"
                class="product-info-item"
              >
                {{ info }}
              </div>
            </div>
          </div>
          <div class="toast-close" @click="show_cart_message = false">
            <i class="bi bi-x"></i>
          </div>
          <div class="toast-progress-bar"></div>
        </div>

        <div class="row gx-0">
          <div class="col-12 col-lg-6 p-0">
            <div class="px-lg-2 px-3 d-flex gap-4">
              <div class="product-thumnail">
                <div
                  v-if="product_store.anh_phu"
                  v-for="(image, index) in product_store.anh_phu"
                  :key="index"
                  class="custom-thumnail__box mb-3"
                >
                  <img
                    :src="getImageSrc(image)"
                    :alt="product_store.Ten_san_pham"
                    class="img-fluid"
                  />
                </div>
              </div>
              <div class="flex-fill">
                <img
                  :src="getImageSrc(product_store.Anh_dai_dien)"
                  :alt="product_store.Ten_san_pham"
                  class="rounded-4"
                />
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-6 p-0">
            <div class="product-info px-5">
              <a href="#" class="category-bkdetail__link mt-2">{{
                product_store.danh_muc?.ten_danh_muc
              }}</a>
              <h2 class="text-start productname my-2">
                {{ product_store.Ten_san_pham }}
              </h2>
              <p class="text-muted fs-4">Mã sản phẩm: {{ product_store.id }}</p>
              <p class="mt-4">
                <del
                  class="fs-4 me-3"
                  style="color: var(--colortext3)"
                  v-if="product_store.gia_giam"
                  >{{ product_store.gia_giam.toLocaleString("vi-VN") }}đ</del
                >
                <strong style="color: var(--accent); font-size: 1.9rem"
                  >{{ product_store.Gia.toLocaleString("vi-VN") }}đ</strong
                >
              </p>
              <div class="d-flex align-items-center gap-2 mt-2 mb-3">
                <template v-for="star in 5" :key="star">
                  <i
                    class="bi bi-star-fill"
                    :class="
                      star <= averageRating ? 'color-star' : 'color-star-gray'
                    "
                  ></i>
                </template>
                <p
                  class="m-0 me-1 fs-3 fw-bold"
                  style="color: var(--colortext1)"
                >
                  {{ averageRating }}
                </p>
                <i class="bi bi-dot fs-2" style="color: var(--colortext2)"></i>
                <a
                  href="#"
                  class="m-0 fs-4 fw-regular fst-italic rating-link-view"
                  @click.prevent="handleTabClick('reviews')"
                  >Đánh giá ({{ totalRatings }})</a
                >
              </div>
              <p class="product-desc">{{ product_store.Mo_ta }}</p>
              <template
                v-if="
                  product_store.san_pham_mau_size &&
                  product_store.san_pham_mau_size.length > 0
                "
              >
                <h5 class="mt-4 fs-4">Màu sắc:</h5>
                <div class="color-options mt-3">
                  <div
                    v-for="(colorId, index) in uniqueColors"
                    :key="'color-' + index"
                    class="color-option"
                    :class="{ selected: selectedColor === colorId }"
                    :style="{ backgroundColor: '#' + colorId }"
                    @click="selectColor(colorId)"
                  ></div>
                </div>

                <h5 class="mt-4 fs-4">Size:</h5>
                <div class="size-options mt-3">
                  <div
                    v-for="(sizeId, index) in uniqueSizes"
                    :key="'size-' + index"
                    class="size-option fs-4"
                    :class="{ selected: selectedSize === sizeId }"
                    @click="selectSize(sizeId)"
                  >
                    {{ sizeId }}
                  </div>
                </div>
              </template>
              <div class="mt-4 d-flex align-items-center gap-4">
                <div>
                  <input
                    type="number"
                    v-model="quantity"
                    class="form-date product-quantity"
                    name="quantity"
                    min="1"
                  />
                </div>
                <button
                  class="btn-order"
                  :disabled="isLoading"
                  @click.prevent="addToCart"
                >
                  <i v-if="isLoading" class="bi bi-arrow-repeat"></i>
                  <span v-else>Thêm vào giỏ hàng</span>
                </button>
                <div class="d-flex align-items-center gap-1 btn-add-wishlist">
                  <div><i class="bi bi-heart-fill fs-3"></i></div>
                  <div><i class="bi bi-facebook fs-3"></i></div>
                  <div><i class="bi bi-messenger fs-3"></i></div>
                  <div><i class="bi bi-share fs-3"></i></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="detail-infor">
      <div class="container">
        <ul class="d-flex list-unstyled detail-infor__list">
          <li
            class="detail-infor__desc-rating"
            :class="{ 'desc-detail__seleted': activeTab == 'description' }"
            @click="handleTabClick('description')"
          >
            Mô tả
          </li>
          <li
            class="detail-infor__desc-rating"
            :class="{ 'desc-detail__seleted': activeTab == 'reviews' }"
            @click="handleTabClick('reviews')"
          >
            Đánh giá
          </li>
        </ul>
        <div v-show="activeTab == 'description'" class="desc-box">
          <div class="row gx-0">
            <div class="col-12 col-lg-7">
              <div class="padding-custom">
                <div>
                  <h3 class="fs-3 fw-semibold text-start">
                    Thông tin sản phẩm
                  </h3>
                  <div
                    class="mt-3"
                    style="
                      color: var(--colortext2);
                      font-size: 1.5rem;
                      line-height: 2.4rem;
                      text-align: justify;
                    "
                  >
                    {{ product_store.Mo_ta }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-5">
              <div class="ps-5 pe-lg-0 pe-3 detail-infor__thumnail">
                <template v-if="product_store.anh_phu">
                  <div
                    v-for="(image, index) in product_store.anh_phu"
                    :key="index"
                    class="my-2"
                  >
                    <img
                      :src="getImageSrc(image)"
                      :alt="product_store.Ten_san_pham"
                      class="img-fluid"
                    />
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
        <div v-show="activeTab === 'reviews'" class="comment-box">
          <div class="row gx-0 justify-content-between mt-5">
            <div class="col-6">
              <div class="comment-box__rating mt-4">
                <div
                  class="d-flex align-items-center justify-content-between gap-3"
                >
                  <h1 class="m-0 fw-semibold" style="font-size: 3.2rem">
                    {{ averageRating }}
                  </h1>
                  <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                      <template v-for="star in 5" :key="star">
                        <i
                          class="bi bi-star-fill fs-2"
                          :class="
                            star <= averageRating
                              ? 'color-star'
                              : 'color-star-gray'
                          "
                        ></i>
                      </template>
                    </div>
                    <p
                      class="fs-4 fst-italic m-0 text-end"
                      style="color: var(--colortext3)"
                    >
                      {{ totalRatings }} đánh giá
                    </p>
                  </div>
                </div>
                <hr style="color: var(--colortext3)" />

                <!-- 5 sao -->
                <div
                  class="d-flex align-items-center justify-content-between my-4 mt-5"
                >
                  <h3 style="width: 5%; font-size: 2rem">5</h3>
                  <i class="bi bi-star-fill fs-3 color-star"></i>
                  <div class="bkdt__percent-rating">
                    <div
                      class="bkdt__percent-rating-child"
                      :style="{ width: getRatingPercentage(4) + '%' }"
                    ></div>
                  </div>
                  <p
                    class="fs-4 fst-italic m-0 text-end"
                    style="color: var(--colortext3); width: 90px"
                  >
                    {{ ratingCounts[4] }} đánh giá
                  </p>
                </div>

                <!-- 4 sao -->
                <div
                  class="d-flex align-items-center justify-content-between my-4"
                >
                  <h3 style="width: 5%; font-size: 2rem">4</h3>
                  <i class="bi bi-star-fill fs-3 color-star"></i>
                  <div class="bkdt__percent-rating">
                    <div
                      class="bkdt__percent-rating-child"
                      :style="{ width: getRatingPercentage(3) + '%' }"
                    ></div>
                  </div>
                  <p
                    class="fs-4 fst-italic m-0 text-end"
                    style="color: var(--colortext3); width: 90px"
                  >
                    {{ ratingCounts[3] }} đánh giá
                  </p>
                </div>

                <!-- 3 sao -->
                <div
                  class="d-flex align-items-center justify-content-between my-4"
                >
                  <h3 style="width: 5%; font-size: 2rem">3</h3>
                  <i class="bi bi-star-fill fs-3 color-star"></i>
                  <div class="bkdt__percent-rating">
                    <div
                      class="bkdt__percent-rating-child"
                      :style="{ width: getRatingPercentage(2) + '%' }"
                    ></div>
                  </div>
                  <p
                    class="fs-4 fst-italic m-0 text-end"
                    style="color: var(--colortext3); width: 90px"
                  >
                    {{ ratingCounts[2] }} đánh giá
                  </p>
                </div>

                <!-- 2 sao -->
                <div
                  class="d-flex align-items-center justify-content-between my-4"
                >
                  <h3 style="width: 5%; font-size: 2rem">2</h3>
                  <i class="bi bi-star-fill fs-3 color-star"></i>
                  <div class="bkdt__percent-rating">
                    <div
                      class="bkdt__percent-rating-child"
                      :style="{ width: getRatingPercentage(1) + '%' }"
                    ></div>
                  </div>
                  <p
                    class="fs-4 fst-italic m-0 text-end"
                    style="color: var(--colortext3); width: 90px"
                  >
                    {{ ratingCounts[1] }} đánh giá
                  </p>
                </div>

                <!-- 1 sao -->
                <div
                  class="d-flex align-items-center justify-content-between my-4"
                >
                  <h3 style="width: 5%; font-size: 2rem">1</h3>
                  <i class="bi bi-star-fill fs-3 color-star"></i>
                  <div class="bkdt__percent-rating">
                    <div
                      class="bkdt__percent-rating-child"
                      :style="{ width: getRatingPercentage(0) + '%' }"
                    ></div>
                  </div>
                  <p
                    class="fs-4 fst-italic m-0 text-end"
                    style="color: var(--colortext3); width: 90px"
                  >
                    {{ ratingCounts[0] }} đánh giá
                  </p>
                </div>

                <p class="my-5 py-2 fs-3" style="color: var(--colortext2)">
                  Dựa trên {{ totalRatings }} đánh giá đến từ khách hàng
                </p>
              </div>
            </div>
            <div class="col-5">
              <div
                style="
                  background-color: var(--white);
                  border-radius: 12px;
                  box-shadow: 0 0 16px var(--shadow2);
                "
              >
                <h2
                  class="m-0 fs-2 fw-semibold text-start"
                  style="padding: 24px"
                >
                  Tất cả bình luận
                </h2>
                <div class="comment-box__comment-content mt-1">
                  <!-- Hiển thị danh sách bình luận nếu có dữ liệu -->
                  <div
                    v-if="
                      product_store.danh_gia &&
                      product_store.danh_gia.length > 0
                    "
                  >
                    <div
                      v-for="(review, index) in product_store.danh_gia"
                      :key="index"
                      class="px-5"
                    >
                      <div class="d-flex gap-4 flex-fill">
                        <div class="bkdt__comment-user">
                          <img
                            :src="review.user?.avatar || '../img/user1.jpg'"
                            alt=""
                            class="img-fluid rounded-circle"
                          />
                        </div>
                        <div>
                          <div class="">
                            <h3 class="fs-3 text-start m-0">
                              {{ review.user?.name || "Người dùng" }}
                            </h3>
                            <div class="d-flex align-items-center gap-3">
                              <div class="d-flex align-items-center gap-1 mb-1">
                                <template v-for="star in 5" :key="star">
                                  <i
                                    class="bi bi-star-fill fs-4"
                                    :class="
                                      star <= review.So_sao
                                        ? 'color-star'
                                        : 'color-star-gray'
                                    "
                                  ></i>
                                </template>
                              </div>
                              <i
                                class="bi bi-dot fs-4"
                                style="color: var(--colortext2)"
                              ></i>
                              <p
                                class="m-0 fs-4 hidden-text"
                                style="color: var(--colortext3)"
                              >
                                {{
                                  new Date(
                                    review.created_at
                                  ).toLocaleDateString("vi-VN")
                                }}
                              </p>
                              <div class="flex-fill">
                                <i
                                  class="bi bi-hand-thumbs-up-fill fs-2 color-star"
                                ></i>
                              </div>
                            </div>
                          </div>
                          <div>
                            <p
                              class="m-0 mt-2"
                              style="
                                color: var(--colortext1);
                                font-size: 1.5rem;
                                line-height: 2rem;
                              "
                            >
                              {{
                                review.Noi_dung || "Không có nội dung đánh giá"
                              }}
                            </p>
                          </div>
                        </div>
                      </div>
                      <hr
                        style="color: var(--colortext3); margin: 24px 0 20px 0"
                      />
                    </div>
                  </div>

                  <!-- Hiển thị thông báo khi không có bình luận -->
                  <div v-else class="px-5 py-4 text-center">
                    <p class="fs-4">Chưa có đánh giá nào cho sản phẩm này</p>
                  </div>
                </div>
                <form action="" class="p-5 pt-2">
                  <input
                    type="text"
                    class="form-date d-block w-100"
                    placeholder="Viết bình luận..."
                  />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="popular-y mx-lg-auto mx-2">
      <div class="container">
        <div
          class="d-flex justify-content-between align-items-center mb-2 px-2"
        >
          <h1 class="title-section">ĐỀ XUẤT CHO BẠN</h1>
          <a href="" class="seemore">
            <div>Xem thêm</div>
            <i class="bi bi-caret-right-fill"></i>
          </a>
        </div>
        <div class="row gx-0">
          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="product my-3">
              <a href="#" class="link-img-p">
                <img src="/public/img/p1.png" alt="" class="img-fluid" />
              </a>
              <div class="product-infor">
                <a href="#" class="m-0 title-product fs-3"
                  >Áo thun Unisex phối bo cổ</a
                >
                <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                  <p class="text-rating m-0 ms-3 fs-4">(4/5)</p>
                </div>
                <div class="text-location py-2">
                  <p class="m-0">
                    Áo thun nam thể thao phối bo cổ được thiết kế năng động, trẻ
                    trung
                  </p>
                </div>
                <div class="d-flex align-items-center gap-2 text-location">
                  <i class="bi bi-eye"></i>
                  <p class="custom-open m-0">Lượt xem: 230</p>
                </div>
                <div class="price d-flex align-items-end gap-4">
                  <p class="m-0">240.000đ</p>
                  <del>320.000đ</del>
                </div>
              </div>
              <div class="atc-love-box">
                <a href="" class="atc-love"><i class="bi bi-heart-fill"></i></a>
                <a href="" class="atc-love atc-icon"
                  ><i class="bi bi-cart-fill"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="product my-3">
              <a href="#" class="link-img-p">
                <img src="/public/img/p1.png" alt="" class="img-fluid" />
              </a>
              <div class="product-infor">
                <a href="#" class="m-0 title-product fs-3"
                  >Áo thun Unisex phối bo cổ</a
                >
                <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                  <p class="text-rating m-0 ms-3 fs-4">(4/5)</p>
                </div>
                <div class="text-location py-2">
                  <p class="m-0">
                    Áo thun nam thể thao phối bo cổ được thiết kế năng động, trẻ
                    trung
                  </p>
                </div>
                <div class="d-flex align-items-center gap-2 text-location">
                  <i class="bi bi-eye"></i>
                  <p class="custom-open m-0">Lượt xem: 230</p>
                </div>
                <div class="price d-flex align-items-end gap-4">
                  <p class="m-0">240.000đ</p>
                  <del>320.000đ</del>
                </div>
              </div>
              <div class="atc-love-box">
                <a href="" class="atc-love"><i class="bi bi-heart-fill"></i></a>
                <a href="" class="atc-love atc-icon"
                  ><i class="bi bi-cart-fill"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="product my-3">
              <a href="#" class="link-img-p">
                <img src="/public/img/p1.png" alt="" class="img-fluid" />
              </a>
              <div class="product-infor">
                <a href="#" class="m-0 title-product fs-3"
                  >Áo thun Unisex phối bo cổ</a
                >
                <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                  <p class="text-rating m-0 ms-3 fs-4">(4/5)</p>
                </div>
                <div class="text-location py-2">
                  <p class="m-0">
                    Áo thun nam thể thao phối bo cổ được thiết kế năng động, trẻ
                    trung
                  </p>
                </div>
                <div class="d-flex align-items-center gap-2 text-location">
                  <i class="bi bi-eye"></i>
                  <p class="custom-open m-0">Lượt xem: 230</p>
                </div>
                <div class="price d-flex align-items-end gap-4">
                  <p class="m-0">240.000đ</p>
                  <del>320.000đ</del>
                </div>
              </div>
              <div class="atc-love-box">
                <a href="" class="atc-love"><i class="bi bi-heart-fill"></i></a>
                <a href="" class="atc-love atc-icon"
                  ><i class="bi bi-cart-fill"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-3 col-md-6 p-0">
            <div class="product my-3">
              <a href="#" class="link-img-p">
                <img src="/public/img/p1.png" alt="" class="img-fluid" />
              </a>
              <div class="product-infor">
                <a href="#" class="m-0 title-product fs-3"
                  >Áo thun Unisex phối bo cổ</a
                >
                <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill color-star fs-5"></i>
                  <i class="bi bi-star-fill fs-5 color-star-gray"></i>
                  <p class="text-rating m-0 ms-3 fs-4">(4/5)</p>
                </div>
                <div class="text-location py-2">
                  <p class="m-0">
                    Áo thun nam thể thao phối bo cổ được thiết kế năng động, trẻ
                    trung
                  </p>
                </div>
                <div class="d-flex align-items-center gap-2 text-location">
                  <i class="bi bi-eye"></i>
                  <p class="custom-open m-0">Lượt xem: 230</p>
                </div>
                <div class="price d-flex align-items-end gap-4">
                  <p class="m-0">240.000đ</p>
                  <del>320.000đ</del>
                </div>
              </div>
              <div class="atc-love-box">
                <a href="" class="atc-love"><i class="bi bi-heart-fill"></i></a>
                <a href="" class="atc-love atc-icon"
                  ><i class="bi bi-cart-fill"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <div v-else class="container py-5 text-center">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Đang tải...</span>
    </div>
  </div>
</template>

<style scoped>
.color-star {
  color: var(--hover2);
}

.color-star-gray {
  color: #ddd;
}

.color-option {
  display: inline-block;
  width: 35px;
  height: 35px;
  margin-right: 8px;
  margin-bottom: 8px;
  border: 1px solid #ddd;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.2s ease;
}

.color-option:hover {
  transform: scale(1.1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.color-option.selected {
  border: 2px solid var(--accent);
  transform: scale(1.1);
  box-shadow: 0 0 0 2px rgba(0, 128, 0, 0.2);
}

.size-options {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.size-option {
  padding: 8px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  text-align: center;
  transition: all 0.2s ease;
}

.size-option:hover {
  border-color: var(--accent);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.size-option.selected {
  background-color: var(--accent);
  color: white;
  border-color: var(--accent);
}

/* CSS cho thanh đánh giá */
.bkdt__percent-rating {
  width: 60%;
  height: 10px;
  background-color: #e7e7e7;
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

.alert {
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.btn-order {
  display: inline-block;
  background-color: var(--accent);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1.5rem;
  min-width: 200px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-order:hover {
  background-color: #006600;
  transform: translateY(-2px);
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.btn-order:disabled {
  background-color: #aaaaaa;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-order i {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* CSS cho toast notification */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 380px;
  min-height: 80px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  display: flex;
  padding: 15px;
  z-index: 9999;
  animation: slideIn 0.3s ease-out forwards;
  overflow: hidden;
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-success {
  border-left: 5px solid #4caf50;
}

.toast-error {
  border-left: 5px solid #f44336;
}

.toast-icon {
  width: 32px;
  height: 32px;
  font-size: 2.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
}

.toast-success .toast-icon {
  color: #4caf50;
}

.toast-error .toast-icon {
  color: #f44336;
}

.toast-content {
  flex: 1;
  padding-right: 10px;
}

.toast-title {
  margin: 0 0 5px 0;
  font-size: 1.6rem;
  font-weight: 600;
  color: #333;
}

.toast-message {
  margin: 0;
  font-size: 1.4rem;
  color: #666;
}

.toast-close {
  width: 24px;
  height: 24px;
  font-size: 1.6rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #999;
  cursor: pointer;
  transition: color 0.2s;
}

.toast-close:hover {
  color: #333;
}

.toast-progress-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 100%;
  background-color: #ddd;
}

.toast-success .toast-progress-bar::before {
  content: "";
  position: absolute;
  height: 100%;
  width: 100%;
  background-color: #4caf50;
  animation: progress 3s linear forwards;
}

.toast-error .toast-progress-bar::before {
  content: "";
  position: absolute;
  height: 100%;
  width: 100%;
  background-color: #f44336;
  animation: progress 3s linear forwards;
}

@keyframes progress {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

/* Thêm CSS cho thông tin sản phẩm trong toast */
.product-info-toast {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px dashed #ddd;
  font-size: 1.3rem;
}

.product-info-item {
  margin-bottom: 5px;
  color: #555;
  display: flex;
  align-items: center;
}

.product-info-item:last-child {
  margin-bottom: 0;
}

/* Thêm animation cho nút Thêm vào giỏ */
@keyframes successPulse {
  0% {
    box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
  }
}

.btn-order.success-pulse {
  animation: successPulse 1.5s ease-out;
}
</style>
