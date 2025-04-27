<?php
/**
 * Script tạo tài khoản admin
 * Chạy script này bằng cách: php create-admin.php
 */

// Kết nối đến autoload
require __DIR__ . '/vendor/autoload.php';

// Load các biến môi trường từ file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Kết nối CSDL
$host = $_ENV['DB_HOST'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kết nối cơ sở dữ liệu thành công.\n";
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Thông tin tài khoản admin
$ten = 'Admin';
$email = 'admin2@keysport.com';
$password = password_hash('Admin@123', PASSWORD_BCRYPT);
$phone = '0123456789';
$role = 1; // 1 = admin (thay đổi từ 4 xuống 1 vì cột role có thể là tinyint giới hạn giá trị nhỏ)

// Kiểm tra email đã tồn tại chưa
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "Email $email đã tồn tại trong hệ thống. Vui lòng sử dụng email khác.\n";
} else {
    // Thêm user mới
    $stmt = $db->prepare("INSERT INTO users (name, email, password, phone, role, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), NOW())");
    $result = $stmt->execute([$ten, $email, $password, $phone, $role]);
    
    if ($result) {
        echo "Tạo tài khoản admin thành công!\n";
        echo "Email: $email\n";
        echo "Mật khẩu: Admin@123\n";
    } else {
        echo "Lỗi khi tạo tài khoản: " . print_r($stmt->errorInfo(), true);
    }
}

echo "\nHoàn tất.\n"; 