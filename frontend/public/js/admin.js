// Admin JavaScript for CRUD operations and Charts

// API Base URL
const API_BASE_URL = "/api";

// Authentication token
let authToken = localStorage.getItem("auth_token");

/**
 * Set the authentication token
 * @param {string} token - The token to set
 */
function setAuthToken(token) {
  authToken = token;
  localStorage.setItem("auth_token", token);
}

/**
 * Clear the authentication token (logout)
 */
function clearAuthToken() {
  authToken = null;
  localStorage.removeItem("auth_token");
}

/**
 * Make an authenticated API request
 * @param {string} endpoint - API endpoint
 * @param {string} method - HTTP method (GET, POST, PUT, DELETE)
 * @param {object} data - Request data (optional)
 * @returns {Promise} - Promise with response data
 */
async function apiRequest(endpoint, method = "GET", data = null) {
  const url = `${API_BASE_URL}${endpoint}`;

  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  };

  if (authToken) {
    options.headers["Authorization"] = `Bearer ${authToken}`;
  }

  if (data) {
    if (method === "GET") {
      // For GET requests, encode data as query parameters
      const params = new URLSearchParams(data);
      url += `?${params.toString()}`;
    } else {
      // For other methods, add data as JSON in the request body
      options.body = JSON.stringify(data);
    }
  }

  try {
    const response = await fetch(url, options);

    // Parse the JSON response
    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || "Có lỗi xảy ra");
    }

    return responseData;
  } catch (error) {
    console.error("API Request Error:", error);
    throw error;
  }
}

/**
 * Upload file with authentication
 * @param {string} endpoint - API endpoint
 * @param {FormData} formData - Form data with files
 * @returns {Promise} - Promise with response data
 */
async function uploadFile(endpoint, formData) {
  const url = `${API_BASE_URL}${endpoint}`;

  const options = {
    method: "POST",
    headers: {},
  };

  if (authToken) {
    options.headers["Authorization"] = `Bearer ${authToken}`;
  }

  options.body = formData;

  try {
    const response = await fetch(url, options);

    // Parse the JSON response
    const responseData = await response.json();

    if (!response.ok) {
      throw new Error(responseData.message || "Có lỗi xảy ra");
    }

    return responseData;
  } catch (error) {
    console.error("File Upload Error:", error);
    throw error;
  }
}

/**
 * Login to admin panel
 * @param {string} email - User email
 * @param {string} password - User password
 * @returns {Promise} - Promise with login response
 */
async function login(email, password) {
  try {
    const response = await apiRequest("/login", "POST", { email, password });

    if (response.access_token) {
      setAuthToken(response.access_token);
    }

    return response;
  } catch (error) {
    clearAuthToken();
    throw error;
  }
}

/**
 * Logout from admin panel
 * @returns {Promise} - Promise with logout response
 */
async function logout() {
  try {
    await apiRequest("/logout", "POST");
    clearAuthToken();
    window.location.href = "/html/admin/admindangnhap.html";
  } catch (error) {
    console.error("Logout Error:", error);
  }
}

// Dashboard Functions
/**
 * Get dashboard data
 * @returns {Promise} - Promise with dashboard data
 */
async function getDashboardData() {
  return await apiRequest("/admin/dashboard");
}

/**
 * Get chart statistics
 * @param {string} period - Period for statistics (week, month, year)
 * @returns {Promise} - Promise with chart data
 */
async function getChartData(period = "week") {
  return await apiRequest(`/admin/stats?period=${period}`);
}

// Product Management Functions
/**
 * Get product list
 * @returns {Promise} - Promise with product list
 */
async function getProducts() {
  return await apiRequest("/admin/products");
}

/**
 * Create a new product
 * @param {FormData} productData - Product form data
 * @returns {Promise} - Promise with created product
 */
async function createProduct(productData) {
  return await uploadFile("/admin/products", productData);
}

