<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-exclamation-triangle-fill"></i> Sự cố
            </h2>
            <p class="text-muted mb-0 mt-1">Danh sách các sự cố được báo cáo trong các tour</p>
        </div>
        <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="reports/incidents">
                <div class="col-md-5">
                    <label class="form-label">Tour</label>
                    <select name="tour_id" class="form-select">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" <?= (!empty($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tour['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" <?= (!empty($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Đang xử lý</option>
                        <option value="resolved" <?= (!empty($_GET['status']) && $_GET['status'] == 'resolved') ? 'selected' : '' ?>>Đã giải quyết</option>
                        <option value="closed" <?= (!empty($_GET['status']) && $_GET['status'] == 'closed') ? 'selected' : '' ?>>Đã đóng</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/incidents" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng sự cố</p>
                            <h3 class="stat-value"><?= count($incidents) ?></h3>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đang xử lý</p>
                            <h3 class="stat-value">
                                <?= count(array_filter($incidents, function($i) { return $i['status'] === 'pending'; })) ?>
                            </h3>
                        </div>
                        <i class="bi bi-hourglass-split stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đã giải quyết</p>
                            <h3 class="stat-value">
                                <?= count(array_filter($incidents, function($i) { return $i['status'] === 'resolved'; })) ?>
                            </h3>
                        </div>
                        <i class="bi bi-check-circle-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Mức độ cao</p>
                            <h3 class="stat-value">
                                <?= count(array_filter($incidents, function($i) { return $i['severity'] === 'high' || $i['severity'] === 'critical'; })) ?>
                            </h3>
                        </div>
                        <i class="bi bi-exclamation-circle-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách chi tiết</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($incidents)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-header-bg">
                                <th style="width: 12%">Ngày báo cáo</th>
                                <th style="width: 20%">Tiêu đề</th>
                                <th style="width: 20%">Tour</th>
                                <th style="width: 10%">Mức độ</th>
                                <th style="width: 12%">Trạng thái</th>
                                <th style="width: 13%">Báo cáo bởi</th>
                                <th style="width: 13%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $incident): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar"></i> 
                                        <strong><?= date('d/m/Y', strtotime($incident['incident_date'] ?? $incident['created_at'])) ?></strong>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($incident['title'] ?? '') ?></strong>
                                    </td>
                                    <td>
                                        <small><strong><?= htmlspecialchars($incident['tour_name']) ?></strong></small><br>
                                        <small class="text-muted">Mã: <?= htmlspecialchars($incident['tour_code']) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        $severityColors = [
                                            'low' => 'primary',
                                            'medium' => 'warning',
                                            'high' => 'danger',
                                            'critical' => 'dark'
                                        ];
                                        $severityText = [
                                            'low' => 'Thấp',
                                            'medium' => 'Vừa',
                                            'high' => 'Cao',
                                            'critical' => 'Rất cao'
                                        ];
                                        $severity = $incident['severity'] ?? 'low';
                                        ?>
                                        <span class="badge bg-<?= $severityColors[$severity] ?? 'primary' ?>">
                                            <?= $severityText[$severity] ?? 'N/A' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'resolved' => 'success',
                                            'closed' => 'secondary'
                                        ];
                                        $statusText = [
                                            'pending' => 'Đang xử lý',
                                            'resolved' => 'Đã giải quyết',
                                            'closed' => 'Đã đóng'
                                        ];
                                        $status = $incident['status'] ?? 'pending';
                                        ?>
                                        <span class="badge bg-<?= $statusColors[$status] ?? 'primary' ?>">
                                            <?= $statusText[$status] ?? 'N/A' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($incident['reported_by_name'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#incidentModal" onclick="viewIncident(<?= htmlspecialchars(json_encode($incident)) ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Xóa" data-bs-toggle="modal" data-bs-target="#deleteIncidentModal" onclick="confirmDeleteIncident(<?= $incident['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Không có sự cố nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="incidentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Chi tiết Sự cố</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="incidentContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa Sự cố -->
<div class="modal fade" id="deleteIncidentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sự cố này? <strong>Hành động này không thể hoàn tác.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteIncidentForm" method="POST" action="<?= BASE_URL ?>?action=reports/delete-incident" style="display: inline;">
                    <input type="hidden" id="deleteIncidentId" name="incident_id">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </div>
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

.stat-card.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

.stat-card.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.stat-card.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
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

.btn-outline-primary {
    color: #3b82f6;
    border-color: #3b82f6;
}

.btn-outline-primary:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}
</style>

<script>
function viewIncident(incident) {
    const severityText = {
        'low': 'Thấp',
        'medium': 'Vừa',
        'high': 'Cao',
        'critical': 'Rất cao'
    };
    
    const statusText = {
        'pending': 'Đang xử lý',
        'resolved': 'Đã giải quyết',
        'closed': 'Đã đóng'
    };
    
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Tiêu đề:</strong><br>
                ${incident.title || 'N/A'}
            </div>
            <div class="col-md-6">
                <strong>Ngày báo cáo:</strong><br>
                ${new Date(incident.incident_date || incident.created_at).toLocaleDateString('vi-VN')}
            </div>
            <div class="col-md-6">
                <strong>Mức độ:</strong><br>
                <span class="badge bg-warning">${severityText[incident.severity] || 'N/A'}</span>
            </div>
            <div class="col-md-6">
                <strong>Trạng thái:</strong><br>
                <span class="badge bg-info">${statusText[incident.status] || 'N/A'}</span>
            </div>
            <div class="col-12">
                <strong>Mô tả:</strong><br>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                    ${incident.description || 'Không có mô tả'}
                </div>
            </div>
            <div class="col-md-6">
                <strong>Báo cáo bởi:</strong><br>
                ${incident.reported_by_name || 'N/A'}
            </div>
            <div class="col-md-6">
                <strong>Giải quyết bởi:</strong><br>
                ${incident.resolved_by_name || 'Chưa có'}
            </div>
        </div>
    `;
    document.getElementById('incidentContent').innerHTML = content;
}

function confirmDeleteIncident(incidentId) {
    document.getElementById('deleteIncidentId').value = incidentId;
}
</script>
