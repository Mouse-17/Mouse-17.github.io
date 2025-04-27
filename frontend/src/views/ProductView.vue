<script lang="ts" setup>
import {onMounted, ref, watch} from "vue";
import {useRoute, useRouter} from "vue-router";
import type {Product} from "../stores/product";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const products = ref<Product[]>([]);
const bestseller = ref<Product[]>([]);
const currentPage = ref(1);
const lastPage = ref(1);
const totalItems = ref(0);
const perPage = ref(9);
const isLoading = ref(false);

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

// Dữ liệu cho bộ lọc từ API
const categories = ref<any[]>([]);
const brands = ref<any[]>([]);

// Lấy danh sách danh mục và thương hiệu
const fetchCategories = async () => {
  try {
    const response = await axios.get("/api/categories");
    if (response.data.status === "success") {
      categories.value = response.data.data;
    }
  } catch (error) {
    console.error("Lỗi khi tải danh mục:", error);
  }
};

const fetchBrands = async () => {
  try {
    const response = await axios.get("/api/thuong-hieu");
    const data = response.data;
    if (data.status === "success") {
      brands.value = data.data;
    } else {
      // Fallback nếu API không có sẵn
      brands.value = [
        {id: 1, Ten_thuong_hieu: "Nike"},
        {id: 2, Ten_thuong_hieu: "Adidas"},
        {id: 3, Ten_thuong_hieu: "Puma"},
        {id: 4, Ten_thuong_hieu: "Lining"},
        {id: 5, Ten_thuong_hieu: "Yonex"},
        {id: 6, Ten_thuong_hieu: "Wilson"},
        {id: 7, Ten_thuong_hieu: "Kamito"},
        {id: 8, Ten_thuong_hieu: "Head"},
      ];
    }
  } catch (error) {
    console.error("Lỗi khi tải thương hiệu:", error);
    // Fallback nếu API không có sẵn
    brands.value = [
      {id: 1, Ten_thuong_hieu: "Nike"},
      {id: 2, Ten_thuong_hieu: "Adidas"},
      {id: 3, Ten_thuong_hieu: "Puma"},
      {id: 4, Ten_thuong_hieu: "Lining"},
      {id: 5, Ten_thuong_hieu: "Yonex"},
      {id: 6, Ten_thuong_hieu: "Wilson"},
      {id: 7, Ten_thuong_hieu: "Kamito"},
      {id: 8, Ten_thuong_hieu: "Head"},
    ];
  }
};

// Hàm lấy danh sách sản phẩm
const fetchProducts = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get(`/api/sanpham?trang=${currentPage.value}&tukhoa=${route.query.tukhoa || ""}`);
    const data = response.data;

    if (data.status == "success") {
      products.value = data.data;
      currentPage.value = data.pagination.current_page;
      lastPage.value = data.pagination.last_page;
      totalItems.value = data.pagination.total;
      perPage.value = data.pagination.per_page;
    }
  } catch (error) {
    console.error("Lỗi khi tải sản phẩm:", error);
  } finally {
    isLoading.value = false;
  }
};

// Hàm xử lý khi chuyển trang
const handlePageChange = (page: number) => {
  if (page < 1 || page > lastPage.value) return;

  currentPage.value = page;

  // Kiểm tra nếu đang lọc thì gọi sortProduct để giữ nguyên bộ lọc
  if (
      sort.value !== "Phổ biến" ||
      price.value !== "" ||
      category.value !== "" ||
      brand.value !== "" ||
      rating.value !== "" ||
      minPrice.value !== "" ||
      maxPrice.value !== ""
  ) {
    sortProduct();
  } else {
    // Không có bộ lọc, cập nhật URL và gọi fetchProducts
    router.push({
      path: route.path,
      query: {...route.query, trang: page},
    });
  }
};

// Khi component được mount
onMounted(() => {
  currentPage.value = parseInt(route.query.trang as string) || 1;
  fetchProducts();
  fetchCategories();
  fetchBrands();
});

// Theo dõi thay đổi của route để cập nhật dữ liệu
watch(
    () => route.query,
    () => {
      currentPage.value = parseInt(route.query.trang as string) || 1;
      fetchProducts();
    },
    {immediate: true}
);

// lấy bestseller
const fetchBestseller = async () => {
  try {
    const response = await axios.get("/api/bestseller");
    if (response.data.status === "success" && Array.isArray(response.data.data)) {
      bestseller.value = response.data.data;
    }
  } catch (error) {
    console.error("Lỗi khi tải sản phẩm bán chạy:", error);
  }
};

onMounted(() => {
  fetchBestseller();
});

