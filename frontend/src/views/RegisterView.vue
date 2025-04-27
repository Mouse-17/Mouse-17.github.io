<template>
  <main>
    <section class="banner">
      <img class="imgban" src="../../public/img/Banner2.png" alt="" />
    </section>
    <section class="register-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="register-form">
              <h2 class="text-center mb-4 fw-bold">ĐĂNG KÝ TÀI KHOẢN</h2>

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

              <!-- Step 1: Register Form -->
              <form v-if="currentStep === 1" @submit.prevent="registerUser">
                <div class="mb-3">
                  <label class="form-label fw-bold">Họ tên</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-person-fill"></i
                    ></span>
                    <input
                      type="text"
                      v-model="name"
                      class="form-control"
                      placeholder="Nhập họ tên của bạn"
                      required
                    />
                  </div>
                  <div v-if="errors.name" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.name[0] }}
                  </div>
                </div>

                <div class="mb-3">
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
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.email[0] }}
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Số điện thoại</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-telephone-fill"></i
                    ></span>
                    <input
                      type="tel"
                      v-model="phone"
                      class="form-control"
                      placeholder="Nhập số điện thoại của bạn"
                    />
                  </div>
                  <div v-if="errors.phone" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.phone[0] }}
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Mật khẩu</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-lock-fill"></i
                    ></span>
                    <input
                      type="password"
                      v-model="password"
                      class="form-control"
                      placeholder="Nhập mật khẩu"
                      required
                      minlength="8"
                    />
                  </div>
                  <div v-if="errors.password" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.password[0] }}
                  </div>
                  <small class="text-muted"
                    >Mật khẩu phải có ít nhất 8 ký tự</small
                  >
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-lock-fill"></i
                    ></span>
                    <input
                      type="password"
                      v-model="password_confirmation"
                      class="form-control"
                      placeholder="Nhập lại mật khẩu"
                      required
                      minlength="8"
                    />
                  </div>
                </div>

                <div class="mb-4 form-check">
                  <input
                    type="checkbox"
                    v-model="agreeTerms"
                    class="form-check-input"
                    id="terms"
                    required
                  />
                  <label class="form-check-label" for="terms">
                    Tôi đồng ý với
                    <a href="#" class="terms-link">điều khoản sử dụng</a> của
                    KeySport
                  </label>
                </div>

                <button
                  type="submit"
                  class="btn btn-warning w-100 py-2"
                  :disabled="loading || !agreeTerms"
                >
                  <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  ĐĂNG KÝ
                </button>

                <div class="text-center mt-3">
                  <span>Bạn đã có tài khoản? </span>
                  <router-link to="/dangnhap" class="login-link"
                    >Đăng nhập</router-link
                  >
                </div>

                <div class="text-center mt-3">
                  <span>Bạn muốn đăng ký tài khoản chủ sân? </span>
                  <router-link to="/chusan/dangky" class="login-link"
                    >Đăng ký chủ sân</router-link
                  >
                </div>
              </form>

              <!-- Step 2: OTP Verification Form -->
              <form v-if="currentStep === 2" @submit.prevent="verifyOtp">
                <div class="text-center mb-4">
                  <div class="mb-3">
                    <i 
                      class="bi bi-envelope-check" 
                      style="font-size: 3rem; color: #ff9900;"
                    ></i>
                  </div>
                  <h5>Xác thực Email</h5>
                  <p>
                    Chúng tôi đã gửi mã OTP 6 chữ số đến email
                    <strong>{{ email }}</strong>
                  </p>
                  <p class="small text-muted">
                    Vui lòng kiểm tra hộp thư đến và nhập mã xác thực để hoàn tất đăng ký
                  </p>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold">Mã xác thực OTP</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="bi bi-shield-lock-fill"></i>
                    </span>
                    <input
                      type="text"
                      v-model="otpCode"
                      class="form-control form-control-lg text-center"
                      placeholder="Nhập mã OTP gồm 6 chữ số"
                      required
                      maxlength="6"
                      pattern="[0-9]{6}"
                    />
                  </div>
                  <div v-if="errors.otp" class="text-danger mt-1">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ errors.otp[0] }}
                  </div>
                </div>

                <div class="d-grid gap-2">
                  <button
                    type="submit"
                    class="btn btn-warning py-2"
                    :disabled="loading || !otpCode || otpCode.length !== 6"
                  >
                    <span
                      v-if="loading"
                      class="spinner-border spinner-border-sm me-2"
                      role="status"
                      aria-hidden="true"
                    ></span>
                    XÁC THỰC
                  </button>
                  
                  <div class="text-center mt-3">
                    <p>Bạn chưa nhận được mã?</p>
                    <button 
                      type="button" 
                      class="btn btn-link" 
                      @click="resendOtp"
                      :disabled="resendLoading || resendCooldown > 0"
                    >
                      <span
                        v-if="resendLoading"
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true"
                      ></span>
                      Gửi lại mã
                      <span v-if="resendCooldown > 0">({{ resendCooldown }}s)</span>
                    </button>
                  </div>
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
                <h4 class="mb-3">Đăng ký thành công!</h4>
                <p class="mb-4">
                  Tài khoản của bạn đã được tạo thành công. Bạn sẽ được chuyển
                  đến trang đăng nhập trong vài giây.
                </p>
                <div class="d-flex justify-content-center">
                  <div class="spinner-border text-primary me-2" role="status">
                    <span class="visually-hidden">Đang chuyển hướng...</span>
                  </div>
                  <span>Đang chuyển hướng...</span>
                </div>
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
import { API_URL } from "../main";
import { useAuthStore } from "../stores/auth";

