<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Booking: <?= htmlspecialchars($booking['booking_code']) ?></h2>
        <div>
            <a href="<?= BASE_URL ?>?action=bookings/exportQuote&id=<?= $booking['id'] ?>" class="btn btn-info me-2" target="_blank">
                <i class="bi bi-file-earmark-text"></i> Xuất báo giá
            </a>
            <a href="<?= BASE_URL ?>?action=bookings/exportInvoice&id=<?= $booking['id'] ?>" class="btn btn-success me-2" target="_blank">
                <i class="bi bi-receipt"></i> Xuất hóa đơn
            </a>
            <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
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
            <!-- Xuất tài liệu -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Xuất tài liệu</h5>
                </div>
                <div class="card-body">
                    <a href="<?= BASE_URL ?>?action=bookings/exportQuote&id=<?= $booking['id'] ?>" 
                       class="btn btn-info w-100 mb-2" target="_blank">
                        <i class="bi bi-file-earmark-text"></i> Báo giá hợp đồng
                    </a>
                    <a href="<?= BASE_URL ?>?action=bookings/exportInvoice&id=<?= $booking['id'] ?>" 
                       class="btn btn-success w-100 mb-2" target="_blank">
                        <i class="bi bi-receipt"></i> Hóa đơn
                    </a>
                </div>
            </div>

            <!-- Xóa booking -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Thao tác nguy hiểm</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Xóa booking này sẽ xóa vĩnh viễn tất cả dữ liệu liên quan. Hành động này không thể hoàn tác!</p>
                    <a href="<?= BASE_URL ?>?action=bookings/delete&id=<?= $booking['id'] ?>" 
                       class="btn btn-danger w-100"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa booking <?= htmlspecialchars($booking['booking_code']) ?>?\n\nTất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn!\n\nHành động này không thể hoàn tác!');">
                        <i class="bi bi-trash"></i> Xóa booking
                    </a>
                </div>
            </div>

            <!-- Cập nhật trạng thái -->
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

    <!-- Thông tin từ HDV -->
    <?php if (!empty($guideInfo) && !empty($guideInfo['guide'])): ?>
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Thông tin từ Hướng dẫn viên</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Thông tin HDV -->
                            <div class="col-md-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                                        <h6 class="mt-2 mb-1"><?= htmlspecialchars($guideInfo['guide']['guide_name'] ?? 'N/A') ?></h6>
                                        <small class="text-muted">Hướng dẫn viên</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Nhật ký hành trình -->
                            <div class="col-md-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="bi bi-journal-text fs-1 text-success"></i>
                                        <h4 class="mt-2 mb-1"><?= count($guideInfo['daily_logs'] ?? []) ?></h4>
                                        <small class="text-muted">Nhật ký hành trình</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sự cố -->
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                                        <h4 class="mt-2 mb-1"><?= count($guideInfo['incidents'] ?? []) ?></h4>
                                        <small class="text-muted">Sự cố đã báo</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Phản hồi -->
                            <div class="col-md-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <i class="bi bi-chat-left-text fs-1 text-info"></i>
                                        <h4 class="mt-2 mb-1"><?= count($guideInfo['feedbacks'] ?? []) ?></h4>
                                        <small class="text-muted">Phản hồi đánh giá</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chi tiết nhật ký -->
                        <?php if (!empty($guideInfo['daily_logs'])): ?>
                            <div class="mt-4">
                                <h6><i class="bi bi-journal-text"></i> Nhật ký hành trình gần đây</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ngày</th>
                                                <th>Hoạt động</th>
                                                <th>Thời tiết</th>
                                                <th>Giao thông</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($guideInfo['daily_logs'], 0, 3) as $log): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($log['date'])) ?></td>
                                                    <td>
                                                        <small><?= htmlspecialchars(substr($log['activities'] ?? '', 0, 50)) ?>...</small>
                                                    </td>
                                                    <td><?= htmlspecialchars($log['weather'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($log['traffic'] ?? 'N/A') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Sự cố -->
                        <?php if (!empty($guideInfo['incidents'])): ?>
                            <div class="mt-4">
                                <h6><i class="bi bi-exclamation-triangle"></i> Sự cố đã báo cáo</h6>
                                <div class="list-group">
                                    <?php foreach (array_slice($guideInfo['incidents'], 0, 3) as $incident): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?= htmlspecialchars($incident['title']) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars(substr($incident['description'] ?? '', 0, 100)) ?>...</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-<?= $incident['severity'] === 'high' ? 'danger' : ($incident['severity'] === 'medium' ? 'warning' : 'info') ?>">
                                                        <?= $incident['severity'] === 'high' ? 'Nghiêm trọng' : ($incident['severity'] === 'medium' ? 'Trung bình' : 'Thấp') ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($incident['incident_date'])) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Chi phí -->
                        <?php if (!empty($guideInfo['costs'])): ?>
                            <div class="mt-4">
                                <h6><i class="bi bi-cash-stack"></i> Chi phí đã cập nhật</h6>
                                <div class="alert alert-light">
                                    <strong>Tổng chi phí:</strong> 
                                    <?= number_format(array_sum(array_column($guideInfo['costs'], 'amount')), 0, ',', '.') ?> đ
                                    <br>
                                    <small class="text-muted"><?= count($guideInfo['costs']) ?> mục chi phí</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

