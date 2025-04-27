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
              <h2 class="text-center mb-4 fw-bold">ĐĂNG NHẬP</h2>

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

              <!-- Form đăng nhập thông thường -->
              <form v-if="currentStep === 'login'" @submit.prevent="handleLogin">
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
                    <i class="bi bi-exclamation-circle me-1"></i>
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
                    <i class="bi bi-exclamation-circle me-1"></i>
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
                <div class="text-center my-3">
                  <span class="or-divider">OR</span>
                </div>
                <div class="social-buttons d-flex gap-2 mb-3">
                  <a
                    href="#"
                    class="btn btn-primary w-50 d-flex align-items-center justify-content-center"
                  >
                    <i class="bi bi-facebook me-2"></i> Facebook
                  </a>
                  <a
                    href="#"
                    class="btn btn-info text-white w-50 d-flex align-items-center justify-content-center"
                  >
                    <i class="bi bi-google me-2"></i> Google
                  </a>
                </div>
                <div class="text-center">
                  <span>Bạn chưa có tài khoản? </span>
                  <router-link to="/dangky" class="register-link"
                    >Đăng ký</router-link
                  >
                </div>

                <div class="text-center mt-3">
                  <router-link to="/chusan/login" class="btn btn-link">
                    Đăng nhập dành cho chủ sân
                  </router-link>
                </div>
              </form>
              
              <!-- Form xác thực OTP -->
              <form v-if="currentStep === 'verify-otp'" @submit.prevent="verifyOtp">
                <div class="text-center mb-4">
                  <div class="mb-3">
                    <i 
                      class="bi bi-envelope-check" 
                      style="font-size: 3rem; color: #ff9900;"
                    ></i>
                  </div>
                  <h5>Xác thực Email</h5>
                  <p>
                    Tài khoản của bạn chưa được xác thực.<br />
                    Chúng tôi đã gửi mã OTP 6 chữ số đến email
                    <strong>{{ email }}</strong>
                  </p>
                  <p class="small text-muted">
                    Vui lòng kiểm tra hộp thư đến và nhập mã xác thực để đăng nhập
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
                  
                  <button 
                    type="button" 
                    class="btn btn-secondary mt-3"
                    @click="backToLogin"
                  >
                    <i class="bi bi-arrow-left me-2"></i> Quay lại đăng nhập
                  </button>
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
  name: "LoginView",
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    return { router, authStore };
  },
  data() {
    return {
      currentStep: "login",
      email: "",
      password: "",
      rememberMe: false,
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
    async handleLogin() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post(
          `${API_URL}/api/login`,
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

        // Kiểm tra nếu có tham số redirect trong URL
        const redirectUrl = this.$route.query.redirect;
        const requestedRole = this.$route.query.role;
        
        // Kiểm tra quyền truy cập nếu có yêu cầu vai trò cụ thể
        if (requestedRole === 'field_owner' && user.role !== 'field_owner' && user.role !== 'admin') {
          this.isError = true;
          this.message = "Bạn không có quyền truy cập trang chủ sân. Vui lòng đăng nhập bằng tài khoản chủ sân.";
          return;
        }

        // Redirect to home page or specified redirect URL
        setTimeout(() => {
          this.message = "Đang chuyển hướng...";
          if (redirectUrl) {
            this.router.push(redirectUrl.toString());
          } else {
            // Nếu là chủ sân và không có URL chuyển hướng cụ thể, đưa họ đến trang chủ sân
            if (user.role === 'field_owner') {
              this.router.push("/chusan");
            } else if (user.role === 'admin') {
              // Nếu là admin, chuyển hướng đến trang quản trị admin
              this.router.push("/admin");
            } else {
              this.router.push("/");
            }
          }
        }, 1000);
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            
            // Hiển thị thông báo lỗi chi tiết dựa trên loại lỗi
            if (this.errors.email && this.errors.email[0].includes("không tồn tại")) {
              this.message = "Email không tồn tại trong hệ thống. Vui lòng kiểm tra lại.";
            } else if (this.errors.password && this.errors.password[0].includes("không chính xác")) {
              this.message = "Mật khẩu không chính xác. Vui lòng thử lại.";
            } else if (error.response.data.message && error.response.data.message.includes("credential")) {
              this.message = "Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại email và mật khẩu.";
            } else {
              this.message = error.response.data.message || "Thông tin đăng nhập không chính xác.";
            }
          } else if (error.response.status === 403 && error.response.data.email_verified === false) {
            // Email chưa được xác thực, chuyển sang form nhập OTP
            this.message = error.response.data.message || "Email chưa được xác thực. Vui lòng nhập mã OTP để xác thực tài khoản.";
            this.isError = false;
            this.currentStep = "verify-otp";
            
            // Bắt đầu thời gian chờ để gửi lại OTP
            this.startResendCooldown();
          } else if (error.response.status === 401) {
            this.message = "Tài khoản hoặc mật khẩu không chính xác.";
          } else if (error.response.status === 429) {
            this.message = "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau ít phút.";
          } else if (error.response.status === 423) {
            this.message = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ.";
          } else {
            this.message = error.response.data.message || "Đã xảy ra lỗi khi đăng nhập.";
          }
        } else {
          this.message = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet.";
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
        
        // Xác thực thành công, lưu token đăng nhập
        if (response.data.access_token) {
          this.authStore.setToken(response.data.access_token);
          this.authStore.setUser(response.data.user);
          
          // Hiển thị thông báo thành công
          this.message = response.data.message || "Xác thực email thành công! Đăng nhập thành công.";
          this.isError = false;
          
          // Save to localStorage if remember me is checked
          if (this.rememberMe) {
            localStorage.setItem("remember_email", this.email);
          } else {
            localStorage.removeItem("remember_email");
          }
          
          // Redirect to home page
          setTimeout(() => {
            this.message = "Đang chuyển hướng...";
            // Kiểm tra vai trò người dùng để chuyển hướng phù hợp
            if (response.data.user && response.data.user.role === 'admin') {
              this.router.push("/admin");
            } else if (response.data.user && response.data.user.role === 'field_owner') {
              this.router.push("/chusan");
            } else {
              this.router.push("/");
            }
          }, 1000);
        } else {
          // Nếu không có token, quay lại form đăng nhập
          this.message = "Xác thực thành công, vui lòng đăng nhập lại.";
          this.currentStep = "login";
        }
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
    },
    
    backToLogin() {
      this.currentStep = "login";
      this.message = "";
      this.errors = {};
      this.isError = false;
      this.otpCode = "";
      
      // Xóa bộ đếm thời gian nếu có
      if (this.cooldownTimer) {
        clearInterval(this.cooldownTimer);
        this.cooldownTimer = null;
        this.resendCooldown = 0;
      }
    }
  },
  mounted() {
    // Check if there's a remembered email
    const rememberedEmail = localStorage.getItem("remember_email");
    if (rememberedEmail) {
      this.email = rememberedEmail;
      this.rememberMe = true;
    }

    // Kiểm tra nếu có tham số role=field_owner trong URL
    const roleParam = this.$route.query.role;
    if (roleParam === "field_owner") {
      this.message =
        "Vui lòng đăng nhập để truy cập vào giao diện quản lý sân.";
      this.isError = false;
    }
    
    // Kiểm tra xem người dùng vừa xác thực email thành công không
    const verified = this.$route.query.verified;
    const verifiedEmail = this.$route.query.email;
    if (verified === "success" && verifiedEmail) {
      this.email = verifiedEmail;
      this.message = "Xác thực email thành công! Vui lòng đăng nhập với tài khoản của bạn.";
      this.isError = false;
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

.forgot-link,
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

.forgot-link:hover,
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
  background-color: #db4437;
  border-color: #db4437;
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

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  transition: all 0.3s ease;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #545b62;
  box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
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
