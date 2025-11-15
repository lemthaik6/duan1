<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person"></i> Chi tiết Hướng dẫn viên</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=guides/edit&id=<?= $guide['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=guides/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thông tin chính -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong><br>
                            <?= htmlspecialchars($guide['full_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Tên đăng nhập:</strong><br>
                            <?= htmlspecialchars($guide['username']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Điện thoại:</strong><br>
                            <?= htmlspecialchars($guide['phone'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <span class="badge bg-<?= $guide['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= $guide['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số tour đã đi:</strong><br>
                            <span class="badge bg-info fs-6"><?= $totalTours ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tour đã được phân công -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Tour đã được phân công (<?= count($tours) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tours)): ?>
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
                                    <?php foreach ($tours as $tour): ?>
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
                        <p class="text-muted text-center py-4">Chưa được phân công tour nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

