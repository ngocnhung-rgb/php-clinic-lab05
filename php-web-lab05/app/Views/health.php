<div class="card p-4 shadow-sm border-0 mb-3">
    <h2 class="mb-4">Trạng thái hệ thống</h2>
    <p class="fs-5">
        <strong>Kết nối CSDL:</strong> 
        <span id="db-status" class="badge bg-info">Đang kiểm tra...</span>
    </p>
    <p><strong>Server:</strong> <span class="text-success"><?= e($status['server']) ?></span></p>
    <p><strong>Thời gian hệ thống:</strong> <?= e($status['time']) ?></p>
</div>

<a href="/" class="btn btn-primary">Quay lại Dashboard</a>

<script>
    fetch('/health', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        const el = document.getElementById('db-status');
        if (data.database === 'connected') {
            el.innerText = '✅ Online';
            el.className = 'badge bg-success';
        } else {
            el.innerText = '❌ Offline';
            el.className = 'badge bg-danger';
        }
    })
    .catch(() => {
        const el = document.getElementById('db-status');
        el.innerText = '❌ Lỗi kết nối';
        el.className = 'badge bg-danger';
    });
</script>