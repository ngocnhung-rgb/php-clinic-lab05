<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">Quản lý Lịch hẹn</h2>
    <a class="btn btn-success" href="/orders/create">+ Tạo lịch hẹn mới</a>
</div>

<form method="get" action="/orders" class="row g-3 mb-4">
    <div class="col-md-8">
        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder="Tìm theo mã, tên khách hoặc email...">
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
        <a href="/orders" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Số tiền</th>
                <th>Trạng thái</th>
                <th>Ngày hẹn</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php 
                $stt = $total - (($page - 1) * 10); 
                ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $stt-- ?></td> 
                    <td><strong><?= htmlspecialchars($order['order_code'] ?? '') ?></strong></td>
                    <td><?= htmlspecialchars($order['customer_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($order['customer_email'] ?? 'N/A') ?></td>
                    <td><?= number_format((float)($order['total_amount'] ?? 0), 0, ',', '.') ?> VNĐ</td>
                    <td>
                        <?php 
                            $statusClass = ($order['status'] === 'paid') ? 'bg-success' : (($order['status'] === 'cancelled') ? 'bg-danger' : 'bg-secondary');
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($order['status'] ?? 'pending') ?></span>
                    </td>
                    <td><?= !empty($order['appointment_date']) ? date('d/m/Y', strtotime($order['appointment_date'])) : 'Chưa đặt' ?></td>
                    <td>
                        <a href="/orders/edit?id=<?= (int)($order['id'] ?? 0) ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <form method="post" action="/orders/delete" class="d-inline" onsubmit="return confirm('Xóa lịch hẹn này?')">
                            <input type="hidden" name="id" value="<?= (int)($order['id'] ?? 0) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center py-4">Không tìm thấy lịch hẹn nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php 
                $queryParams = ['page' => $i];
                if (!empty($_GET['search'])) $queryParams['search'] = $_GET['search'];
            ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link" href="/orders?<?= http_build_query($queryParams) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>