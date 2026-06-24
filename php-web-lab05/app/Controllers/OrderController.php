<?php
namespace App\Controllers;

use App\Core\Container;
use App\Core\DuplicateRecordException;
use Exception;

class OrderController
{
    private $repo;

    public function __construct()
    {
        $this->repo = Container::get('orderRepo');
    }

    public function index(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->repo->getPaginated($search, $limit, $offset, 'appointment_date', 'desc');
        $total = $this->repo->countAll($search);

        view('orders/index', [
            'orders'     => $orders,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $total > 0 ? ceil($total / $limit) : 1,
            'search'     => $search
        ]);
    }

    public function create(): void
    {
        view('orders/create', ['title' => 'Thêm Lịch hẹn mới']);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/orders');

        $validated = $this->validate($_POST);

        if (!empty($validated['errors'])) {
            view('orders/create', ['errors' => $validated['errors'], 'old' => $validated['values']]);
            return;
        }

        try {
            $this->repo->create($validated['values']);
            flash_set('success', 'Đã tạo lịch hẹn thành công.');
            redirect('/orders');
        } catch (DuplicateRecordException $e) {
            flash_set('error', $e->getMessage());
            view('orders/create', ['errors' => ['order_code' => $e->getMessage()], 'old' => $validated['values']]);
        } catch (Exception $e) {
            logger("Order Creation Error: " . $e->getMessage(), "ERROR");
            flash_set('error', 'Có lỗi xảy ra khi lưu lịch hẹn.');
            redirect('/orders/create');
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $order = $this->repo->findById($id);
        
        if (!$order) { 
            flash_set('error', 'Không tìm thấy lịch hẹn.'); 
            redirect('/orders'); 
        }
        
        view('orders/edit', ['order' => $order]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/orders');

        $id = (int)($_POST['id'] ?? 0);
        $validated = $this->validate($_POST);

        if (!empty($validated['errors'])) {
            view('orders/edit', ['order' => array_merge(['id' => $id], $validated['values']), 'errors' => $validated['errors']]);
            return;
        }

        try {
            $this->repo->update($id, $validated['values']);
            flash_set('success', 'Đã cập nhật lịch hẹn.');
            redirect('/orders');
        } catch (DuplicateRecordException $e) {
            flash_set('error', $e->getMessage());
            redirect('/orders/edit?id=' . $id);
        } catch (Exception $e) {
            logger("Order Update Error: " . $e->getMessage(), "ERROR");
            flash_set('error', 'Lỗi cập nhật dữ liệu.');
            redirect('/orders/edit?id=' . $id);
        }
    }

    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->repo->delete($id);
            flash_set('success', 'Đã xóa lịch hẹn.');
        } catch (Exception $e) {
            logger("Order Delete Error: " . $e->getMessage(), "ERROR");
            flash_set('error', 'Không thể xóa lịch hẹn này.');
        }
        redirect('/orders');
    }

    private function validate(array $input): array
    {
        $values = [
            'order_code'       => trim($input['order_code'] ?? ''),
            'customer_name'    => trim($input['customer_name'] ?? ''),
            'customer_email'   => trim($input['customer_email'] ?? ''),
            'appointment_date' => trim($input['appointment_date'] ?? ''), 
            'status'           => trim($input['status'] ?? 'pending'),
            'total_amount'     => (float) ($input['amount'] ?? $input['total_amount'] ?? 0),
        ];
        
        $errors = [];

        if (!preg_match('/^[A-Za-z0-9]{3,}$/', $values['order_code'])) {
            $errors['order_code'] = 'Mã lịch hẹn phải có ít nhất 3 ký tự.';
        }

        if (!preg_match('/^[^0-9]{2,}$/', $values['customer_name'])) {
            $errors['customer_name'] = 'Tên không được chứa số và ít nhất 2 ký tự.';
        }

        if ($values['customer_email'] !== '' && !filter_var($values['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email không hợp lệ.';
        }

        if (empty($values['appointment_date'])) {
            $errors['appointment_date'] = 'Ngày hẹn là bắt buộc.';
        }

        if ($values['total_amount'] < 0) {
            $errors['total_amount'] = 'Số tiền không được âm.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}