export default {
  name: "RegisterView",
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    return { router, authStore };
  },
  data() {
    return {
      currentStep: 1,
      name: "",
      email: "",
      phone: "",
      password: "",
      password_confirmation: "",
      agreeTerms: false,
      loading: false,
      message: "",
      isError: false,
      errors: {},
      // OTP verification
      otpCode: "",
      resendLoading: false,
      resendCooldown: 0,
      cooldownTimer: null,
    };
  },
  methods: {
    async registerUser() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const registerData = {
          name: this.name,
          email: this.email,
          phone: this.phone,
          password: this.password,
          password_confirmation: this.password_confirmation,
          role: "user",
        };

        console.log("Sending register request with data:", registerData);
        console.log("Using API URL:", API_URL);

        // Sử dụng URL đầy đủ
        const response = await axios.post(
          `${API_URL}/api/register`,
          registerData,
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );

        console.log("Register response:", response);

        // Đăng ký thành công, chuyển sang bước xác thực OTP
        this.message = response.data.message || "Vui lòng xác thực email của bạn!";
        this.isError = false;
        
        // Chuyển sang bước nhập OTP
        this.currentStep = 2;
        
        // Bắt đầu thời gian chờ để gửi lại OTP
        this.startResendCooldown();
      } catch (error) {
        this.isError = true;
        console.error("Register error:", error);

        if (error.response) {
          console.log("Error response data:", error.response.data);
          console.log("Error status:", error.response.status);

          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            
            // Hiển thị thông báo lỗi chi tiết dựa trên loại lỗi
            if (this.errors.email && this.errors.email[0].includes("đã tồn tại")) {
              this.message = "Email này đã được sử dụng. Vui lòng sử dụng email khác hoặc đăng nhập.";
            } else if (this.errors.password && this.errors.password[0].includes("không khớp")) {
              this.message = "Mật khẩu xác nhận không khớp với mật khẩu đã nhập.";
            } else if (this.errors.email && this.errors.email[0].includes("hợp lệ")) {
              this.message = "Định dạng email không hợp lệ. Vui lòng kiểm tra lại.";
            } else if (this.errors.password && this.errors.password[0].includes("ít nhất")) {
              this.message = "Mật khẩu phải có ít nhất 8 ký tự.";
            } else if (this.errors.phone && this.errors.phone[0].includes("đã tồn tại")) {
              this.message = "Số điện thoại này đã được sử dụng cho tài khoản khác.";
            } else if (Object.keys(this.errors).length > 0) {
              // Nếu có nhiều lỗi, hiển thị thông báo chung
              this.message = "Có lỗi trong thông tin đăng ký. Vui lòng kiểm tra các trường bên dưới.";
            } else {
              this.message = error.response.data.message || "Vui lòng kiểm tra thông tin đăng ký.";
            }
          } else if (error.response.status === 500) {
            this.message = "Đã xảy ra lỗi máy chủ. Vui lòng thử lại sau.";
          } else if (error.response.status === 409) {
            this.message = "Email đã được sử dụng cho tài khoản khác. Vui lòng sử dụng email khác.";
          } else {
            this.message = error.response.data.message || "Đã xảy ra lỗi khi đăng ký tài khoản.";
          }
        } else if (error.request) {
          // Request was made but no response received
          this.message = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet của bạn.";
        } else {
          this.message = "Lỗi không xác định: " + error.message;
        }
      } finally {
        this.loading = false;
      }
    },
    
    async verifyOtp() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;
      
      try {
        const verifyData = {
          email: this.email,
          otp: this.otpCode
        };
        
        const response = await axios.post(
          `${API_URL}/api/verify-otp`,
          verifyData,
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );
        
        console.log("OTP verification response:", response);
        
        // Hiển thị thông báo thành công
        this.message = response.data.message || "Xác thực email thành công!";
        this.isError = false;
        
        // Chuyển sang bước thành công
        this.currentStep = 3;
        
        // Chuyển hướng đến trang đăng nhập sau 2 giây
        setTimeout(() => {
          this.router.push({
            path: "/dangnhap", 
            query: { 
              verified: "success", 
              email: this.email 
            }
          });
        }, 2000);
      } catch (error) {
        this.isError = true;
        console.error("OTP verification error:", error);
        
        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            this.message = error.response.data.message || "Mã OTP không hợp lệ.";
          } else if (error.response.status === 400) {
            // OTP hết hạn hoặc không đúng
            this.message = error.response.data.message || "Mã OTP không đúng hoặc đã hết hạn.";
            
            // Nếu OTP đã hết hạn và hệ thống đã gửi mã mới
            if (error.response.data.otp_expired) {
              this.startResendCooldown();
            }
          } else {
            this.message = error.response.data.message || "Đã xảy ra lỗi khi xác thực OTP.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ. Vui lòng thử lại sau.";
        }
      } finally {
        this.loading = false;
      }
    },
    
    async resendOtp() {
      if (this.resendCooldown > 0) return;
      
      this.resendLoading = true;
      this.message = "";
      
      try {
        const response = await axios.post(
          `${API_URL}/api/resend-otp`,
          { email: this.email },
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );
        
        console.log("Resend OTP response:", response);
        
        // Hiển thị thông báo
        this.message = response.data.message || "Đã gửi lại mã OTP thành công. Vui lòng kiểm tra email của bạn.";
        this.isError = false;
        
        // Bắt đầu thời gian chờ
        this.startResendCooldown();
      } catch (error) {
        this.isError = true;
        console.error("Resend OTP error:", error);
        
        if (error.response) {
          this.message = error.response.data.message || "Không thể gửi lại mã OTP. Vui lòng thử lại sau.";
        } else {
          this.message = "Không thể kết nối đến máy chủ. Vui lòng thử lại sau.";
        }
      } finally {
        this.resendLoading = false;
      }
    },
    
    startResendCooldown() {
      // Đặt thời gian chờ 60 giây trước khi có thể gửi lại OTP
      this.resendCooldown = 60;
      
      // Xóa bộ đếm thời gian hiện tại nếu có
      if (this.cooldownTimer) {
        clearInterval(this.cooldownTimer);
      }
      
      // Tạo bộ đếm thời gian mới
      this.cooldownTimer = setInterval(() => {
        if (this.resendCooldown > 0) {
          this.resendCooldown--;
        } else {
          clearInterval(this.cooldownTimer);
        }
      }, 1000);
    }
  },
  beforeUnmount() {
    // Xóa bộ đếm thời gian khi component bị hủy
    if (this.cooldownTimer) {
      clearInterval(this.cooldownTimer);
    }
  }
};
</script>