// Hàm xử lý khi thay đổi bộ lọc
const sort = ref("Phổ biến");
const price = ref("");
const category = ref("");
const brand = ref("");
const rating = ref("");
const minPrice = ref("");
const maxPrice = ref("");

const sortProduct = async () => {
  try {
    isLoading.value = true;

    // Xử lý dữ liệu trước khi gửi
    let minPriceVal = null;
    let maxPriceVal = null;

    // Chỉ chuyển đổi và sử dụng giá trị nếu đã nhập
    if (
        minPrice.value !== "" &&
        minPrice.value !== null &&
        minPrice.value !== undefined
    ) {
      minPriceVal = parseFloat(minPrice.value);
      if (isNaN(minPriceVal)) {
        minPriceVal = null;
      }
    }

    if (
        maxPrice.value !== "" &&
        maxPrice.value !== null &&
        maxPrice.value !== undefined
    ) {
      maxPriceVal = parseFloat(maxPrice.value);
      if (isNaN(maxPriceVal)) {
        maxPriceVal = null;
      }
    }

    const ratingVal = rating.value ? parseFloat(rating.value) : null;

    console.log("Sending filter request:", {
      popular: sort.value || null,
      price: price.value || null,
      minPrice: minPriceVal,
      maxPrice: maxPriceVal,
      category: category.value || null,
      brand: brand.value || null,
      rating: ratingVal,
      page: currentPage.value || 1,
    });

    const response = await axios.post(
        "/api/sanpham/boloc",
        {
          popular: sort.value || null,
          price: price.value || null,
          minPrice: minPriceVal,
          maxPrice: maxPriceVal,
          category: category.value || null,
          brand: brand.value || null,
          rating: ratingVal,
          page: currentPage.value || 1,
        }
    );

    if (response.data.status === "success") {
      console.log("Filter response:", response.data);
      products.value = response.data.data;
      currentPage.value = response.data.pagination.current_page;
      lastPage.value = response.data.pagination.last_page;
      totalItems.value = response.data.pagination.total;
      perPage.value = response.data.pagination.per_page;
    } else {
      console.error("Lỗi khi lọc sản phẩm:", response.data.message);
    }
  } catch (error) {
    console.error("Lỗi:", error);

    // Hiển thị thông báo lỗi cụ thể hơn
    if (error.response) {
      console.error("Lỗi response:", error.response.data);

      // Hiển thị thông báo lỗi chi tiết từ backend nếu có
      const errorMessage = error.response.data.message
          ? error.response.data.message
          : "Đã xảy ra lỗi khi lọc sản phẩm";

      console.error("Chi tiết lỗi:", errorMessage);
    }

    // Đặt giá trị mặc định để tránh lỗi rendering
    products.value = [];
    currentPage.value = 1;
    lastPage.value = 1;
    totalItems.value = 0;
  } finally {
    isLoading.value = false;
  }
};

// Biến để lưu timeout
let minPriceTimeout: ReturnType<typeof setTimeout> | null = null;

// Hàm áp dụng bộ lọc
const applyFilters = () => {
  currentPage.value = 1; // Reset về trang đầu tiên khi áp dụng bộ lọc
  sortProduct();
};

// Reset tất cả các bộ lọc
const resetFilter = () => {
  sort.value = "Phổ biến";
  price.value = "";
  category.value = "";
  brand.value = "";
  rating.value = "";
  minPrice.value = "";
  maxPrice.value = "";
  currentPage.value = 1;

  // Trở về trang sản phẩm không có bộ lọc
  fetchProducts();

  console.log("Đã đặt lại tất cả các bộ lọc");
};

const addCart = (product: Product) => {
  // console.log(product);
  showQuickAddModal.value = true;
  selectedProduct.value = product;
  // Reset các giá trị đã chọn trước đó
  selectedQuantity.value = 1;
  // Size và màu sẽ được thiết lập trong fetchProductOptions
};

// Thêm mới: Xử lý mở popup chọn size, màu, số lượng
const showQuickAddModal = ref(false);
const selectedProduct = ref<Product | null>(null);
const selectedSize = ref("");
const selectedColor = ref("");
const selectedQuantity = ref(1);
const availableSizes = ref<string[]>([]);
const availableColors = ref<string[]>([]);

