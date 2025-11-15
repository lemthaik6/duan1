<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-exclamation-triangle"></i> Báo cáo Sự cố</h2>
        <a href="<?= BASE_URL ?>?action=incidents/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
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
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại sự cố</label>
                            <select name="incident_type" class="form-select">
                                <option value="weather">Thời tiết</option>
                                <option value="traffic">Giao thông</option>
                                <option value="delay">Trễ giờ</option>
                                <option value="customer_issue">Vấn đề khách hàng</option>
                                <option value="lost_item">Thất lạc, mất đồ</option>
                                <option value="other" selected>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mức độ nghiêm trọng</label>
                            <select name="severity" class="form-select">
                                <option value="low">Thấp</option>
                                <option value="medium" selected>Trung bình</option>
                                <option value="high">Cao</option>
                                <option value="critical">Nghiêm trọng</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày xảy ra</label>
                            <input type="date" class="form-control" name="incident_date" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Gửi báo cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

