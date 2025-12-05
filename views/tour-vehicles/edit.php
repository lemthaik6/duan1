<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa Phân công Xe</h2>
        <a href="<?= BASE_URL ?>?action=tour-vehicles/index&tour_id=<?= $assignment['tour_id'] ?>" class="btn btn-secondary">
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

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Xe: <?= htmlspecialchars($assignment['license_plate']) ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label"><strong>Loại xe:</strong> <?= htmlspecialchars($assignment['vehicle_type']) ?></label>
                            <div class="form-control-plaintext">
                                Sức chứa: <strong><?= $assignment['capacity'] ?> chỗ</strong> | 
                                Tài xế: <strong><?= htmlspecialchars($assignment['driver_name'] ?? 'N/A') ?></strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mục đích sử dụng</label>
                            <input type="text" name="usage_purpose" class="form-control" 
                                   value="<?= htmlspecialchars($assignment['usage_purpose'] ?? '') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="<?= $assignment['start_date'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="<?= $assignment['end_date'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($assignment['notes'] ?? '') ?></textarea>
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
    </div>
</div>
