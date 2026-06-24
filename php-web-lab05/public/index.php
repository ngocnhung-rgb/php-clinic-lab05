<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1. Autoloader - Cấu trúc lại để an toàn hơn
spl_autoload_register(function ($class) {
    // Chuyển namespace App\... thành đường dẫn thực tế /app/...
    $file = dirname(__DIR__) . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// 2. Nạp helpers
require __DIR__ . '/../app/Core/helpers.php';

// 3. Container & Services
use App\Core\{Router, Container, Database};
use App\Controllers\{HomeController, LeadController, OrderController, HealthController};
use App\Repositories\{LeadRepository, OrderRepository};

try {
    Container::set('db', fn() => Database::getConnection());
    Container::set('leadRepo', fn() => new LeadRepository(Container::get('db')));
    Container::set('orderRepo', fn() => new OrderRepository(Container::get('db')));

    // 4. Khởi tạo Router và Định nghĩa Route
    $router = new Router();
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/health', [HealthController::class, 'index']);

    // Routes cho Leads
    $router->get('/leads', [LeadController::class, 'index']);
    $router->get('/leads/create', [LeadController::class, 'create']);
    $router->post('/leads/store', [LeadController::class, 'store']);
    $router->get('/leads/edit', [LeadController::class, 'edit']);
    $router->post('/leads/update', [LeadController::class, 'update']);
    $router->post('/leads/delete', [LeadController::class, 'delete']);

    // Routes cho Orders
    $router->get('/orders', [OrderController::class, 'index']);
    $router->get('/orders/create', [OrderController::class, 'create']);
    $router->post('/orders/store', [OrderController::class, 'store']);
    $router->get('/orders/edit', [OrderController::class, 'edit']);
    $router->post('/orders/update', [OrderController::class, 'update']);
    $router->post('/orders/delete', [OrderController::class, 'delete']);

    // 5. Dispatch Request
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/php-web-lab05'; 
    
    if (strpos($uri, $basePath) === 0) {
        $uri = substr($uri, strlen($basePath));
    }
    
    $uri = (empty($uri) || $uri === '/') ? '/' : $uri;

    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

} catch (\App\Core\RouterException $e) {
    http_response_code(404);
    echo "<h1>404 - Trang không tồn tại</h1><p>" . e($e->getMessage()) . "</p>";
} catch (Exception $e) {
    logger("CRITICAL ERROR: " . $e->getMessage(), "ERROR");
    die("<h1>Lỗi hệ thống</h1><p>Đã xảy ra sự cố, vui lòng thử lại sau.</p>");
}