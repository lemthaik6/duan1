<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Thêm Xe mới</h2>
        <a href="<?= BASE_URL ?>?action=vehicles/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin xe</h5>
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
                            <label class="form-label">Biển số <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="license_plate" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại xe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vehicle_type" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" name="capacity" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tên tài xế</label>
                            <input type="text" class="form-control" name="driver_name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Điện thoại tài xế</label>
                            <input type="text" class="form-control" name="driver_phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="available" selected>Sẵn sàng</option>
                                <option value="in_use">Đang sử dụng</option>
                                <option value="maintenance">Bảo trì</option>
                                <option value="unavailable">Không sẵn sàng</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Tạo xe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

