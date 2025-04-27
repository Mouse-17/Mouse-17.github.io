import { defineStore } from "pinia";
import axios from "axios";
import { API_URL } from "../main";

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  phone?: string;
  address?: string;
  avatar?: string;
  email_verified_at?: string;
  created_at?: string;
  updated_at?: string;
}

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null as User | null,
    token: null as string | null,
    isAuthenticated: false,
  }),

  getters: {
    userFullName: (state) => state.user?.name || "Người dùng",
    userAvatar: (state) => state.user?.avatar || null,
    isAdmin: (state) => state.user?.role === "admin",
    isFieldOwner: (state) => state.user?.role === "field_owner",
  },

  actions: {
    setUser(user: User) {
      this.user = user;
      this.isAuthenticated = true;
    },

    setToken(token: string) {
      this.token = token;
      localStorage.setItem("auth_token", token);
      localStorage.setItem("token", token);
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    },

    async login(email: string, password: string) {
      const response = await axios.post("/api/login", { email, password });
      const { access_token, user } = response.data;

      this.setUser(user);
      this.setToken(access_token);

      return user;
    },

    async logout() {
      try {
        await axios.post("/api/logout");
      } catch (error) {
        console.error("Logout error:", error);
      } finally {
        this.clearAuth();
      }
    },

    clearAuth() {
      this.user = null;
      this.token = null;
      this.isAuthenticated = false;
      localStorage.removeItem("auth_token");
      localStorage.removeItem("token");
      delete axios.defaults.headers.common["Authorization"];
    },

    async checkAuth() {
      // If we already have a user, no need to check
      if (this.user) return this.user;

      // Check if we have a token in localStorage
      const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
      if (!token) return null;

      try {
        console.log("Kiểm tra token:", token.substring(0, 15) + "...");
        
        // Set token headers
        this.token = token;
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        // Get user info
        const response = await axios.get(`${API_URL}/api/user`, {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json'
          }
        });
        
        console.log("API response user info:", response.status, response.statusText);
        
        if (response.data && response.data.id) {
          this.setUser(response.data);
          return this.user;
        } else {
          throw new Error("Không nhận được dữ liệu người dùng");
        }
      } catch (error: any) {
        console.error("Lỗi khi kiểm tra xác thực:", error);
        
        // Log chi tiết để debug
        if (error.response) {
          console.error("Status code:", error.response.status);
          console.error("Response data:", error.response.data);
        }
        
        this.clearAuth();
        return null;
      }
    },

    async updateProfile(formData: FormData) {
      const response = await axios.post("/api/update-profile", formData);

      // Update user in store with the new data
      this.user = response.data.user;
      return response.data;
    },
  },
});