/**
 * Update a product
 * @param {number} id - Product ID
 * @param {FormData} productData - Product form data
 * @returns {Promise} - Promise with updated product
 */
async function updateProduct(id, productData) {
  // Add _method=PUT to the formData for method spoofing
  productData.append("_method", "PUT");
  return await uploadFile(`/admin/products/${id}`, productData);
}

/**
 * Delete a product
 * @param {number} id - Product ID
 * @returns {Promise} - Promise with delete response
 */
async function deleteProduct(id) {
  return await apiRequest(`/admin/products/${id}`, "DELETE");
}

/**
 * Get product categories
 * @returns {Promise} - Promise with categories list
 */
async function getCategories() {
  return await apiRequest("/categories");
}

/**
 * Create a new category
 * @param {object} categoryData - Category data
 * @returns {Promise} - Promise with created category
 */
async function createCategory(categoryData) {
  return await apiRequest("/admin/categories", "POST", categoryData);
}

/**
 * Update a category
 * @param {number} id - Category ID
 * @param {object} categoryData - Category data
 * @returns {Promise} - Promise with updated category
 */
async function updateCategory(id, categoryData) {
  return await apiRequest(`/admin/categories/${id}`, "PUT", categoryData);
}

/**
 * Delete a category
 * @param {number} id - Category ID
 * @returns {Promise} - Promise with delete response
 */
async function deleteCategory(id) {
  return await apiRequest(`/admin/categories/${id}`, "DELETE");
}

// User Management Functions
/**
 * Get user list
 * @returns {Promise} - Promise with user list
 */
async function getUsers() {
  return await apiRequest("/admin/users");
}

/**
 * Update a user
 * @param {number} id - User ID
 * @param {object} userData - User data
 * @returns {Promise} - Promise with updated user
 */
async function updateUser(id, userData) {
  return await apiRequest(`/admin/users/${id}`, "PUT", userData);
}

/**
 * Delete a user
 * @param {number} id - User ID
 * @returns {Promise} - Promise with delete response
 */
async function deleteUser(id) {
  return await apiRequest(`/admin/users/${id}`, "DELETE");
}

// Order Management Functions
/**
 * Get order list
 * @returns {Promise} - Promise with order list
 */
async function getOrders() {
  return await apiRequest("/admin/orders");
}

/**
 * Update order status
 * @param {number} id - Order ID
 * @param {number} status - New status
 * @returns {Promise} - Promise with updated order
 */
async function updateOrderStatus(id, status) {
  return await apiRequest(`/admin/orders/${id}/status`, "PUT", { status });
}

// Comment & Rating Management Functions
/**
 * Get comment list
 * @returns {Promise} - Promise with comment list
 */
async function getComments() {
  return await apiRequest("/admin/comments");
}

/**
 * Update comment status
 * @param {number} id - Comment ID
 * @param {string} status - New status
 * @returns {Promise} - Promise with updated comment
 */
async function updateCommentStatus(id, status) {
  return await apiRequest(`/admin/comments/${id}/status`, "PUT", { status });
}

/**
 * Get rating list
 * @returns {Promise} - Promise with rating list
 */
async function getRatings() {
  return await apiRequest("/admin/ratings");
}

/**
 * Update rating status
 * @param {number} id - Rating ID
 * @param {string} status - New status
 * @returns {Promise} - Promise with updated rating
 */
async function updateRatingStatus(id, status) {
  return await apiRequest(`/admin/ratings/${id}/status`, "PUT", { status });
}

// Initialize charts on dashboard
async function initDashboardCharts() {
  try {
    // Get dashboard data
    const dashboardData = await getDashboardData();
    const chartData = await getChartData("week");

    // Update dashboard statistics
    updateDashboardStats(dashboardData);

    // Initialize revenue chart
    initRevenueChart(chartData.revenue_stats);

    // Initialize customer satisfaction chart
    initSatisfactionChart(chartData.satisfaction_stats);

    // Initialize product chart
    initProductChart(dashboardData.top_selling_products);
  } catch (error) {
    console.error("Error initializing dashboard:", error);
    showErrorMessage(
      "Không thể tải dữ liệu bảng điều khiển. Vui lòng thử lại sau."
    );
  }
}

