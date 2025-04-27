<template>
  <main>
    <section class="banner">
      <img class="imgban" src="../../public/img/Banner2.png" alt="" />
    </section>

    <section class="forgot-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="forgot-form">
              <h2 class="text-center mb-4 fw-bold">QUÊN MẬT KHẨU</h2>

              <div
                v-if="message"
                class="alert alert-dismissible fade show"
                :class="isError ? 'alert-danger' : 'alert-success'"
                role="alert"
              >
                <div class="d-flex align-items-center">
                  <i 
                    class="bi me-2" 
                    :class="isError ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'"
                    style="font-size: 1.5rem;"
                  ></i>
                  <div style="font-weight: 500; font-size: 1.05rem;">{{ message }}</div>
                </div>
                <button type="button" class="btn-close" @click="message = ''" aria-label="Close"></button>
              </div>

              <!-- Step 1: Request OTP -->
              <form v-if="currentStep === 1" @submit.prevent="requestOtp">
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
                      placeholder="Nhập email của bạn"
                      required
                    />
                  </div>
                  <div v-if="errors.email" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.email[0] }}
                  </div>
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
                  Gửi mã OTP
                </button>

                <div class="text-center mt-4">
                  <router-link to="/dangnhap" class="back-link">
                    <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                  </router-link>
                </div>
              </form>

              <!-- Step 2: Verify OTP and set new password -->
              <form v-if="currentStep === 2" @submit.prevent="resetPassword">
                <div class="mb-4">
                  <label class="form-label fw-bold">Mã OTP</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"
                      ><i class="bi bi-shield-lock-fill"></i
                    ></span>
                    <input
                      type="text"
                      v-model="otp"
                      class="form-control"
                      placeholder="Nhập mã OTP từ email"
                      required
                      maxlength="6"
                      pattern="[0-9]{6}"
                    />
                  </div>
                  <div v-if="errors.otp" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.otp[0] }}
                  </div>
                  <div class="text-muted mt-1 small">
                    Mã OTP đã được gửi đến email của bạn
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold">Mật khẩu mới</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"
                      ><i class="bi bi-lock-fill"></i
                    ></span>
                    <input
                      type="password"
                      v-model="password"
                      class="form-control"
                      placeholder="Nhập mật khẩu mới"
                      required
                      minlength="8"
                    />
                  </div>
                  <div v-if="errors.password" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.password[0] }}
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"
                      ><i class="bi bi-lock-fill"></i
                    ></span>
                    <input
                      type="password"
                      v-model="password_confirmation"
                      class="form-control"
                      placeholder="Nhập lại mật khẩu mới"
                      required
                      minlength="8"
                    />
                  </div>
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
                  Đặt lại mật khẩu
                </button>

                <div class="mt-3 d-flex justify-content-between">
                  <button
                    type="button"
                    class="btn btn-link p-0"
                    @click="requestOtp"
                  >
                    Gửi lại mã OTP
                  </button>
                  <button
                    type="button"
                    class="btn btn-link p-0"
                    @click="currentStep = 1"
                  >
                    Thay đổi email
                  </button>
                </div>
              </form>

              <!-- Step 3: Success -->
              <div v-if="currentStep === 3" class="text-center">
                <div class="success-icon mb-4">
                  <i
                    class="bi bi-check-circle-fill text-success"
                    style="font-size: 4rem"
                  ></i>
                </div>
                <h4 class="mb-3">Đặt lại mật khẩu thành công!</h4>
                <p class="mb-4">
                  Mật khẩu của bạn đã được cập nhật. Vui lòng đăng nhập bằng mật
                  khẩu mới.
                </p>
                <router-link to="/dangnhap" class="btn btn-warning py-2 px-4">
                  Đăng nhập ngay
                </router-link>
              </div>
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

