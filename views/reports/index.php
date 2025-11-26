<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow text-primary"></i> Báo cáo & Thống kê</h2>
            <p class="text-muted mb-0">Tổng quan hiệu suất và doanh thu</p>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-primary"><i class="bi bi-funnel-fill"></i> Bộ lọc dữ liệu</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="reports/index">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Năm</label>
                    <select name="year" class="form-select form-select-lg">
                        <?php 
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tour</label>
                    <select name="tour_id" class="form-select form-select-lg">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tour['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="bi bi-search"></i> Áp dụng
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng quan tài chính -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-white-50 mb-2 fw-normal">Tổng Doanh thu</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($totalRevenue / 1000000, 1) ?>M</h2>
                            <small class="text-white-50"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-up-circle me-2"></i>
                        <small>Doanh thu từ booking</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-white-50 mb-2 fw-normal">Tổng Chi phí</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($totalCost / 1000000, 1) ?>M</h2>
                            <small class="text-white-50"><?= number_format($totalCost, 0, ',', '.') ?> đ</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-cash-coin fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-down-circle me-2"></i>
                        <small>Chi phí vận hành</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, <?= $profit >= 0 ? '#11998e 0%, #38ef7d 100%' : '#ee0979 0%, #ff6a00 100%' ?>);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-white-50 mb-2 fw-normal">Lợi nhuận</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($profit / 1000000, 1) ?>M</h2>
                            <small class="text-white-50"><?= number_format($profit, 0, ',', '.') ?> đ</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <?php if (isset($profitMargin)): ?>
                            <i class="bi bi-percent me-2"></i>
                            <small>Tỷ suất: <?= number_format($profitMargin, 1) ?>%</small>
                        <?php else: ?>
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Doanh thu - Chi phí</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-white-50 mb-2 fw-normal">Tổng Booking</h6>
                            <h2 class="mb-0 fw-bold"><?= $bookingStats['total'] ?? 0 ?></h2>
                            <small class="text-white-50">Tổng số đơn đặt</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle me-2"></i>
                        <small>Đã xác nhận: <?= $bookingStats['confirmed'] ?? 0 ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê từ HDV -->
    <?php if (isset($guideStats)): ?>
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-person-badge-fill text-primary"></i> Thống kê từ Hướng dẫn viên
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 h-100">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-people-fill fs-3 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <a href="<?= BASE_URL ?>?action=guides/index" class="text-decoration-none text-dark">
                                            <h3 class="mb-0 fw-bold"><?= $guideStats['total_guides'] ?></h3>
                                            <small class="text-muted">Tổng số HDV</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 h-100">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-journal-text fs-3 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <?php
                                            $dlUrl = BASE_URL . '?action=daily-logs/index';
                                            if (!empty($_GET['tour_id'])) {
                                                $dlUrl .= '&tour_id=' . intval($_GET['tour_id']);
                                            }
                                        ?>
                                        <a href="<?= $dlUrl ?>" class="text-decoration-none text-dark">
                                            <h3 class="mb-0 fw-bold"><?= $guideStats['total_daily_logs'] ?></h3>
                                            <small class="text-muted">Nhật ký hành trình</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 h-100">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <?php
                                            $incUrl = BASE_URL . '?action=incidents/index';
                                            if (!empty($_GET['tour_id'])) {
                                                $incUrl .= '&tour_id=' . intval($_GET['tour_id']);
                                            }
                                        ?>
                                        <a href="<?= $incUrl ?>" class="text-decoration-none text-dark">
                                            <h3 class="mb-0 fw-bold"><?= $guideStats['total_incidents'] ?></h3>
                                            <small class="text-muted">Sự cố đã báo</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 h-100">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                            <i class="bi bi-star-fill fs-3 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <?php
                                            $fbUrl = BASE_URL . '?action=feedbacks/admin';
                                            if (!empty($_GET['tour_id'])) {
                                                $fbUrl .= '&tour_id=' . intval($_GET['tour_id']);
                                            }
                                        ?>
                                        <a href="<?= $fbUrl ?>" class="text-decoration-none text-dark">
                                            <h3 class="mb-0 fw-bold"><?= $guideStats['avg_rating'] ?></h3>
                                            <small class="text-muted">Điểm đánh giá TB</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Thống kê booking -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-calendar-check-fill text-primary"></i> Thống kê Booking
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Chờ xác nhận</h6>
                                    <small class="text-muted">Đang chờ xử lý</small>
                                </div>
                            </div>
                            <span class="badge bg-warning rounded-pill fs-6 px-3 py-2"><?= $bookingStats['pending'] ?></span>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-cash-coin text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Đã cọc</h6>
                                    <small class="text-muted">Đã thanh toán cọc</small>
                                </div>
                            </div>
                            <span class="badge bg-info rounded-pill fs-6 px-3 py-2"><?= $bookingStats['deposited'] ?></span>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Đã xác nhận</h6>
                                    <small class="text-muted">Đã xác nhận tour</small>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill fs-6 px-3 py-2"><?= $bookingStats['confirmed'] ?></span>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check2-all text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Hoàn tất</h6>
                                    <small class="text-muted">Tour đã hoàn thành</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill fs-6 px-3 py-2"><?= $bookingStats['completed'] ?></span>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Đã hủy</h6>
                                    <small class="text-muted">Booking đã hủy</small>
                                </div>
                            </div>
                            <span class="badge bg-danger rounded-pill fs-6 px-3 py-2"><?= $bookingStats['cancelled'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo tháng -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-calendar-month-fill text-success"></i> Thống kê tour theo tháng (Năm <?= $year ?>)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($stats)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Tháng</th>
                                        <th class="border-0 text-center">Tổng</th>
                                        <th class="border-0 text-center">Hoàn thành</th>
                                        <th class="border-0">Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $stat): 
                                        $completionRate = $stat['total_tours'] > 0 
                                            ? round(($stat['completed_tours'] / $stat['total_tours']) * 100, 1) 
                                            : 0;
                                    ?>
                                        <tr>
                                            <td class="fw-semibold">Tháng <?= $stat['month'] ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary rounded-pill"><?= $stat['total_tours'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill"><?= $stat['completed_tours'] ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: <?= $completionRate ?>%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted fw-semibold"><?= $completionRate ?>%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">Không có dữ liệu</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu theo tháng -->
    <?php if (isset($monthlyRevenue)): ?>
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-info"></i> Doanh thu theo tháng (Năm <?= $year ?>)</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Tháng</th>
                                        <th class="border-0 fw-semibold">Doanh thu</th>
                                        <th class="border-0 fw-semibold">Biểu đồ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $maxRevenue = max($monthlyRevenue);
                                    $monthNames = ['', 'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                                                  'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
                                    for ($m = 1; $m <= 12; $m++): 
                                        $revenue = $monthlyRevenue[$m] ?? 0;
                                        $percentage = $maxRevenue > 0 ? ($revenue / $maxRevenue * 100) : 0;
                                    ?>
                                        <tr>
                                            <td class="fw-semibold"><?= $monthNames[$m] ?></td>
                                            <td>
                                                <span class="fw-bold text-primary fs-6">
                                                    <?= number_format($revenue, 0, ',', '.') ?> đ
                                                </span>
                                                <?php if ($revenue > 0): ?>
                                                    <br>
                                                    <small class="text-muted"><?= number_format($revenue / 1000000, 2) ?> triệu</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 30px;">
                                                        <div class="progress-bar bg-info progress-bar-striped" 
                                                             role="progressbar" 
                                                             style="width: <?= $percentage ?>%"
                                                             aria-valuenow="<?= $percentage ?>" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            <?= $percentage > 8 ? number_format($revenue / 1000000, 1) . 'M' : '' ?>
                                                        </div>
                                                    </div>
                                                    <?php if ($percentage > 0 && $percentage <= 8): ?>
                                                        <small class="text-muted fw-semibold"><?= number_format($revenue / 1000000, 1) ?>M</small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa !important;
        border-radius: 8px;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-weight: 500;
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
</style>

