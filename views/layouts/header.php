<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>?action=dashboard">
            <i class="bi bi-airplane-fill"></i> Quản lý Tour
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>?action=dashboard">
                        <i class="bi bi-house-door"></i> Trang chủ
                    </a>
                </li>
                
                <?php if (isAdmin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-map"></i> Quản lý Tour
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=tours/index">Danh sách tour</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=tours/create">Tạo tour mới</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people"></i> Quản lý HDV
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=guides/index">Danh sách HDV</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=guides/create">Thêm HDV mới</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=vehicles/index">
                            <i class="bi bi-truck"></i> Quản lý Xe
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=bookings/index">
                            <i class="bi bi-calendar-check"></i> Quản lý Booking
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=reports/index">
                            <i class="bi bi-graph-up"></i> Báo cáo & Thống kê
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=chat/index">
                            <i class="bi bi-chat-dots"></i> Chat nội bộ
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if (isGuide()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=tours/my-tours">
                            <i class="bi bi-calendar-check"></i> Tour của tôi
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=daily-logs/index">
                            <i class="bi bi-journal-text"></i> Nhật ký hành trình
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=costs/my-costs">
                            <i class="bi bi-cash-stack"></i> Cập nhật chi phí
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=incidents/index">
                            <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=feedbacks/index">
                            <i class="bi bi-chat-left-text"></i> Phản hồi đánh giá
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?action=chat/index">
                            <i class="bi bi-chat-dots"></i> Chat nội bộ
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> 
                        <?= htmlspecialchars(getCurrentUser()['full_name'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=profile">
                            <i class="bi bi-person"></i> Thông tin cá nhân
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>?action=auth/logout">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

