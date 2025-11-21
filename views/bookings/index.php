<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-calendar-check"></i> Quản lý Booking</h2>
        <a href="<?= BASE_URL ?>?action=bookings/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo booking mới
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

    <!-- Thống kê nhanh -->
    <?php if (isset($quickStats)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Tổng số</h6>
                        <h4 class="text-primary mb-0"><?= $quickStats['total'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Chờ xác nhận</h6>
                        <h4 class="text-warning mb-0"><?= $quickStats['pending'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Đã cọc</h6>
                        <h4 class="text-info mb-0"><?= $quickStats['deposited'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Đã xác nhận</h6>
                        <h4 class="text-primary mb-0"><?= $quickStats['confirmed'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Hoàn tất</h6>
                        <h4 class="text-success mb-0"><?= $quickStats['completed'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-1">Đã hủy</h6>
                        <h4 class="text-danger mb-0"><?= $quickStats['cancelled'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($quickStats['total_revenue'] > 0): ?>
            <div class="alert alert-info mb-4">
                <i class="bi bi-cash-stack"></i> 
                <strong>Tổng doanh thu (đã lọc):</strong> 
                <?= number_format($quickStats['total_revenue'], 0, ',', '.') ?> đ
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Bộ lọc tìm kiếm</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="bookings/index">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="booking_date_from" class="form-control" 
                           value="<?= $_GET['booking_date_from'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="booking_date_to" class="form-control" 
                           value="<?= $_GET['booking_date_to'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Mã, tên khách, SĐT..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                    <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
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
                                    <td>
                                        <?php 
                                        $bookingDate = strtotime($booking['booking_date']);
                                        echo $bookingDate ? date('d/m/Y', $bookingDate) : 'N/A';
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= BASE_URL ?>?action=bookings/view&id=<?= $booking['id'] ?>" 
                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $booking['tour_id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Xem tour">
                                                <i class="bi bi-map"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>?action=bookings/delete&id=<?= $booking['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="Xóa"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa booking <?= htmlspecialchars($booking['booking_code'], ENT_QUOTES) ?>? Hành động này không thể hoàn tác!');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
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