// Update dashboard statistics
function updateDashboardStats(data) {
  // Update total revenue
  const totalRevenue = data.order_stats.revenue || 0;
  document.querySelector(".bg-red-admin h5").textContent =
    formatCurrency(totalRevenue);

  // Update total orders
  const totalOrders = data.order_stats.total || 0;
  document.querySelector(".bg-orange-admin h5").textContent = totalOrders;

  // Update total sold products
  const totalProducts = data.product_stats.total || 0;
  document.querySelector(".bg-green-admin h5").textContent = totalProducts;

  // Update new customers
  const totalUsers =
    (data.user_stats.user || 0) + (data.user_stats.field_owner || 0);
  document.querySelector(".bg-purple-admin h5").textContent = totalUsers;

  // Update featured products table
  updateFeaturedProductsTable(data.top_selling_products);
}

// Initialize revenue chart
function initRevenueChart(revenueData) {
  const ctx1 = document.getElementById("chart1").getContext("2d");

  // Prepare data
  const labels = revenueData.map((item) => formatDate(item.date));
  const data = revenueData.map((item) => item.revenue || 0);

  new Chart(ctx1, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Doanh thu",
          data: data,
          backgroundColor: "#0095FF",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return formatCurrency(value, true);
            },
          },
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return formatCurrency(context.raw);
            },
          },
        },
      },
    },
  });
}

