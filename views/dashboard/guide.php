<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-speedometer2"></i> Dashboard - Hướng dẫn viên
        </h2>
        <span class="text-muted">Xin chào, <?= htmlspecialchars(getCurrentUser()['full_name']) ?></span>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Sắp diễn ra</h6>
                            <h2 class="mb-0"><?= count($upcomingTours ?? []) ?></h2>
                        </div>
                        <i class="bi bi-calendar-event fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Đang diễn ra</h6>
                            <h2 class="mb-0"><?= count($ongoingTours ?? []) ?></h2>
                        </div>
                        <i class="bi bi-play-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Đã hoàn thành</h6>
                            <h2 class="mb-0"><?= count($completedTours ?? []) ?></h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Tour được phân công</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($myTours)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                    <?php foreach ($myTours as $tour): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($tour['code']) ?></strong></td>
                                            <td><?= htmlspecialchars($tour['name']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($tour['start_date'])) ?></td>
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
                                                <span class="badge bg-<?= $statusClass[$tour['status']] ?? 'secondary' ?>">
                                                    <?= $statusText[$tour['status']] ?? $tour['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Bạn chưa được phân công tour nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>?action=tours/my-tours" class="btn btn-primary">
                            <i class="bi bi-calendar-check"></i> Xem lịch trình tour
                        </a>
                        <a href="<?= BASE_URL ?>?action=daily-logs/index" class="btn btn-outline-primary">
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
        </div>
    </div>
</div>

