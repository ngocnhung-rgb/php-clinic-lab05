<h2 class="mb-4 mt-5">Tạo lịch hẹn mới</h2>
<form method="post" action="/orders/store" class="row g-3 p-4 bg-light rounded shadow-sm">
    <div class="col-md-6">
        <label class="form-label">Mã lịch hẹn</label>
        <input type="text" name="order_code" class="form-control <?= isset($errors['order_code']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['order_code'] ?? '') ?>" required>
        <?php if (isset($errors['order_code'])): ?>
            <div class="invalid-feedback"><?= e($errors['order_code']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Tên khách hàng</label>
        <input type="text" name="customer_name" class="form-control <?= isset($errors['customer_name']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['customer_name'] ?? '') ?>" required>
        <?php if (isset($errors['customer_name'])): ?>
            <div class="invalid-feedback"><?= e($errors['customer_name']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email khách hàng</label>
        <input type="email" name="customer_email" class="form-control <?= isset($errors['customer_email']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['customer_email'] ?? '') ?>">
        <?php if (isset($errors['customer_email'])): ?>
            <div class="invalid-feedback"><?= e($errors['customer_email']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Tổng tiền</label>
        <input type="number" name="total_amount" class="form-control <?= isset($errors['total_amount']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['total_amount'] ?? 0) ?>" step="0.01">
        <?php if (isset($errors['total_amount'])): ?>
            <div class="invalid-feedback"><?= e($errors['total_amount']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Ngày hẹn</label>
        <input type="date" name="appointment_date" class="form-control <?= isset($errors['appointment_date']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['appointment_date'] ?? '') ?>" required>
        <?php if (isset($errors['appointment_date'])): ?>
            <div class="invalid-feedback"><?= e($errors['appointment_date']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <?php foreach (['pending' => 'Chờ xử lý', 'paid' => 'Đã thanh toán', 'cancelled' => 'Đã hủy'] as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= ($old['status'] ?? 'pending') === $key ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-primary px-4">Tạo lịch hẹn</button>
        <a href="/orders" class="btn btn-secondary px-4">Quay lại</a>
    </div>
</form>