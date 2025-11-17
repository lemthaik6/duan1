<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa phản hồi</h2>
        <a href="<?= BASE_URL ?>?action=feedbacks/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Nội dung phản hồi</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Loại phản hồi</label>
                    <input type="text" class="form-control" 
                           value="<?php
                           $feedbackTypes = [
                               'tour_evaluation' => 'Đánh giá tour',
                               'guide_evaluation' => 'Đánh giá HDV',
                               'customer_feedback' => 'Phản hồi khách hàng'
                           ];
                           echo $feedbackTypes[$feedback['feedback_type']] ?? $feedback['feedback_type'];
                           ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Đánh giá (1-5 sao)</label>
                    <select name="rating" class="form-select">
                        <option value="" <?= !$feedback['rating'] ? 'selected' : '' ?>>Không đánh giá</option>
                        <option value="5" <?= $feedback['rating'] == 5 ? 'selected' : '' ?>>5 sao - Rất tốt</option>
                        <option value="4" <?= $feedback['rating'] == 4 ? 'selected' : '' ?>>4 sao - Tốt</option>
                        <option value="3" <?= $feedback['rating'] == 3 ? 'selected' : '' ?>>3 sao - Bình thường</option>
                        <option value="2" <?= $feedback['rating'] == 2 ? 'selected' : '' ?>>2 sao - Kém</option>
                        <option value="1" <?= $feedback['rating'] == 1 ? 'selected' : '' ?>>1 sao - Rất kém</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($feedback['content']) ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

