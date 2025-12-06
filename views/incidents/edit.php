<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-exclamation-triangle"></i> Sửa Báo cáo Sự cố</h2>
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
                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($incident['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại sự cố</label>
                            <select name="incident_type" class="form-select">
                                <option value="weather" <?= $incident['incident_type'] === 'weather' ? 'selected' : '' ?>>Thời tiết</option>
                                <option value="traffic" <?= $incident['incident_type'] === 'traffic' ? 'selected' : '' ?>>Giao thông</option>
                                <option value="delay" <?= $incident['incident_type'] === 'delay' ? 'selected' : '' ?>>Trễ giờ</option>
                                <option value="customer_issue" <?= $incident['incident_type'] === 'customer_issue' ? 'selected' : '' ?>>Vấn đề khách hàng</option>
                                <option value="lost_item" <?= $incident['incident_type'] === 'lost_item' ? 'selected' : '' ?>>Thất lạc, mất đồ</option>
                                <option value="other" <?= $incident['incident_type'] === 'other' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mức độ nghiêm trọng</label>
                            <select name="severity" class="form-select">
                                <option value="low" <?= $incident['severity'] === 'low' ? 'selected' : '' ?>>Thấp</option>
                                <option value="medium" <?= $incident['severity'] === 'medium' ? 'selected' : '' ?>>Trung bình</option>
                                <option value="high" <?= $incident['severity'] === 'high' ? 'selected' : '' ?>>Cao</option>
                                <option value="critical" <?= $incident['severity'] === 'critical' ? 'selected' : '' ?>>Nghiêm trọng</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày xảy ra</label>
                            <input type="date" class="form-control" value="<?= $incident['incident_date'] ?>" disabled>
                            <small class="text-muted">Không thể thay đổi ngày của sự cố</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required><?= htmlspecialchars($incident['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="reported" <?= $incident['status'] === 'reported' ? 'selected' : '' ?>>Đã báo cáo</option>
                                <option value="investigating" <?= $incident['status'] === 'investigating' ? 'selected' : '' ?>>Đang điều tra</option>
                                <option value="resolved" <?= $incident['status'] === 'resolved' ? 'selected' : '' ?>>Đã xử lý</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giải pháp</label>
                            <textarea class="form-control" name="resolution" rows="4"><?= htmlspecialchars($incident['resolution'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật báo cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
