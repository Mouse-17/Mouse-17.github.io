<template>
  <main>
    <section class="banner">
      <img src="/img/Banner2.png" alt="Banner">
    </section>

    <section class="recovery-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <div class="recovery-form">
              <h2 class="text-center mb-4 fw-bold">KHÔI PHỤC MẬT KHẨU</h2>

              <div
                v-if="message"
                class="alert"
                :class="isError ? 'alert-danger' : 'alert-success'"
                role="alert"
              >
                {{ message }}
              </div>

              <p class="text-center mb-4">
                Vui lòng nhập địa chỉ email của bạn để nhận liên kết đặt lại mật
                khẩu.
              </p>

              <form @submit.prevent="handleRecovery">
                <div class="mb-4">
                  <label class="form-label fw-bold">Email</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-envelope-fill"></i
                    ></span>
                    <input
                      type="email"
                      v-model="email"
                      class="form-control"
                      placeholder="Nhập email của bạn"
                      required
                    />
                  </div>
                  <div v-if="errors.email" class="text-danger mt-1">
                    {{ errors.email[0] }}
                  </div>
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
                  GỬI LIÊN KẾT KHÔI PHỤC
                </button>

                <div class="text-center mt-4">
                  <router-link to="/chusan/login" class="login-link">
                    Quay lại đăng nhập
                  </router-link>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="text-center mt-3 mb-5">
    <small class="text-muted">Bản quyền © 2024 - THUỘC NHÀ SẢN XUẤT</small>
  </div>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";
import { API_URL } from "../../main";

export default {
  name: "ChusanForgotPasswordView",
  setup() {
    const router = useRouter();
    return { router };
  },
  data() {
    return {
      email: "",
      loading: false,
      message: "",
      isError: false,
      errors: {},
    };
  },
  methods: {
    async handleRecovery() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post(
          `${API_URL}/api/chusan/forgot-password`,
          {
            email: this.email,
          },
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );

        // Password recovery email sent successfully
        this.message =
          "Liên kết khôi phục mật khẩu đã được gửi đến email của bạn.";

        // Redirect to login page after 3 seconds
        setTimeout(() => {
          this.router.push("/chusan/login");
        }, 3000);
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            this.message =
              error.response.data.message || "Vui lòng kiểm tra email của bạn.";
          } else if (error.response.status === 404) {
            // Account not found
            this.message = "Không tìm thấy tài khoản với email này.";
          } else {
            this.message =
              error.response.data.message ||
              "Đã xảy ra lỗi khi gửi yêu cầu khôi phục mật khẩu.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ.";
        }
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.recovery-section {
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

.recovery-form {
  background-color: white;
  padding: 2.5rem;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(0, 0, 0, 0.05);
  position: relative;
  margin: 0 auto;
}

.form-label {
  font-size: 1rem;
  margin-bottom: 0.5rem;
  color: #333;
}

.input-group-text {
  background-color: #28a745;
  color: white;
  border: none;
  font-size: 1.1rem;
  padding: 0.5rem 0.75rem;
}

.form-control {
  border: 1px solid #ddd;
  padding: 0.7rem 0.9rem;
  font-size: 1rem;
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
  margin: 0 auto;
  box-sizing: border-box;
}

.btn-success:hover {
  background-color: #218838;
  border-color: #1e7e34;
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
  transform: translateY(-2px);
}

.btn-success:disabled {
  background-color: #6c757d;
  border-color: #6c757d;
  opacity: 0.65;
}

.login-link {
  color: #28a745;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  padding: 2px 0;
  position: relative;
}

.login-link:hover {
  text-decoration: underline;
  color: #1e7e34;
}

.imgban {
  width: 100%;
  height: auto;
}

@media (max-width: 768px) {
  .recovery-form {
    padding: 2rem 1.5rem;
  }
}
</style>
