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
                    <a href="<?= BASE_URL ?>?action=guides/index" class="guide-stat-item" title="Danh sách hướng dẫn viên">
                        <div class="guide-stat-icon guide-stat-icon-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_guides'] ?></h3>
                            <small class="guide-stat-label">Tổng số HDV</small>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>?action=daily-logs/index" class="guide-stat-item" title="Nhật ký hành trình">
                        <div class="guide-stat-icon guide-stat-icon-success">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_daily_logs'] ?></h3>
                            <small class="guide-stat-label">Nhật ký hành trình</small>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>?action=incidents/index" class="guide-stat-item" title="Sự cố đã báo">
                        <div class="guide-stat-icon guide-stat-icon-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['total_incidents'] ?></h3>
                            <small class="guide-stat-label">Sự cố đã báo</small>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>?action=feedbacks/admin" class="guide-stat-item" title="Phản hồi đánh giá">
                        <div class="guide-stat-icon guide-stat-icon-star">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="guide-stat-content">
                            <h3 class="guide-stat-value"><?= $guideStats['avg_rating'] ?></h3>
                            <small class="guide-stat-label">Điểm đánh giá TB</small>
                        </div>
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

<style>
/* ===========================
   BASE STYLES
   =========================== */
.reports-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 2rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* ===========================
   HEADER
   =========================== */
.reports-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    animation: fadeInDown 0.6s ease-out;
}

.reports-title {
    color: #0f172a;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    letter-spacing: -0.5px;
}

.reports-title i {
    color: #3b82f6;
    font-size: 1.75rem;
}

.reports-subtitle {
    color: #64748b;
    font-size: 0.9375rem;
    margin: 0;
    font-weight: 500;
}

.reports-date-badge {
    background: white;
    color: #334155;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.reports-date-badge:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* ===========================
   FILTER CARD
   =========================== */
.filter-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out 0.1s both;
}

.filter-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.filter-card-body {
    padding: 1.75rem;
}

.filter-form {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 1.25rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group-large {
    grid-column: span 1;
}

.filter-label {
    color: #334155;
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0;
}

.filter-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    font-weight: 500;
    color: #1e293b;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.btn-filter-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-filter-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.btn-filter-reset {
    background: white;
    color: #64748b;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-filter-reset:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #334155;
    transform: rotate(180deg);
}

/* ===========================
   STATS GRID
   =========================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.75rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, currentColor, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card-revenue::before { color: #3b82f6; }
.stat-card-cost::before { color: #ec4899; }
.stat-card-profit::before { color: <?= $profit >= 0 ? '#10b981' : '#ef4444'; ?>; }
.stat-card-booking::before { color: #0ea5e9; }

.stat-card-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.stat-card-info {
    flex: 1;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    color: #0f172a;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    letter-spacing: -1px;
}

.stat-icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.stat-icon-wrapper i {
    font-size: 1.75rem;
}

.stat-icon-revenue {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #3b82f6;
}

.stat-icon-cost {
    background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
    color: #ec4899;
}

.stat-icon-profit-positive {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.stat-icon-profit-negative {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #ef4444;
}

.stat-icon-booking {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #0ea5e9;
}

.stat-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}

.stat-footer small {
    color: #64748b;
    font-size: 0.8125rem;
    font-weight: 500;
}

.stat-arrow-up {
    color: #10b981;
    font-size: 1rem;
}

.stat-arrow-down {
    color: #ef4444;
    font-size: 1rem;
}

.stat-info {
    color: #64748b;
    font-size: 1rem;
}

/* ===========================
   GUIDE STATS
   =========================== */
.guide-stats-section {
    margin-bottom: 2rem;
    animation: fadeInUp 0.6s ease-out 0.3s both;
}

.guide-stats-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.guide-stats-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.guide-stats-header {
    margin-bottom: 1.5rem;
}

.guide-stats-title {
    color: #0f172a;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.guide-stats-title i {
    color: #3b82f6;
    font-size: 1.5rem;
}

.guide-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
}

.guide-stat-item {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 14px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.guide-stat-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
}

.guide-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.guide-stat-icon i {
    font-size: 1.5rem;
}

.guide-stat-icon-primary {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #3b82f6;
}

.guide-stat-icon-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.guide-stat-icon-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #f59e0b;
}

.guide-stat-icon-star {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #f59e0b;
}

.guide-stat-content {
    flex: 1;
}

