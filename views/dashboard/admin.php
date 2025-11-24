<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-speedometer2"></i> Dashboard
            </h2>
            <p class="text-muted mb-0 mt-1">Xin chào, <?= htmlspecialchars(getCurrentUser()['full_name']) ?></p>
        </div>
        <span class="badge bg-primary p-2">
            <i class="bi bi-calendar-event"></i> <?= date('d/m/Y H:i') ?>
        </span>
    </div>

    <!-- Thống kê nhanh -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng số tour</p>
                            <h3 class="stat-value"><?= $totalTours ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-map stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Sắp diễn ra</p>
                            <h3 class="stat-value"><?= $upcomingTours ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-calendar-event stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đang diễn ra</p>
                            <h3 class="stat-value"><?= $ongoingTours ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-play-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đã hoàn thành</p>
                            <h3 class="stat-value"><?= $completedTours ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Tour gần đây
                        </h5>
                        <a href="<?= BASE_URL ?>?action=tours/index" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right"></i> Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentTours)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mã tour</th>
                                        <th>Tên tour</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentTours as $tour): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark fw-bold">
                                                    <?= htmlspecialchars($tour['code']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars(substr($tour['name'], 0, 30)) ?>...</strong>
                                            </td>
                                            <td>
                                                <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'upcoming' => 'info',
                                                    'ongoing' => 'warning',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusText = [
                                                    'upcoming' => 'Sắp diễn ra',
                                                    'ongoing' => 'Đang diễn ra',
                                                    'completed' => 'Đã hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ];
                                                ?>
                                                <span class="badge badge-<?= $statusClass[$tour['status']] ?? 'secondary' ?>">
                                                    <?= $statusText[$tour['status']] ?? $tour['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" 
                                                   class="btn btn-sm btn-primary" title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Chưa có tour nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar phải -->
        <div class="col-lg-4">
            <!-- Card HDV -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-people"></i> Hướng dẫn viên
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-5 fw-bold" style="color: #667eea;">
                        <?= $totalGuides ?? 0 ?>
                    </h1>
                    <p class="text-muted mb-3">HDV đang hoạt động</p>
                    <a href="<?= BASE_URL ?>?action=guides/index" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right"></i> Quản lý HDV
                    </a>
                </div>
            </div>

            <!-- Thao tác nhanh -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>?action=tours/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tạo tour mới
                        </a>
                        <a href="<?= BASE_URL ?>?action=guides/create" class="btn btn-outline-primary">
                            <i class="bi bi-person-plus"></i> Thêm HDV mới
                        </a>
                        <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-outline-info">
                            <i class="bi bi-calendar-check"></i> Quản lý Booking
                        </a>
                        <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-success">
                            <i class="bi bi-graph-up"></i> Xem báo cáo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

