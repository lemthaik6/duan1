<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-star-fill"></i> Báo cáo Đánh giá
            </h2>
            <p class="text-muted mb-0 mt-1">Danh sách các đánh giá từ khách hàng cho hướng dẫn viên</p>
        </div>
        <a href="<?= BASE_URL ?>?action=reports/index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="reports/feedbacks">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label class="form-label">Điểm đánh giá</label>
                    <select name="rating" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="5" <?= (!empty($_GET['rating']) && $_GET['rating'] == '5') ? 'selected' : '' ?>>⭐ 5 sao</option>
                        <option value="4" <?= (!empty($_GET['rating']) && $_GET['rating'] == '4') ? 'selected' : '' ?>>⭐ 4 sao</option>
                        <option value="3" <?= (!empty($_GET['rating']) && $_GET['rating'] == '3') ? 'selected' : '' ?>>⭐ 3 sao</option>
                        <option value="2" <?= (!empty($_GET['rating']) && $_GET['rating'] == '2') ? 'selected' : '' ?>>⭐ 2 sao</option>
                        <option value="1" <?= (!empty($_GET['rating']) && $_GET['rating'] == '1') ? 'selected' : '' ?>>⭐ 1 sao</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                    <a href="<?= BASE_URL ?>?action=reports/feedbacks" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Tổng đánh giá</p>
                            <h3 class="stat-value"><?= $stats['total'] ?></h3>
                        </div>
                        <i class="bi bi-chat-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Đánh giá trung bình</p>
                            <h3 class="stat-value">
                                <i class="bi bi-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i>
                                <?= $stats['avg_rating'] ?>
                            </h3>
                        </div>
                        <i class="bi bi-star-fill stat-icon"></i>
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

    <!-- Rating Distribution -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Phân phối đánh giá</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <?php 
                $ratingLabels = [5 => '5 sao', 4 => '4 sao', 3 => '3 sao', 2 => '2 sao', 1 => '1 sao'];
                $ratingColors = [5 => '#10b981', 4 => '#3b82f6', 3 => '#f59e0b', 2 => '#ef4444', 1 => '#dc2626'];
                $totalFeedbacks = $stats['total'] > 0 ? $stats['total'] : 1;
                
                foreach ([5, 4, 3, 2, 1] as $rating): 
                    $count = $stats['rating_' . $rating];
                    $percentage = ($count / $totalFeedbacks) * 100;
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="rating-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong><?= $ratingLabels[$rating] ?></strong>
                            <span class="badge bg-secondary"><?= $count ?></span>
                        </div>
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar" style="width: <?= $percentage ?>%; background-color: <?= $ratingColors[$rating] ?>;">
                                <span style="color: white; font-size: 0.75rem; font-weight: 600;">
                                    <?= round($percentage, 1) ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách chi tiết</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($feedbacks)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-header-bg">
                                <th style="width: 15%">Ngày đánh giá</th>
                                <th style="width: 20%">Hướng dẫn viên</th>
                                <th style="width: 20%">Tour</th>
                                <th class="text-center" style="width: 10%">Điểm</th>
                                <th style="width: 25%">Bình luận</th>
                                <th style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedbacks as $feedback): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar"></i> 
                                        <small><?= date('d/m/Y', strtotime($feedback['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($feedback['rated_by_name'] ?? 'Khách hàng') ?></small>
                                    </td>
                                    <td>
                                        <small><strong><?= htmlspecialchars($feedback['tour_name']) ?></strong></small><br>
                                        <small class="text-muted">Mã: <?= htmlspecialchars($feedback['tour_code']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div style="color: #ffc107; font-weight: bold;">
                                            <?php 
                                            $rating = (int)($feedback['rating'] ?? 0);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '⭐' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars(substr($feedback['comment'] ?? '', 0, 40)) ?>...</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#feedbackModal" onclick="viewFeedback(<?= htmlspecialchars(json_encode($feedback)) ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Xóa" data-bs-toggle="modal" data-bs-target="#deleteFeedbackModal" onclick="confirmDeleteFeedback(<?= $feedback['id'] ?>)">
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
                    <p class="text-muted mt-3">Không có đánh giá nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star-fill"></i> Chi tiết Đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="feedbackContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa Đánh giá -->
<div class="modal fade" id="deleteFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa đánh giá này? <strong>Hành động này không thể hoàn tác.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteFeedbackForm" method="POST" action="<?= BASE_URL ?>?action=reports/delete-feedback" style="display: inline;">
                    <input type="hidden" id="deleteFeedbackId" name="feedback_id">
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

.stat-card.bg-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
}

.stat-card.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
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

.rating-item {
    padding: 1rem;
    border-radius: 8px;
    background: #f8fafc;
}

.progress {
    background: #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
    display: flex;
    align-items: center;
    justify-content: center;
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
    padding: 0.4rem 0.8rem;
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
function viewFeedback(feedback) {
    const rating = parseInt(feedback.rating) || 0;
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += i <= rating ? '⭐' : '☆';
    }
    
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Điểm đánh giá:</strong><br>
                <span style="color: #ffc107; font-size: 1.5rem;">
                    ${stars} (${feedback.rating}/5)
                </span>
            </div>
            <div class="col-md-6">
                <strong>Ngày đánh giá:</strong><br>
                ${new Date(feedback.created_at).toLocaleDateString('vi-VN')}
            </div>
            <div class="col-md-6">
                <strong>Hướng dẫn viên:</strong><br>
                ${feedback.rated_by_name || 'Khách hàng'}
            </div>
            <div class="col-md-6">
                <strong>Tour:</strong><br>
                ${feedback.tour_name || 'N/A'}
            </div>
            <div class="col-12">
                <strong>Bình luận:</strong><br>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                    ${feedback.comment || 'Không có bình luận'}
                </div>
            </div>
        </div>
    `;
    document.getElementById('feedbackContent').innerHTML = content;
}

function confirmDeleteFeedback(feedbackId) {
    document.getElementById('deleteFeedbackId').value = feedbackId;
}
</script>
