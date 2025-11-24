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
        <div class="col-md-4">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Sắp diễn ra</p>
                            <h3 class="stat-value"><?= count($upcomingTours ?? []) ?></h3>
                        </div>
                        <i class="bi bi-calendar-event stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đang diễn ra</p>
                            <h3 class="stat-value"><?= count($ongoingTours ?? []) ?></h3>
                        </div>
                        <i class="bi bi-play-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đã hoàn thành</p>
                            <h3 class="stat-value"><?= count($completedTours ?? []) ?></h3>
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
                            <i class="bi bi-calendar3"></i> Tour được phân công
                        </h5>
                        <a href="<?= BASE_URL ?>?action=tours/my-tours" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right"></i> Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($myTours)): ?>
                        <div class="list-group list-group-flush">
                            <?php 
                            $displayTours = array_slice($myTours, 0, 5);
                            if (!empty($displayTours)):
                                foreach ($displayTours as $tour):
                            ?>
                                <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" 
                                   class="list-group-item list-group-item-action px-4 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <strong><?= htmlspecialchars(substr($tour['name'], 0, 40)) ?>...</strong>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> 
                                                <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                                            </small>
                                        </div>
                                        <?php
                                        $statusClass = [
                                            'upcoming' => 'info',
                                            'ongoing' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        ?>
                                        <span class="badge badge-<?= $statusClass[$tour['status']] ?? 'secondary' ?>">
                                            <?php
                                            $statusText = [
                                                'upcoming' => 'Sắp',
                                                'ongoing' => 'Đang',
                                                'completed' => 'Xong',
                                                'cancelled' => 'Hủy'
                                            ];
                                            echo $statusText[$tour['status']] ?? $tour['status'];
                                            ?>
                                        </span>
                                    </div>
                                </a>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar2-x" style="font-size: 2.5rem; color: #ccc;"></i>
                                    <p class="text-muted mt-3">Bạn chưa được phân công tour nào</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Bạn chưa được phân công tour nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar phải -->
        <div class="col-lg-4">
            <!-- Thao tác nhanh -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>?action=daily-logs/index" class="btn btn-primary">
                            <i class="bi bi-journal-text"></i> Nhật ký hành trình
                        </a>
                        <a href="<?= BASE_URL ?>?action=costs/my-costs" class="btn btn-outline-success">
                            <i class="bi bi-cash-stack"></i> Cập nhật chi phí
                        </a>
                        <a href="<?= BASE_URL ?>?action=incidents/index" class="btn btn-outline-warning">
                            <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
                        </a>
                        <a href="<?= BASE_URL ?>?action=feedbacks/index" class="btn btn-outline-info">
                            <i class="bi bi-chat-left-text"></i> Phản hồi đánh giá
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thông tin cá nhân -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Hồ sơ của tôi
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div style="width: 80px; height: 80px; background: var(--primary-gradient); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h6 class="fw-bold"><?= htmlspecialchars(getCurrentUser()['full_name']) ?></h6>
                    <p class="text-muted small mb-3"><?= htmlspecialchars(getCurrentUser()['phone'] ?? 'N/A') ?></p>
                    <a href="<?= BASE_URL ?>?action=profile" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