// Lấy sizes và colors cho sản phẩm từ database
const fetchProductOptions = async (productId: number) => {
  try {
    // API endpoint để lấy thông tin size và màu
    const response = await axios.get(`/api/sanpham/giohang/${productId}`);
    const data = response.data;

    if (data.status == "success") {
      // Lấy thông tin từ API
      const productData = data.data;

      // Lấy kích thước từ API
      if (
          productData.sizes &&
          Array.isArray(productData.sizes) &&
          productData.sizes.length > 0
      ) {
        availableSizes.value = productData.sizes;
      } else {
        // Fallback nếu API không trả về sizes
        setDefaultSizes(productData.categoryId);
      }

      // Lấy màu sắc từ API
      if (
          productData.colors &&
          Array.isArray(productData.colors) &&
          productData.colors.length > 0
      ) {
        availableColors.value = productData.colors;
      } else {
        // Fallback nếu API không trả về colors
        setDefaultColors(productData.categoryId);
      }

      // Đặt giá trị mặc định cho size và màu
      if (availableSizes.value.length > 0) {
        selectedSize.value = availableSizes.value[0];
      }
      if (availableColors.value.length > 0) {
        selectedColor.value = availableColors.value[0];
      }
    } else {
      // Fallback khi API thất bại - sử dụng thông tin categoryId từ product
      if (selectedProduct.value && selectedProduct.value.id_danhmuc) {
        setDefaultSizes(selectedProduct.value.id_danhmuc);
        setDefaultColors(selectedProduct.value.id_danhmuc);
      } else {
        // Fallback dựa trên productId nếu không có categoryId
        setDefaultSizesByProductId(productId);
        setDefaultColorsByProductId(productId);
      }

      // Đặt giá trị mặc định
      if (availableSizes.value.length > 0) {
        selectedSize.value = availableSizes.value[0];
      }
      if (availableColors.value.length > 0) {
        selectedColor.value = availableColors.value[0];
      }
    }
  } catch (error) {
    console.error("Lỗi khi lấy thông tin size và màu:", error);
    // Fallback giá trị mặc định khi có lỗi
    if (selectedProduct.value && selectedProduct.value.id_danhmuc) {
      setDefaultSizes(selectedProduct.value.id_danhmuc);
      setDefaultColors(selectedProduct.value.id_danhmuc);
    } else {
      setDefaultSizesByProductId(productId);
      setDefaultColorsByProductId(productId);
    }

    // Đặt giá trị mặc định
    if (availableSizes.value.length > 0) {
      selectedSize.value = availableSizes.value[0];
    }
    if (availableColors.value.length > 0) {
      selectedColor.value = availableColors.value[0];
    }
  }
};

// Thiết lập size mặc định theo danh mục
const setDefaultSizes = (categoryId: number) => {
  if (categoryId == 1) {
    // Giày
    availableSizes.value = ["35", "36", "37", "38", "39", "40", "41", "42"];
  } else if (categoryId == 2 || categoryId == 3) {
    // Áo
    availableSizes.value = ["XS", "S", "M", "L", "XL", "XXL"];
  } else if (categoryId == 4) {
    // Quần
    availableSizes.value = ["28", "29", "30", "31", "32", "33", "34", "36"];
  } else {
    availableSizes.value = [];
  }
};

// Thiết lập màu mặc định theo danh mục
const setDefaultColors = (categoryId: number) => {
  if (categoryId == 1) {
    // Giày
    availableColors.value = ["ffc0cb", "008000", "000000", "ffffff"];
  } else if (categoryId == 2 || categoryId == 3) {
    // Áo
    availableColors.value = ["000000", "ffffff", "0000ff", "ff0000"];
  } else if (categoryId == 4) {
    // Quần
    availableColors.value = ["000000", "0000ff", "cccccc"];
  } else {
    availableColors.value = ["000000", "ffffff"];
  }
};

// Thiết lập size mặc định dựa trên productId khi không có categoryId
const setDefaultSizesByProductId = (productId: number) => {
  const idStr = productId.toString();
  if (idStr.includes("1")) {
    // Giày
    availableSizes.value = ["35", "36", "37", "38", "39", "40", "41", "42"];
  } else if (["2", "3"].includes(idStr)) {
    // Áo
    availableSizes.value = ["XS", "S", "M", "L", "XL", "XXL"];
  } else if (idStr.includes("4")) {
    // Quần
    availableSizes.value = ["28", "29", "30", "31", "32", "33", "34", "36"];
  } else {
    availableSizes.value = [];
  }
};

// Thiết lập màu mặc định dựa trên productId khi không có categoryId
const setDefaultColorsByProductId = (productId: number) => {
  const idStr = productId.toString();
  if (idStr.includes("1")) {
    // Giày
    availableColors.value = ["ffc0cb", "008000", "000000", "ffffff"];
  } else if (["2", "3"].includes(idStr)) {
    // Áo
    availableColors.value = ["000000", "ffffff", "0000ff", "ff0000"];
  } else if (idStr.includes("4")) {
    // Quần
    availableColors.value = ["000000", "0000ff", "cccccc"];
  } else {
    availableColors.value = ["000000", "ffffff"];
  }
};

