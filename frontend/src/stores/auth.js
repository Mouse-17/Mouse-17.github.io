import { defineStore } from "pinia";
import axios from "axios";
import { API_URL } from "../main";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false,
  }),

  getters: {
    isAdmin: (state) => state.user?.role === "admin",
    isFieldOwner: (state) => state.user?.role === "field_owner",
    isUser: (state) => state.user?.role === "user",
    userFullName: (state) => state.user?.name || "",
    userEmail: (state) => state.user?.email || "",
    userAvatar: (state) => {
      return state.user?.avatar || null;
    },
  },

  actions: {
    setUser(user) {
      if (!user) {
        console.warn("Empty user data provided to setUser");
        return;
      }
      
      console.log("Setting user data:", JSON.stringify(user));
      
      // Store user data in state
      this.user = user;
      this.isAuthenticated = true;
      
      // Store user data in localStorage for additional persistence
      try {
        localStorage.setItem('user_data', JSON.stringify(user));
      } catch (e) {
        console.error("Failed to store user data in localStorage:", e);
      }
      
      console.log("User authenticated successfully, role:", user.role || 'user');
    },

    setToken(token) {
      if (token) {
        this.token = token;
        // Store token in all common storage locations for consistency
        localStorage.setItem("auth_token", token);
        localStorage.setItem("user_token", token);
        localStorage.setItem("token", token);
        localStorage.setItem("auth_status", "authenticated");
        
        // Set authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        console.log("Token set successfully in all storage locations");
      } else {
        console.log("No token provided, clearing auth state");
        this.clearAuth();
      }
    },

    isUserLoggedIn() {
      const token =
        localStorage.getItem("auth_token") ||
        localStorage.getItem("user_token");
      const status = localStorage.getItem("auth_status");
      return !!token && status === "authenticated";
    },

    logout() {
      // Call the logout API with proper URL and headers
      return axios
        .post(
          `${API_URL}/api/logout`,
          {},
          {
            headers: {
              Authorization: `Bearer ${this.token}`,
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        )
        .catch((error) => console.error("Logout error:", error))
        .finally(() => {
          this.clearAuth();
        });
    },

    clearAuth() {
      this.user = null;
      this.token = null;
      this.isAuthenticated = false;
      
      // Remove from axios headers
      delete axios.defaults.headers.common["Authorization"];
      
      // Clear all possible token storage locations
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user_token");
      localStorage.removeItem("token");
      localStorage.removeItem("auth_status");
      localStorage.removeItem("user_data");
      
      console.log("Authentication state completely cleared");
    },

    async checkAuth() {
      try {
        // Check if we already have authenticated user data
        if (this.isAuthenticated && this.user) {
          console.log("Already authenticated with user data");
          return true;
        }
        
        // Check for token in all possible localStorage keys
        const token = localStorage.getItem('auth_token') || 
                      localStorage.getItem('token') || 
                      localStorage.getItem('user_token');
                      
        console.log("Checking authentication with token:", token ? "Token exists" : "No token found");
        
        if (!token) {
          console.log("No token found in any storage location");
          this.clearAuth(); // Use clearAuth instead of clearUserState to ensure complete cleanup
          return false;
        }

        // Set token in store and axios headers
        this.token = token;
        // Ensure consistent token storage
        localStorage.setItem('auth_token', token);
        localStorage.setItem('token', token);
        localStorage.setItem('user_token', token);
        localStorage.setItem('auth_status', 'authenticated');
        
        // Set authorization header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        try {
          // Call API to verify the token and get user data
          const response = await axios.get(`${API_URL}/api/user`, {
            headers: {
              'Authorization': `Bearer ${token}`,
              'Accept': 'application/json'
            }
          });
          
          console.log("API Response:", response.data);
          
          // Handle different response formats - API might return data directly or in a user property
          let userData = null;
          
          if (response.data && response.data.user) {
            // Format: { user: {...} }
            userData = response.data.user;
          } else if (response.data && response.data.id) {
            // Format: { id: ..., name: ..., etc }
            userData = response.data;
          } else if (response.data && response.data.data) {
            // Format: { data: {...} }
            userData = response.data.data;
          } else if (response.data && response.data.status === 'success' && response.data.data) {
            // Format: { status: 'success', data: {...} }
            userData = response.data.data;
          }
          
          if (userData && userData.id) {
            // Successfully retrieved user data
            console.log("User data retrieved successfully:", userData);
            this.setUser(userData);
            this.setToken(token); // Reset token to ensure everything is consistent
            return true;
          } else {
            console.log("API returned success but no valid user data found", response.data);
            this.clearAuth();
            return false;
          }
        } catch (error) {
          console.error('Authentication check error:', error);
          
          // Only clear auth for specific auth-related errors
          if (error.response && (error.response.status === 401 || error.response.status === 403)) {
            console.log(`Auth error ${error.response.status}: Clearing authentication state`);
            this.clearAuth();
            return false;
          } else {
            // For other errors (like network issues), try to use cached user data
            console.log("Non-auth related error, trying to use cached user data");
            
            // Try to get cached user data from localStorage
            try {
              const cachedUserData = localStorage.getItem('user_data');
              if (cachedUserData) {
                const userData = JSON.parse(cachedUserData);
                if (userData && userData.id) {
                  console.log("Using cached user data:", userData);
                  this.setUser(userData);
                  return true;
                }
              }
            } catch (e) {
              console.error("Error parsing cached user data:", e);
            }
            
            // Keep the token but don't authenticate user without data
            console.log("No valid cached user data found, keeping token but not authenticating");
            return false;
          }
        }
      } catch (e) {
        console.error('Unexpected error during authentication check:', e);
        // Don't clear auth state for unexpected errors to prevent unintended logouts
        return false;
      }
    },

    clearUserState() {
      this.user = null;
      this.isAuthenticated = false;
    },

    async updateProfile(profileData) {
      try {
        const response = await axios.post("/api/update-profile", profileData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        this.setUser(response.data.user);
        return response.data;
      } catch (error) {
        throw error;
      }
    },
  },
});
