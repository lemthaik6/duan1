-- ============================================
-- DATABASE: QUẢN LÝ TOUR NỘI BỘ
-- Mô tả: Hệ thống quản lý tour nội bộ cho Admin và Hướng dẫn viên
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================
-- BẢNG: users - Người dùng hệ thống
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','guide') NOT NULL DEFAULT 'guide',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_role` (`role`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_categories - Danh mục tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tours - Tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `name` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `schedule` text DEFAULT NULL COMMENT 'Lịch trình tổng quan',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `internal_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá gốc nội bộ',
  `priority_level` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('upcoming','ongoing','completed','cancelled') NOT NULL DEFAULT 'upcoming',
  `pdf_program_path` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file PDF chương trình tour',
  `created_by` int(11) NOT NULL COMMENT 'Admin tạo tour',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`start_date`, `end_date`),
  KEY `idx_created_by` (`created_by`),
  FOREIGN KEY (`category_id`) REFERENCES `tour_categories` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: itineraries - Lịch trình tour (từng ngày)
-- ============================================
CREATE TABLE IF NOT EXISTS `itineraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL COMMENT 'Số ngày trong tour',
  `date` date NOT NULL,
  `location` varchar(200) NOT NULL COMMENT 'Địa điểm',
  `activities` text NOT NULL COMMENT 'Hoạt động',
  `departure_time` time DEFAULT NULL COMMENT 'Giờ khởi hành',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_date` (`date`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_assignments - Phân công HDV cho tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL COMMENT 'Hướng dẫn viên',
  `assigned_by` int(11) NOT NULL COMMENT 'Admin phân công',
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('assigned','confirmed','completed') NOT NULL DEFAULT 'assigned',
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tour_guide` (`tour_id`, `guide_id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_guide` (`guide_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: vehicles - Xe
-- ============================================
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `license_plate` varchar(20) NOT NULL UNIQUE COMMENT 'Biển số',
  `vehicle_type` varchar(50) NOT NULL COMMENT 'Loại xe',
  `capacity` int(11) DEFAULT NULL COMMENT 'Sức chứa',
  `driver_name` varchar(100) DEFAULT NULL COMMENT 'Tên tài xế',
  `driver_phone` varchar(20) DEFAULT NULL,
  `status` enum('available','in_use','maintenance','unavailable') NOT NULL DEFAULT 'available',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: vehicle_assignments - Gán xe cho tour
-- ============================================
CREATE TABLE IF NOT EXISTS `vehicle_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_vehicle` (`vehicle_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: cost_categories - Loại chi phí
-- ============================================
CREATE TABLE IF NOT EXISTS `cost_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_costs - Chi phí tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_costs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `cost_category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `invoice_image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh hóa đơn',
  `created_by` int(11) NOT NULL COMMENT 'Người tạo (admin hoặc HDV)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_category` (`cost_category_id`),
  KEY `idx_date` (`date`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`cost_category_id`) REFERENCES `cost_categories` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_customers - Khách nội bộ
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_card` varchar(20) DEFAULT NULL COMMENT 'CMND/CCCD',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_daily_logs - Nhật ký hàng ngày của HDV
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_daily_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `activities` text NOT NULL COMMENT 'Hoạt động trong ngày',
  `customer_status` text DEFAULT NULL COMMENT 'Tình trạng khách',
  `weather` varchar(100) DEFAULT NULL COMMENT 'Thời tiết',
  `traffic` varchar(100) DEFAULT NULL COMMENT 'Tình trạng giao thông',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tour_guide_date` (`tour_id`, `guide_id`, `date`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_guide` (`guide_id`),
  KEY `idx_date` (`date`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_images - Hình ảnh tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `daily_log_id` int(11) DEFAULT NULL COMMENT 'Liên kết với nhật ký ngày',
  `image_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_daily_log` (`daily_log_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`daily_log_id`) REFERENCES `tour_daily_logs` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_incidents - Sự cố trong tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL COMMENT 'Người báo cáo (thường là HDV)',
  `incident_type` enum('weather','traffic','delay','customer_issue','lost_item','other') NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `incident_date` date NOT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('reported','investigating','resolved','closed') NOT NULL DEFAULT 'reported',
  `resolution` text DEFAULT NULL COMMENT 'Giải pháp/xử lý',
  `resolved_by` int(11) DEFAULT NULL COMMENT 'Người xử lý',
  `resolved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_reported_by` (`reported_by`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_attendance - Chấm công HDV
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `status` enum('present','absent','late','leave_early') NOT NULL DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `recorded_by` int(11) NOT NULL COMMENT 'Người ghi nhận (admin)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tour_guide_date_attendance` (`tour_id`, `guide_id`, `date`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_guide` (`guide_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_reports - Báo cáo tour
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `report_type` enum('daily','final','incident','cost') NOT NULL DEFAULT 'daily',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL COMMENT 'File báo cáo đính kèm',
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by` int(11) DEFAULT NULL COMMENT 'Admin xem xét',
  `reviewed_at` datetime DEFAULT NULL,
  `status` enum('draft','submitted','reviewed','approved') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_guide` (`guide_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_feedbacks - Đánh giá tour (nội bộ)
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `rated_by` int(11) NOT NULL COMMENT 'Người đánh giá (admin hoặc HDV)',
  `feedback_type` enum('guide_evaluation','tour_evaluation','customer_feedback') NOT NULL,
  `rating` int(11) DEFAULT NULL COMMENT 'Điểm đánh giá 1-5',
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  KEY `idx_rated_by` (`rated_by`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`rated_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG: tour_documents - Tài liệu tour (hướng dẫn, timeline, checklist)
-- ============================================
CREATE TABLE IF NOT EXISTS `tour_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `document_type` enum('guide','timeline','checklist','other') NOT NULL,
  `title` varchar(200) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tour` (`tour_id`),
  FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DỮ LIỆU MẪU
-- ============================================

-- Thêm dữ liệu mẫu cho tour_categories
INSERT INTO `tour_categories` (`name`, `description`) VALUES
('Tour trong nước', 'Các tour du lịch trong nước'),
('Tour quốc tế', 'Các tour du lịch nước ngoài'),
('Tour theo yêu cầu', 'Tour được thiết kế theo yêu cầu đặc biệt');

-- Thêm dữ liệu mẫu cho cost_categories
INSERT INTO `cost_categories` (`name`, `description`) VALUES
('Ăn uống', 'Chi phí ăn uống trong tour'),
('Khách sạn', 'Chi phí lưu trú'),
('Vé tham quan', 'Vé vào cửa các điểm tham quan'),
('Xăng, xe', 'Chi phí nhiên liệu và thuê xe'),
('Chi phí phát sinh', 'Các chi phí khác phát sinh trong tour');

-- Tạo tài khoản admin mặc định (password: admin123 - cần hash trong thực tế)
-- Lưu ý: Password này cần được hash bằng password_hash() trong PHP
INSERT INTO `users` (`username`, `password`, `full_name`, `email`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 'admin@example.com', 'admin');

-- Tạo tài khoản HDV mẫu (password: guide123)
INSERT INTO `users` (`username`, `password`, `full_name`, `email`, `phone`, `role`) VALUES
('guide1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hướng dẫn viên 1', 'guide1@example.com', '0901234567', 'guide');

