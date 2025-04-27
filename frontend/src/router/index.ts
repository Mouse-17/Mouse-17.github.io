import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import YardOwnerView from "@/views/chu_san/FormAddYardView.vue";
import { useAuthStore } from "../stores/auth";

const routesadmin = [
  // admin
  {
    path: "/admin",
    name: "Trang chủ admin",
    component: () => import("../views/admin/DashboardView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlidanhmuc",
    name: "Quản lí danh mục",
    component: () => import("../views/admin/AdminCategoryView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlisanpham",
    name: "Quản lí sản phẩm",
    component: () => import("../views/admin/AdminProductView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlidonhang",
    name: "Quản lí đơn hàng",
    component: () => import("../views/admin/AdminOrderView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlinguoidung",
    name: "Quản lí người dùng",
    component: () => import("../views/admin/AdminUserView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlibinhluan",
    name: "Quản lí bình luận",
    component: () => import("../views/admin/AdminCommentView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlibaiviet",
    name: "Quản lí bài viết",
    component: () => import("../views/admin/AdminBlogView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/quanlidanhgia",
    name: "Quản lí đánh giá",
    component: () => import("../views/admin/AdminRatingView.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/themdanhmuc",
    name: "Thêm danh mục",
    component: () => import("../views/admin/FormAddCategory.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/themsanpham",
    name: "Thêm sản phẩm",
    component: () => import("../views/admin/FormAddProduct.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
  {
    path: "/admin/thembaiviet",
    name: "Thêm bài viết",
    component: () => import("../views/admin/FormAddBlog.vue"),
    meta: { hideHeaderFooter: true, requiresAuth: true, requiresAdmin: true },
  },
];
const routeuser = [
  {
    path: "/",
    name: "Trang chủ",
    component: HomeView,
  },
  {
    path: "/dangnhap",
    name: "Đăng nhập",
    component: () => import("../views/LoginView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  {
    path: "/login",
    redirect: "/dangnhap",
  },
  {
    path: "/quenmatkhau",
    name: "Quên mật khẩu",
    component: () => import("../views/ForgotPasswordView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  {
    path: "/dangky",
    name: "Đăng ký",
    component: () => import("../views/RegisterView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  {
    path: "/booking",
    name: "Booking",
    component: () => import("../views/BookingView.vue"),
  },
  {
    path: "/sanpham",
    name: "Sản phẩm",
    component: () => import("../views/ProductView.vue"),
  },
  {
    path: "/gioithieu",
    name: "Giới thiệu",
    component: () => import("../views/AboutView.vue"),
  },
  {
    path: "/lienhe",
    name: "Liên hệ",
    component: () => import("../views/ContactView.vue"),
  },
  {
    path: "/tintuc",
    name: "Tin tức",
    component: () => import("../views/NewsView.vue"),
  },
  {
    path: "/sanpham/:id",
    name: "Chi tiết sản phẩm",
    component: () => import("../views/ProductDetailView.vue"),
  },
  {
    path: "/giohang",
    name: "cart",
    component: () => import("../views/CartView.vue"),
  },
  {
    path: "/thanhtoan",
    name: "Thanh toán",
    component: () => import("../views/PayView.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/san/:id",
    name: "Chi tiết booking",
    component: () => import("../views/BookingDetailView.vue"),
  },
  {
    path: "/booking-detail/:id",
    name: "BookingDetail",
    component: () => import("../views/BookingDetailView.vue"),
    meta: { hideHeaderFooter: false, requiresAuth: true },
  },
  {
    path: "/don-hang",
    name: "Đơn hàng",
    component: () => import("../views/OrderView.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/don-hang/:id",
    name: "Chi tiết đơn hàng",
    component: () => import("../views/OrderDetailView.vue"),
    meta: { requiresAuth: true },
  },

  // chủ sân
  {
    path: "/chusan",
    name: "Chủ sân",
    component: () => import("@/views/chu_san/AnalyticsView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/themsanmoi",
    name: "Thêm sân",
    component: () => import("@/views/chu_san/FormAddYardView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/lichsan",
    name: "Lịch sân",
    component: () => import("@/views/chu_san/BookingCalendarView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/pheduyet",
    name: "Chờ phê duyệt",
    component: () => import("@/views/chu_san/AcceptView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/khyeuthich",
    name: "Khách hàng",
    component: () => import("@/views/chu_san/CustomerLoveView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/suasan",
    name: "Chỉnh sửa thông tin sân",
    component: () => import("@/views/chu_san/FormEditYardView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/caidat",
    name: "Cài đặt",
    component: () => import("@/views/chu_san/SettingView.vue"),
    meta: {
      hideHeaderFooter: true,
      requiresAuth: true,
      requiresFieldOwner: true,
    },
  },
  {
    path: "/profile",
    name: "Hồ sơ người dùng",
    component: () => import("../views/UserProfileView.vue"),
    meta: { requiresAuth: true },
  },
  // chủ sân auth
  {
    path: "/chusan/login",
    name: "Đăng nhập chủ sân",
    component: () => import("../views/chusan-auth/ChusanLoginView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  {
    path: "/chusan/dangky",
    name: "Đăng ký chủ sân",
    component: () => import("../views/chusan-auth/ChusanRegisterView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  {
    path: "/chusan/quenmatkhau",
    name: "Quên mật khẩu chủ sân",
    component: () =>
      import("../views/chusan-auth/ChusanForgotPasswordView.vue"),
    meta: { hideHeaderFooter: true, guestOnly: true },
  },
  // Error routes
  {
    path: "/unauthorized",
    name: "Không có quyền truy cập",
    component: () => import("../views/UnauthorizedView.vue"),
    meta: { hideHeaderFooter: false },
  },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: () => import("../views/NotFoundView.vue"),
    meta: { hideHeaderFooter: false },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [...routeuser, ...routesadmin], // Nối mảng route admin vào
});

// Navigation Guard
router.beforeEach(async (to, from, next) => {
  // Access the auth store
  const authStore = useAuthStore();

  // Check if authentication status is known, if not try to restore from localStorage
  if (!authStore.isAuthenticated) {
    await authStore.checkAuth();
  }

  // Nếu người dùng đã đăng nhập và có role field_owner và đang truy cập trang chủ
  if (authStore.isAuthenticated && authStore.isFieldOwner && to.path === "/") {
    // Chuyển hướng đến trang quản lý chủ sân
    next({ path: "/chusan" });
    return;
  }
  
  // Nếu người dùng đã đăng nhập và có role admin và đang truy cập trang chủ hoặc đăng nhập
  if (authStore.isAuthenticated && authStore.isAdmin && (to.path === "/" || to.path === "/dangnhap")) {
    // Chuyển hướng đến trang quản trị admin
    next({ path: "/admin" });
    return;
  }

  // Handle route access based on authentication and roles
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // If route requires auth but user is not authenticated, redirect to login
    next({ path: "/dangnhap", query: { redirect: to.fullPath } });
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    // If route requires admin role but user is not an admin
    next({ path: "/unauthorized" });
  } else if (to.meta.requiresFieldOwner && !authStore.isFieldOwner) {
    // If route requires field owner role but user is not a field owner
    // Redirect to field owner login page
    next({ path: "/chusan/login", query: { redirect: to.fullPath } });
  } else if (to.meta.guestOnly && authStore.isAuthenticated) {
    // If route is for guests only (like login page) but user is already authenticated
    if (authStore.isFieldOwner && to.path.includes("/chusan")) {
      // If field owner is trying to access field owner login page, redirect to field owner dashboard
      next({ path: "/chusan" });
    } else if (authStore.isAdmin) {
      // If admin is trying to access login or guest pages, redirect to admin dashboard
      next({ path: "/admin" });
    } else if (!authStore.isFieldOwner && to.path.includes("/chusan")) {
      // If regular user is trying to access field owner login page, redirect to unauthorized
      next({ path: "/unauthorized" });
    } else {
      // For other guest-only pages
      next({ path: "/" });
    }
  } else {
    // Proceed to the route
    next();
  }
});

export default router;
