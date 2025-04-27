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

// Configure axios - đảm bảo đúng cấu hình cho backend
const apiURL = "http://localhost:8000";
axios.defaults.baseURL = apiURL;
axios.defaults.withCredentials = true; // Cho phép gửi cookies với request
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.post["Content-Type"] = "application/json";

// In ra log để debug axios configuration
console.log("Axios configuration:", {
  baseURL: axios.defaults.baseURL,
  withCredentials: axios.defaults.withCredentials,
  headers: axios.defaults.headers.common,
});

// Export để components khác có thể sử dụng
export const API_URL = apiURL;

// Axios request interceptor to ensure Authorization header is set
axios.interceptors.request.use(
  (config) => {
    // Don't override if Authorization is already set (like in specific requests)
    if (!config.headers.Authorization) {
      const token = localStorage.getItem('auth_token') || 
                    localStorage.getItem('token') || 
                    localStorage.getItem('user_token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Axios response interceptor để xử lý lỗi chung
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("API Error:", error);

    // Nếu backend không hoạt động
    if (!error.response) {
      console.error("Network error or server is not running");
    }

    return Promise.reject(error);
  }
);

// Thêm interceptor để xử lý lỗi API
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 500) {
      console.error('API Error:', error);
      
      // Log thông tin chi tiết về request gây lỗi
      console.error('Request URL:', error.config.url);
      console.error('Request Method:', error.config.method);
      console.error('Request Headers:', error.config.headers);
      
      if (error.response.data) {
        console.error('Server Error Details:', error.response.data);
      }
    }
    return Promise.reject(error);
  }
);

// Create the app first
const app = createApp(App);
const pinia = createPinia();
app.use(pinia);
app.use(router);

// Initialize the store after pinia is installed
const authStore = useAuthStore();
const cartStore = useCartStore();

// Check for saved auth token and set in axios headers
// Looking for token in both possible localStorage keys
const token = localStorage.getItem("auth_token") || localStorage.getItem("token") || localStorage.getItem("user_token");
if (token) {
  console.log("Found authentication token, setting up headers");
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  
  // Ensure token is saved in both locations for consistency
  localStorage.setItem("auth_token", token);
  localStorage.setItem("token", token);
  localStorage.setItem("user_token", token);
  localStorage.setItem("auth_status", "authenticated");
  
  // Try to load user data if token exists
  authStore.checkAuth().catch((error) => {
    console.error("Failed to restore authentication:", error);
    // If token is invalid, clear it
    authStore.clearAuth(); // Use the store method to ensure complete cleanup
  });
} else {
  console.log("No authentication token found in localStorage");
}

// Load initial cart data
cartStore.loadCart().catch(error => {
  console.error("Failed to load initial cart data:", error);
});

app.mount("#app");
