<?php
// app/Core/helpers.php

// 1. Bảo mật dữ liệu
function e(?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// 2. Điều hướng
function redirect(string $path): void {
    header("Location: {$path}");
    exit;
}

// 3. Quản lý Query String
function query_string(array $params = []): string {
    $current = $_GET;
    foreach ($params as $key => $value) {
        $current[$key] = $value;
    }
    return http_build_query($current);
}

// 4. Flash messages
function flash_set(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string {
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

// 5. Ghi nhật ký hệ thống 
function logger(string $message, string $level = 'INFO'): void {
    $dir = __DIR__ . '/../../storage/logs';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $logFile = $dir . '/app.log'; // Chỉ cần khai báo 1 lần
    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// 6. Hệ thống View với Layout 
function view(string $path, array $data = []): void {
    if (!isset($data['status'])) {
        $data['status'] = ['server' => 'N/A', 'time' => 'N/A'];
    }
    
    extract($data);
    $rootPath = dirname(__DIR__); 
    $viewFile = $rootPath . '/views/' . $path . '.php';
    $layoutFile = $rootPath . '/views/layouts/main.php';
    
    ob_start();
    if (file_exists($viewFile)) {
        require $viewFile;
    } else {
        die("KHÔNG THẤY FILE: " . $viewFile);
    }
    $content = ob_get_clean();
    
    if (file_exists($layoutFile)) {
        require $layoutFile;
    } else {
        echo $content;
    }
}