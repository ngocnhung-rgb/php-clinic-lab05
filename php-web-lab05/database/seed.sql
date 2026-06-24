USE `web_php_lab05`;

-- Chèn dữ liệu mẫu cho Bệnh nhân
INSERT INTO `leads` (`name`, `email`, `phone`, `status`, `note`) VALUES 
('Nguyễn Văn An', 'an.nguyen@example.com', '0901234567', 'new', 'Khách hàng tiềm năng'),
('Trần Thị Bình', 'binh.tran@example.com', '0912345678', 'contacted', 'Đang tư vấn'),
('Lê Hoàng Cường', 'cuong.le@example.com', '0923456789', 'new', NULL);

-- Chèn dữ liệu mẫu cho Lịch hẹn
INSERT INTO `orders` (`order_code`, `customer_name`, `customer_email`, `total_amount`, `appointment_date`, `status`) VALUES 
('ORD001', 'Nguyễn Văn An', 'an.nguyen@example.com', 500000, '2026-06-22 09:00:00', 'pending'),
('ORD002', 'Trần Thị Bình', 'binh.tran@example.com', 1200000, '2026-06-22 10:30:00', 'completed'),
('ORD003', 'Lê Hoàng Cường', 'cuong.le@example.com', 0, '2026-06-23 14:00:00', 'pending');

-- Chèn dữ liệu mẫu cho Người dùng
INSERT INTO users(username, email, password_hash) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- hash của 'password'
('staff', 'staff@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');