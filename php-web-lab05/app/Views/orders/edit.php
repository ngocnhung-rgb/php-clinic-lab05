<h2 class="mb-4">Chỉnh sửa Lịch hẹn</h2>
<form action="/orders/update" method="POST" class="p-4 bg-light rounded shadow-sm">
    <input type="hidden" name="id" value="<?= e($order['id'] ?? 0) ?>">
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Mã lịch hẹn:</label>
            <input type="text" name="order_code" class="form-control" value="<?= e($order['order_code'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tên bệnh nhân:</label>
            <input type="text" name="customer_name" class="form-control" value="<?= e($order['customer_name'] ?? '') ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Email bệnh nhân:</label>
            <input type="email" name="customer_email" class="form-control" value="<?= e($order['customer_email'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Ngày hẹn:</label>
            <input type="date" name="appointment_date" class="form-control" 
                   value="<?= e(isset($order['appointment_date']) ? date('Y-m-d', strtotime($order['appointment_date'])) : '') ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Trạng thái:</label>
            <select name="status" class="form-select">
                <?php foreach (['pending' => 'Chờ xử lý', 'paid' => 'Đã thanh toán', 'cancelled' => 'Đã hủy'] as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= ($order['status'] ?? 'pending') === $key ? 'selected' : '' ?>>
                        <?= e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Số tiền:</label>
            <input type="number" name="total_amount" class="form-control" value="<?= e($order['total_amount'] ?? 0) ?>" step="0.01">
        </div>
    </div>
          
    <div class="mt-3">
        <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
        <a href="/orders" class="btn btn-secondary px-4">Quay lại</a>
    </div>
</form>