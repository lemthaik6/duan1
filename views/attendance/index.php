<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person-check"></i> Điểm danh khách</h2>
        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin tour -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= htmlspecialchars($tour['name']) ?></h5>
                    <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="" class="row g-3">
                        <input type="hidden" name="action" value="attendance/index">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <div class="col-md-8">
                            <label class="form-label">Chọn ngày</label>
                            <input type="date" name="date" class="form-control" value="<?= $date ?>" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Thống kê -->
    <?php if ($stats): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-primary"><?= $stats['total'] ?? 0 ?></h5>
                        <small class="text-muted">Tổng điểm danh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-success"><?= $stats['present'] ?? 0 ?></h5>
                        <small class="text-muted">Có mặt</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-danger"><?= $stats['absent'] ?? 0 ?></h5>
                        <small class="text-muted">Vắng mặt</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-warning"><?= $stats['late'] ?? 0 ?></h5>
                        <small class="text-muted">Đi muộn</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Danh sách khách và điểm danh -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách khách (<?= count($customers) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($customers)): ?>
                <div class="table-responsive table-responsive-no-scroll">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ và tên</th>
                                <th>Điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Giờ check-in</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $index => $customer): ?>
                                <?php 
                                $att = $attendanceMap[$customer['id']] ?? null;
                                $status = $att['status'] ?? 'not_checked';
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($customer['full_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'late' => 'warning',
                                            'not_checked' => 'secondary'
                                        ];
                                        $statusText = [
                                            'present' => 'Có mặt',
                                            'absent' => 'Vắng mặt',
                                            'late' => 'Đi muộn',
                                            'not_checked' => 'Chưa điểm danh'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?>">
                                            <?= $statusText[$status] ?? $status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($att && $att['check_in_time']): ?>
                                            <?= date('H:i', strtotime($att['check_in_time'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($att && $att['notes']): ?>
                                            <small><?= htmlspecialchars($att['notes']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#attendanceModal<?= $customer['id'] ?>">
                                            <i class="bi bi-check-circle"></i> Điểm danh
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có khách trong tour này</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Attendance Modals Container - Must be completely outside card and container for proper z-index stacking -->
<?php if (!empty($customers)): ?>
    <?php foreach ($customers as $customer): ?>
        <?php 
        $att = $attendanceMap[$customer['id']] ?? null;
        $status = $att['status'] ?? 'not_checked';
        ?>
        <div class="modal fade" id="attendanceModal<?= $customer['id'] ?>" tabindex="-1" aria-labelledby="attendanceModalLabel<?= $customer['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="">
                        <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="attendanceModalLabel<?= $customer['id'] ?>">Điểm danh: <?= htmlspecialchars($customer['full_name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="present" <?= $status === 'present' ? 'selected' : '' ?>>Có mặt</option>
                                    <option value="absent" <?= $status === 'absent' ? 'selected' : '' ?>>Vắng mặt</option>
                                    <option value="late" <?= $status === 'late' ? 'selected' : '' ?>>Đi muộn</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giờ check-in</label>
                                <input type="datetime-local" name="check_in_time" class="form-control" 
                                       value="<?= $att && $att['check_in_time'] ? date('Y-m-d\TH:i', strtotime($att['check_in_time'])) : date('Y-m-d\TH:i') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="2"><?= $att ? htmlspecialchars($att['notes'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