// Initialize customer satisfaction chart
function initSatisfactionChart(satisfactionData) {
  const ctx2 = document.getElementById("chart2").getContext("2d");

  // Prepare data
  const ratingLabels = ["1 sao", "2 sao", "3 sao", "4 sao", "5 sao"];
  const ratingCounts = [0, 0, 0, 0, 0];

  // Fill in the data we have
  satisfactionData.forEach((item) => {
    const rating = Math.round(item.rating) - 1; // Convert to 0-based index
    if (rating >= 0 && rating < 5) {
      ratingCounts[rating] = item.count;
    }
  });

  new Chart(ctx2, {
    type: "doughnut",
    data: {
      labels: ratingLabels,
      datasets: [
        {
          data: ratingCounts,
          backgroundColor: [
            "#e74a3b",
            "#f6c23e",
            "#36b9cc",
            "#4e73df",
            "#1cc88a",
          ],
          hoverBackgroundColor: [
            "#c23321",
            "#dda20a",
            "#258391",
            "#2e59d9",
            "#17a673",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: "70%",
      plugins: {
        legend: {
          position: "bottom",
        },
      },
    },
  });
}

// Initialize product chart
function initProductChart(productData) {
  // Get the table body
  const tableBody = document.querySelector(".product-card-admin table tbody");
  if (!tableBody) return;

  // Clear existing rows
  tableBody.innerHTML = "";

  // Add rows for each product (top 3)
  const topProducts = productData.slice(0, 3);

  topProducts.forEach((product, index) => {
    // Calculate a percentage value for visualization
    const percentage = Math.min(
      Math.round((product.bestseller / 100) * 100),
      100
    );

    // Determine the color class based on the index
    let colorClass;
    switch (index) {
      case 0:
        colorClass = "blue";
        break;
      case 1:
        colorClass = "green";
        break;
      case 2:
        colorClass = "purple";
        break;
      default:
        colorClass = "blue";
    }

    // Create the table row
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>0${index + 1}</td>
            <td>${product.Ten_san_pham}</td>
            <td>
                <div class="progress">
                    <div class="progress-bar-${colorClass}" style="width: ${percentage}%;"></div>
                </div>
            </td>
            <td><span class="badge-${colorClass}">${percentage}%</span></td>
        `;

    tableBody.appendChild(row);
  });
}

// Update featured products table
function updateFeaturedProductsTable(products) {
  const tableBody = document.querySelector(".product-card-admin table tbody");
  if (!tableBody) return;

  // Clear existing rows
  tableBody.innerHTML = "";

  // Add new rows
  products.slice(0, 3).forEach((product, index) => {
    // Calculate percentage based on bestseller value (assuming 100 as max)
    const percentage =
      Math.min(Math.round((product.bestseller / 100) * 100), 100) ||
      Math.round(Math.random() * 50);

    // Determine color
    let colorClass;
    if (index === 0) colorClass = "blue";
    else if (index === 1) colorClass = "green";
    else colorClass = "purple";

    // Create row
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>0${index + 1}</td>
            <td>${product.Ten_san_pham}</td>
            <td>
                <div class="progress">
                    <div class="progress-bar-${colorClass}" style="width: ${percentage}%;"></div>
                </div>
            </td>
            <td><span class="badge-${colorClass}">${percentage}%</span></td>
        `;

    tableBody.appendChild(row);
  });
}

// Format currency
function formatCurrency(value, short = false) {
  if (short) {
    // For axis labels, use shorter format
    if (value >= 1000000) {
      return (value / 1000000).toFixed(1) + "tr";
    } else if (value >= 1000) {
      return (value / 1000).toFixed(0) + "k";
    }
    return value;
  }

  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
}

// Format date
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("vi-VN");
}

// Show error message
function showErrorMessage(message) {
  const errorDiv = document.createElement("div");
  errorDiv.className = "alert alert-danger";
  errorDiv.textContent = message;

  // Find a container to show the message
  const container = document.querySelector(".container") || document.body;
  container.prepend(errorDiv);

  // Remove after 5 seconds
  setTimeout(() => {
    errorDiv.remove();
  }, 5000);
}

// Show success message
function showSuccessMessage(message) {
  const successDiv = document.createElement("div");
  successDiv.className = "alert alert-success";
  successDiv.textContent = message;

  // Find a container to show the message
  const container = document.querySelector(".container") || document.body;
  container.prepend(successDiv);

  // Remove after 3 seconds
  setTimeout(() => {
    successDiv.remove();
  }, 3000);
}

// Check if user is logged in
function checkAuth() {
  const token = localStorage.getItem("auth_token");
  if (!token && !window.location.href.includes("admindangnhap.html")) {
    window.location.href = "admindangnhap.html";
    return false;
  }
  return true;
}

// Document ready function
document.addEventListener("DOMContentLoaded", function () {
  // Check authentication
  if (!checkAuth()) return;

  // Setup logout button
  const logoutButton = document.querySelector(
    ".tienichadmin-left a:nth-child(3)"
  );
  if (logoutButton) {
    logoutButton.addEventListener("click", function (e) {
      e.preventDefault();
      logout();
    });
  }

  // Setup login form
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        await login(email, password);
        window.location.href = "admin.html";
      } catch (error) {
        showErrorMessage(
          "Đăng nhập thất bại. Vui lòng kiểm tra email và mật khẩu."
        );
      }
    });
  }

  // Initialize dashboard if we're on the dashboard page
  if (window.location.href.includes("admin.html")) {
    initDashboardCharts();
  }

  // Product Management
  if (window.location.href.includes("adminsanpham.html")) {
    loadProducts();
  }

  // Add product form
  const addProductForm = document.getElementById("addProductForm");
  if (addProductForm) {
    addProductForm.addEventListener("submit", handleAddProduct);
  }

  // Category Management
  if (window.location.href.includes("admindanhmuc.html")) {
    loadCategories();
  }

  // Add category form
  const addCategoryForm = document.getElementById("addCategoryForm");
  if (addCategoryForm) {
    addCategoryForm.addEventListener("submit", handleAddCategory);
  }

  // User Management
  if (window.location.href.includes("admindangnhap.html")) {
    loadUsers();
  }

  // Order Management
  if (window.location.href.includes("admingiohanh.html")) {
    loadOrders();
  }

  // Comment Management
  if (window.location.href.includes("adminbinhluan.html")) {
    loadComments();
  }
});

