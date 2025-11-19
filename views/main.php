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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .main-content {
            padding: 20px 0;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        /* Override cho trang login */
        body:has(.login-container) {
            background: transparent;
            padding: 0;
        }
        body:has(.login-container) .main-content {
            padding: 0;
        }
        body:has(.login-container) .container-fluid {
            padding: 0;
        }
        body:has(.login-container) footer {
            display: none;
        }
    </style>
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

    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
        </div>
    </footer>
</body>

</html>