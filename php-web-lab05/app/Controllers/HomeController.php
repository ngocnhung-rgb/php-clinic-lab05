<?php
namespace App\Controllers;

use App\Core\Container;

class HomeController 
{
    public function index(): void 
    {
        $orderRepo = Container::get('orderRepo');
        $leadRepo  = Container::get('leadRepo');

        $stats = [
            'total_orders' => $orderRepo->countAll(),
            'total_leads'  => $leadRepo->countAll()
        ];

        logger("Người dùng đã truy cập Bảng điều khiển (Dashboard).");

        // Gọi hàm view() thay vì require trực tiếp
        view('dashboard', [
            'stats' => $stats,
            'title' => 'Dashboard - Mini Clinic'
        ]);
    }
}