// Load products for product management page
async function loadProducts() {
  try {
    const response = await getProducts();
    const products = response.data;

    // Get the table body
    const tableBody = document.querySelector(".admin-sanpham");
    if (!tableBody) return;

    // Clear existing rows
    tableBody.innerHTML = "";

    // Add rows for each product
    products.forEach((product) => {
      const row = document.createElement("tr");
      row.dataset.id = product.id;

      row.innerHTML = `
                <td class="input-head"><input type="checkbox"></td>
                <td><img src="${product.Hinh_anh}" alt="${
        product.Ten_san_pham
      }" style="width: 50px; height: 50px;">${product.Ten_san_pham}</td>
                <td>${formatCurrency(product.Gia)}</td>
                <td>${product.So_luong > 0 ? "Đang bán" : "Hết hàng"}</td>
                <td>
                    <i class="bi bi-eye me-2" data-action="view"></i>
                    <i class="bi bi-pencil-square me-2" data-action="edit"></i>
                    <i class="bi bi-trash" data-action="delete"></i>
                </td>
            `;

      // Add event listeners for action buttons
      row.querySelectorAll("[data-action]").forEach((button) => {
        button.addEventListener("click", () => {
          const action = button.dataset.action;
          const productId = row.dataset.id;

          if (action === "view") {
            // View product details
            viewProduct(productId);
          } else if (action === "edit") {
            // Edit product
            window.location.href = `adminaddproduct.html?id=${productId}`;
          } else if (action === "delete") {
            // Delete product
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
              deleteProductHandler(productId, row);
            }
          }
        });
      });

      tableBody.appendChild(row);
    });
  } catch (error) {
    console.error("Error loading products:", error);
    showErrorMessage("Không thể tải danh sách sản phẩm. Vui lòng thử lại sau.");
  }
}

// Handle add product form submission
async function handleAddProduct(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    // Check if this is an edit (update)
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get("id");

    let response;
    if (productId) {
      // Update existing product
      response = await updateProduct(productId, formData);
      showSuccessMessage("Sản phẩm đã được cập nhật thành công!");
    } else {
      // Create new product
      response = await createProduct(formData);
      showSuccessMessage("Sản phẩm đã được tạo thành công!");

      // Clear form
      form.reset();
    }

    // Redirect to product list after a short delay
    setTimeout(() => {
      window.location.href = "adminsanpham.html";
    }, 1500);
  } catch (error) {
    console.error("Error saving product:", error);
    showErrorMessage(
      "Lỗi khi lưu sản phẩm: " + (error.message || "Vui lòng thử lại sau.")
    );
  }
}

// Delete product handler
async function deleteProductHandler(productId, rowElement) {
  try {
    await deleteProduct(productId);

    // Remove row from table
    if (rowElement) {
      rowElement.remove();
    }

    showSuccessMessage("Sản phẩm đã được xóa thành công!");
  } catch (error) {
    console.error("Error deleting product:", error);
    showErrorMessage(
      "Lỗi khi xóa sản phẩm: " + (error.message || "Vui lòng thử lại sau.")
    );
  }
}

// View product details
function viewProduct(productId) {
  window.location.href = `adminsanpham.html?view=${productId}`;
}

// Export functions for global access
window.adminAPI = {
  login,
  logout,
  getProducts,
  createProduct,
  updateProduct,
  deleteProduct,
  getCategories,
  createCategory,
  updateCategory,
  deleteCategory,
  getUsers,
  updateUser,
  deleteUser,
  getOrders,
  updateOrderStatus,
  getComments,
  updateCommentStatus,
  getRatings,
  updateRatingStatus,
  getDashboardData,
  getChartData,
};