// Tăng/giảm số lượng
const increaseQuantity = () => {
  selectedQuantity.value++;
};

const decreaseQuantity = () => {
  if (selectedQuantity.value > 1) {
    selectedQuantity.value--;
  }
};

// Thêm sản phẩm vào giỏ hàng với options đã chọn
const addToCart = async () => {
  if (!selectedProduct.value) return;

  try {
    // Đảm bảo có cart_session cookie
    const cookies = document.cookie.split(";").map((cookie) => cookie.trim());
    const cartSessionCookie = cookies.find((cookie) =>
        cookie.startsWith("cart_session=")
    );
    if (!cartSessionCookie) {
      // Tạo một session ID mới nếu chưa có
      const sessionId = "cart_" + Math.random().toString(36).substring(2, 15);
      document.cookie = `cart_session=${sessionId}; path=/; max-age=2592000`; // 30 ngày
    }

    // Chuẩn bị dữ liệu gửi đi
    const cart_data = {
      id_sp: selectedProduct.value.id,
      so_luong: selectedQuantity.value,
      id_mau: selectedColor.value,
      id_size: selectedSize.value,
    };

    console.log("Dữ liệu gửi đi:", cart_data);

    // Lấy token từ localStorage nếu có (người dùng đã đăng nhập)
    const token = localStorage.getItem("user_token");

    // Chuẩn bị headers
    const headers = {
      "Content-Type": "application/json",
    };

    // Thêm token nếu có
    if (token) {
      headers["Authorization"] = `Bearer ${token}`;
    }

    // Gọi API thêm vào giỏ hàng
    const response = await axios.post(
        "/api/cart/add",
        cart_data,
        {
          headers: headers,
          withCredentials: true
        }
    );

    // Kiểm tra status code
    if (response.status !== 200) {
      const errorData = response.data;
      console.error("Lỗi " + response.status + " từ server:", errorData);
      throw new Error("Lỗi máy chủ: " + JSON.stringify(errorData));
    }

    const result = await response.data;

    if (result.status === "success") {
      // Đóng modal
      showQuickAddModal.value = false;
      selectedProduct.value = null;
      selectedQuantity.value = 1;

      // Thông báo đã thêm vào giỏ hàng
      alert("Đã thêm vào giỏ hàng");

      // Chuyển hướng đến trang giỏ hàng với tham số query để hiển thị thông báo
      window.location.href = "/giohang?from_product=true&added=1";
    } else {
      alert(result.message || "Có lỗi xảy ra khi thêm vào giỏ hàng");
    }
  } catch (error) {
    console.error("Lỗi khi thêm vào giỏ hàng:", error);
    alert("Có lỗi xảy ra khi thêm vào giỏ hàng");
  }
};