.guide-stat-value {
    color: #0f172a;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.guide-stat-label {
    color: #64748b;
    font-size: 0.8125rem;
    font-weight: 500;
}

/* ===========================
   CHARTS SECTION
   =========================== */
.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

.chart-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    overflow: hidden;
}

.chart-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.chart-card-large {
    min-height: 400px;
}

.chart-card-header {
    padding: 1.75rem 1.75rem 0;
}

.chart-title {
    color: #0f172a;
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chart-title i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.chart-card-body {
    padding: 1.75rem;
    height: calc(100% - 80px);
}

.chart-card-body canvas {
    max-height: 350px;
}

/* ===========================
   BOOKING STATS
   =========================== */
.booking-stats-card {
    background: white;
    border-radius: 16px;
    padding: 1.75rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.booking-stats-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.booking-stats-header {
    margin-bottom: 1.5rem;
}

.booking-stats-title {
    color: #0f172a;
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.booking-stats-title i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.booking-stats-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.booking-stat-item {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.booking-stat-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.booking-stat-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.booking-stat-icon-wrapper {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.booking-stat-item:hover .booking-stat-icon-wrapper {
    transform: scale(1.1);
}

.booking-stat-pending .booking-stat-icon-wrapper {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #f59e0b;
}

.booking-stat-deposited .booking-stat-icon-wrapper,
.booking-stat-confirmed .booking-stat-icon-wrapper {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #3b82f6;
}

.booking-stat-completed .booking-stat-icon-wrapper {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.booking-stat-cancelled .booking-stat-icon-wrapper {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #ef4444;
}

.booking-stat-info {
    flex: 1;
}

.booking-stat-name {
    color: #0f172a;
    font-size: 0.9375rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.booking-stat-desc {
    color: #64748b;
    font-size: 0.8125rem;
    font-weight: 500;
}

.booking-stat-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.875rem;
    min-width: 48px;
    text-align: center;
}

.booking-badge-pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #f59e0b;
}

.booking-badge-deposited,
.booking-badge-confirmed {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #3b82f6;
}

.booking-badge-completed {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.booking-badge-cancelled {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #ef4444;
}

/* ===========================
   MONTHLY STATS
   =========================== */
.monthly-stats-section {
    animation: fadeInUp 0.6s ease-out 0.5s both;
}

.monthly-stats-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    overflow: hidden;
}

.monthly-stats-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.monthly-stats-header {
    padding: 1.75rem 1.75rem 0;
}

.monthly-stats-title {
    color: #0f172a;
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.monthly-stats-title i {
    color: #10b981;
    font-size: 1.25rem;
}

.monthly-stats-body {
    padding: 1.75rem;
}

.table-wrapper {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.stats-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.stats-table th {
    color: #64748b;
    font-weight: 700;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.25rem;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
}

.stats-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f5f9;
}

.stats-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: scale(1.01);
}

.stats-table tbody tr:last-child {
    border-bottom: none;
}

.stats-table td {
    padding: 1.25rem;
    color: #0f172a;
    font-weight: 600;
}

.month-name {
    font-size: 0.9375rem;
}

.table-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.875rem;
    display: inline-block;
}

.table-badge-primary {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #3b82f6;
}

.table-badge-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.progress-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.progress-bar-wrapper {
    flex: 1;
    height: 10px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    border-radius: 10px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.progress-bar-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.progress-percentage {
    color: #64748b;
    font-size: 0.8125rem;
    font-weight: 600;
    min-width: 45px;
    text-align: right;
}

/* ===========================
   EMPTY STATE
   =========================== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
    display: block;
}

.empty-state p {
    color: #64748b;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

/* ===========================
   ANIMATIONS
   =========================== */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===========================
   RESPONSIVE
   =========================== */
@media (max-width: 1200px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .chart-card-large {
        min-height: 350px;
    }
}

@media (max-width: 768px) {
    .reports-container {
        padding: 1.25rem;
    }
    
    .reports-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .reports-title {
        font-size: 1.5rem;
    }
    
    .filter-form {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        width: 100%;
    }
    
    .btn-filter-primary {
        flex: 1;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    
    .guide-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 1.75rem;
    }
    
    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
    }
    
    .stat-icon-wrapper i {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .reports-container {
        padding: 1rem;
    }
    
    .reports-title {
        font-size: 1.25rem;
    }
    
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .chart-card-body {
        padding: 1.25rem;
    }
    
    .booking-stat-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .booking-stat-badge {
        align-self: flex-end;
    }
}
</style>
