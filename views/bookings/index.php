<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-calendar-check"></i> Quản lý Booking</h2>
        <a href="<?= BASE_URL ?>?action=bookings/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo booking mới
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="bookings/index">
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="deposited" <?= ($_GET['status'] ?? '') === 'deposited' ? 'selected' : '' ?>>Đã cọc</option>
                        <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Hoàn tất</option>
                        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tour</label>
                    <select name="tour_id" class="form-select">
                        <option value="">Tất cả</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" <?= ($_GET['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tour['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách booking -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách Booking (<?= count($bookings) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($bookings)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã booking</th>
                                <th>Tour</th>
                                <th>Khách hàng</th>
                                <th>Số khách</th>
                                <th>Tổng tiền</th>
                                <th>Đã cọc</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($booking['booking_code']) ?></strong></td>
                                    <td><?= htmlspecialchars($booking['tour_name']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($booking['customer_name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($booking['customer_phone']) ?></small>
                                    </td>
                                    <td><?= $booking['number_of_guests'] ?></td>
                                    <td><strong><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                    <td><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> đ</td>
                                    <td>
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
                                        <span class="badge bg-<?= $statusClass[$booking['status']] ?? 'secondary' ?>">
                                            <?= $statusText[$booking['status']] ?? $booking['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=bookings/view&id=<?= $booking['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Không có booking nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