// Theo dõi khi sản phẩm được chọn
watch(selectedProduct, (newProduct) => {
  if (newProduct) {
    fetchProductOptions(newProduct.id || 0);
  }
});
</script>
<template>
  <main>
    <section class="booking py-4">
      <div class="container">
        <div class="form-hidden">
          <h3 class="aside-title text-white text-start p-4 m-0">BỘ LỌC</h3>
          <form
              class="d-flex justify-content-between gap-3 p-4"
              style="
              border: 1px solid var(--colortext2);
              border-radius: 0 0 12px 12px;
            "
          >
            <select v-model="sort" class="form-date w-100 mt-2 select-sort">
              <option value="Phổ biến">Phổ biến</option>
              <option value="Mới nhất">Mới nhất</option>
              <option value="Cũ nhất">Cũ nhất</option>
            </select>

            <select v-model="price" class="form-date w-100 mt-2 select-sort">
              <option value="">Giá</option>
              <option value="Cao - Thấp">Cao - Thấp</option>
              <option value="Thấp - Cao">Thấp - Cao</option>
            </select>

            <select v-model="brand" class="form-date w-100 mt-2 select-sort">
              <option value="">Thương hiệu</option>
              <option v-for="b in brands" :key="b.id" :value="b.id.toString()">
                {{ b.Ten_thuong_hieu }}
              </option>
            </select>

            <select v-model="rating" class="form-date w-100 mt-2 select-sort">
              <option value="">Đánh giá</option>
              <option value="5">5 &#9733; (5 sao)</option>
              <option value="4">4 &#9733; (4-5 sao)</option>
              <option value="3">3 &#9733; (3-4 sao)</option>
              <option value="2">2 &#9733; (2-3 sao)</option>
              <option value="1">1 &#9733; (1-2 sao)</option>
            </select>

            <select v-model="category" class="form-date w-100 mt-2 select-sort">
              <option value="">Danh mục</option>
              <option
                  v-for="cat in categories"
                  :key="cat.id"
                  :value="cat.id.toString()"
              >
                {{ cat.Ten_danh_muc }}
              </option>
            </select>

            <div class="d-flex w-100 gap-2 mt-2">
              <button
                  class="btn btn-primary w-50"
                  type="button"
                  @click="applyFilters"
              >
                Áp dụng
              </button>
              <button
                  class="btn btn-secondary w-50"
                  type="button"
                  @click="resetFilter"
              >
                Đặt lại
              </button>
            </div>
          </form>
        </div>
        <div class="row gx-0">
          <aside class="col-12 col-lg-3 col-md-6 p-0 mt-2 sticky-aside">
            <div class="me-3">
              <h3 class="aside-title text-white text-start p-4 m-0">
                HIỂN THỊ THEO
              </h3>
              <form class="aside-container p-4" @submit.prevent="sortProduct">
                <select v-model="sort" class="form-date w-100 mt-2 select-sort">
                  <option value="Phổ biến">Phổ biến</option>
                  <option value="Mới nhất">Mới nhất</option>
                  <option value="Cũ nhất">Cũ nhất</option>
                </select>

                <hr class="line"/>
                <h3 class="section-title mt-3 text-start">Giá</h3>
                <div class="form-check d-flex align-items-center gap-3">
                  <input
                      id="low-to-high"
                      v-model="price"
                      class="form-check-input"
                      name="price"
                      type="radio"
                      value="Thấp - Cao"
                  />
                  <label class="form-check-label" for="low-to-high"
                  >Thấp - Cao</label
                  >
                </div>
                <div class="form-check d-flex align-items-center gap-3">
                  <input
                      id="high-to-low"
                      v-model="price"
                      class="form-check-input"
                      name="price"
                      type="radio"
                      value="Cao - Thấp"
                  />
                  <label class="form-check-label" for="high-to-low"
                  >Cao - Thấp</label
                  >
                </div>
                <div class="d-flex mt-4">
                  <input
                      v-model="minPrice"
                      class="form-control rounded-4 p-3 me-2"
                      min="0"
                      placeholder="Từ"
                      step="1000"
                      type="number"
                  />
                  <input
                      v-model="maxPrice"
                      class="form-control rounded-4 p-3"
                      min="0"
                      placeholder="Đến"
                      step="1000"
                      type="number"
                  />
                </div>

                <hr class="line"/>
                <h3 class="section-title mt-3 text-start">Đánh giá</h3>
                <div class="rating">
                  <div class="form-check d-flex align-items-center gap-3">
                    <input
                        id="rating-5"
                        v-model="rating"
                        class="form-check-input"
                        name="rating"
                        type="radio"
                        value="5"
                    />
                    <label class="form-check-label star-label" for="rating-5">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <span class="ms-2 text-muted">(5 sao)</span>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input
                        id="rating-4"
                        v-model="rating"
                        class="form-check-input"
                        name="rating"
                        type="radio"
                        value="4"
                    />
                    <label class="form-check-label star-label" for="rating-4">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <span class="ms-2 text-muted">(4 đến dưới 5 sao)</span>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input
                        id="rating-3"
                        v-model="rating"
                        class="form-check-input"
                        name="rating"
                        type="radio"
                        value="3"
                    />
                    <label class="form-check-label star-label" for="rating-3">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <span class="ms-2 text-muted">(3 đến dưới 4 sao)</span>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input
                        id="rating-2"
                        v-model="rating"
                        class="form-check-input"
                        name="rating"
                        type="radio"
                        value="2"
                    />
                    <label class="form-check-label star-label" for="rating-2">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <span class="ms-2 text-muted">(2 đến dưới 3 sao)</span>
                    </label>
                  </div>
                  <div class="form-check d-flex align-items-center gap-3">
                    <input
                        id="rating-1"
                        v-model="rating"
                        class="form-check-input"
                        name="rating"
                        type="radio"
                        value="1"
                    />
                    <label class="form-check-label star-label" for="rating-1">
                      <i class="bi bi-star-fill color-star fs-5"></i>
                      <span class="ms-2 text-muted">(1 đến dưới 2 sao)</span>
                    </label>
                  </div>
                </div>

                <hr class="line"/>
                <h3 class="section-title mt-3 text-start">Thương hiệu</h3>
                <div
                    v-for="b in brands"
                    :key="b.id"
                    class="form-check d-flex align-items-center gap-3"
                >
                  <input
                      :id="`brand-${b.id}`"
                      v-model="brand"
                      :value="b.id.toString()"
                      class="form-check-input"
                      name="brand"
                      type="radio"
                  />
                  <label :for="`brand-${b.id}`" class="form-check-label">{{
                      b.Ten_thuong_hieu
                    }}</label>
                </div>

                <hr class="line"/>
                <h3 class="section-title mt-3 text-start">Danh mục</h3>
                <div
                    v-for="cat in categories"
                    :key="cat.id"
                    class="form-check d-flex align-items-center gap-3"
                >
                  <input
                      :id="`category-${cat.id}`"
                      v-model="category"
                      :value="cat.id.toString()"
                      class="form-check-input"
                      name="category"
                      type="radio"
                  />
                  <label :for="`category-${cat.id}`" class="form-check-label">{{
                      cat.Ten_danh_muc
                    }}</label>
                </div>

                <!-- Thêm 2 nút Áp dụng và Đặt lại -->
                <div class="d-flex mt-4 gap-2">
                  <button
                      class="btn btn-primary w-50 py-2 fs-5 rounded-4"
                      type="button"
                      @click="applyFilters"
                  >
                    Áp dụng
                  </button>
                  <button
                      class="btn btn-secondary w-50 py-2 fs-5 rounded-4"
                      type="button"
                      @click="resetFilter"
                  >
                    Đặt lại
                  </button>
                </div>
              </form>
            </div>
          </aside>
          <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center m-3">
              <p class="fs-4 m-0" style="color: var(--colortext2)">
                Hiển thị {{ products.length }} trong tổng số
                {{ totalItems }} sản phẩm
              </p>
              <p class="fs-4 m-0" style="color: var(--colortext2)">
                Trang {{ currentPage }} / {{ lastPage }}
              </p>
            </div>

            <div v-if="isLoading" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
              </div>
            </div>

            <div v-else-if="products.length > 0" class="row gx-0">
              <div
                  v-for="product in products"
                  :key="product.id"
                  class="col-12 col-lg-4 col-md-6 p-0"
              >
                <div class="product my-3">
                  <a :href="`/sanpham/${product.id}`" class="link-img-p">
                    <img
                        :alt="product.Ten_san_pham || 'Sản phẩm'"
                        :src="getImageSrc(product.Anh_dai_dien)"
                        class="img-fluid"
                    />
                  </a>
                  <div class="product-infor">
                    <a
                        :href="`/sanpham/${product.id}`"
                        class="m-0 title-product fs-3"
                    >
                      {{ product.Ten_san_pham || "Tên sản phẩm" }}
                    </a>
                    <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                      <template v-for="star in 5" :key="star">
                        <i
                            :class="
                            star <= (product.diem_trung_binh || 0)
                              ? 'color-star'
                              : 'color-star-gray'
                          "
                            class="bi bi-star-fill fs-5"
                        ></i>
                      </template>
                      <p class="text-rating m-0 ms-3 fs-4">
                        ({{ Number(product.diem_trung_binh) ? Number(product.diem_trung_binh).toFixed(Number.isInteger(Number(product.diem_trung_binh)) ? 0 : 1) : 0 }}/5)
                      </p>
                    </div>
                    <div class="text-location py-2">
                      <p class="m-0">{{ product.Mo_ta || "Chưa có mô tả" }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-location">
                      <i class="bi bi-eye"></i>
                      <p class="custom-open m-0">
                        Lượt xem: {{ product.view || 0 }}
                      </p>
                    </div>
                    <div class="price d-flex align-items-end gap-4">
                      <p class="m-0">
                        {{ (product.Gia || 0).toLocaleString("vi-VN") }}đ
                      </p>
                      <del v-if="product.gia_giam"
                      >{{ product.gia_giam.toLocaleString("vi-VN") }}đ
                      </del
                      >
                    </div>
                  </div>
                  <div class="atc-love-box">
                    <a class="atc-love" href="#"
                    ><i class="bi bi-heart-fill"></i
                    ></a>
                    <div class="atc-love atc-icon" @click="addCart(product)">
                      <i class="bi bi-cart-fill"></i>
                      <div class="select_number-size"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="col-12 text-center py-5">
              <p>Không tìm thấy sản phẩm nào</p>
            </div>

            <div
                class="d-flex justify-content-center align-items-center gap-2 mt-4"
            >
              <button
                  :disabled="currentPage == 1"
                  class="btn-pagination"
                  @click="handlePageChange(currentPage - 1)"
              >
                <i class="bi bi-chevron-left fs-4"></i>
              </button>

              <template v-for="pageNum in lastPage" :key="pageNum">
                <button
                    :class="{ active: pageNum == currentPage }"
                    class="btn-pagination"
                    @click="handlePageChange(pageNum)"
                >
                  {{ pageNum }}
                </button>
              </template>

              <button
                  :disabled="currentPage == lastPage"
                  class="btn-pagination"
                  @click="handlePageChange(currentPage + 1)"
              >
                <i class="bi bi-chevron-right fs-4"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="popular-y mx-lg-auto mx-2">
      <div class="container">
        <div
            class="d-flex justify-content-between align-items-center mb-3 px-2"
        >
          <h1 class="title-section">SẢN PHẨM BÁN CHẠY</h1>
        </div>
        <div
            v-if="bestseller.length > 0"
            class="row gx-0 bestseller-product-srcoll"
        >
          <div
              v-for="bestsellerp in bestseller"
              :key="bestsellerp.id"
              class="col-12 col-lg-3 col-md-6 p-0"
          >
            <div class="product my-3">
              <a :href="`/sanpham/${bestsellerp.id}`" class="link-img-p">
                <img
                    :alt="bestsellerp.Ten_san_pham || 'Sản phẩm'"
                    :src="getImageSrc(bestsellerp.Anh_dai_dien)"
                    class="img-fluid"
                />
              </a>
              <div class="product-infor">
                <a
                    :href="`/sanpham/${bestsellerp.id}`"
                    class="m-0 title-product fs-3"
                >
                  {{ bestsellerp.Ten_san_pham || "Tên sản phẩm" }}
                </a>
                <div class="d-flex align-items-center gap-2 pt-1 pb-2">
                  <template v-for="star in 5" :key="star">
                    <i class="bi bi-star-fill color-star fs-5"></i>
                  </template>
                  <p class="text-rating m-0 ms-3 fs-4">(5/5)</p>
                </div>
                <div class="text-location py-2">
                  <p class="m-0">{{ bestsellerp.Mo_ta || "Chưa có mô tả" }}</p>
                </div>
                <div class="d-flex align-items-center gap-2 text-location">
                  <i class="bi bi-eye"></i>
                  <p class="custom-open m-0">
                    Lượt xem: {{ bestsellerp.view || 0 }}
                  </p>
                </div>
                <div class="price d-flex align-items-end gap-4">
                  <p class="m-0">
                    {{ (bestsellerp.Gia || 0).toLocaleString("vi-VN") }}đ
                  </p>
                  <del v-if="bestsellerp.gia_giam"
                  >{{ bestsellerp.gia_giam.toLocaleString("vi-VN") }}đ
                  </del
                  >
                </div>
              </div>
              <div class="atc-love-box">
                <a class="atc-love" href="#"
                ><i class="bi bi-heart-fill"></i
                ></a>
                <div class="atc-love atc-icon" @click="addCart(bestsellerp)">
                  <i class="bi bi-cart-fill"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal chọn size, màu và số lượng -->
  <div v-if="showQuickAddModal && selectedProduct" class="quick-add-modal">
    <div class="quick-add-content">
      <div class="quick-add-header">
        <h5 class="fs-3">Thêm vào giỏ hàng</h5>
        <button class="close-btn" @click="showQuickAddModal = false">
          &times;
        </button>
      </div>
      <div class="quick-add-body">
        <div class="product-info d-flex mb-3">
          <img
              v-if="selectedProduct.Anh_dai_dien"
              :alt="selectedProduct.Ten_san_pham"
              :src="getImageSrc(selectedProduct.Anh_dai_dien)"
              class="product-thumbnail"
          />
          <div class="ms-3">
            <h6 class="fs-4">{{ selectedProduct.Ten_san_pham }}</h6>
            <p class="price fs-4 fw-bold" style="color: var(--accent)">
              {{ selectedProduct.Gia?.toLocaleString("vi-VN") }}đ
            </p>
          </div>
        </div>

        <!-- Màu sắc -->
        <div v-if="availableColors.length > 0" class="mb-3">
          <h5>Màu sắc:</h5>
          <div class="color-options">
            <div
                v-for="color in availableColors"
                :key="color"
                :class="{ active: selectedColor == color }"
                :style="{ backgroundColor: `#${color}` }"
                class="color-option"
                @click="selectedColor = color"
            ></div>
          </div>
        </div>

        <!-- Kích thước -->
        <div v-if="availableSizes.length > 0" class="mb-3">
          <h5>Kích thước:</h5>
          <div class="size-options">
            <div
                v-for="size in availableSizes"
                :key="size"
                :class="{ active: selectedSize === size }"
                class="size-option fs-5"
                @click="selectedSize = size"
            >
              {{ size }}
            </div>
          </div>
        </div>

        <!-- Số lượng -->
        <div class="mb-3">
          <h5>Số lượng:</h5>
          <div class="d-flex">
            <i class="bi bi-dash" @click="decreaseQuantity"></i>
            <span class="cart-quantity mx-0 fs-4" style="width: 8px">{{
                selectedQuantity
              }}</span>
            <i class="bi bi-plus" @click="increaseQuantity"></i>
          </div>
        </div>
      </div>
      <div class="quick-add-footer">
        <button class="btn-booknow px-4 py-3 fs-4 mt-0" @click="addToCart">
          Thêm
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.btn-pagination {
  width: 30px;
  height: 30px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;

  &:hover {
    background-color: #ca9c2726 !important;
    color: var(--accent) !important;
    border-color: var(--accent) !important;
  }
}

.btn-pagination:hover:not(:disabled) {
  background: var(--colortext2);
  color: white;
  border-color: var(--colortext2);
}

.btn-pagination.active {
  background: var(--accent);
  color: white;
  border-color: var(--accent);
}

.btn-pagination:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.color-star {
  color: var(--hover2);
}

.color-star-gray {
  color: #ddd;
}

.bestseller-product-srcoll {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  scroll-behavior: smooth;
}

/* CSS cho modal chọn kích thước, màu sắc và số lượng */
.quick-add-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.quick-add-content {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.quick-add-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #eee;
}

.quick-add-header h5 {
  margin: 0;
  font-weight: 600;
  color: var(--colortext1);
}

.close-btn {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #999;
}

.quick-add-body {
  padding: 15px;
}

.product-thumbnail {
  width: 70px;
  height: 70px;
  object-fit: contain;
  border: 1px solid #eee;
  border-radius: 4px;
}

.color-options,
.size-options {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 8px;
}

.color-option {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 1px solid #ddd;
  cursor: pointer;
  transition: transform 0.2s;
}

.color-option.active {
  transform: scale(1.1);
  border: 2px solid var(--accent);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.size-option {
  min-width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s;
  user-select: none;
}

.size-option.active {
  background-color: var(--accent);
  color: white;
  border-color: var(--accent);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.size-option:hover {
  border-color: var(--accent);
  background-color: rgba(202, 156, 39, 0.1);
}

.quantity-control {
  display: flex;
  align-items: center;
  width: fit-content;
}

.quantity-btn {
  width: 40px;
  height: 40px;
  border: none;
  background: #f5f5f5;
  font-size: 18px;
  cursor: pointer;
}

.quantity-value {
  width: 50px;
  text-align: center;
  font-size: 16px;
}

/* Styling for the quantity selector in the modal */
.quantity-control .bi-dash,
.quantity-control .bi-plus {
  font-size: 2rem;
  cursor: pointer;
  color: var(--accent);
  padding: 0 15px;
}

.cart-quantity {
  margin: 0 15px;
  font-weight: 500;
}

.quick-add-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 15px;
  border-top: 1px solid #eee;
}

.btn-cancel {
  background: #f5f5f5;
  border: none;
  color: var(--colortext1);
  padding: 8px 15px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-add-cart {
  background-color: var(--accent);
  border: none;
  color: white;
  padding: 8px 15px;
  border-radius: 4px;
  cursor: pointer;
}

.link-img-p {
  display: block;
  overflow: hidden;
  position: relative;
  border-radius: 8px 8px 0 0;
}

.link-img-p img {
  transition: all 0.5s ease;
  object-fit: cover;
}

.link-img-p:hover img {
  transform: scale(1.1);
}

.link-img-p::before {
  content: "Xem chi tiết";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: var(--accent);
  color: white;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 30px;
  opacity: 0;
  z-index: 2;
  transition: all 0.3s ease;
  font-size: 1rem;
}

.link-img-p::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0);
  transition: all 0.3s ease;
  z-index: 1;
}

.link-img-p:hover::before {
  opacity: 1;
}

.link-img-p:hover::after {
  background: rgba(0, 0, 0, 0.3);
}

/* Add styles for the new reset button */
.btn-secondary {
  background-color: #6c757d;
  color: white;
  border: none;
  transition: all 0.3s;
}

.btn-secondary:hover {
  background-color: #5a6268;
}
</style>
