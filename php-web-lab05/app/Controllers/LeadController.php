<?php
namespace App\Controllers;

use App\Core\Container;
use App\Core\DuplicateRecordException;
use Exception;

class LeadController {
    private $repo;

    public function __construct() {
        $this->repo = Container::get('leadRepo');
    }

    public function index() {
        $keyword = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';

        $leads = $this->repo->getPaginated($keyword, $limit, $offset, $sort, $direction);
        $total = $this->repo->countAll($keyword);
        $totalPages = $total > 0 ? ceil($total / $limit) : 1;

        view('leads/index', [
            'leads'      => $leads,
            'total'      => $total, 
            'page'       => $page,
            'totalPages' => $totalPages,
            'q'          => $keyword
        ]);
    }  

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/leads');

        $validated = $this->validate($_POST);
        if (!empty($validated['errors'])) {
            view('leads/create', ['errors' => $validated['errors'], 'old' => $validated['values']]);
            return;
        }

        try {
            $this->repo->create($validated['values']);
            flash_set('success', 'Đã thêm bệnh nhân mới.');
            redirect('/leads');
        } catch (DuplicateRecordException $e) {
            flash_set('error', $e->getMessage());
            // Trả về lỗi chung cho cả email và phone vì không biết cái nào trùng
            view('leads/create', ['errors' => ['email' => $e->getMessage(), 'phone' => $e->getMessage()], 'old' => $validated['values']]);
        } catch (Exception $e) {
            logger("Lỗi thêm bệnh nhân: " . $e->getMessage());
            flash_set('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
            redirect('/leads/create');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/leads');

        $id = (int)($_POST['id'] ?? 0);
        $validated = $this->validate($_POST);

        if (!empty($validated['errors'])) {
            view('leads/edit', ['lead' => array_merge(['id' => $id], $validated['values']), 'errors' => $validated['errors']]);
            return;
        }

        try {
            $this->repo->update($id, $validated['values']);
            flash_set('success', 'Đã cập nhật thông tin.');
            redirect('/leads');
        } catch (DuplicateRecordException $e) {
            flash_set('error', $e->getMessage());
            redirect('/leads/edit?id=' . $id);
        } catch (Exception $e) {
            logger("Lỗi cập nhật bệnh nhân: " . $e->getMessage());
            flash_set('error', 'Cập nhật thất bại.');
            redirect('/leads/edit?id=' . $id);
        }
    }

    public function delete() {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->repo->delete($id);
            flash_set('success', 'Đã xóa bệnh nhân.');
        } catch (Exception $e) {
            logger("Lỗi xóa bệnh nhân: " . $e->getMessage());
            flash_set('error', 'Không thể xóa bản ghi này.');
        }
        redirect('/leads');
    }

    public function create() { 
        view('leads/create', ['title' => 'Thêm Bệnh nhân']); 
    }
    
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $lead = $this->repo->findById($id);
        if (!$lead) { 
            flash_set('error', 'Không tìm thấy bệnh nhân.'); 
            redirect('/leads'); 
        }
        view('leads/edit', ['lead' => $lead]);
    }

    private function validate(array $input): array 
    {
        $values = [
            'name'   => trim($input['name'] ?? ''),
            'email'  => trim($input['email'] ?? ''),
            'phone'  => trim($input['phone'] ?? ''),
            'status' => $input['status'] ?? 'new',
            'note'   => trim($input['note'] ?? '')
        ];
        
        $errors = [];

        // Kiểm tra Tên
        if (!preg_match('/^[^0-9]{2,}$/', $values['name'])) {
            $errors['name'] = 'Tên phải có ít nhất 2 ký tự và không được chứa số.';
        }

        // Kiểm tra Email
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        // Kiểm tra Số điện thoại
        if (!preg_match('/^[0-9]{9,11}$/', $values['phone'])) {
            $errors['phone'] = 'Số điện thoại phải từ 9 đến 11 chữ số.';
        }
        
        return ['values' => $values, 'errors' => $errors];
    }
}