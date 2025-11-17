<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết phản hồi</h2>
        <div>
            <?php if (isGuide() && $feedback['rated_by'] == getCurrentUser()['id']): ?>
                <a href="<?= BASE_URL ?>?action=feedbacks/edit&id=<?= $feedback['id'] ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>?action=feedbacks/<?= isAdmin() ? 'admin' : 'index' ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin phản hồi</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <strong>Tour:</strong><br>
                            <span class="text-primary"><?= htmlspecialchars($feedback['tour_name']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Loại phản hồi:</strong><br>
                            <?php
                            $feedbackTypes = [
                                'tour_evaluation' => 'Đánh giá tour',
                                'guide_evaluation' => 'Đánh giá HDV',
                                'customer_feedback' => 'Phản hồi khách hàng'
                            ];
                            ?>
                            <span class="badge bg-info">
                                <?= $feedbackTypes[$feedback['feedback_type']] ?? $feedback['feedback_type'] ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Người gửi:</strong><br>
                            <?= htmlspecialchars($feedback['rated_by_name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày gửi:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?>
                        </div>
                        <?php if ($feedback['rating']): ?>
                            <div class="col-12">
                                <strong>Đánh giá:</strong><br>
                                <?php
                                $rating = (int)$feedback['rating'];
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $rating):
                                        echo '<i class="bi bi-star-fill text-warning fs-4"></i>';
                                    else:
                                        echo '<i class="bi bi-star text-muted fs-4"></i>';
                                    endif;
                                endfor;
                                ?>
                                <span class="ms-2 fs-5"><?= $rating ?>/5</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <div>
                        <strong>Nội dung:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($feedback['content'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

