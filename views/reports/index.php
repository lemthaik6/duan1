<div class="reports-container">
    <!-- Header -->
    <div class="reports-header">
        <div>
            <h2 class="reports-title">
                <i class="bi bi-graph-up-arrow"></i> Báo cáo & Thống kê
            </h2>
            <p class="reports-subtitle">Tổng quan hiệu suất và doanh thu</p>
        </div>
        <div class="reports-date-badge">
            <i class="bi bi-calendar-event"></i> <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-card">
        <div class="filter-card-body">
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="action" value="reports/index">
                <div class="filter-group">
                    <label class="filter-label">Năm</label>
                    <select name="year" class="filter-select">
                        <?php 
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="filter-group filter-group-large">
                    <label class="filter-label">Tour</label>
                    <select name="tour_id" class="filter-select">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tour['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter-primary">
                        <i class="bi bi-search"></i> Áp dụng
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/index" class="btn-filter-reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng quan tài chính -->
    <div class="stats-grid">
        <div class="stat-card stat-card-revenue">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <div class="stat-card-info">
                        <p class="stat-label">Tổng Doanh thu</p>
                        <h3 class="stat-value"><?= number_format($totalRevenue / 1000000, 1) ?>M</h3>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-revenue">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-up-circle-fill stat-arrow-up"></i>
                    <small>Doanh thu từ booking</small>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-cost">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <div class="stat-card-info">
                        <p class="stat-label">Tổng Chi phí</p>
                        <h3 class="stat-value"><?= number_format($totalCost / 1000000, 1) ?>M</h3>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-cost">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-down-circle-fill stat-arrow-down"></i>
                    <small>Chi phí vận hành</small>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-profit">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <div class="stat-card-info">
                        <p class="stat-label">Lợi nhuận</p>
                        <h3 class="stat-value"><?= number_format($profit / 1000000, 1) ?>M</h3>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-profit-<?= $profit >= 0 ? 'positive' : 'negative' ?>">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <?php if (isset($profitMargin)): ?>
                        <i class="bi bi-percent stat-arrow-<?= $profitMargin >= 0 ? 'up' : 'down' ?>"></i>
                        <small>Tỷ suất: <?= number_format($profitMargin, 1) ?>%</small>
                    <?php else: ?>
                        <i class="bi bi-info-circle stat-info"></i>
                        <small>Doanh thu - Chi phí</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-booking">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <div class="stat-card-info">
                        <p class="stat-label">Tổng Booking</p>
                        <h3 class="stat-value"><?= $bookingStats['total'] ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon-wrapper stat-icon-booking">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-check-circle-fill stat-arrow-up"></i>
                    <small>Đã xác nhận: <?= $bookingStats['confirmed'] ?? 0 ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê từ HDV -->
    <?php if (isset($guideStats)): ?>
        <div class="guide-stats-section">
            <div class="guide-stats-card">
                <div class="guide-stats-header">
                    <h5 class="guide-stats-title">
                        <i class="bi bi-person-badge-fill"></i> Thống kê từ Hướng dẫn viên
                    </h5>
                </div>
                <div class="guide-stats-grid">
                    <a href="<?= BASE_URL ?>?action=reports/guides-list" class="guide-stat-item guide-stat-item-link">
                        <div class="guide-stat-icon guide-stat-icon-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_guides'] ?></h3>
                            <small class="guide-stat-label">Tổng số HDV</small>
                        </div>
                        <i class="bi bi-chevron-right guide-stat-arrow"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?action=reports/daily-logs" class="guide-stat-item guide-stat-item-link">
                        <div class="guide-stat-icon guide-stat-icon-success">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_daily_logs'] ?></h3>
                            <small class="guide-stat-label">Nhật ký hành trình</small>
                        </div>
                        <i class="bi bi-chevron-right guide-stat-arrow"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?action=reports/incidents" class="guide-stat-item guide-stat-item-link">
                        <div class="guide-stat-icon guide-stat-icon-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_incidents'] ?></h3>
                            <small class="guide-stat-label">Sự cố đã báo</small>
                        </div>
                        <i class="bi bi-chevron-right guide-stat-arrow"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?action=reports/feedbacks" class="guide-stat-item guide-stat-item-link">
                        <div class="guide-stat-icon guide-stat-icon-star">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['avg_rating'] ?></h3>
                            <small class="guide-stat-label">Điểm đánh giá TB</small>
                        </div>
                        <i class="bi bi-chevron-right guide-stat-arrow"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Biểu đồ và Booking Stats -->
    <div class="charts-section">
        <?php if (isset($monthlyRevenue)): ?>
        <div class="chart-card chart-card-large">
            <div class="chart-card-header">
                <h5 class="chart-title">
                    <i class="bi bi-bar-chart-fill"></i> Doanh thu theo tháng (Năm <?= $year ?>)
                </h5>
            </div>
            <div class="chart-card-body">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
        <?php else: ?>
        <div class="chart-card chart-card-large">
            <div class="chart-card-header">
                <h5 class="chart-title">
                    <i class="bi bi-bar-chart-fill"></i> Doanh thu theo tháng (Năm <?= $year ?>)
                </h5>
            </div>
            <div class="chart-card-body">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>Không có dữ liệu doanh thu</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="booking-stats-card">
            <div class="booking-stats-header">
                <h5 class="booking-stats-title">
                    <i class="bi bi-calendar-check-fill"></i> Thống kê Booking
                </h5>
            </div>
            <div class="booking-stats-list">
                <div class="booking-stat-item booking-stat-pending">
                    <div class="booking-stat-content">
                        <div class="booking-stat-icon-wrapper">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="booking-stat-info">
                            <h6 class="booking-stat-name">Chờ xác nhận</h6>
                            <small class="booking-stat-desc">Đang chờ xử lý</small>
                        </div>
                    </div>
                    <span class="booking-stat-badge booking-badge-pending"><?= $bookingStats['pending'] ?></span>
                </div>
                <div class="booking-stat-item booking-stat-deposited">
                    <div class="booking-stat-content">
                        <div class="booking-stat-icon-wrapper">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="booking-stat-info">
                            <h6 class="booking-stat-name">Đã cọc</h6>
                            <small class="booking-stat-desc">Đã thanh toán cọc</small>
                        </div>
                    </div>
                    <span class="booking-stat-badge booking-badge-deposited"><?= $bookingStats['deposited'] ?></span>
                </div>
                <div class="booking-stat-item booking-stat-confirmed">
                    <div class="booking-stat-content">
                        <div class="booking-stat-icon-wrapper">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="booking-stat-info">
                            <h6 class="booking-stat-name">Đã xác nhận</h6>
                            <small class="booking-stat-desc">Đã xác nhận tour</small>
                        </div>
                    </div>
                    <span class="booking-stat-badge booking-badge-confirmed"><?= $bookingStats['confirmed'] ?></span>
                </div>
                <div class="booking-stat-item booking-stat-completed">
                    <div class="booking-stat-content">
                        <div class="booking-stat-icon-wrapper">
                            <i class="bi bi-check2-all"></i>
                        </div>
                        <div class="booking-stat-info">
                            <h6 class="booking-stat-name">Hoàn tất</h6>
                            <small class="booking-stat-desc">Tour đã hoàn thành</small>
                        </div>
                    </div>
                    <span class="booking-stat-badge booking-badge-completed"><?= $bookingStats['completed'] ?></span>
                </div>
                <div class="booking-stat-item booking-stat-cancelled">
                    <div class="booking-stat-content">
                        <div class="booking-stat-icon-wrapper">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="booking-stat-info">
                            <h6 class="booking-stat-name">Đã hủy</h6>
                            <small class="booking-stat-desc">Booking đã hủy</small>
                        </div>
                    </div>
                    <span class="booking-stat-badge booking-badge-cancelled"><?= $bookingStats['cancelled'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo tháng -->
    <div class="monthly-stats-section">
        <div class="monthly-stats-card">
            <div class="monthly-stats-header">
                <h5 class="monthly-stats-title">
                    <i class="bi bi-calendar-month-fill"></i> Thống kê tour theo tháng (Năm <?= $year ?>)
                </h5>
            </div>
            <div class="monthly-stats-body">
                <?php if (!empty($stats)): ?>
                    <div class="table-wrapper">
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Tháng</th>
                                    <th class="text-center">Tổng</th>
                                    <th class="text-center">Hoàn thành</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $stat): 
                                    $completionRate = $stat['total_tours'] > 0 
                                        ? round(($stat['completed_tours'] / $stat['total_tours']) * 100, 1) 
                                        : 0;
                                ?>
                                    <tr>
                                        <td class="month-name">Tháng <?= $stat['month'] ?></td>
                                        <td class="text-center">
                                            <span class="table-badge table-badge-primary"><?= $stat['total_tours'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="table-badge table-badge-success"><?= $stat['completed_tours'] ?></span>
                                        </td>
                                        <td>
                                            <div class="progress-wrapper">
                                                <div class="progress-bar-wrapper">
                                                    <div class="progress-bar-fill" style="width: <?= $completionRate ?>%"></div>
                                                </div>
                                                <small class="progress-percentage"><?= $completionRate ?>%</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Không có dữ liệu</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($monthlyRevenue)): ?>
    const ctx = document.getElementById('monthlyRevenueChart');
    if (ctx) {
        const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                           'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        const revenueData = [
            <?php 
            for ($m = 1; $m <= 12; $m++): 
                $revenue = $monthlyRevenue[$m] ?? 0;
                echo number_format($revenue / 1000000, 2);
                if ($m < 12) echo ',';
            endfor; 
            ?>
        ];
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Doanh thu (triệu VNĐ)',
                    data: revenueData,
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 10,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        padding: 16,
                        titleFont: {
                            size: 14,
                            weight: '600',
                            family: "'Inter', sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            weight: '500',
                            family: "'Inter', sans-serif"
                        },
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' triệu VNĐ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + 'M';
                            },
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: '500',
                                family: "'Inter', sans-serif"
                            },
                            padding: 10
                        },
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false,
                            lineWidth: 1
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11,
                                weight: '500',
                                family: "'Inter', sans-serif"
                            },
                            padding: 12
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    <?php endif; ?>
});
</script>
