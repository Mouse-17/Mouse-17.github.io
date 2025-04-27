// Import CSS
// import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";

// Import JS
import "bootstrap/dist/js/bootstrap.bundle.min.js";

import { createApp } from "vue";
import { createPinia } from "pinia";
import axios from "axios";

import App from "./App.vue";
import router from "./router";
import { useAuthStore } from "./stores/auth";
import { useCartStore } from "./stores/cart";

// Lấy URL API từ biến môi trường
const apiURL = import.meta.env.VITE_API_URL as string;

// Configure axios - đảm bảo đúng cấu hình cho backend
axios.defaults.baseURL = apiURL;
axios.defaults.withCredentials = true; // Cho phép gửi cookies với request
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.post["Content-Type"] = "application/json";

// In ra cấu hình của axios để kiểm tra
console.log("Axios configuration:", {
    baseURL: axios.defaults.baseURL,
    withCredentials: axios.defaults.withCredentials,
    headers: axios.defaults.headers.common,
});

// Export để components khác có thể sử dụng
export const API_URL = apiURL;

// Axios request interceptor để thêm header Authorization nếu cần
axios.interceptors.request.use(
    (config) => {
        if (!config.headers.Authorization) {
            const token =
                localStorage.getItem("auth_token") ||
                localStorage.getItem("token") ||
                localStorage.getItem("user_token");
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Axios response interceptor để xử lý lỗi chung
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error("API Error:", error);

        // Nếu không có phản hồi từ server
        if (!error.response) {
            console.error("Network error or server is not running");
        }

        // Nếu server trả về lỗi 500
        if (error.response && error.response.status === 500) {
            console.error("Server Error Details:", error.response.data || "No details");
        }

        return Promise.reject(error);
    }
);

// Tạo ứng dụng Vue
const app = createApp(App);
const pinia = createPinia();
app.use(pinia);
app.use(router);

// Đảm bảo Pinia được khởi tạo trước khi sử dụng store
app.mount("#app");

// Sau khi Pinia được khởi tạo, sử dụng các store
const authStore = useAuthStore();
const cartStore = useCartStore();

// Kiểm tra token trong localStorage và thiết lập nếu có
const token =
    localStorage.getItem("auth_token") ||
    localStorage.getItem("token") ||
    localStorage.getItem("user_token");
if (token) {
    console.log("Found authentication token, setting up headers");
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

    // Đồng bộ hóa token trong localStorage
    localStorage.setItem("auth_token", token);
    localStorage.setItem("token", token);
    localStorage.setItem("user_token", token);
    localStorage.setItem("auth_status", "authenticated");

    // Kiểm tra xác thực
    authStore
        .checkAuth()
        .catch((error) => {
            console.error("Failed to restore authentication:", error);
            authStore.clearAuth(); // Nếu token không hợp lệ, xóa nó
        });
} else {
    console.log("No authentication token found in localStorage");
}

// Tải dữ liệu giỏ hàng ban đầu
cartStore.loadCart().catch((error) => {
    console.error("Failed to load initial cart data:", error);
});