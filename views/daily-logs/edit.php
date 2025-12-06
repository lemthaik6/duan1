<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-journal-plus"></i> Sửa nhật ký</h2>
        <a href="<?= BASE_URL ?>?action=daily-logs/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tour: <?= htmlspecialchars($tour['name']) ?></h5>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Ngày</label>
                            <input type="date" class="form-control" value="<?= $log['date'] ?>" disabled>
                            <small class="text-muted">Không thể thay đổi ngày của nhật ký</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hoạt động trong ngày <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="activities" rows="5" required><?= htmlspecialchars($log['activities']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tình trạng khách</label>
                            <textarea class="form-control" name="customer_status" rows="3"><?= htmlspecialchars($log['customer_status'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thời tiết</label>
                                <input type="text" class="form-control" name="weather" placeholder="VD: Nắng đẹp, mưa nhẹ..." value="<?= htmlspecialchars($log['weather'] ?? '') ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tình trạng giao thông</label>
                                <input type="text" class="form-control" name="traffic" placeholder="VD: Thông suốt, tắc đường..." value="<?= htmlspecialchars($log['traffic'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="notes" rows="3"><?= htmlspecialchars($log['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật nhật ký
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
