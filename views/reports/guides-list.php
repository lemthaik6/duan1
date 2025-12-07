<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people-fill"></i> Danh sách Hướng dẫn viên
            </h2>
            <p class="text-muted mb-0 mt-1">Quản lý và theo dõi hoạt động các hướng dẫn viên</p>
        </div>
        <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng HDV</p>
                            <h3 class="stat-value"><?= count($guides) ?></h3>
                        </div>
                        <i class="bi bi-people-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng nhật ký</p>
                            <h3 class="stat-value"><?= array_sum(array_column($guides, 'total_daily_logs')) ?></h3>
                        </div>
                        <i class="bi bi-journal-text stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng sự cố</p>
                            <h3 class="stat-value"><?= array_sum(array_column($guides, 'total_incidents')) ?></h3>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đánh giá TB</p>
                            <h3 class="stat-value">
                                <?php 
                                $avgRatings = array_filter(array_column($guides, 'avg_rating'));
                                echo count($avgRatings) > 0 ? round(array_sum($avgRatings) / count($avgRatings), 1) : 0;
                                ?>
                            </h3>
                        </div>
                        <i class="bi bi-star-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách chi tiết</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($guides)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-header-bg">
                                <th style="width: 25%">Họ tên</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 15%">SĐT</th>
                                <th class="text-center" style="width: 10%">Tours</th>
                                <th class="text-center" style="width: 10%">Nhật ký</th>
                                <th class="text-center" style="width: 10%">Sự cố</th>
                                <th class="text-center" style="width: 10%">Đánh giá TB</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guides as $guide): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($guide['full_name']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($guide['email']) ?></small>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $guide['tour_count'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $guide['total_daily_logs'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark"><?= $guide['total_incidents'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-star-fill" style="color: #ffc107;"></i>
                                            <strong><?= $guide['avg_rating'] ?></strong>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Không có dữ liệu hướng dẫn viên</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-card.bg-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%) !important;
}

.stat-card.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.stat-card.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.stat-card.bg-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
}

.stat-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-value {
    color: white;
    font-size: 2rem;
    font-weight: 700;
}

.stat-icon {
    color: rgba(255, 255, 255, 0.8);
    font-size: 2rem;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
    padding: 1.5rem;
    border-radius: 12px 12px 0 0;
}

.card-header h5 {
    color: #0f172a;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-header-bg {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.table-header-bg th {
    color: #64748b;
    font-weight: 700;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.25rem;
    border: none;
}

.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: scale(1.01);
}

.table tbody td {
    padding: 1.25rem;
    color: #0f172a;
    vertical-align: middle;
}

.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.8rem;
}

.badge.bg-primary {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
    color: #1e40af;
}

.badge.bg-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%) !important;
    color: #065f46;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%) !important;
    color: #0c4a6e;
}
</style>
