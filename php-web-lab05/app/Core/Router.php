<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        // 1. Lấy path sạch và chuẩn hóa: loại bỏ dấu / ở cuối
        $path = rtrim(parse_url($uri, PHP_URL_PATH), '/');
        if ($path === '') $path = '/';

        // 2. Kiểm tra route
        if (isset($this->routes[$method][$path])) {
            [$controllerName, $action] = $this->routes[$method][$path];
            
            // Kiểm tra controller có tồn tại không
            if (!class_exists($controllerName)) {
                die("Controller $controllerName không tồn tại.");
            }

            $controller = new $controllerName();
            if (!method_exists($controller, $action)) {
                die("Action $action không tồn tại trong $controllerName.");
            }

            $controller->$action();
            return;
        }

        // 3. Xử lý lỗi 405 (Method Not Allowed)
        foreach ($this->routes as $m => $mRoutes) {
            if (isset($mRoutes[$path])) {
                http_response_code(405);
                view('errors/405', ['title' => 'Lỗi 405 - Method Not Allowed']);
                return;
            }
        }

        // 4. Xử lý lỗi 404 (Not Found)
        http_response_code(404);
        view('errors/404', ['title' => 'Lỗi 404 - Page Not Found']);
    }
}