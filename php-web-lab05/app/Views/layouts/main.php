<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mini Clinic | <?= e($title ?? 'Quản lý Phòng khám') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">🏥 Mini Clinic</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="/">Dashboard</a>
            <a class="nav-link" href="/leads">Bệnh nhân</a>
            <a class="nav-link" href="/orders">Lịch hẹn</a>
            <a class="nav-link" href="/health">Health</a>
        </div>
    </div>
</nav>

<main class="container mt-4">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= e($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error = flash_get('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= e($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</main>

<footer class="container mt-5 text-center text-muted">
    <hr>
    <p>&copy; <?= date('Y') ?> Mini Clinic App - Hệ thống quản lý phòng khám</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>