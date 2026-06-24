<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">Quản lý Bệnh nhân</h2>
    <a class="btn btn-success" href="/leads/create">+ Thêm Bệnh nhân</a>
</div>

<form method="get" action="/leads" class="row g-3 mb-4">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Tìm kiếm...">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
        <a href="/leads" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th><a href="/leads?<?= e(query_string(['sort' => 'name', 'search' => $q ?? ''])) ?>">Tên</a></th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th><a href="/leads?<?= e(query_string(['sort' => 'created_at'])) ?>" class="text-decoration-none">Ngày tạo</a></th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
    <?php if (!empty($leads)): ?>
        <?php 
        // Logic tính STT giảm dần: Tổng số bản ghi trừ đi số lượng bản ghi đã hiển thị ở các trang trước
        // $total là tổng số bệnh nhân được truyền từ LeadController
        $stt = $total - (($page - 1) * 10); 
        ?>
        <?php foreach ($leads as $lead): ?>
        <tr>
            <td><?= $stt-- ?></td> 
            
            <td><?= htmlspecialchars($lead['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($lead['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($lead['phone'] ?? '') ?></td>
            <td>
                <span class="badge bg-info text-dark"><?= htmlspecialchars($lead['status'] ?? 'new') ?></span>
            </td>
            <td><?= htmlspecialchars($lead['created_at'] ?? '') ?></td>
            <td>
                <a href="/leads/edit?id=<?= (int)($lead['id'] ?? 0) ?>" class="btn btn-sm btn-warning">Sửa</a>
                <form method="post" action="/leads/delete" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bệnh nhân này?')">
                    <input type="hidden" name="id" value="<?= (int)($lead['id'] ?? 0) ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center py-4">Không tìm thấy bệnh nhân nào.</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
</div>

<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php 
                // Xây dựng query string cho từng số trang, giữ lại các tham số tìm kiếm/sort nếu có
                $pageParams = ['page' => $i];
                if (!empty($q)) $pageParams['search'] = $q;
            ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link" href="/leads?<?= e(query_string($pageParams)) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>