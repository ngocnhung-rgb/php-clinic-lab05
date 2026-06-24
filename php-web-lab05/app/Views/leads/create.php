<h2 class="mb-4">Thêm Bệnh nhân mới</h2>

<form method="post" action="/leads/store" class="row g-3 p-4 bg-light rounded shadow-sm">
    <div class="col-md-6">
        <label class="form-label">Họ tên</label>
        <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['name'] ?? '') ?>" 
               required minlength="2" pattern="[^0-9]+" title="Tên không được chứa số và ít nhất 2 ký tự">
        <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback"><?= e($errors['name']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['email'] ?? '') ?>" required>
        <?php if (isset($errors['email'])): ?>
            <div class="invalid-feedback"><?= e($errors['email']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-12">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
               value="<?= e($old['phone'] ?? '') ?>" 
               required pattern="[0-9]{9,11}" title="Số điện thoại phải là ký tự số và có độ dài từ 9 đến 11 số.">
        <?php if (isset($errors['phone'])): ?>
            <div class="invalid-feedback"><?= e($errors['phone']) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <?php foreach (['new' => 'Mới', 'contacted' => 'Đã liên hệ', 'qualified' => 'Đạt chuẩn', 'lost' => 'Hủy'] as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= ($old['status'] ?? 'new') === $key ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" class="form-control" rows="3"><?= e($old['note'] ?? '') ?></textarea>
    </div>

    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-primary px-4">Lưu hồ sơ</button>
        <a href="/leads" class="btn btn-secondary px-4">Quay lại</a>
    </div>
</form>