export default {
  name: "ForgotPasswordView",
  setup() {
    const router = useRouter();
    return { router };
  },
  data() {
    return {
      currentStep: 1,
      email: "",
      otp: "",
      password: "",
      password_confirmation: "",
      loading: false,
      message: "",
      isError: false,
      errors: {},
    };
  },
  methods: {
    async requestOtp() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post("/api/forgot-password", {
          email: this.email,
        });

        // Show success message
        this.message =
          response.data.message || "Mã OTP đã được gửi đến email của bạn.";
        this.isError = false;

        // Move to next step
        this.currentStep = 2;
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            
            // Hiển thị thông báo lỗi chi tiết
            if (this.errors.email && this.errors.email[0].includes("không tồn tại")) {
              this.message = "Email không tồn tại trong hệ thống. Vui lòng kiểm tra lại.";
            } else if (this.errors.email && this.errors.email[0].includes("hợp lệ")) {
              this.message = "Địa chỉ email không hợp lệ. Vui lòng nhập đúng định dạng email.";
            } else {
              this.message = error.response.data.message || "Vui lòng kiểm tra thông tin nhập.";
            }
          } else if (error.response.status === 404) {
            this.message = "Email không tồn tại trong hệ thống.";
          } else if (error.response.status === 429) {
            this.message = "Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau ít phút.";
          } else {
            this.message = error.response.data.message || "Đã xảy ra lỗi khi gửi mã OTP.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet.";
        }
      } finally {
        this.loading = false;
      }
    },

    async resetPassword() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post("/api/reset-password", {
          email: this.email,
          otp: this.otp,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        // Show success message
        this.message =
          response.data.message || "Mật khẩu đã được đặt lại thành công.";
        this.isError = false;

        // Move to success step
        this.currentStep = 3;
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            
            // Hiển thị thông báo lỗi chi tiết
            if (this.errors.otp && this.errors.otp[0].includes("không đúng")) {
              this.message = "Mã OTP không chính xác. Vui lòng nhập lại.";
            } else if (this.errors.otp && this.errors.otp[0].includes("hết hạn")) {
              this.message = "Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.";
            } else if (this.errors.password && this.errors.password[0].includes("không khớp")) {
              this.message = "Mật khẩu xác nhận không khớp với mật khẩu mới.";
            } else if (this.errors.password && this.errors.password[0].includes("ít nhất")) {
              this.message = "Mật khẩu phải có ít nhất 8 ký tự.";
            } else {
              this.message = error.response.data.message || "Vui lòng kiểm tra thông tin nhập.";
            }
          } else if (error.response.status === 400) {
            this.message = "Mã OTP không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu mã mới.";
          } else {
            this.message = error.response.data.message || "Đã xảy ra lỗi khi đặt lại mật khẩu.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet.";
        }
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.forgot-section {
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
    url("../../public/img/background.jpg");
  background-size: cover;
  background-position: center;
  z-index: 0;
}

.forgot-form {
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
  background-color: #ff9900;
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
  border-color: #ff9900;
  box-shadow: 0 0 0 0.25rem rgba(255, 153, 0, 0.25);
}

.btn-warning {
  background-color: #ff9900;
  border-color: #ff9900;
  font-weight: bold;
  font-size: 1.1rem;
  padding: 0.5rem 0;
  letter-spacing: 1px;
  transition: all 0.3s ease;
}

.btn-warning:hover {
  background-color: #e68a00;
  border-color: #e68a00;
  box-shadow: 0 5px 15px rgba(255, 153, 0, 0.3);
  transform: translateY(-2px);
}

.back-link,
.btn-link {
  color: #ff9900;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.back-link:hover,
.btn-link:hover {
  color: #e68a00;
  text-decoration: underline;
}

.banner img {
  width: 100%;
  height: auto;
}

.success-icon {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.alert {
  position: relative;
  padding: 1rem 1.5rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-bottom: 1.5rem;
  border-left: 5px solid;
  transition: all 0.3s ease;
  animation: fadeInDown 0.5s;
  font-weight: 500;
}

.alert-success {
  background-color: rgba(25, 135, 84, 0.15);
  border-left-color: #198754;
  color: #0f5132;
  text-shadow: 0 0 1px rgba(255, 255, 255, 0.5);
}

.alert-danger {
  background-color: rgba(220, 53, 69, 0.15);
  border-left-color: #dc3545;
  color: #842029;
  text-shadow: 0 0 1px rgba(255, 255, 255, 0.5);
}

.alert-dismissible .btn-close {
  padding: 1.25rem 1rem;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.text-danger {
  color: #dc3545;
  font-size: 0.9rem;
  margin-top: 0.25rem;
  animation: fadeIn 0.3s;
  display: flex;
  align-items: center;
}

.text-danger i {
  margin-right: 5px;
  color: #dc3545;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
