<div class="container-fluid px-4">
    <?php $tourId = $_GET['tour_id'] ?? 0; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-journal-text"></i> Nhật ký Tour</h2>
        <?php if ($tour): ?>
            <a href="<?= BASE_URL ?>?action=daily-logs/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ghi nhật ký
            </a>
        <?php endif; ?>
    </div>

    <!-- Chọn tour -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="daily-logs/index">
                <div class="col-md-8">
                    <label class="form-label">Chọn tour</label>
                    <select name="tour_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($myTours as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= $tourId == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($tour): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5><?= htmlspecialchars($tour['name']) ?></h5>
                <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Nhật ký (<?= count($logs) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-0">
                                            <i class="bi bi-calendar"></i> 
                                            <?= date('d/m/Y', strtotime($log['date'])) ?>
                                        </h6>
                                    </div>
                                    <div>
                                        <a href="<?= BASE_URL ?>?action=daily-logs/edit&id=<?= $log['id'] ?>&tour_id=<?= $tour['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>?action=daily-logs/delete" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa nhật ký này?');">
                                            <input type="hidden" name="id" value="<?= $log['id'] ?>">
                                            <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <strong>Hoạt động:</strong><br>
                                    <?= nl2br(htmlspecialchars($log['activities'])) ?>
                                </div>

                                <?php if ($log['customer_status']): ?>
                                    <div class="mb-2">
                                        <strong>Tình trạng khách:</strong><br>
                                        <?= nl2br(htmlspecialchars($log['customer_status'])) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <?php if ($log['weather']): ?>
                                        <div class="col-md-6">
                                            <strong>Thời tiết:</strong> <?= htmlspecialchars($log['weather']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($log['traffic']): ?>
                                        <div class="col-md-6">
                                            <strong>Giao thông:</strong> <?= htmlspecialchars($log['traffic']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($log['notes']): ?>
                                    <div class="mt-2">
                                        <strong>Ghi chú:</strong><br>
                                        <small class="text-muted"><?= nl2br(htmlspecialchars($log['notes'])) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Chưa có nhật ký nào</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Vui lòng chọn tour để xem nhật ký
        </div>
    <?php endif; ?>
</div>

