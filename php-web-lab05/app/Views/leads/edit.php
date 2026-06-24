<h2 class="mb-4">Sửa thông tin Bệnh nhân</h2>

<form action="/leads/update" method="POST" class="p-4 bg-light rounded shadow-sm">
    <input type="hidden" name="id" value="<?= e($lead['id'] ?? '') ?>">
    
    <div class="mb-3">
        <label class="form-label">Họ tên:</label>
        <input type="text" name="name" class="form-control" value="<?= e($lead['name'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" value="<?= e($lead['email'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Điện thoại:</label>
        <input type="text" name="phone" class="form-control" value="<?= e($lead['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
    <label class="form-label">Trạng thái:</label>
        <select name="status" class="form-select">
            <?php foreach (['new' => 'Mới', 'contacted' => 'Đã liên hệ', 'qualified' => 'Đạt chuẩn', 'lost' => 'Hủy'] as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= ($lead['status'] ?? 'new') === $key ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mt-4">
        <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
        <a href="/leads" class="btn btn-secondary px-4">Hủy</a>
    </div>
</form>