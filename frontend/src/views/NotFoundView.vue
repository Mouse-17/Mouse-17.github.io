<template>
  <main>
    <div class="container py-5 text-center">
      <div class="not-found-container">
        <div class="mb-4">
          <i class="bi bi-question-circle display-1 text-warning"></i>
        </div>
        <h1 class="mb-3">Trang không tồn tại</h1>
        <p class="lead mb-4">
          Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
        </p>
        <div class="d-flex justify-content-center gap-3">
          <router-link to="/" class="btn btn-primary px-4 py-2">
            <i class="bi bi-house-door me-2"></i> Trang chủ
          </router-link>
          <button @click="goBack" class="btn btn-outline-secondary px-4 py-2">
            <i class="bi bi-arrow-left me-2"></i> Quay lại
          </button>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: "NotFoundView",
  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.not-found-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.lead {
  font-size: 1.2rem;
}

.btn {
  font-size: 1.1rem;
}
</style>
