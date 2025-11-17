<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-chat-left-text"></i> Quản lý Phản hồi đánh giá</h2>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="feedbacks/admin">
                <div class="col-md-6">
                    <label class="form-label">Lọc theo tour</label>
                    <select name="tour_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($_GET['tour_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <a href="<?= BASE_URL ?>?action=feedbacks/admin" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

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
                                <th>Người gửi</th>
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
                                    <td><?= htmlspecialchars($feedback['rated_by_name']) ?></td>
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
                                        <?= htmlspecialchars(mb_substr($feedback['content'], 0, 80)) ?>
                                        <?= mb_strlen($feedback['content']) > 80 ? '...' : '' ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=feedbacks/view&id=<?= $feedback['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có phản hồi nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