<style scoped>
.register-section {
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
  max-width: 100%;
  margin: 0 auto;
  box-sizing: border-box;
}

.btn-warning:hover {
  background-color: #e68a00;
  border-color: #e68a00;
  box-shadow: 0 5px 15px rgba(255, 153, 0, 0.3);
  transform: translateY(-2px);
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  font-weight: bold;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  padding: 0 15px;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #545b62;
  box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
  transform: translateY(-2px);
}

.register-link {
  color: #ff9900;
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
  color: #e68a00;
}

.or-divider {
  position: relative;
  display: inline-block;
  padding: 0 10px;
  background-color: white;
  z-index: 1;
}

.text-center.my-3 {
  position: relative;
}

.text-center.my-3::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background-color: #ddd;
  z-index: 0;
}

.banner img {
  width: 100%;
  height: auto;
}

.social-buttons .btn {
  padding: 10px 0;
  font-weight: 500;
  transition: all 0.3s ease;
}

.social-buttons .btn i {
  font-size: 1.5rem;
  margin-right: 10px;
  vertical-align: middle;
}

.social-buttons .btn-primary {
  background-color: #1877f2;
  border-color: #1877f2;
}

.social-buttons .btn-info {
  background-color: #1da1f2;
  border-color: #1da1f2;
}

.btn i {
  font-size: 1.1rem;
  vertical-align: middle;
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

  .or-divider {
    font-size: 0.9rem;
  }

  .register-link {
    font-size: 1rem;
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
