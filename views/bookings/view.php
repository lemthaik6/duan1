<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Booking: <?= htmlspecialchars($booking['booking_code']) ?></h2>
        <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Thông tin booking -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Mã booking:</strong><br>
                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($booking['booking_code']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Tour:</strong><br>
                            <?= htmlspecialchars($booking['tour_name']) ?> (<?= htmlspecialchars($booking['tour_code']) ?>)
                        </div>
                        <div class="col-md-6">
                            <strong>Loại:</strong><br>
                            <?= $booking['booking_type'] === 'group' ? 'Đoàn' : 'Khách lẻ' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Số lượng khách:</strong><br>
                            <?= $booking['number_of_guests'] ?> người
                        </div>
                        <div class="col-md-6">
                            <strong>Tên khách hàng:</strong><br>
                            <?= htmlspecialchars($booking['customer_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Điện thoại:</strong><br>
                            <?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt:</strong><br>
                            <?= date('d/m/Y', strtotime($booking['booking_date'])) ?>
                        </div>
                        <div class="col-md-12">
                            <strong>Địa chỉ:</strong><br>
                            <?= htmlspecialchars($booking['customer_address'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Tổng tiền:</strong><br>
                            <span class="text-primary fw-bold fs-5"><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Đã cọc:</strong><br>
                            <span class="text-info fw-bold"><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Còn lại:</strong><br>
                            <span class="text-warning fw-bold"><?= number_format($booking['remaining_amount'], 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="col-md-12">
                            <strong>Trạng thái:</strong><br>
                            <?php
                            $statusClass = [
                                'pending' => 'warning',
                                'deposited' => 'info',
                                'confirmed' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusText = [
                                'pending' => 'Chờ xác nhận',
                                'deposited' => 'Đã cọc',
                                'confirmed' => 'Đã xác nhận',
                                'completed' => 'Hoàn tất',
                                'cancelled' => 'Đã hủy'
                            ];
                            ?>
                            <span class="badge bg-<?= $statusClass[$booking['status']] ?? 'secondary' ?> fs-6">
                                <?= $statusText[$booking['status']] ?? $booking['status'] ?>
                            </span>
                        </div>
                        <?php if ($booking['special_requests']): ?>
                            <div class="col-md-12">
                                <strong>Yêu cầu đặc biệt:</strong><br>
                                <div class="alert alert-info"><?= nl2br(htmlspecialchars($booking['special_requests'])) ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if ($booking['notes']): ?>
                            <div class="col-md-12">
                                <strong>Ghi chú:</strong><br>
                                <?= nl2br(htmlspecialchars($booking['notes'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Lịch sử thay đổi trạng thái -->
            <?php if (!empty($statusHistory)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lịch sử thay đổi trạng thái</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($statusHistory as $history): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($history['changed_by_name']) ?></strong>
                                            <?php if ($history['old_status']): ?>
                                                <span class="text-muted">đã đổi từ</span>
                                                <span class="badge bg-secondary"><?= $history['old_status'] ?></span>
                                                <span class="text-muted">sang</span>
                                            <?php endif; ?>
                                            <span class="badge bg-primary"><?= $history['new_status'] ?></span>
                                        </div>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($history['created_at'])) ?></small>
                                    </div>
                                    <?php if ($history['notes']): ?>
                                        <div class="mt-2 text-muted">
                                            <small><?= htmlspecialchars($history['notes']) ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cập nhật trạng thái</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?action=bookings/updateStatus">
                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái mới</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                <option value="deposited" <?= $booking['status'] === 'deposited' ? 'selected' : '' ?>>Đã cọc</option>
                                <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Hoàn tất</option>
                                <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

