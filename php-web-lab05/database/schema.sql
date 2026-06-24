CREATE DATABASE IF NOT EXISTS web_php_lab05 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE web_php_lab05;

DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `leads`;
DROP TABLE IF EXISTS `users`;

-- Bảng Bệnh nhân (Leads)
CREATE TABLE `leads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30),
  `status` VARCHAR(30) DEFAULT 'new',
  `note` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_lead_email` (`email`),
  UNIQUE KEY `unique_lead_phone` (`phone`),
  INDEX `idx_leads_created_at` (`created_at`)
) ENGINE=InnoDB;

-- Bảng Lịch hẹn (Orders)
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(50) NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(150),
  `total_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `appointment_date` DATETIME NOT NULL,
  `status` VARCHAR(30) DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_order_code` (`order_code`),
  INDEX `idx_orders_appointment_date` (`appointment_date`)
) ENGINE=InnoDB;

-- Bảng người dùng hệ thống (phục vụ mở rộng đăng nhập trong tương lai)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);