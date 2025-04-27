-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for keysport
CREATE DATABASE IF NOT EXISTS `keysport` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `keysport`;

-- Dumping structure for table keysport.bai_viet
CREATE TABLE IF NOT EXISTS `bai_viet` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Tieu_de` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_Loai` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bai_viet_id_loai_foreign` (`ID_Loai`),
  CONSTRAINT `bai_viet_id_loai_foreign` FOREIGN KEY (`ID_Loai`) REFERENCES `loai_bai_viet` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.bai_viet: ~0 rows (approximately)

-- Dumping structure for table keysport.binh_luan
CREATE TABLE IF NOT EXISTS `binh_luan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_KH` bigint unsigned NOT NULL,
  `ID_SP` bigint unsigned NOT NULL,
  `Noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ngay_binh_luan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `binh_luan_id_kh_foreign` (`ID_KH`),
  KEY `binh_luan_id_sp_foreign` (`ID_SP`),
  CONSTRAINT `binh_luan_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `binh_luan_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.binh_luan: ~0 rows (approximately)

-- Dumping structure for table keysport.binh_luan_san
CREATE TABLE IF NOT EXISTS `binh_luan_san` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_KH` bigint unsigned NOT NULL,
  `ID_San` bigint unsigned NOT NULL,
  `Noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ngay_binh_luan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `binh_luan_san_id_kh_foreign` (`ID_KH`),
  KEY `binh_luan_san_id_san_foreign` (`ID_San`),
  CONSTRAINT `binh_luan_san_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `binh_luan_san_id_san_foreign` FOREIGN KEY (`ID_San`) REFERENCES `san` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.binh_luan_san: ~0 rows (approximately)

-- Dumping structure for table keysport.booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kh` bigint unsigned DEFAULT NULL,
  `id_san` bigint unsigned NOT NULL,
  `id_kg` bigint unsigned NOT NULL,
  `Ngay_dat` date NOT NULL,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `Ten_KH` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SDT` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `Tong_tien` decimal(10,0) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phuong_thuc_thanh_toan` tinyint DEFAULT '1' COMMENT '1: Thanh toán tại sân, 2: Chuyển khoản, 3: Thanh toán online',
  `trang_thai_thanh_toan` tinyint DEFAULT '0' COMMENT '0: Chưa thanh toán, 1: Đã thanh toán một phần, 2: Đã thanh toán',
  `ma_giao_dich` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thoi_gian_thanh_toan` timestamp NULL DEFAULT NULL,
  `ghi_chu_thanh_toan` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `booking_id_kh_foreign` (`id_kh`),
  KEY `booking_id_san_foreign` (`id_san`),
  KEY `booking_id_kg_foreign` (`id_kg`),
  CONSTRAINT `booking_id_kg_foreign` FOREIGN KEY (`id_kg`) REFERENCES `khung_gio` (`id`),
  CONSTRAINT `booking_id_kh_foreign` FOREIGN KEY (`id_kh`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_id_san_foreign` FOREIGN KEY (`id_san`) REFERENCES `san` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.booking: ~2 rows (approximately)
INSERT INTO `booking` (`id`, `id_kh`, `id_san`, `id_kg`, `Ngay_dat`, `Trang_thai`, `Ten_KH`, `SDT`, `Email`, `Ghi_chu`, `Tong_tien`, `created_at`, `updated_at`, `phuong_thuc_thanh_toan`, `trang_thai_thanh_toan`, `ma_giao_dich`, `thoi_gian_thanh_toan`, `ghi_chu_thanh_toan`) VALUES
	(4, 1, 9, 5, '2025-04-22', 1, 'nguyen van a', '0123456789', 'vuong20031590@gmail.com', NULL, 300000, '2025-04-22 05:02:50', '2025-04-22 05:02:50', 2, 0, NULL, NULL, NULL),
	(14, 1, 9, 1, '2025-04-22', 1, 'aaaaaaa', '1111111111111', 'vuong20031590@gmail.com', NULL, 150000, '2025-04-22 09:50:31', '2025-04-22 09:50:31', 2, 0, NULL, NULL, NULL);

-- Dumping structure for table keysport.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.bookings: ~0 rows (approximately)

-- Dumping structure for table keysport.books
CREATE TABLE IF NOT EXISTS `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` int DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `available_quantity` int DEFAULT '0',
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.books: ~0 rows (approximately)

-- Dumping structure for table keysport.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_user_id_foreign` (`user_id`),
  KEY `cart_session_id_index` (`session_id`),
  CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.cart: ~0 rows (approximately)

-- Dumping structure for table keysport.carts
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_kh` int DEFAULT NULL COMMENT 'ID khách hàng nếu đã đăng nhập',
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID phiên làm việc cho khách không đăng nhập',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_kh` (`id_kh`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.carts: ~0 rows (approximately)
INSERT INTO `carts` (`id`, `id_kh`, `session_id`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'cZsq7XIYV4HZ5hUbGPLkmVWMbqXcTKNHUNZ79mPK', '2025-04-22 11:15:13', '2025-04-22 11:15:13'),
	(2, NULL, 'cbad1301-2e09-4dcd-8879-b737461adc74', '2025-04-22 11:26:21', '2025-04-22 11:26:21'),
	(3, NULL, '388805c0-2fed-4863-9444-f1d2b9f5d9ee', '2025-04-22 11:26:55', '2025-04-22 11:26:55'),
	(4, NULL, 'd9ef3cfb-c9fc-4ef1-9626-0734371fa70a', '2025-04-22 11:34:23', '2025-04-22 11:34:23'),
	(5, NULL, '312e9830-6c0b-47e4-a346-22ecd02966ef', '2025-04-22 11:34:29', '2025-04-22 11:34:29'),
	(6, NULL, 'd0e75135-8b39-43b4-bf17-aa543497ffbd', '2025-04-22 11:35:03', '2025-04-22 11:35:03'),
	(7, NULL, 'fa91a1b3-248c-4fc9-8856-3fe37d5b1458', '2025-04-22 11:41:38', '2025-04-22 11:41:38'),
	(8, NULL, '0bd4835c-d302-4753-ae8c-fdffcc020479', '2025-04-22 11:41:47', '2025-04-22 11:41:47'),
	(9, NULL, '43495968-5c08-4ddd-a4fe-40aacbc50fc9', '2025-04-22 11:53:10', '2025-04-22 11:53:10'),
	(10, NULL, 'cart_dsnj88s2doa', '2025-04-22 13:35:59', '2025-04-22 13:35:59'),
	(11, NULL, 'd9e3df32-3189-4edf-b1de-a3382da3195f', '2025-04-22 13:48:12', '2025-04-22 13:48:12'),
	(12, NULL, '3o7N7KNRym4OxTDTnSpLOJXHNc9zj2jmsAJIc789', '2025-04-23 05:35:34', '2025-04-23 05:35:34'),
	(13, NULL, 'GPXJ3cRwBaVAy8j8qPyjGb0a79vyOud8EU49r8Po', '2025-04-23 05:35:38', '2025-04-23 05:35:38'),
	(14, NULL, 'fm193L85NtORfApREVXVDfjGw5jPH7zqbHd6UmZZ', '2025-04-23 05:38:21', '2025-04-23 05:38:21'),
	(15, NULL, 'prOs50sEeEla06Isu8EVMZtKW0zFsJArgkghn6uN', '2025-04-23 05:42:29', '2025-04-23 05:42:29'),
	(16, NULL, 'cpvcZefTjWGv0j0xWaqMkVQJS3E72Z8cf3sTnzPK', '2025-04-23 05:47:42', '2025-04-23 05:47:42'),
	(17, NULL, 'SHYSGU0LhVpbvCkICrX0us0v6pDhW0vlwhvTQvHX', '2025-04-23 05:48:07', '2025-04-23 05:48:07'),
	(18, NULL, 'TsyfCoKSk87LaZ1jRhmNDUFMVjWrHjbCCV1aj4n2', '2025-04-23 05:53:41', '2025-04-23 05:53:41'),
	(19, NULL, 'Qt9VJmBag0tbiC85G97cDIASK6065lDEGZGYWuOC', '2025-04-23 05:54:22', '2025-04-23 05:54:22'),
	(20, NULL, '9fN9DjVjHmxooZTeBsMGRkaWfGTaHFknJqk2a8am', '2025-04-23 05:57:29', '2025-04-23 05:57:29'),
	(21, NULL, 'jOgkgoFykDdJiYk2oknl8XQgetDgttfPB4W7Amrg', '2025-04-23 05:57:40', '2025-04-23 05:57:40'),
	(22, NULL, 'u6jXDWZGRLrfZhpnudCSglceOJIWjI9AJc1FBaFF', '2025-04-23 05:58:04', '2025-04-23 05:58:04'),
	(23, NULL, '3Rpw966nFWQaNiaLJ6vICBCHcNOWss4iDqI4Ep1Y', '2025-04-23 05:58:07', '2025-04-23 05:58:07'),
	(24, NULL, 'S9H1be8l8opsDoRbAurR6L02lmMojHPxUsX58pVD', '2025-04-23 06:00:13', '2025-04-23 06:00:13'),
	(25, NULL, 'ny4YxIeEK5omiuFFIy2203jN0l8EYkGHYMXdWGAf', '2025-04-23 06:00:13', '2025-04-23 06:00:13');

