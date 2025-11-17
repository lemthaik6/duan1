<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-chat-left-text"></i> Phản hồi đánh giá của tôi</h2>
        <a href="<?= BASE_URL ?>?action=feedbacks/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Gửi phản hồi mới
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách phản hồi (<?= count($feedbacks) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($feedbacks)): ?>
                <?php
                $feedbackTypes = [
                    'tour_evaluation' => 'Đánh giá tour',
                    'guide_evaluation' => 'Đánh giá HDV',
                    'customer_feedback' => 'Phản hồi khách hàng'
                ];
                ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Loại phản hồi</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedbacks as $feedback): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($feedback['tour_code']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($feedback['tour_name']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $feedbackTypes[$feedback['feedback_type']] ?? $feedback['feedback_type'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($feedback['rating']): ?>
                                            <?php
                                            $rating = (int)$feedback['rating'];
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= $rating):
                                                    echo '<i class="bi bi-star-fill text-warning"></i>';
                                                else:
                                                    echo '<i class="bi bi-star text-muted"></i>';
                                                endif;
                                            endfor;
                                            ?>
                                            <span class="ms-1"><?= $rating ?>/5</span>
                                        <?php else: ?>
                                            <span class="text-muted">Không đánh giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(mb_substr($feedback['content'], 0, 100)) ?>
                                        <?= mb_strlen($feedback['content']) > 100 ? '...' : '' ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=feedbacks/view&id=<?= $feedback['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=feedbacks/edit&id=<?= $feedback['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=feedbacks/delete&id=<?= $feedback['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa phản hồi này?')" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">
                    Bạn chưa gửi phản hồi nào. 
                    <a href="<?= BASE_URL ?>?action=feedbacks/create">Gửi phản hồi đầu tiên</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

