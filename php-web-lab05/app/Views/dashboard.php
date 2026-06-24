<div class="page-header">
    <h1>Dashboard</h1>
    <p>
        Quản lý bệnh nhân và lịch hẹn phòng khám.
    </p>
</div>

<div class="dashboard-grid">

    <div class="stat-card patients">

        <div class="stat-icon">
            👨‍⚕️
        </div>

        <div>
            <h3>Tổng số bệnh nhân</h3>

            <div class="stat-number">
                <?= isset($stats['total_leads'])
                    ? (int)$stats['total_leads']
                    : 0 ?>
            </div>

            <a href="/leads" class="btn btn-primary">
                Xem danh sách
            </a>
        </div>

    </div>

    <div class="stat-card appointments">

        <div class="stat-icon">
            📅
        </div>

        <div>
            <h3>Tổng số lịch hẹn</h3>

            <div class="stat-number">
                <?= isset($stats['total_orders'])
                    ? (int)$stats['total_orders']
                    : 0 ?>
            </div>

            <a href="/orders" class="btn btn-primary">
                Xem danh sách
            </a>
        </div>

    </div>

</div>

<div class="quick-actions">

    <h2>Truy cập nhanh</h2>

    <div class="action-grid">

        <a href="/leads/create" class="action-card">
            <span>➕</span>
            <h3>Thêm bệnh nhân</h3>
        </a>

        <a href="/orders/create" class="action-card">
            <span>📆</span>
            <h3>Tạo lịch hẹn</h3>
        </a>

        <a href="/health" class="action-card">
            <span>⚙️</span>
            <h3>Kiểm tra hệ thống</h3>
        </a>

    </div>

</div>