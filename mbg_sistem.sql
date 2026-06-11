-- ============================================================
-- Sistem MBG - SQL Dump untuk phpMyAdmin Laragon
-- Database: mbg_sistem
-- Generated: 2025
-- ============================================================

CREATE DATABASE IF NOT EXISTS `mbg_sistem` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mbg_sistem`;

-- ─────────────────────────────────────────
-- Tabel: users
-- ─────────────────────────────────────────
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','asisten','driver') NOT NULL DEFAULT 'guru',
  `phone` varchar(20) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: orders
-- ─────────────────────────────────────────
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `guru_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pengiriman` date NOT NULL,
  `jumlah_porsi_besar` int(11) NOT NULL DEFAULT 0,
  `jumlah_porsi_kecil` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','diterima','diproses','dikemas','siap_dikirim','dalam_perjalanan','sampai_sekolah','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_guru_id_foreign` (`guru_id`),
  CONSTRAINT `orders_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: menus
-- ─────────────────────────────────────────
CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `lauk` varchar(255) NOT NULL,
  `sayur` varchar(255) NOT NULL,
  `buah` varchar(255) NOT NULL,
  `sambal` varchar(255) DEFAULT NULL,
  `kalori` decimal(8,2) NOT NULL DEFAULT 0.00,
  `protein` decimal(8,2) NOT NULL DEFAULT 0.00,
  `lemak` decimal(8,2) NOT NULL DEFAULT 0.00,
  `karbohidrat` decimal(8,2) NOT NULL DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_order_id_foreign` (`order_id`),
  CONSTRAINT `menus_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: deliveries
-- ─────────────────────────────────────────
CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `status_pengiriman` enum('menunggu','dalam_perjalanan','sampai_sekolah','selesai') NOT NULL DEFAULT 'menunggu',
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `tracking_active` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `catatan_driver` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliveries_order_id_foreign` (`order_id`),
  KEY `deliveries_driver_id_foreign` (`driver_id`),
  CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deliveries_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: tracking_logs
-- ─────────────────────────────────────────
CREATE TABLE `tracking_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `speed` decimal(6,2) DEFAULT NULL COMMENT 'km/h',
  `accuracy` decimal(8,2) DEFAULT NULL COMMENT 'meters',
  `recorded_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tracking_logs_delivery_id_foreign` (`delivery_id`),
  CONSTRAINT `tracking_logs_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: sessions
-- ─────────────────────────────────────────
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Tabel: password_reset_tokens
-- ─────────────────────────────────────────
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────
-- Data Awal: Akun Default
-- password = "password" (bcrypt)
-- ─────────────────────────────────────────
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `school`, `created_at`, `updated_at`) VALUES
('Administrator',    'admin@mbg.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   '081234567890', NULL,           NOW(), NOW()),
('Bu Sari Guru',     'guru@mbg.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru',    '081234567891', 'SDN Malang 01', NOW(), NOW()),
('Pak Budi Wicaksono','guru2@mbg.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru',    '081234567894', 'SDN Malang 02', NOW(), NOW()),
('Dewi Asisten',     'asisten@mbg.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'asisten', '081234567892', NULL,           NOW(), NOW()),
('Pak Joko Driver',  'driver@mbg.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'driver',  '081234567893', NULL,           NOW(), NOW());

-- ─────────────────────────────────────────
-- Data Contoh: Orders
-- ─────────────────────────────────────────
INSERT INTO `orders` (`guru_id`, `tanggal_pengiriman`, `jumlah_porsi_besar`, `jumlah_porsi_kecil`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 15, 5, 'selesai', 'Mohon tepat waktu ya', NOW(), NOW()),
(2, CURDATE(), 20, 8, 'dalam_perjalanan', 'Porsi anak kelas 1 yang kecil', NOW(), NOW()),
(3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 25, 10, 'pending', NULL, NOW(), NOW()),
(2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 18, 6, 'siap_dikirim', NULL, NOW(), NOW());

-- ─────────────────────────────────────────
-- Data Contoh: Menus
-- ─────────────────────────────────────────
INSERT INTO `menus` (`order_id`, `lauk`, `sayur`, `buah`, `sambal`, `kalori`, `protein`, `lemak`, `karbohidrat`, `created_at`, `updated_at`) VALUES
(1, 'Ayam Goreng Bumbu Kuning', 'Tumis Kangkung', 'Pisang Ambon', 'Sambal Terasi', 520.00, 28.00, 18.00, 65.00, NOW(), NOW()),
(2, 'Ikan Bakar Bumbu Kecap', 'Sayur Bayam Jagung', 'Jeruk Manis', 'Sambal Bawang', 480.00, 32.00, 14.00, 58.00, NOW(), NOW()),
(4, 'Telur Dadar Bumbu Balado', 'Capcay Sayuran', 'Semangka', 'Sambal Ijo', 450.00, 22.00, 16.00, 60.00, NOW(), NOW());

-- ─────────────────────────────────────────
-- Data Contoh: Deliveries
-- ─────────────────────────────────────────
INSERT INTO `deliveries` (`order_id`, `driver_id`, `status_pengiriman`, `current_latitude`, `current_longitude`, `tracking_active`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'selesai',          -7.96660000, 112.63260000, 0, DATE_SUB(NOW(), INTERVAL 2 DAY), NOW(), NOW()),
(2, 5, 'dalam_perjalanan', -7.97500000, 112.63500000, 1, NULL,                            NOW(), NOW());

-- ─────────────────────────────────────────
-- Data Contoh: Tracking Logs
-- ─────────────────────────────────────────
INSERT INTO `tracking_logs` (`delivery_id`, `latitude`, `longitude`, `speed`, `accuracy`, `recorded_at`, `created_at`, `updated_at`) VALUES
(2, -7.97000000, 112.63000000, 30.00, 5.00, NOW(), NOW(), NOW()),
(2, -7.97100000, 112.63100000, 28.00, 5.00, NOW(), NOW(), NOW()),
(2, -7.97200000, 112.63200000, 25.00, 6.00, NOW(), NOW(), NOW()),
(2, -7.97350000, 112.63350000, 32.00, 4.00, NOW(), NOW(), NOW()),
(2, -7.97500000, 112.63500000, 20.00, 5.00, NOW(), NOW(), NOW());
