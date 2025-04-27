<template>
  <main>
    <section class="banner">
      <img class="imgban" src="../../../public/img/Banner-ChuSan.png" alt="" />
    </section>

    <section class="register-section py-5">
      <div class="sports-background"></div>
      <div class="container" style="position: relative; z-index: 1">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <div class="register-form">
              <h2 class="text-center mb-4 fw-bold">
                ĐĂNG KÝ TÀI KHOẢN CHỦ SÂN
              </h2>

              <div
                v-if="message"
                class="alert"
                :class="isError ? 'alert-danger' : 'alert-success'"
                role="alert"
              >
                {{ message }}
              </div>

              <form @submit.prevent="handleRegister">
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Họ tên</label>
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="bi bi-person-fill"></i
                      ></span>
                      <input
                        type="text"
                        v-model="name"
                        class="form-control"
                        placeholder="Nhập họ tên đầy đủ"
                        required
                      />
                    </div>
                    <div v-if="errors.name" class="text-danger mt-1">
                      {{ errors.name[0] }}
                    </div>
                  </div>

                  <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="bi bi-telephone-fill"></i
                      ></span>
                      <input
                        type="text"
                        v-model="phone"
                        class="form-control"
                        placeholder="Nhập số điện thoại"
                        required
                      />
                    </div>
                    <div v-if="errors.phone" class="text-danger mt-1">
                      {{ errors.phone[0] }}
                    </div>
                  </div>
                </div>

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
                      placeholder="Nhập email"
                      required
                    />
                  </div>
                  <div v-if="errors.email" class="text-danger mt-1">
                    {{ errors.email[0] }}
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold">Địa chỉ</label>
                  <div class="input-group">
                    <span class="input-group-text"
                      ><i class="bi bi-geo-alt-fill"></i
                    ></span>
                    <input
                      type="text"
                      v-model="address"
                      class="form-control"
                      placeholder="Nhập địa chỉ"
                      required
                    />
                  </div>
                  <div v-if="errors.address" class="text-danger mt-1">
                    {{ errors.address[0] }}
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4">
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
                      />
                    </div>
                    <div v-if="errors.password" class="text-danger mt-1">
                      {{ errors.password[0] }}
                    </div>
                  </div>

                  <div class="col-md-6 mb-4">
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
                      />
                    </div>
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
                    <a href="#" class="term-link">Điều khoản dịch vụ</a> và
                    <a href="#" class="term-link">Chính sách bảo mật</a>
                  </label>
                </div>

                <button
                  type="submit"
                  class="btn btn-success w-100 py-2"
                  :disabled="loading || !agreeTerms"
                >
                  <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  ĐĂNG KÝ TÀI KHOẢN
                </button>

                <div class="text-center mt-4">
                  <span>Đã có tài khoản? </span>
                  <router-link to="/chusan/login" class="login-link">
                    Đăng nhập ngay
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
  name: "ChusanRegisterView",
  setup() {
    const router = useRouter();
    return { router };
  },
  data() {
    return {
      name: "",
      email: "",
      phone: "",
      address: "",
      password: "",
      password_confirmation: "",
      agreeTerms: false,
      loading: false,
      message: "",
      isError: false,
      errors: {},
    };
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      this.message = "";
      this.errors = {};
      this.isError = false;

      try {
        const response = await axios.post(
          `${API_URL}/api/chusan/register`,
          {
            name: this.name,
            email: this.email,
            phone: this.phone,
            address: this.address,
            password: this.password,
            password_confirmation: this.password_confirmation,
            role: "field_owner",
          },
          {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          }
        );

        // Registration successful
        this.message =
          "Đăng ký tài khoản chủ sân thành công! Đang chuyển hướng đến trang đăng nhập sau 2 giây...";

        // Redirect to field owner login page after 2 seconds
        setTimeout(() => {
          this.router.push("/chusan/login");
        }, 2000);
      } catch (error) {
        this.isError = true;

        if (error.response) {
          if (error.response.status === 422) {
            // Validation errors
            this.errors = error.response.data.errors || {};
            this.message =
              error.response.data.message ||
              "Vui lòng kiểm tra thông tin đăng ký.";
          } else {
            this.message =
              error.response.data.message || "Đã xảy ra lỗi khi đăng ký.";
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
    url("../../../public/img/background-chusan.jpg");
  background-size: cover;
  background-position: center;
  z-index: 0;
}

.register-form {
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

.login-link,
.term-link {
  color: #28a745;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  padding: 2px 0;
  position: relative;
}

.login-link:hover,
.term-link:hover {
  text-decoration: underline;
  color: #1e7e34;
}

.imgban {
  width: 100%;
  height: auto;
}

.form-check-input:checked {
  background-color: #28a745;
  border-color: #28a745;
}

@media (max-width: 768px) {
  .register-form {
    padding: 2rem 1.5rem;
  }
}
</style>
