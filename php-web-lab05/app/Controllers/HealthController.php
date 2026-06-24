<?php
namespace App\Controllers;

use App\Core\Container;
use Exception;

class HealthController {

    public function index(): void
    {
        $dbStatus = 'disconnected';
        try {
            $db = Container::get('db');
            // Sử dụng một query đơn giản nhất có thể
            $db->query('SELECT 1'); 
            $dbStatus = 'connected';
        } catch (Exception $e) {
            logger("Health Check Fail: " . $e->getMessage(), "ERROR");
            $dbStatus = 'disconnected';
        }

        $status = [
            'database' => $dbStatus,
            'server'   => 'Running',
            'time'     => date('Y-m-d H:i:s')
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode($status);
            exit;
        }

        view('health', [
            'status' => $status,
            'title'  => 'Trạng thái hệ thống'
        ]);
}
}