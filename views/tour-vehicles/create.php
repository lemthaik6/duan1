<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Thêm Xe vào Tour: <?= htmlspecialchars($tour['name']) ?></h2>
        <a href="<?= BASE_URL ?>?action=tour-vehicles/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
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
                    <h5 class="mb-0">Thông tin phân công xe</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Chọn xe <span class="text-danger">*</span></label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="">-- Chọn xe --</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?= $vehicle['id'] ?>">
                                        <?= htmlspecialchars($vehicle['license_plate']) ?> 
                                        (<?= htmlspecialchars($vehicle['vehicle_type']) ?> - <?= $vehicle['capacity'] ?> chỗ)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mục đích sử dụng</label>
                            <input type="text" name="usage_purpose" class="form-control" 
                                   placeholder="VD: Đưa đón khách, vận chuyển hàng hoá...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="<?= $tour['start_date'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="<?= $tour['end_date'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Ghi chú thêm về phân công xe..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Thêm xe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
