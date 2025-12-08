<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-journal-text"></i> Nhật ký Hành trình
            </h2>
            <p class="text-muted mb-0 mt-1">Danh sách tất cả nhật ký hành trình của hướng dẫn viên</p>
        </div>
        <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="reports/daily-logs">
                <div class="col-md-6">
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
                <div class="col-md-6 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/daily-logs" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng nhật ký</p>
                            <h3 class="stat-value"><?= count($dailyLogs) ?></h3>
                        </div>
                        <i class="bi bi-journal-text stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">HDV liên quan</p>
                            <h3 class="stat-value">
                                <?= count(array_unique(array_column($dailyLogs, 'guide_id'))) ?>
                            </h3>
                        </div>
                        <i class="bi bi-people-fill stat-icon"></i>
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
            <?php if (!empty($dailyLogs)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-header-bg">
                                <th style="width: 15%">Ngày</th>
                                <th style="width: 25%">Hướng dẫn viên</th>
                                <th style="width: 25%">Tour</th>
                                <th style="width: 20%">Nội dung</th>
                                <th style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dailyLogs as $log): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar"></i> 
                                        <strong><?= date('d/m/Y', strtotime($log['date'])) ?></strong>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($log['guide_name'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted">Tour <?= $log['tour_id'] ?></small>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars(substr($log['content'] ?? '', 0, 50)) ?>...</small>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#logModal" onclick="viewLog(<?= htmlspecialchars(json_encode($log)) ?>)">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Xóa" data-bs-toggle="modal" data-bs-target="#deleteLogModal" onclick="confirmDeleteLog(<?= $log['id'] ?>)">
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
                    <p class="text-muted mt-3">Không có dữ liệu nhật ký hành trình</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="logModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-text"></i> Chi tiết Nhật ký</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa Nhật ký -->
<div class="modal fade" id="deleteLogModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa nhật ký này? <strong>Hành động này không thể hoàn tác.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteLogForm" method="POST" action="<?= BASE_URL ?>?action=reports/delete-log" style="display: inline;">
                    <input type="hidden" id="deleteLogId" name="log_id">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function viewLog(log) {
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Ngày:</strong><br>
                ${new Date(log.date).toLocaleDateString('vi-VN')}
            </div>
            <div class="col-md-6">
                <strong>Hướng dẫn viên:</strong><br>
                ${log.guide_name || 'N/A'}
            </div>
            <div class="col-12">
                <strong>Nội dung:</strong><br>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                    ${log.content || 'Không có nội dung'}
                </div>
            </div>
            ${log.notes ? `
            <div class="col-12">
                <strong>Ghi chú:</strong><br>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                    ${log.notes}
                </div>
            </div>
            ` : ''}
        </div>
    `;
    document.getElementById('logContent').innerHTML = content;
}

function confirmDeleteLog(logId) {
    document.getElementById('deleteLogId').value = logId;
}
</script>
