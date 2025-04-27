<template>
  <main>
    <section class="banner">
      <img class="imgban" src="../../public/img/Banner2.png" alt="" />
    </section>

    <section class="login-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="login-form">
              <h2 class="text-center mb-4 fw-bold">ĐĂNG NHẬP CHỦ SÂN</h2>

              <div
                v-if="message"
                class="alert"
                :class="isError ? 'alert-danger' : 'alert-success'"
                role="alert"
              >
                {{ message }}
              </div>

              <form @submit.prevent="handleLogin">
                <div class="mb-4">
                  <label class="form-label fw-bold">Email</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"
                      ><i class="bi bi-envelope-fill"></i
                    ></span>
                    <input
                      type="email"
                      v-model="email"
                      class="form-control"
                      placeholder="Vui lòng nhập email"
                      required
                    />
                  </div>
                  <div v-if="errors.email" class="text-danger mt-1">
                    {{ errors.email[0] }}
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label fw-bold">Mật khẩu</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"
                      ><i class="bi bi-lock-fill"></i
                    ></span>
                    <input
                      type="password"
                      v-model="password"
                      class="form-control"
                      placeholder="Vui lòng nhập mật khẩu"
                      required
                    />
                  </div>
                  <div v-if="errors.password" class="text-danger mt-1">
                    {{ errors.password[0] }}
                  </div>
                </div>
                <div
                  class="d-flex justify-content-between align-items-center mb-3"
                >
                  <div class="form-check d-flex align-items-center">
                    <input
                      type="checkbox"
                      v-model="rememberMe"
                      class="form-check-input me-2"
                      id="remember"
                    />
                    <label class="form-check-label" for="remember"
                      >Nhớ thông tin đăng nhập</label
                    >
                  </div>
                  <router-link to="/quenmatkhau" class="forgot-link"
                    >Quên mật khẩu?</router-link
                  >
                </div>
                <button
                  type="submit"
                  class="btn btn-warning w-100 py-2"
                  :disabled="loading"
                >
                  <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  ĐĂNG NHẬP
                </button>

                <div class="text-center mt-3">
                  <span>Bạn chưa đăng ký làm chủ sân? </span>
                  <router-link to="/dangky-chusan" class="register-link"
                    >Đăng ký ngay</router-link
                  >
                </div>
                <div class="text-center mt-3">
                  <span>Bạn là người dùng thông thường? </span>
                  <router-link to="/dangnhap-nguoidung" class="user-link"
                    >Đăng nhập dành cho người dùng</router-link
                  >
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="text-center mt-3">
    <small class="text-muted">Bản quyền © 2024 - THUỘC NHÀ SẢN XUẤT</small>
  </div>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { API_URL } from "../main";

export default {
  name: "FieldOwnerLoginView",
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    return { router, authStore };
  },
  data() {
    return {
      email: "",
      password: "",
      rememberMe: false,
      loading: false,
      message: "",
      isError: false,
      errors: {},
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post(
          `${API_URL}/api/login/field-owner`,
          {
            email: this.email,
            password: this.password,
          },
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );

        // Login successful
        const { access_token, user } = response.data;

        // Kiểm tra role nghiêm ngặt - chỉ cho phép field_owner
        if (user.role !== "field_owner") {
          this.isError = true;
          this.message =
            "Tài khoản này không có quyền truy cập vào trang quản lý chủ sân";
          this.loading = false;
          return;
        }

        // Store token and user information
        this.authStore.setUser(user);
        this.authStore.setToken(access_token);

        // Display success message
        this.message = "Đăng nhập thành công!";

        // Save to localStorage if remember me is checked
        if (this.rememberMe) {
          localStorage.setItem("remember_email", this.email);
        } else {
          localStorage.removeItem("remember_email");
        }

        // Redirect to field owner dashboard
        setTimeout(() => {
          this.message = "Đang chuyển hướng đến trang quản lý...";
          this.router.push("/chusan");
        }, 1000);
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            this.message =
              error.response.data.message ||
              "Thông tin đăng nhập không chính xác.";
          } else if (error.response.status === 401) {
            this.message = "Email hoặc mật khẩu không chính xác.";
          } else if (error.response.status === 403) {
            this.message =
              "Tài khoản này không có quyền truy cập trang quản lý chủ sân.";
          } else {
            this.message =
              error.response.data.message || "Đã xảy ra lỗi khi đăng nhập.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ.";
        }
      } finally {
        this.loading = false;
      }
    },
  },
  mounted() {
    // Check if there's a remembered email
    const rememberedEmail = localStorage.getItem("remember_email");
    if (rememberedEmail) {
      this.email = rememberedEmail;
      this.rememberMe = true;
    }
  },
};
</script>

<style scoped>
.sports-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("../../public/img/background-pattern-light.png");
  background-size: cover;
  opacity: 0.1;
  z-index: 0;
}

.login-form {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  padding: 30px;
}

.forgot-link,
.register-link,
.user-link {
  color: #ff6b00;
  text-decoration: none;
  font-weight: 600;
}

.forgot-link:hover,
.register-link:hover,
.user-link:hover {
  text-decoration: underline;
}

.imgban {
  width: 100%;
}
</style>
