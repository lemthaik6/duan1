<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-graph-up"></i> Báo cáo & Thống kê</h2>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="reports/index">
                <div class="col-md-4">
                    <label class="form-label">Năm</label>
                    <select name="year" class="form-select">
                        <?php 
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tour</label>
                    <select name="tour_id" class="form-select">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tour['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng quan tài chính -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Tổng Doanh thu</h6>
                    <h3 class="text-primary mb-0"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Tổng Chi phí</h6>
                    <h3 class="text-danger mb-0"><?= number_format($totalCost, 0, ',', '.') ?> đ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-<?= $profit >= 0 ? 'success' : 'warning' ?>">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Lợi nhuận</h6>
                    <h3 class="text-<?= $profit >= 0 ? 'success' : 'warning' ?> mb-0">
                        <?= number_format($profit, 0, ',', '.') ?> đ
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thống kê booking -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê Booking</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Chờ xác nhận</td>
                                    <td class="text-end"><span class="badge bg-warning"><?= $bookingStats['pending'] ?></span></td>
                                </tr>
                                <tr>
                                    <td>Đã cọc</td>
                                    <td class="text-end"><span class="badge bg-info"><?= $bookingStats['deposited'] ?></span></td>
                                </tr>
                                <tr>
                                    <td>Đã xác nhận</td>
                                    <td class="text-end"><span class="badge bg-primary"><?= $bookingStats['confirmed'] ?></span></td>
                                </tr>
                                <tr>
                                    <td>Hoàn tất</td>
                                    <td class="text-end"><span class="badge bg-success"><?= $bookingStats['completed'] ?></span></td>
                                </tr>
                                <tr>
                                    <td>Đã hủy</td>
                                    <td class="text-end"><span class="badge bg-danger"><?= $bookingStats['cancelled'] ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo tháng -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê tour theo tháng (Năm <?= $year ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Tháng</th>
                                        <th>Tổng số tour</th>
                                        <th>Tour đã hoàn thành</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $stat): ?>
                                        <tr>
                                            <td>Tháng <?= $stat['month'] ?></td>
                                            <td><span class="badge bg-primary"><?= $stat['total_tours'] ?></span></td>
                                            <td><span class="badge bg-success"><?= $stat['completed_tours'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Không có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

