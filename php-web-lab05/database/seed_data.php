<?php
// Cấu hình kết nối
$host = 'localhost';
$db   = 'web_php_lab05';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Tắt kiểm tra khóa ngoại để truncate
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE orders;");
    $pdo->exec("TRUNCATE TABLE leads;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "Đang sinh dữ liệu mẫu...\n";

    // Sinh 200 bản ghi
    for ($i = 1; $i <= 200; $i++) {
        // Tạo Lead
        $stmtLead = $pdo->prepare("INSERT INTO leads (name, email, phone, status) VALUES (?, ?, ?, ?)");
        $stmtLead->execute([
            "Bệnh nhân $i", 
            "patient$i@example.com", 
            "090" . str_pad($i, 7, '0', STR_PAD_LEFT),
            (['new', 'contacted', 'qualified', 'lost'])[rand(0, 3)]
        ]);

        // Tạo Order tương ứng
        $stmtOrder = $pdo->prepare("INSERT INTO orders (order_code, customer_name, customer_email, total_amount, appointment_date, status, product_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtOrder->execute([
            "ORD" . str_pad($i, 5, '0', STR_PAD_LEFT),
            "Bệnh nhân $i",
            "patient$i@example.com",
            rand(100, 5000) * 1000,
            date('Y-m-d H:i:s', strtotime("+" . rand(1, 30) . " days")),
            (['pending', 'completed', 'cancelled'])[rand(0, 2)],
            "Dịch vụ khám răng số $i"
        ]);
    }
    
    echo "Đã sinh xong 200 bản ghi cho cả leads và orders!";
} catch (PDOException $e) {
    die("Lỗi seed dữ liệu: " . $e->getMessage());
}