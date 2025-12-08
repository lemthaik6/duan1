<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản lý Tour' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <!-- Animations CSS -->
    <link href="<?= BASE_URL ?>assets/css/animations.css" rel="stylesheet">
    <!-- Pages CSS -->
    <link href="<?= BASE_URL ?>assets/css/pages.css" rel="stylesheet">
    <!-- Reports CSS -->
    <link href="<?= BASE_URL ?>assets/css/reports.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/custom.js"></script>
</head>

<body>
    <?php if (isLoggedIn()): ?>
        <?php require_once PATH_ROOT . 'views/layouts/header.php'; ?>
    <?php endif; ?>

    <div class="main-content">
        <div class="container-fluid">
            <?php
            if (isset($view)) {
                require_once PATH_VIEW . $view . '.php';
            }
            ?>
        </div>
    </div>

    <footer class="bg-light text-center py-4 mt-5 border-top">
        <div class="container">
            <p class="text-muted mb-0">&copy; 2025 Tour Management System - Hệ thống Quản lý Tour Nội bộ</p>
        </div>
    </footer>
</body>

</html>