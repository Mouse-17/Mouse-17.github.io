<template>
  <main>
    <section class="banner">
      <img src="/img/Banner-ChuSan.png" alt="Banner">
    </section>

    <section class="login-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-5">
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
                  <router-link to="/chusan/quenmatkhau" class="forgot-link"
                    >Quên mật khẩu?</router-link
                  >
                </div>
                <button
                  type="submit"
                  class="btn btn-success w-100 py-2"
                  :disabled="loading"
                >
                  <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  ĐĂNG NHẬP CHỦ SÂN
                </button>

                <div class="text-center mt-4">
                  <span>Bạn chưa có tài khoản chủ sân? </span>
                  <router-link to="/chusan/dangky" class="register-link"
                    >Đăng ký ngay</router-link
                  >
                </div>

                <div class="text-center mt-3">
                  <router-link to="/dangnhap" class="btn btn-link">
                    Đăng nhập dành cho khách hàng
                  </router-link>
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
import { useAuthStore } from "../../stores/auth";
import { API_URL } from "../../main";

export default {
  name: "ChusanLoginView",
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
        // Xác minh đây là chủ sân trước khi đăng nhập
        const response = await axios.post(
          `${API_URL}/api/chusan/login`,
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
.login-section {
  position: relative;
  overflow: hidden;
  padding: 3rem 0;
}

.sports-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
      rgba(255, 255, 255, 0.85),
      rgba(255, 255, 255, 0.85)
    ),
    url("../../../public/img/background-chusan.jpg");
  background-size: cover;
  background-position: center;
  z-index: 0;
}

.login-form {
  background-color: white;
  padding: 2.5rem;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(0, 0, 0, 0.05);
  position: relative;
  max-width: 550px;
  margin: 0 auto;
}

.form-label {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  color: #333;
}

.input-group-text {
  background-color: #28a745;
  color: white;
  border: none;
  font-size: 1.25rem;
  padding: 0.6rem 1rem;
}

.form-control {
  border: 1px solid #ddd;
  padding: 0.8rem 1rem;
  font-size: 1.1rem;
}

.form-control::placeholder {
  color: #aaa;
  font-style: italic;
}

.form-control:focus {
  border-color: #28a745;
  box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
}

.btn-success {
  background-color: #28a745;
  border-color: #28a745;
  font-weight: bold;
  font-size: 1.1rem;
  padding: 0.5rem 0;
  letter-spacing: 1px;
  transition: all 0.3s ease;
  max-width: 100%;
  margin: 0 auto;
  box-sizing: border-box;
}

.btn-success:hover {
  background-color: #218838;
  border-color: #1e7e34;
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
  transform: translateY(-2px);
}

.register-link {
  color: #28a745;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  padding: 2px 0;
  font-size: 1.1rem;
  z-index: 1;
  position: relative;
}

.register-link:hover {
  text-decoration: underline;
  color: #1e7e34;
}

.forgot-link {
  color: #6c757d;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.forgot-link:hover {
  color: #5a6268;
  text-decoration: underline;
}

.imgban {
  width: 100%;
  height: auto;
}

@media (max-width: 576px) {
  .login-form {
    padding: 2rem 1.5rem;
  }

  .form-label {
    font-size: 1rem;
  }

  .input-group-text {
    font-size: 1.1rem;
    padding: 0.5rem 0.8rem;
  }

  .form-control {
    font-size: 1rem;
    padding: 0.7rem 0.9rem;
  }

  .register-link {
    font-size: 1rem;
  }
}
</style>
