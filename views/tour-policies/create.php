<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Thêm chính sách</h2>
        <a href="<?= BASE_URL ?>?action=tour-policies/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
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
            <h5 class="mb-0">Thông tin chính sách</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Loại chính sách <span class="text-danger">*</span></label>
                        <select name="policy_type" class="form-select" required>
                            <option value="booking">Chính sách đặt tour</option>
                            <option value="cancellation">Chính sách hủy tour</option>
                            <option value="refund">Chính sách hoàn tiền</option>
                            <option value="reschedule">Chính sách đổi lịch</option>
                            <option value="terms">Điều khoản</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Độ ưu tiên</label>
                        <input type="number" name="priority" class="form-control" value="0" min="0">
                        <small class="text-muted">Số càng cao, hiển thị càng trước</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="8" required></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Tạo chính sách
                        </button>
                        <a href="<?= BASE_URL ?>?action=tour-policies/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

