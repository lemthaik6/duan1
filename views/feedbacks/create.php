<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Gửi phản hồi đánh giá</h2>
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

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tour</h5>
                </div>
                <div class="card-body">
                    <h5><?= htmlspecialchars($tour['name']) ?></h5>
                    <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
                    <p class="mb-0">
                        <i class="bi bi-calendar"></i> 
                        <?= date('d/m/Y', strtotime($tour['start_date'])) ?> - 
                        <?= date('d/m/Y', strtotime($tour['end_date'])) ?>
                    </p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Nội dung phản hồi</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Loại phản hồi <span class="text-danger">*</span></label>
                            <select name="feedback_type" class="form-select" required>
                                <option value="tour_evaluation" <?= ($feedbackType ?? 'tour_evaluation') === 'tour_evaluation' ? 'selected' : '' ?>>
                                    Đánh giá tour
                                </option>
                                <option value="guide_evaluation" <?= ($feedbackType ?? '') === 'guide_evaluation' ? 'selected' : '' ?>>
                                    Đánh giá HDV
                                </option>
                                <option value="customer_feedback" <?= ($feedbackType ?? '') === 'customer_feedback' ? 'selected' : '' ?>>
                                    Phản hồi khách hàng
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đánh giá (1-5 sao)</label>
                            <select name="rating" class="form-select">
                                <option value="">Không đánh giá</option>
                                <option value="5">5 sao - Rất tốt</option>
                                <option value="4">4 sao - Tốt</option>
                                <option value="3">3 sao - Bình thường</option>
                                <option value="2">2 sao - Kém</option>
                                <option value="1">1 sao - Rất kém</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="8" required 
                                      placeholder="Nhập nội dung phản hồi chi tiết về tour, dịch vụ, nhà cung cấp..."></textarea>
                            <small class="text-muted">
                                Gợi ý: Đánh giá về chất lượng tour, khách sạn, nhà hàng, xe vận chuyển, 
                                dịch vụ nhà cung cấp, phản hồi của khách hàng...
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Gửi phản hồi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nhà cung cấp của tour</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($suppliers)): ?>
                        <?php
                        $types = [
                            'hotel' => 'Khách sạn',
                            'restaurant' => 'Nhà hàng',
                            'transport' => 'Vận chuyển',
                            'ticket' => 'Vé',
                            'visa' => 'Visa',
                            'insurance' => 'Bảo hiểm',
                            'other' => 'Khác'
                        ];
                        ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($suppliers as $supplier): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($supplier['supplier_name']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= $types[$supplier['supplier_type']] ?? $supplier['supplier_type'] ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Chưa có nhà cung cấp</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