-- Dumping structure for table keysport.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `cart_id` bigint NOT NULL,
  `id_sp` bigint NOT NULL COMMENT 'ID sản phẩm',
  `id_mau` int DEFAULT NULL COMMENT 'ID màu sắc',
  `id_size` int DEFAULT NULL COMMENT 'ID kích thước',
  `so_luong` int NOT NULL DEFAULT '1',
  `don_gia` decimal(12,2) NOT NULL COMMENT 'Đơn giá tại thời điểm thêm vào giỏ hàng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `id_sp` (`id_sp`),
  KEY `id_mau` (`id_mau`),
  KEY `id_size` (`id_size`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.cart_items: ~0 rows (approximately)
INSERT INTO `cart_items` (`id`, `cart_id`, `id_sp`, `id_mau`, `id_size`, `so_luong`, `don_gia`, `created_at`, `updated_at`) VALUES
	(6, 6, 1, NULL, NULL, 1, 2800000.00, '2025-04-22 11:35:03', '2025-04-22 11:35:03'),
	(7, 7, 1, NULL, NULL, 1, 2800000.00, '2025-04-22 11:41:38', '2025-04-22 11:41:38'),
	(8, 8, 1, NULL, NULL, 1, 2800000.00, '2025-04-22 11:41:47', '2025-04-22 11:41:47'),
	(9, 9, 1, NULL, NULL, 1, 2800000.00, '2025-04-22 11:53:10', '2025-04-22 11:53:10'),
	(11, 11, 2, NULL, NULL, 1, 420000.00, '2025-04-22 13:48:12', '2025-04-22 13:48:12'),
	(12, 12, 5, NULL, NULL, 1, 560000.00, '2025-04-23 05:35:34', '2025-04-23 05:35:34'),
	(13, 22, 3, NULL, NULL, 1, 560000.00, '2025-04-23 05:58:04', '2025-04-23 05:58:04'),
	(14, 24, 1, NULL, NULL, 1, 2800000.00, '2025-04-23 06:00:13', '2025-04-23 06:00:13');

-- Dumping structure for table keysport.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.comments: ~0 rows (approximately)

-- Dumping structure for table keysport.contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','responded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.contacts: ~0 rows (approximately)

-- Dumping structure for table keysport.danh_gia
CREATE TABLE IF NOT EXISTS `danh_gia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_SP` bigint unsigned NOT NULL,
  `ID_KH` bigint unsigned NOT NULL,
  `So_sao` double(8,2) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `danh_gia_id_sp_foreign` (`ID_SP`),
  KEY `danh_gia_id_kh_foreign` (`ID_KH`),
  CONSTRAINT `danh_gia_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.danh_gia: ~7 rows (approximately)
INSERT INTO `danh_gia` (`id`, `ID_SP`, `ID_KH`, `So_sao`, `created_at`, `updated_at`) VALUES
	(8, 1, 1, 4.50, '2025-01-10 01:30:00', '2025-01-10 01:30:00'),
	(9, 2, 2, 3.75, '2025-01-12 07:20:00', '2025-01-12 07:20:00'),
	(10, 3, 3, 5.00, '2025-02-01 02:15:00', '2025-02-01 02:15:00'),
	(11, 4, 1, 4.00, '2025-02-15 09:45:00', '2025-02-15 09:45:00'),
	(12, 5, 4, 2.50, '2025-03-05 03:00:00', '2025-03-05 03:00:00'),
	(13, 33, 2, 4.25, '2025-03-20 05:30:00', '2025-03-20 05:30:00'),
	(14, 43, 3, 3.50, '2025-04-01 04:10:00', '2025-04-01 04:10:00');

-- Dumping structure for table keysport.danh_gia_san
CREATE TABLE IF NOT EXISTS `danh_gia_san` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_KH` bigint unsigned NOT NULL,
  `ID_San` bigint unsigned NOT NULL,
  `So_sao` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `danh_gia_san_id_kh_foreign` (`ID_KH`),
  KEY `danh_gia_san_id_san_foreign` (`ID_San`),
  CONSTRAINT `danh_gia_san_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_san_id_san_foreign` FOREIGN KEY (`ID_San`) REFERENCES `san` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.danh_gia_san: ~6 rows (approximately)
INSERT INTO `danh_gia_san` (`id`, `ID_KH`, `ID_San`, `So_sao`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 5, '2025-01-05 02:00:00', '2025-01-05 02:00:00'),
	(2, 2, 2, 4, '2025-01-20 08:30:00', '2025-01-20 08:30:00'),
	(3, 3, 9, 3, '2025-02-10 03:45:00', '2025-02-10 03:45:00'),
	(4, 4, 16, 5, '2025-02-25 06:20:00', '2025-02-25 06:20:00'),
	(5, 1, 19, 2, '2025-03-15 10:00:00', '2025-03-15 10:00:00'),
	(6, 2, 24, 4, '2025-04-10 01:15:00', '2025-04-10 01:15:00');

-- Dumping structure for table keysport.danh_muc
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `id` bigint unsigned NOT NULL,
  `Ten_danh_muc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Trang_Thai` bigint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.danh_muc: ~7 rows (approximately)
INSERT INTO `danh_muc` (`id`, `Ten_danh_muc`, `Mo_ta`, `Trang_Thai`, `created_at`, `updated_at`) VALUES
	(1, 'Thể thao', 'Sản phẩm thể thao', 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(2, 'Giày', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20'),
	(3, 'Áo thun ', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20'),
	(4, 'Áo Polo', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20'),
	(5, 'Quần', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20'),
	(6, 'Dụng cụ thể thao', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20'),
	(7, 'Phụ kiện khác', NULL, 1, '2025-04-20 14:28:20', '2025-04-20 14:28:20');

-- Dumping structure for table keysport.datnbook
CREATE TABLE IF NOT EXISTS `datnbook` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.datnbook: ~0 rows (approximately)

-- Dumping structure for table keysport.don_hang
CREATE TABLE IF NOT EXISTS `don_hang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ngay_mua` date NOT NULL,
  `Tong_tien` decimal(10,2) NOT NULL,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `phuong_thuc_thanh_toan` int NOT NULL DEFAULT '1' COMMENT '1: COD, 2: Chuyển khoản ngân hàng',
  `trang_thai_thanh_toan` int NOT NULL DEFAULT '1' COMMENT '1: Chưa thanh toán, 2: Đang xử lý, 3: Đã thanh toán, 4: Đã hủy',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú đơn hàng',
  `ho_ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ tên người nhận',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email người nhận',
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại người nhận',
  `dia_chi` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa chỉ giao hàng',
  `thanh_pho` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thành phố/Tỉnh',
  `phuong_xa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phường/Xã',
  `ID_KH` bigint unsigned NOT NULL,
  `Ma_don_hang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ma don hang dung de theo doi',
  `id_san_pham` bigint unsigned DEFAULT NULL,
  `ten_san_pham` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ID_Khuyenmai` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `don_hang_id_kh_foreign` (`ID_KH`),
  KEY `don_hang_id_khuyenmai_foreign` (`ID_Khuyenmai`),
  KEY `idx_phuong_thuc_thanh_toan` (`phuong_thuc_thanh_toan`),
  KEY `idx_trang_thai_thanh_toan` (`trang_thai_thanh_toan`),
  KEY `idx_so_dien_thoai` (`so_dien_thoai`),
  KEY `idx_don_hang_ma_don_hang` (`Ma_don_hang`),
  CONSTRAINT `don_hang_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `don_hang_id_khuyenmai_foreign` FOREIGN KEY (`ID_Khuyenmai`) REFERENCES `khuyen_mai` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.don_hang: ~1 rows (approximately)
INSERT INTO `don_hang` (`id`, `Ngay_mua`, `Tong_tien`, `Trang_thai`, `phuong_thuc_thanh_toan`, `trang_thai_thanh_toan`, `ghi_chu`, `ho_ten`, `email`, `so_dien_thoai`, `dia_chi`, `thanh_pho`, `phuong_xa`, `ID_KH`, `Ma_don_hang`, `id_san_pham`, `ten_san_pham`, `ID_Khuyenmai`, `created_at`, `updated_at`) VALUES
	(5, '2025-04-23', 3360000.00, 1, 1, 0, NULL, 'Nguyen van a', 'vuong20031590@gmail.com', '0123457689', '123, abc HCM', NULL, NULL, 22, 'DH34SQUNpg', NULL, NULL, NULL, '2025-04-23 09:38:12', '2025-04-23 09:38:12');

-- Dumping structure for table keysport.don_hang_chi_tiet
CREATE TABLE IF NOT EXISTS `don_hang_chi_tiet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DH` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ID_SP` int(11) DEFAULT NULL,
  `So_luong` int(11) DEFAULT NULL,
  `Gia` float DEFAULT NULL,
  `Thanh_tien` float DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `don_gia` float DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ID_DH` (`ID_DH`),
  KEY `user_id` (`user_id`),
  KEY `ID_SP` (`ID_SP`),
  KEY `mau_size_fk` (`color_id`,`size_id`),
  CONSTRAINT `don_hang_chi_tiet_ibfk_1` FOREIGN KEY (`ID_DH`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `don_hang_chi_tiet_ibfk_2` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`),
  CONSTRAINT `don_hang_chi_tiet_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.don_hang_chi_tiet: ~2 rows (approximately)
INSERT INTO `don_hang_chi_tiet` (`id`, `ID_DH`, `user_id`, `ID_SP`, `So_luong`, `Gia`, `Thanh_tien`, `color_id`, `size_id`, `don_gia`, `hinh_anh`, `created_at`, `updated_at`) VALUES
	(1, 5, 1, NULL, NULL, 1, 2800000.00, 2800000.00, NULL, NULL, 2800000.00, NULL, NULL, NULL),
	(2, 5, 5, NULL, NULL, 1, 560000.00, 560000.00, NULL, NULL, 560000.00, NULL, NULL, NULL);

-- Dumping structure for table keysport.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table keysport.hinh_anh
CREATE TABLE IF NOT EXISTS `hinh_anh` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_SP` bigint unsigned NOT NULL,
  `Duong_dan_hinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hinh_anh_id_sp_foreign` (`ID_SP`),
  CONSTRAINT `hinh_anh_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.hinh_anh: ~0 rows (approximately)

-- Dumping structure for table keysport.khach_hang
CREATE TABLE IF NOT EXISTS `khach_hang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_dien_thoai` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vai_tro` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `khach_hang_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.khach_hang: ~4 rows (approximately)
INSERT INTO `khach_hang` (`id`, `ho_ten`, `email`, `password`, `so_dien_thoai`, `dia_chi`, `vai_tro`, `created_at`, `updated_at`) VALUES
	(1, 'Nguyễn Văn A', 'nguyenvana@example.com', '123', '0901234567', 'Quận 1, TP HCM', 0, '2025-04-20 14:29:26', '2025-04-20 14:29:26'),
	(2, 'Trần Thị B', 'tranthib@example.com', '123', '0912345678', 'Quận 2, TP HCM', 0, '2025-04-20 14:29:26', '2025-04-20 14:29:26'),
	(3, 'Lê Văn C', 'levanc@example.com', '123', '0923456789', 'Quận 3, TP HCM', 0, '2025-04-20 14:29:26', '2025-04-20 14:29:26'),
	(4, 'Admin', 'admin@example.com', '123', '0987654321', 'Quận 4, TP HCM', 1, '2025-04-20 14:29:26', '2025-04-20 14:29:26');

-- Dumping structure for table keysport.khung_gio
CREATE TABLE IF NOT EXISTS `khung_gio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Gio_bat_dau` time NOT NULL,
  `Gio_ket_thuc` time NOT NULL,
  `Gia_thue` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.khung_gio: ~6 rows (approximately)
INSERT INTO `khung_gio` (`id`, `Gio_bat_dau`, `Gio_ket_thuc`, `Gia_thue`, `created_at`, `updated_at`) VALUES
	(1, '07:00:00', '08:30:00', 150000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00'),
	(2, '08:30:00', '10:00:00', 150000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00'),
	(3, '10:00:00', '11:30:00', 200000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00'),
	(4, '17:00:00', '18:30:00', 250000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00'),
	(5, '18:30:00', '20:00:00', 300000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00'),
	(6, '20:00:00', '21:30:00', 300000.00, '2025-04-20 07:30:00', '2025-04-20 07:30:00');

-- Dumping structure for table keysport.khuyen_mai
CREATE TABLE IF NOT EXISTS `khuyen_mai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ma_KM` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ten_khuyen_mai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ngay_bat_dau` date NOT NULL,
  `Ngay_ket_thuc` date NOT NULL,
  `Loai_khuyen_mai` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Dieu_kien_ap_dung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `So_luong` int unsigned NOT NULL,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `khuyen_mai_ma_km_unique` (`Ma_KM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.khuyen_mai: ~0 rows (approximately)

-- Dumping structure for table keysport.loai_bai_viet
CREATE TABLE IF NOT EXISTS `loai_bai_viet` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ten_loai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.loai_bai_viet: ~0 rows (approximately)

-- Dumping structure for table keysport.loai_san
CREATE TABLE IF NOT EXISTS `loai_san` (
  `id` bigint unsigned NOT NULL,
  `Ten_loai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `Mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.loai_san: ~8 rows (approximately)
INSERT INTO `loai_san` (`id`, `Ten_loai`, `Trang_thai`, `Mo_ta`, `created_at`, `updated_at`) VALUES
	(1, 'Sân Bóng Đá', 1, 'Sân dành cho môn bóng đá', '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(2, 'Sân Tennis', 1, 'Sân dành cho môn tennis', '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(3, 'Sân Golf', 1, 'Sân dành cho môn golf', '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(4, 'Sân Cầu Lông', 1, 'Sân dành cho môn cầu lông', '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(5, 'Sân Bóng Bàn', 1, 'Sân dành cho môn bóng bàn', '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(6, 'Sân trong nhà', 1, 'Sân thể thao trong nhà là công trình được thiết kế khép kín, với mái che và hệ thống tường bao nhằm đảm bảo hoạt động thể thao diễn ra ổn định, không bị ảnh hưởng bởi điều kiện thời tiết. Không gian bên trong được bố trí linh hoạt để phục vụ nhiều bộ môn. ', '2025-04-20 14:29:18', '2025-04-20 14:29:18'),
	(7, 'Sân ngoài trời', 1, 'Sân thể thao ngoài trời là khu vực không gian mở được thiết kế và xây dựng để phục vụ các hoạt động thể dục thể thao. Sân thường được bố trí ở vị trí thoáng đãng, có hệ thống mặt sân được xử lý kỹ lưỡng bằng các vật liệu chuyên dụng như bê tông, thảm cỏ nhân tạo, cao su tổng hợp hoặc sơn phủ epoxy chống trượt, phù hợp với từng môn thể thao cụ thể.', '2025-04-20 14:29:18', '2025-04-20 14:29:18'),
	(10, 'Sân Pickleball', 1, 'Sân dành cho môn pickleball', '2025-04-30 17:00:00', '2025-04-30 17:00:00');

-- Dumping structure for table keysport.mau
CREATE TABLE IF NOT EXISTS `mau` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ten_mau` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.mau: ~0 rows (approximately)

-- Dumping structure for table keysport.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `membership_date` date NOT NULL,
  `membership_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.members: ~0 rows (approximately)

-- Dumping structure for table keysport.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.migrations: ~36 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_03_22_133342_create_khach_hang_table', 1),
	(6, '2025_03_22_133448_create_thuong_hieu_table', 1),
	(7, '2025_03_22_133500_create_danh_muc_table', 1),
	(8, '2025_03_22_133512_create_san_pham_table', 1),
	(9, '2025_03_22_133521_create_danh_gia_table', 1),
	(10, '2025_03_22_133532_create_hinh_anh_table', 1),
	(11, '2025_03_22_133543_create_yeu_thich_table', 1),
	(12, '2025_03_22_133552_create_binh_luan_table', 1),
	(13, '2025_03_22_133600_create_mau_table', 1),
	(14, '2025_03_22_133608_create_size_table', 1),
	(15, '2025_03_22_133620_create_san_pham_mau_size_table', 1),
	(16, '2025_03_22_133633_create_loai_bai_viet_table', 1),
	(17, '2025_03_22_133644_create_bai_viet_table', 1),
	(18, '2025_03_22_133710_create_khung_gio_table', 1),
	(19, '2025_03_22_133720_create_loai_san_table', 1),
	(20, '2025_03_22_133728_create_san_table', 1),
	(21, '2025_03_22_133740_create_binh_luan_san_table', 1),
	(22, '2025_03_22_133750_create_danh_gia_san_table', 1),
	(23, '2025_03_22_133801_create_booking_table', 1),
	(24, '2025_03_22_133811_create_khuyen_mai_table', 1),
	(25, '2025_03_22_133820_create_don_hang_table', 1),
	(26, '2025_03_22_133906_create_don_hang_chi_tiet_table', 1),
	(27, '2025_04_03_102501_datnbook', 1),
	(28, '2025_04_19_044415_add_role_to_users_table', 2),
	(29, '2025_04_19_044427_create_posts_table', 2),
	(30, '2025_04_19_044443_create_comments_table', 2),
	(31, '2025_04_19_044451_create_bookings_table', 2),
	(32, '2025_04_19_044455_create_orders_table', 2),
	(33, '2025_04_19_044458_create_order_items_table', 2),
	(34, '2025_04_19_044501_create_ratings_table', 2),
	(35, '2025_04_19_044510_create_contacts_table', 2),
	(36, '2025_04_19_054014_add_otp_columns_to_users_table', 2),
	(37, '2025_04_19_191229_add_status_and_otp_columns_to_users_table', 3),
	(38, '2025_06_20_123456_update_booking_table_for_payment', 4),
	(39, '2023_06_05_000000_add_product_info_to_orders', 5);

-- Dumping structure for table keysport.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.orders: ~0 rows (approximately)

-- Dumping structure for table keysport.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.order_items: ~0 rows (approximately)

-- Dumping structure for table keysport.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table keysport.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.personal_access_tokens: ~6 rows (approximately)
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(23, 'App\\Models\\User', 12, 'auth_token', '6967c8858b8e1842198511c0074673cc77105d1da029e2089129ebe58804aef6', '["*"]', NULL, NULL, '2025-04-19 08:52:49', '2025-04-19 08:52:49'),
	(28, 'App\\Models\\User', 21, 'auth_token', '521595daab15035def79f2cfec0331e98cc350c7fff1dd22ebf70ce2ce8ac609', '["*"]', NULL, NULL, '2025-04-19 12:52:36', '2025-04-19 12:52:36'),
	(32, 'App\\Models\\User', 21, 'auth_token', 'a0e989bffdb9f66beca4c90da3413983f6db2891c3642d4298e01c85e9abc24e', '["*"]', '2025-04-19 23:48:57', NULL, '2025-04-19 23:09:54', '2025-04-19 23:48:57'),
	(35, 'App\\Models\\User', 22, 'auth_token', 'efe68244048acf094de89a7acbc8f416056f90335d43fdd7152c78e528177c25', '["*"]', NULL, NULL, '2025-04-20 21:08:27', '2025-04-20 21:08:27'),
	(38, 'App\\Models\\User', 18, 'auth_token', 'a5e339562f46326a558665ba6b4829850520bced188b9e9d9cc5822c2aab1237', '["*"]', '2025-04-21 03:52:15', NULL, '2025-04-20 22:05:00', '2025-04-21 03:52:15'),
	(41, 'App\\Models\\User', 22, 'auth_token', 'd079ffa5ae3d598280358634f1bd714a2077f152dbe8202e76bcaa9e7ce21b96', '["*"]', '2025-04-23 09:38:30', NULL, '2025-04-23 08:24:03', '2025-04-23 09:38:30');

-- Dumping structure for table keysport.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.posts: ~0 rows (approximately)

-- Dumping structure for table keysport.ratings
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_SP` bigint unsigned NOT NULL,
  `ID_KH` bigint unsigned NOT NULL,
  `So_sao` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ratings_id_sp_foreign` (`ID_SP`),
  KEY `ratings_id_kh_foreign` (`ID_KH`),
  CONSTRAINT `ratings_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.ratings: ~6 rows (approximately)
INSERT INTO `ratings` (`id`, `ID_SP`, `ID_KH`, `So_sao`, `created_at`, `updated_at`) VALUES
	(1, 34, 1, 4, '2025-01-15 03:00:00', '2025-01-15 03:00:00'),
	(2, 35, 2, 5, '2025-02-05 05:30:00', '2025-02-05 05:30:00'),
	(3, 45, 3, 3, '2025-02-20 08:45:00', '2025-02-20 08:45:00'),
	(4, 46, 4, 5, '2025-03-10 02:20:00', '2025-03-10 02:20:00'),
	(5, 47, 1, 2, '2025-03-25 07:00:00', '2025-03-25 07:00:00'),
	(6, 48, 2, 4, '2025-04-05 04:30:00', '2025-04-05 04:30:00');

-- Dumping structure for table keysport.san
CREATE TABLE IF NOT EXISTS `san` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ten_san` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `Mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Hinh_anh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hot` int NOT NULL DEFAULT '0',
  `view` int NOT NULL DEFAULT '0',
  `bestseller` int NOT NULL DEFAULT '0',
  `Gia` int NOT NULL DEFAULT '0',
  `So_luong` int unsigned NOT NULL DEFAULT '1',
  `Thoi_gian_hoat_dong` time DEFAULT NULL,
  `ID_Loai` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `san_id_loai_foreign` (`ID_Loai`),
  CONSTRAINT `san_id_loai_foreign` FOREIGN KEY (`ID_Loai`) REFERENCES `loai_san` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.san: ~31 rows (approximately)
INSERT INTO `san` (`id`, `Ten_san`, `Trang_thai`, `Mo_ta`, `Dia_chi`, `Hinh_anh`, `hot`, `view`, `bestseller`, `Gia`, `So_luong`, `Thoi_gian_hoat_dong`, `ID_Loai`, `created_at`, `updated_at`) VALUES
	(1, 'Sân bong Cửu Long', 1, 'Sân bóng đá chất lượng cao, phù hợp cho mọi lứa tuổi', 'Đường Trần Thị Thơm, Ấp Bình Phong, Tân Mỹ Chánh, Mỹ Tho, Tiền Giang', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 1, 0, 5, 360000, 3, '20:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(2, 'Dan Sport Center (Bóng Đá)', 1, 'Sân bóng đá tiêu chuẩn, phục vụ tốt, đầy đủ tiện nghi', '258 Hùng Vương, Đông Lương, TP. Đông Hà, Quảng Trị, Việt Nam', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 3, 250000, 2, '22:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(3, '532 sport', 1, 'Sân bóng đá cỏ nhân tạo chất lượng cao', '6 Đường Khánh An 7, phường Hòa Khánh Nam, Quận Liên Chiểu, TP. Đà Nẵng, Việt Nam', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 4, 250000, 4, '23:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(4, 'Đức Anh', 1, 'Sân bóng đá với hệ thống đèn chiếu sáng hiện đại', '165 Đường Cầu Giấy, Quan Hoa, Cầu Giấy, Hà Nội, Việt Nam', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 2, '22:30:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(5, 'Sân bóng Mai Ngân', 1, 'Sân bóng đá mini phù hợp cho các trận giao hữu', '22 Duy tân, Kinh Môn, Hải Dương', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 2, 200000, 2, '21:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(6, 'Sân Bóng Việt Hùng Đông Hương', 1, 'Sân bóng đá cỏ nhân tạo chất lượng cao, mặt sân êm, an toàn', '26 Nguyễn Duy Hiệu, Bào Ngoại, Đông Hương, Thanh Hóa, Việt Nam', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 1, 0, 8, 420000, 3, '23:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(7, 'Sân Bóng Đá Nguyên Đức', 1, 'Sân bóng đá với hệ thống đèn chiếu sáng tốt, phù hợp thi đấu buổi tối', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 2, '22:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(8, 'Demo Sân bóng đá Cầu Giấy', 1, 'Sân bóng đá mini tại khu vực Cầu Giấy', '165 Đường Cầu Giấy, Quan Hoa, Cầu Giấy, Hà Nội, Việt Nam', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 1, '22:30:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(9, 'Gò Mây Arena', 1, 'Sân bóng đá hiện đại với nhiều tiện ích', '330/8/19A Quốc lộ 1A, Bình Hưng Hòa B, Bình Tân, Hồ Chí Minh, Việt Nam', 'https://manager.datsan247.com/uploads/20241001112317-gm%20pst-8.png', 1, 0, 10, 420000, 5, '23:30:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(10, 'Sân bóng Vũng Đục', 1, 'Sân bóng đá cỏ nhân tạo chất lượng', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 2, '21:30:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(11, 'Sân bóng Tấn Tài', 1, 'Sân bóng đá mini với dịch vụ tốt', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 1, 200000, 2, '22:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(12, 'Kimpossible', 1, 'Sân bóng đá hiện đại, thích hợp cho các giải đấu phong trào', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 1, '23:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(13, 'Sân gas 10.2', 1, 'Sân bóng đá cỏ nhân tạo chất lượng', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 1, '22:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(14, 'Sân bóng đá Phủi Thuận Giao', 1, 'Sân bóng đá phủi chất lượng cao', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 2, '23:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(15, 'Sân Bóng Từ Sơn Demo', 1, 'Sân bóng đá cỏ nhân tạo tại Từ Sơn', 'Địa chỉ chưa cập nhật', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 0, 180000, 1, '22:00:00', 1, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(16, 'Tennis Center Cầu Giấy', 1, 'Sân tennis tiêu chuẩn quốc tế, mặt sân cao su tổng hợp', '50 Duy Tân, Cầu Giấy, Hà Nội', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 1, 0, 6, 300000, 4, '22:00:00', 2, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(17, 'Tennis Club HCM', 1, 'Sân tennis cao cấp với dịch vụ đẳng cấp', 'Quận 7, TP Hồ Chí Minh', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 3, 200000, 2, '21:00:00', 2, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(18, 'Tennis Pro Đà Nẵng', 1, 'Sân tennis chuyên nghiệp tại Đà Nẵng', 'Sơn Trà, Đà Nẵng', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 2, 180000, 2, '20:30:00', 2, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(19, 'Cầu Lông Sports Hà Nội', 1, 'Sân cầu lông chất lượng cao, ánh sáng tốt', 'Đống Đa, Hà Nội', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 1, 0, 8, 180000, 6, '22:30:00', 4, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(20, 'CLB Cầu Lông Thăng Long', 1, 'Sân cầu lông tiêu chuẩn thi đấu chuyên nghiệp', 'Long Biên, Hà Nội', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 5, 150000, 4, '23:00:00', 4, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(21, 'Cầu Lông Saigon', 1, 'Sân cầu lông chuyên nghiệp, mặt sân êm, đèn chiếu sáng tốt', 'Quận 1, TP Hồ Chí Minh', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 4, 120000, 5, '22:00:00', 4, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(22, 'Badminton Arena Đà Nẵng', 1, 'Sân cầu lông đạt tiêu chuẩn thi đấu', 'Hải Châu, Đà Nẵng', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 3, 120000, 3, '21:30:00', 5, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(23, 'Cầu Lông Pro Hải Phòng', 1, 'Sân cầu lông với dịch vụ chuyên nghiệp', 'Ngô Quyền, Hải Phòng', 'https://manager.datsan247.com/assets/images/banner-client-placeholder.jpg', 0, 0, 2, 100000, 3, '22:00:00', 4, '2025-04-30 17:00:00', '2025-04-30 17:00:00'),
	(24, 'Sân cầu lông Nhật Nam', 1, 'Sân cầu lông tiêu chuẩn, có máy lạnh', '143/12 Phan Huy Ích, Phường 15, Quận Gò vấp, Tp.HCM', 's1-sancaulong1.png', 1, 182, 10, 420000, 5, NULL, 4, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(25, 'Sân tennis Khang An ', 1, 'Sân tennis trong nhà, mặt sân cứng', '18A Phan Văn Trị, Phường 10 , Quận Gò Vấp , Tp Hồ Chí Minh.', 's1-santennis2.png', 0, 83, 0, 180000, 1, NULL, 2, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(26, 'Sân cầu lông Bảo Hà', 1, 'Sân cầu lông trong nhà,  ánh sáng tốt', '1d Đ. Nguyễn Văn Lượng, Phường 6, Gò Vấp, TP HCM', 's1-sancaulong2.png', 0, 0, 0, 180000, 1, NULL, 4, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(27, 'Sân bóng đá AN PHÚ ARENA', 1, 'Sân bóng đá cỏ nhân tạo, có đèn chiếu sáng', '306 Võ Văn Hát, Long Trường, Quận 9, TP HCM', 's1-sanbongda1.png', 0, 9, 0, 180000, 1, NULL, 1, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(28, 'Sân tennis Gò Vấp Bệnh Viện 175', 1, 'Sân tennis ngoài trời, mặt sân cứng', '786B Nguyễn Kiệm, P.3, Quận Gò Vấp, TP HCM', 's2-santennis2.png', 0, 82, 0, 150000, 1, NULL, 2, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(29, 'Sân bóng đá cỏ nhân tạo VOV', 1, 'Sân bóng đá ngoài trời, sân cỏ nhân tạo', 'Hẻm 38/89 Đông Hưng Thuận 11, P. Đông Hưng Thuận, Q.12, TP HCM', 's2-sanbongda2.png', 0, 15, 0, 150000, 3, NULL, 1, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(30, 'Sân golf Tân Sơn Nhất', 1, 'Tan Son Nhat Golf Course là một trong những sân golf ở thành phố Hồ Chí Minh được nhiều golfer biết đến,\n                    là địa chỉ quen thuộc của golf thủ phía Nam', 'số 6 đường Tân Sơn, P. 12, Q. Gò Vấp, TP HCM', 's2-sangolf1.png', 0, 12, 0, 150000, 4, NULL, 3, '2025-04-20 14:29:38', '2025-04-20 14:29:38'),
	(31, 'Sân golf TPHCM Him Lam Driving Range', 1, 'Sân golf Him Lam driving range trở thành điểm đến lý tưởng của nhiều golfer phía Nam.\n                    Sở hữu khung cảnh đẹp ngay bên bờ sông Sài Gòn', '234 đường Ngô Tất Tố, P. 22, Q. Bình Thạnh, TP HCM', 's2-sangolf2.png', 0, 34, 0, 150000, 2, NULL, 3, '2025-04-20 14:29:38', '2025-04-20 14:29:38');

-- Dumping structure for table keysport.san_pham
CREATE TABLE IF NOT EXISTS `san_pham` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ten_san_pham` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Gia` int NOT NULL,
  `Mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Anh_dai_dien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hot` int NOT NULL DEFAULT '0',
  `view` int NOT NULL DEFAULT '0',
  `bestseller` int NOT NULL DEFAULT '0',
  `So_luong` int unsigned NOT NULL DEFAULT '0',
  `Trang_thai` bigint NOT NULL DEFAULT '1',
  `ID_Thuonghieu` bigint unsigned NOT NULL,
  `ID_Danhmuc` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `san_pham_id_thuonghieu_foreign` (`ID_Thuonghieu`),
  KEY `san_pham_id_danhmuc_foreign` (`ID_Danhmuc`),
  CONSTRAINT `san_pham_id_danhmuc_foreign` FOREIGN KEY (`ID_Danhmuc`) REFERENCES `danh_muc` (`id`) ON DELETE SET NULL,
  CONSTRAINT `san_pham_id_thuonghieu_foreign` FOREIGN KEY (`ID_Thuonghieu`) REFERENCES `thuong_hieu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.san_pham: ~69 rows (approximately)
INSERT INTO `san_pham` (`id`, `Ten_san_pham`, `Gia`, `Mo_ta`, `Anh_dai_dien`, `hot`, `view`, `bestseller`, `So_luong`, `Trang_thai`, `ID_Thuonghieu`, `ID_Danhmuc`, `created_at`, `updated_at`) VALUES
	(1, 'Quần ba môn Nam Compressport Tri Under Control Short - Đen (Black)', 2800000, 'Quần ba môn nam chất lượng cao từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_ac9978c5-f61d-4a37-8d06-ae479255c034_2100x.png', 1, 0, 0, 10, 1, 1, 5, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(2, 'Băng đô thể thao Compressport Thin Headband On/Off - Đen (Black)', 420000, 'Băng đô thể thao từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_d461e971-fb62-4704-88d9-4c4b8aa1b173_2100x.png', 0, 0, 0, 15, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(3, 'Tất chạy bộ Compressport Pro Racing Socks v4.0 Run High - Trắng/Đỏ', 560000, 'Tất chạy bộ cao cấp từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_fe1dae6f-6c99-4760-b21e-0499bfb79c71_2100x.png', 0, 0, 1, 20, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(4, 'Tất chạy bộ Compressport Pro Racing Socks v4.0 Run High - Trắng/Đen', 560000, 'Tất chạy bộ cao cấp từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_1850e8d8-892b-480e-8eeb-9e22e5cdf8d1_2100x.png', 0, 0, 1, 18, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(5, 'Tất chạy bộ Compressport Pro Racing Socks v4.0 - Đen', 560000, 'Tất chạy bộ cao cấp từ Compressport', 'https://keypowersports.vn/cdn/shop/files/10_f30e0ac8-28ad-4656-a544-de8656d6b6ca_2100x.png', 0, 0, 1, 25, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(6, 'Tất chạy bộ Compressport Mid Compression Socks - Đỏ', 990000, 'Tất chạy bộ nén từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_d658dc77-be75-4a09-a044-3aa5383edba2_2100x.png', 0, 0, 0, 12, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(7, 'Bình nước Compressport Cage Cycling Bottle - Đỏ/Trắng', 350000, 'Bình nước dành cho đạp xe từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_1fac2e6d-9d66-4bff-a2f3-4e94dbd6402d_2100x.png', 0, 0, 0, 0, 2, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(8, 'Áo chạy bộ Nữ Compressport Performance Singlet - Trắng/Cam', 1690000, 'Áo ba lỗ chạy bộ nữ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_00686414-e1aa-4437-82ab-e0c820ad8b68_2100x.png', 1, 0, 0, 8, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(9, 'Áo chạy Trail Nữ Compressport Trail Racing Tank - Xanh', 2190000, 'Áo ba lỗ chạy trail nữ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_7e21c3ae-f7eb-4210-acb5-becb2ebe604a_2100x.png', 0, 0, 0, 0, 2, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(10, 'Áo chạy Trail Nữ Compressport Trail Racing Tank - Hồng/Cam', 2190000, 'Áo ba lỗ chạy trail nữ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_082ab91e-3173-49a0-b7ee-37e4372b259f_2100x.png', 0, 0, 0, 5, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(11, 'Áo chạy Trail Nữ Compressport Trail Racing SS Tshirt - Xanh', 2490000, 'Áo tay ngắn chạy trail nữ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_53e797a8-4950-4ca0-879f-f6a7e8280311_2100x.png', 0, 0, 0, 7, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(12, 'Áo chạy Trail Nam Compressport Trail Racing Postural SS Top - Xanh', 2950000, 'Áo tay ngắn chạy trail nam từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_1c909cad-3336-4c1b-9893-6df6bf724b28_2100x.png', 1, 0, 0, 4, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(13, 'Áo chạy Trail Nam Compressport Trail Racing Tank - Đen', 2190000, 'Áo ba lỗ chạy trail nam từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_c2473d9d-a528-4474-851f-827545fb246e_2100x.png', 0, 0, 0, 0, 2, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(14, 'Áo chạy Trail Nam Compressport Trail Racing SS Tshirt - Xanh Lime', 2490000, 'Áo tay ngắn chạy trail nam từ Compressport', 'https://keypowersports.vn/cdn/shop/files/10_416471e6-7759-4628-a3cb-8df5bc957545_2100x.png', 0, 0, 0, 6, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(15, 'Áo chạy Trail Nam Compressport Trail Racing SS Tshirt - Xanh Blues', 2490000, 'Áo tay ngắn chạy trail nam từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_50f860d0-41ee-416c-b634-d697afc46ea4_2100x.png', 0, 0, 0, 8, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(16, 'Áo chạy bộ Nam Compressport Pro Racing SS Tshirt - Đỏ', 2490000, 'Áo tay ngắn chạy bộ nam từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_e51663d0-435d-41e9-8347-3404329e32d4_2100x.png', 1, 0, 0, 10, 1, 1, 3, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(17, 'Khăn đa năng Compressport 3D Thermo UltraLight Headtube - Xanh Nile', 790000, 'Khăn đa năng thể thao từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_b0a9b99e-2897-4707-aaa1-d2167f2ad0d2_2100x.png', 0, 0, 0, 0, 2, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(18, 'Khăn đa năng Compressport 3D Thermo UltraLight Headtube - Xanh Poseidon', 790000, 'Khăn đa năng thể thao từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_5415dce8-4551-4a98-a8c8-e1304f3980e1_150x.png', 0, 0, 0, 0, 2, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(19, 'Quần ba môn Nữ Compressport Tri Under Control Short - Đen', 2800000, 'Quần ba môn nữ chất lượng cao từ Compressport', 'https://keypowersports.vn/cdn/shop/products/pressport-ss20-aw00007b_990-tri-under-control-short-w-black_01-600x600_894a256fc91a4e5098c83c1b9a84abc2_150x.jpg', 0, 0, 0, 7, 1, 1, 5, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(20, 'Mũ Compressport 5 Panel Light Cap Buff - Xanh/Đỏ', 1200000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_a57558f8-b677-4f43-8cfd-f9e65ad34924_150x.png', 0, 0, 0, 9, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(21, 'Mũ Compressport 5 Panel Light Cap Buff - Xanh Indigo/Blues', 1200000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_d78fd70e-4d0d-4eed-886e-0d04cb2af2bb_150x.png', 0, 0, 0, 8, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(22, 'Mũ Compressport 5 Panel Light Cap Buff - Cam/Hồng', 1200000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_d32db942-5246-4a67-a00e-70c5facf101e_150x.png', 0, 0, 0, 6, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(23, 'Mũ Compressport 5 Panel Light Cap 2025 - Trắng/Đen', 1200000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_84370a34-9548-4d7c-af7e-27cf9b729b8e_150x.png', 0, 0, 0, 0, 2, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(24, 'Mũ Compressport 5 Panel Light Cap 2025 - Đen', 1200000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_f8b23ef9-4e1b-48d5-ae17-86d07619f540_150x.png', 0, 0, 0, 10, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(25, 'Mũ chạy bộ Compressport Pro Racing Cap - Đen', 970000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_c72f1c7b-a4b3-462c-af7b-6646b351d4a6_150x.png', 0, 0, 0, 12, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(26, 'Mũ chạy bộ Compressport Pro Racing Cap - Xanh/Đỏ', 1050000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_5523ede4-b211-4404-977e-6e49847e2ce7_150x.png', 0, 0, 0, 8, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(27, 'Mũ chạy bộ Compressport Pro Racing Cap - Trắng', 970000, 'Mũ chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_1789d43d-6859-4e33-9455-47abfdbd5272_150x.png', 0, 0, 0, 10, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(28, 'Mũ chạy bộ Compressport Pro Racing Visor - Đen', 850000, 'Mũ lưỡi trai chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_3de915f7-adbd-4655-ba05-a7214f515e83_150x.png', 0, 0, 0, 15, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(29, 'Mũ chạy bộ Compressport Pro Racing Visor - Xanh Indigo', 850000, 'Mũ lưỡi trai chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_7172dcb3-c291-4a48-8ce5-5f787556fa69_150x.png', 0, 0, 0, 8, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(30, 'Mũ chạy bộ Compressport Pro Racing Visor - Đỏ', 850000, 'Mũ lưỡi trai chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/4_ede9b29c-28bd-48bf-8e6e-59cb15c23360_150x.png', 0, 0, 0, 10, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(31, 'Mũ chạy bộ Compressport Pro Racing Visor - Trắng', 850000, 'Mũ lưỡi trai chạy bộ từ Compressport', 'https://keypowersports.vn/cdn/shop/files/3_298964ca-fb79-4fb3-9e19-7e0f9c3df48b_150x.png', 0, 0, 0, 12, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(32, 'Mũ chạy bộ Compressport Trucker Cap 6P - Xanh Indigo', 960000, 'Mũ lưỡi trai kiểu trucker từ Compressport', 'https://keypowersports.vn/cdn/shop/files/17_ea8aed55-f18b-4191-aa83-0cf2cb0de04e_150x.png', 0, 0, 0, 8, 1, 1, 1, '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(33, 'Nike Mercurial Vapor 15 Pro TF - DJ5605-601 - Hồng/Đen', 2350000, '+ Form giày: Hợp với anh em chân hơi bè hoặc thon. \n\n                            + Lưỡi gà  co giãn và may liền thân nên giày đi vào ôm sát bàn chân, cảm giác bóng rất thật.\n\n                            + Đế có bộ đệm nên lúc chạy khá êm, ngoài ra độ đàn hồi tốt nên cảm giác lúc bật nhảy hoặc tăng tốc cũng được hỗ trợ phần nào.Tuy nhiên do được chèn bộ đệm nên đế sẽ cao hơn 1 chút xíu so với đế thông thường ,nhưng anh em yên tâm, chỉ cần khoảng 1,2 trận là  sẽ quen đế. Khi đã quen rồi thì đá rất thích.\n\n                            + Các cầu thủ đang đi Nike Mercurial Vapor 15: Vinicius Junior, Robert Lewandowski, Bruno Fernandes…\n                            + Nike Vapor 15 pro này nằm trong bộ sưu tập: Nike Mad Brilliance', 'd1-giay1.png', 0, 120, 0, 20, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(34, 'Nike Zoom Mercurial Superfly 9 Academy MDS TF - Xanh lá FJ7199-300', 1690000, '+ Form hợp chân hơi bè hoặc thon\n                        + Hỗ trợ sút và rê dắt.', 'd1-giay2.png', 1, 40, 5, 10, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(35, 'Áo Thun Nam Nike As M Nsw Club Tee AR4999-013 Màu Đen Size M', 1150000, 'là mẫu áo thun thời trang dành cho nam đến từ thương hiệu nổi tiếng Nike của Mỹ. Mẫu áo được thiết kế năng động trẻ trung,\n                 kết hợp chất vải mềm mịn thấm hút tốt, giúp người mặc thấy thoải mái trong mọi hoạt động hàng ngày.', 'd2-ao1.png', 0, 0, 0, 20, 1, 2, 3, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(36, 'Áo cầu lông Yonex TPM2898 - Rose Smoke chính hãng ', 169000, 'Áo cầu lông Yonex TPM2898 - Rose Smoke là sản phẩm chính hãng của thương hiệu Yonex, được thiết kế dành riêng cho người chơi cầu lông.\n                 Áo có màu hồng nhạt (Rose Smoke) trang nhã, phù hợp cho cả nam và nữ.', 'd3-ao2.png', 0, 60, 9, 60, 1, 6, 4, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(37, 'Vợt Cầu Lông Li-Ning Turbo Charging Marshal Black (4U) AYPU077-4', 2012727, 'Vợt cầu lông Turbo Charging Marshal tăng áp được thiết kế dành cho những người chơi đang tìm kiếm hiệu suất vượt trội và sự thoải mái. Cây vợt này có kết cấu nhẹ giúp tăng cường sự nhanh nhẹn và tốc độ trên sân. Công nghệ hấp thụ sốc HDF cải tiến, được tích hợp vào khung, giảm đáng kể tình trạng căng cổ tay và cánh tay, ngăn ngừa chấn thương mà không ảnh hưởng đến những cú đập mạnh mẽ của bạn. Thiết kế đèn pha, kết hợp với hình dạng đầu hình thang, đảm bảo hiệu quả khí động học vượt trội, cho phép những cú đánh nhanh và chính xác. Trục linh hoạt giúp tăng cường khả năng kiểm soát và tính linh hoạt, làm cho Turbocharge Marshal trở thành lựa chọn lý tưởng cho cả người chơi tấn công và phòng thủ.', 'd5-vot1.png', 0, 30, 4, 60, 1, 5, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(38, 'Băng cổ tay 2.5', 149000, 'Mã sản phẩm: vỉ: 285050               \n                            Màu sắc: Cam, đen, xanh nước biển, xanh navi, hồng, đỏ.\n                            Xuất xứ: Đài Loan.\n                            Đóng gói: 2chiếc/ túi.\n                            Thành phần: 80% cotton,5% Nylon, 5%  Cao su', 'd6-bang1.png', 0, 0, 0, 20, 1, 9, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(39, 'Giày Nike Air Max Nuaxis Nam - Trắng Xanh', 2190000, ' Giày Nike Air Max Nuaxis là đôi giày lý tưởng cho cuộc sống hàng ngày, mang lại cảm giác thoải mái và phong cách hiện đại. Thiết kế lấy cảm hứng từ Air Max 270,\n                             kết hợp cùng bộ đệm Air Max tinh tế, tạo nên một đôi giày vừa êm ái vừa nổi bật, sẵn sàng cùng bạn bước đi mọi lúc', 'd1-giay3.png', 0, 0, 0, 20, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(40, 'Giày Nike Run Swift 3 Nam - Xanh Xám', 1990000, 'Giày Nike Run Swift 3 là mẫu giày được thiết kế cực kỳ đẹp và tinh tế với đặc điểm rất thoáng khí,\n                     êm và rất nhẹ. Đây là mẫu giày có thể sử dụng trong mọi hoạt động hàng ngày.', 'd1-giay4.png', 0, 0, 0, 10, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(41, 'Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300', 5790000, ' Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300 là mẫu giày phổ thông dành cho sân cỏ tự nhiên 11 người. \n                    Phiên bản Phantom GX mang đến sự tươi mới với màu xanh bạc hà (Mint) làm chủ đạo, kết hợp cùng logo màu hồng nổi bật (Atomic Red) và các chi tiết màu xanh navy đậm gần như đen (Atomic Noir), tạo nên sự hài hòa và tinh tế.', 'd1-giay5.png', 0, 0, 0, 14, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(42, 'Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300', 5790000, ' Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300 là mẫu giày phổ thông dành cho sân cỏ tự nhiên 11 người. \n                    Phiên bản Phantom GX mang đến sự tươi mới với màu xanh bạc hà (Mint) làm chủ đạo, kết hợp cùng logo màu hồng nổi bật (Atomic Red) và các chi tiết màu xanh navy đậm gần như đen (Atomic Noir), tạo nên sự hài hòa và tinh tế.', 'd1-giay6.png', 0, 0, 0, 18, 1, 2, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(43, 'Giày Adidas Supernova Prima Nữ - Tím Xanh Ngọc', 2790000, ' Adidas Supernova Prima luôn sẵn sàng đồng hành cùng bạn. Với thiết kế tối ưu cho sự thoải mái và ổn định, \n                    đôi giày này mang đến cảm giác êm ái và tự tin trên từng bước chạy.', 'd1-giay7.png', 1, 120, 12, 18, 1, 3, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(44, 'Giày Puma Anzarun 2.0 Open Road Nam - Trắng Cam', 1690000, ' Giày Puma Anzarun 2.0 Open Road mẫu giày sneaker có thiết kế rất đẹp cùng với những công nghệ cao cấp của Puma.\n                     Đây chính là mẫu giày đa năng tuyệt vời cho mọi hoạt động hàng ngày.', 'd1-giay8.png', 0, 0, 0, 20, 1, 4, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(45, 'Giày Cầu Lông Yonex Eclipsion Z3 Wide', 2519000, '  Giày cầu lông Yonex Eclipsion Z3 Wide mang lại tính ổn định và vừa vặn là cốt lõi của dòng sản phẩm ECLIPSION. Dòng sản phẩm này được thiết kế để xử lý ngay cả những động tác chân tiên tiến hoặc phức tạp nhất, \n                    giúp người dùng tự tin trong từng bước và từng bước nhảy.', 'd1-giay9.png', 0, 46, 11, 29, 1, 6, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(46, 'Áo cầu lông nam Kamito Hanabi', 179000, ' Áo cầu lông nam Kamito Hanabi với chất liệu vải cao cấp cùng khả năng thấm hút mồ hôi tốt, sản phẩm là sự lựa chọn tuyệt vời\n                     bất chấp mọi điều kiện thời tiết để bạn sử dụng thoải mái ở bất kỳ hoạt động nào như: chơi thể thao (cầu lông, tennis, chạy bộ,...), dã ngoại, sự kiện ngoài trời,...', 'd2-ao2.png', 1, 100, 12, 50, 1, 8, 3, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(47, 'Áo tennis Wilson WN2002-17-01 nam - Xanh đen biển chính hãng', 580000, ' Áo tennis Wilson WN2002-17-01 nam - Xanh đen biển chính hãng là một trong những mẫu áo tennis chính hãng nổi trội với chất liệu vải thấm hút tốt, mát mẻ, form áo đẹp, \n                    màu sắc bắt mắt và đặc biệt là có giá thành phải chăng đảm bảo sẽ làm các tay vợt cực ưng ý ngay từ lần đầu tiên sử dụng.', 'd2-ao3.png', 1, 150, 40, 80, 1, 7, 4, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(48, 'Áo T-shirt Nam AHSU533-3V', 775000, 'Áo T-shirt Nam AHSU533-3V\n                        Chất liệu: 56% modal 44% polyester\n                        Dòng sản phẩm: Thời Trang/Sportlife\n                        Form dáng: Loose fit\n                        Xuất xứ: Chính hãng ', 'd2-ao4.png', 0, 0, 0, 20, 1, 5, 3, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(49, 'Áo HEAD CLUB 22 Tech Polo Shirt M', 790000, 'Club 22 tech Polo được được thiết kế với công nghệ hút ẩm tiên tiến của HEAD mang lại hiệu quả làm mát và cho phép chất liệu khô nhanh chóng.\n                                Thiết kế cổ bẻ không chỉ mang lại vẻ ngoài nam tính, lịch thiệp mà còn rất trẻ trung và phóng khoáng. \n                                Logo head được thiết kế in đằng trước ngực sang trọng và tinh tế.', 'd3-ao2.png', 0, 0, 0, 20, 1, 9, 4, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(50, 'Áo polo Kamito Master được thiết kế dành riêng cho những ai yêu thích sự đơn giản \n                    nhưng vẫn muốn nổi bật. Với phom dáng thoải mái, chất liệu thoáng mát, chiếc áo này giúp bạn tự do di chuyển, từ tập luyện đến các hoạt động thường ngày.', 390000, ' ', 'd3-ao3.png', 0, 0, 0, 20, 1, 8, 4, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(51, 'Áo Polo Nam APLU119-1V', 932000, 'Áo Polo Nam APLU119-1V\n\n                        Chất liệu vải : Polyester100%\n                        Công nghệ :AT DRY ULTRA\n                        Dòng sản phẩm :Fitness/Luyện tập\n                        Form dáng :Regular Fit\n                        Xuất xứ: Trung Quốc\n                        Form Chọn Size : Châu Á', 'd3-ao4.png', 0, 0, 0, 20, 1, 5, 4, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(52, 'Áo Polo Kamito Artista - Xanh', 399000, 'Áo Polo Kamito Artista mang đến phong cách hiện đại và đầy lịch lãm – lựa chọn lý tưởng cho mọi hoạt động thường ngày, \n                    để bạn tự do thể hiện cá tính và phong cách riêng. ', 'd3-ao5.png', 0, 0, 0, 20, 1, 8, 2, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(53, 'Quần Ngắn Nike Woven Flow Shorts', 360000, '- Lớp vải Poly mang đến độ bền cao cùng với khả năng kháng nước cực tốt cho người mặc.\n                    - Kiểu dáng vừa vặn không quá ôm sát cơ thể, tạo cảm giác thoải mái.\n                    - Thiết kế đơn giản nhưng trẻ trung và tinh tế, là sự lựa chọn hoàn hảo cho những buổi dạo phố và kể cả nhưng buổi tập thể thao. ', 'd4-quan1.png', 0, 0, 0, 20, 1, 1, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(54, 'Quần ngắn Nike Big Swoosh Repeat Shorts', 450000, 'Quần ngắn Nike Big Swoosh Repeat Shorts là sản phẩm thể thao nổi bật với thiết kế logo Swoosh lặp lại,\n                     mang lại phong cách năng động và hiện đại. ', 'd4-quan2.png', 0, 0, 0, 20, 1, 1, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(55, 'Quần Ngắn Nữ Adidas Own The Run Bike - Hồng', 575000, 'Hãy thúc đẩy bản thân chinh phục đường chạy với chiếc quần short adidas siêu nhẹ này. Với công nghệ AEROREADY thấm hút ẩm giúp bạn luôn khô ráo trên từng dặm đường, bạn sẽ tự tin làm chủ về cả cự ly và tốc độ.\n                     Các túi khóa kéo giữ chắc chìa khóa hoặc thanh năng lượng để tiện lấy khi di chuyển. ', 'd4-quan3.png', 0, 0, 0, 20, 1, 3, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(56, 'Quần Ngắn Nữ Adidas Own The Run - Xanh Dương', 600000, 'Tự tin về đích với Quần Ngắn Nữ Adidas Own The Run siêu nhẹ! Công nghệ AEROREADY thấm hút ẩm giúp bạn luôn khô ráo trên từng dặm đường, tự tin làm chủ về cả cự ly và tốc độ.\n                     Các túi khóa kéo giữ chắc chìa khóa hoặc thanh năng lượng để tiện lấy khi di chuyển.  ', 'd4-quan4.png', 0, 0, 0, 20, 1, 3, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(57, 'Quần thể thao puma running 5 inch active woven "brown" 576728 - hàng chính hãng', 350000, ' ', 'd4-quan5.png', 0, 0, 0, 20, 1, 4, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(58, 'Quần thời trang puma drycell pants black 519500-01 - hàng chính hãng', 350000, 'Puma Drycell Pants Black 519500-01 Quần Thời Trang Puma Drycell Pants "Black" 519500-01 là sự kết hợp hoàn hảo giữa phong cách thời trang và tính năng thể thao. Với công nghệ Drycell tiên tiến, \n                    quần giúp thấm hút mồ hôi hiệu quả, giữ cho cơ thể luôn khô ráo và thoải mái. ', 'd4-quan6.png', 0, 0, 0, 20, 1, 4, 5, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(59, 'Vợt tennis Wilson Triad Five', 5750000, 'Vợt tennis Wilson Triad Five (Mã: WR056611U2 ) là dòng vợt Power and Comfort (trợ lực và tạo cảm giác thoải mái), công nghệ Triad tạo cho người đánh có cảm giác thoải mái nhất với mặt vợt trung bình 103" và cảm giác mềm nhẹ nhàng khi đánh.\n                    Vợt dành cho các bạn tìm khung vợt trợ lực vửa phải , mặt vợt đủ nhỏ để có thể kiểm soát bóng + nhiều lực và phải xoáy.\n                    Khung vợt được thiết kế khí động học rất khác biệt + làm tăng lực đánh, nhẹ nhàng và linh hoạt hơn trong và có khả năng chịu lực đánh bóng nặng. ', 'd5-tennis1.png', 0, 0, 0, 20, 1, 7, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(60, 'Vợt tennis Wilson Roland Garros Elite 21', 1200000, 'Vợt tennis Wilson Roland Garros Elite 21 (Mã: WR029610H ) dành cho trẻ em độ tuổi từ 4-6 tuổi. Vợt được thiết kế với các công nghệ mới tạo thành dòng vợt dễ đánh, dễ vung vợt, độ ổn định cao.\n                     Thiết kế màu sắc theo giải GRAND SLAM ROLAND GARROS ', 'd5-tennis2.png', 0, 0, 0, 20, 1, 7, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(61, 'QUẢ BÓNG ĐÁ KAMITO ARTISTA', 1799000, 'Bóng Kamito Artista được lấy cảm hứng từ phong cách chơi bóng kỹ thuật của Danh thủ Nguyễn Hồng Sơn, mang đến trải nghiệm thi đấu tuyệt vời, giúp cầu thủ luôn thăng hoa trong mọi trận đấu. Bóng Kamito Artista, được thiết kế với công nghệ tiên tiến,\n                     đạt tiêu chuẩn FIFA PRO đáp ứng tối ưu cho thi đấu bóng đá chuyên nghiệp trên sân 7, và sân 11 người. ', 'd5-bong1.png', 0, 0, 0, 20, 1, 8, 7, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(62, 'QUẢ BÓNG ĐÁ KAMITO LEGEND', 1199000, 'Quả bóng đá Kamito Legend, được thiết kế với công nghệ tiên tiến, \n                    đáp ứng tiêu chuẩn FIFA PRO dành cho bóng đá chuyên nghiệp sân 11 người. ', 'd5-bong2.png', 0, 0, 0, 20, 1, 8, 7, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(63, 'Wilson Vợt Pickleball Cadence Edgeless 16 PB WR181011U2  ', 5209000, 'Wilson Vợt Pickleball Cadence Edgeless 16 PB WR181011U2\n                    Được thiết kế cho những người chơi cạnh tranh, có khả năng đặt bóng chính xác và mong muốn hỗ trợ thêm sức mạnh cho các cú đánh của mình. Vợt có thiết kế không viền, giúp cải thiện tính khí động học, tăng khả năng linh hoạt cho các cú vô lê gần lưới. Bề mặt sợi carbon thô tạo độ xoáy ,\n                    trong khi lõi tổ ong polypropylene dày 16mm giúp hỗ trợ giảm rung chấn và mang lại cảm giác nhất quán. ', 'd5-pickleball1.png', 0, 0, 0, 20, 1, 7, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(64, 'Wilson Fierce Team Pickleball Paddle Purple Vợt Pickleball WR161011U2', 2089000, 'Vợt dành cho người mới bắt đầu và người chơi thỉnh thoảng đang tìm kiếm cây vợt pickleball đầu tiên của mình, Wilsons Fierce Team giúp bạn vào sân và chơi một cách tự tin trong thời gian ngắn. Hình dạng lai của nó kết hợp những điểm tốt của cả hai loại vợt, mang đến cho bạn khả năng kiểm soát và điểm ngọt lớn hơn của thân vợt rộng và sức mạnh bổ sung của một cây vợt dài. Lõi tổ ong polypropylene làm giảm độ rung và tăng cường cảm giác,\n                     và mặt vợt bằng sợi thủy tinh 13 mm giúp bạn thực hiện những cú đánh thoải mái, nhất quán.', 'd5-pickleball2.png', 0, 0, 0, 20, 1, 7, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(65, 'Vợt cầu lông Yonex Nanoflare Junior', 1419000, 'Vợt cầu lông Yonex Nanoflare Junior được thiết kế cho lối chơi tốc độ, linh hoạt giữa công và thủ với điểm cân bằng ở mức cân bằng. Đũa vợt siêu dẻo mang lại khả năng trợ lực một cách tối ưu, trọng lượng 4U không quá nặng,\n                     thích hợp cho những người mới bắt đầu tập làm quen với bộ môn này hoặc các lông thủ nhí. ', 'd5-vot2.png', 0, 0, 0, 20, 1, 6, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(66, 'Băng Đô, Băng Trán Thể Thao Adidas CF6926', 190000, 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn', 'd6-bangdo1.png', 0, 0, 0, 20, 1, 3, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(67, 'Bộ 3 Đôi Tất Cổ Cao Lót Đệm 3 Sọc', 180000, 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn', 'd6-vo1.png', 0, 0, 0, 20, 1, 3, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(68, 'Nón Thể Thao Unisex NIKE Dri-Fit Club Unstructured Featherlight FB5682-100', 290000, 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn', 'd6-non1.png', 0, 0, 0, 20, 1, 1, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31'),
	(69, 'Túi Xách Tập Luyện Unisex NIKE Nike Heritage DB0490-010', 180000, 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn', 'd6-tuideo1.png', 0, 0, 0, 20, 1, 1, 6, '2025-04-20 14:28:31', '2025-04-20 14:28:31');

-- Dumping structure for table keysport.san_pham_mau_size
CREATE TABLE IF NOT EXISTS `san_pham_mau_size` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_Mau` bigint unsigned NOT NULL,
  `ID_Kichthuoc` bigint unsigned NOT NULL,
  `ID_SP` bigint unsigned NOT NULL,
  `So_luong` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `san_pham_mau_size_id_mau_foreign` (`ID_Mau`),
  KEY `san_pham_mau_size_id_kichthuoc_foreign` (`ID_Kichthuoc`),
  KEY `san_pham_mau_size_id_sp_foreign` (`ID_SP`),
  CONSTRAINT `san_pham_mau_size_id_kichthuoc_foreign` FOREIGN KEY (`ID_Kichthuoc`) REFERENCES `size` (`id`) ON DELETE CASCADE,
  CONSTRAINT `san_pham_mau_size_id_mau_foreign` FOREIGN KEY (`ID_Mau`) REFERENCES `mau` (`id`) ON DELETE CASCADE,
  CONSTRAINT `san_pham_mau_size_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.san_pham_mau_size: ~0 rows (approximately)

-- Dumping structure for table keysport.size
CREATE TABLE IF NOT EXISTS `size` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Ten_size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.size: ~0 rows (approximately)

-- Dumping structure for table keysport.thong_bao
CREATE TABLE IF NOT EXISTS `thong_bao` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tieu_de` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'booking, sanpham, hethong',
  `da_xem` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `thong_bao_user_id_foreign` (`user_id`),
  CONSTRAINT `thong_bao_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.thong_bao: ~0 rows (approximately)

-- Dumping structure for table keysport.thuong_hieu
CREATE TABLE IF NOT EXISTS `thuong_hieu` (
  `id` bigint unsigned NOT NULL,
  `Ten_thuong_hieu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.thuong_hieu: ~9 rows (approximately)
INSERT INTO `thuong_hieu` (`id`, `Ten_thuong_hieu`, `created_at`, `updated_at`) VALUES
	(1, 'COMPRESSPORT', '2025-04-19 20:52:23', '2025-04-19 20:52:23'),
	(2, 'Nike', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(3, 'Adidas', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(4, 'Puma', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(5, 'L-ning', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(6, 'Yonex', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(7, 'Wilson', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(8, 'Kamito', '2025-04-20 14:28:06', '2025-04-20 14:28:06'),
	(9, 'Head', '2025-04-20 14:28:06', '2025-04-20 14:28:06');

-- Dumping structure for table keysport.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','field_owner','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thành phố/Tỉnh',
  `district` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Quận/Huyện',
  `ward` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phường/Xã',
  `shipping_address` text COLLATE utf8mb4_unicode_ci COMMENT 'Địa chỉ giao hàng (nếu khác địa chỉ chính)',
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại giao hàng (nếu khác phone chính)',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `idx_users_phone` (`phone`),
  KEY `idx_users_city` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.users: ~6 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `address`, `city`, `district`, `ward`, `shipping_address`, `shipping_phone`, `avatar`, `email_verified_at`, `status`, `password`, `otp`, `otp_expires_at`, `remember_token`, `created_at`, `updated_at`) VALUES
	(8, 'Admin', 'vuong20032604@gmail.com', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-20 21:54:56', 'active', '$2y$10$4klfCDTjT3KSVHMaPI.KGesAm1m1Nz2UFXrbqDisVj/STxKDGS0iW', NULL, NULL, NULL, '2025-04-19 06:54:14', '2025-04-20 21:54:56'),
	(9, 'Admin', 'admin@keysport.com', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '$2y$12$V7gWktBJENpCLONcYN9HJ.Nkrs5fuXnR2F430xWb4nftm/f2kdddO', NULL, NULL, NULL, '2025-04-19 06:56:52', '2025-04-19 06:56:52'),
	(16, 'Nguyễn', 'khanhtrinh1233@gmail.com', 'user', '02312412321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 08:42:31', 'active', '$2y$12$R2.dse.UPbDbD6syQ/e13OFkI0KnVG1iBumCAjgxkdXJd0feRJaBm', NULL, NULL, NULL, '2025-04-19 08:42:31', '2025-04-19 08:42:31'),
	(17, 'Nguyễn siêu', 'cuonghotran17022004@gmail.com', 'field_owner', '0355999141', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 08:59:50', 'active', '$2y$12$cg/ZqQn1LSWy.6/RW9qr4OWNEIXQEPPvME2h8PEDEpYU02wva8KTS', NULL, NULL, NULL, '2025-04-19 08:59:50', '2025-04-19 08:59:50'),
	(18, 'Nguyễn Kim', 'cuonghotran1233@gmail.com', 'field_owner', '0321739129', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 09:01:28', 'active', '$2y$10$4klfCDTjT3KSVHMaPI.KGesAm1m1Nz2UFXrbqDisVj/STxKDGS0iW', NULL, NULL, NULL, '2025-04-19 09:01:28', '2025-04-19 09:01:28'),
	(22, 'Nguyen van a', 'vuong20031590@gmail.com', 'user', '0123457689', '123, abc HCM', 'aaaaa', 'aaaaaa', 'aaaaaaaaa', 'aaaaaaaaaa', '123456789', NULL, '2025-04-20 21:08:27', 'active', '$2y$10$p049nNL9ER3PpEzKss4A3eq/n8ECB64NaqEQ8mBsUoD3HRJ2.K/jy', NULL, NULL, NULL, '2025-04-20 21:08:09', '2025-04-23 08:10:27');

-- Dumping structure for table keysport.yeu_thich
CREATE TABLE IF NOT EXISTS `yeu_thich` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ID_KH` bigint unsigned NOT NULL,
  `ID_SP` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `yeu_thich_id_kh_foreign` (`ID_KH`),
  KEY `yeu_thich_id_sp_foreign` (`ID_SP`),
  CONSTRAINT `yeu_thich_id_kh_foreign` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `yeu_thich_id_sp_foreign` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table keysport.yeu_thich: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
