<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Tạo tour mới</h2>
        <a href="<?= BASE_URL ?>?action=tours/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tour</h5>
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
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Tên tour <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cấp độ ưu tiên</label>
                                <select name="priority_level" class="form-select">
                                    <option value="low" <?= (isset($_POST['priority_level']) && $_POST['priority_level'] == 'low') ? 'selected' : '' ?>>Thấp</option>
                                    <option value="medium" <?= (!isset($_POST['priority_level']) || $_POST['priority_level'] == 'medium') ? 'selected' : '' ?>>Trung bình</option>
                                    <option value="high" <?= (isset($_POST['priority_level']) && $_POST['priority_level'] == 'high') ? 'selected' : '' ?>>Cao</option>
                                    <option value="urgent" <?= (isset($_POST['priority_level']) && $_POST['priority_level'] == 'urgent') ? 'selected' : '' ?>>Khẩn cấp</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" required value="<?= $_POST['start_date'] ?? '' ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" required value="<?= $_POST['end_date'] ?? '' ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Giá nội bộ (VNĐ)</label>
                                <input type="number" class="form-control" name="internal_price" min="0" step="1000" value="<?= $_POST['internal_price'] ?? '' ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="upcoming" <?= (!isset($_POST['status']) || $_POST['status'] == 'upcoming') ? 'selected' : '' ?>>Sắp diễn ra</option>
                                    <option value="ongoing" <?= (isset($_POST['status']) && $_POST['status'] == 'ongoing') ? 'selected' : '' ?>>Đang diễn ra</option>
                                    <option value="completed" <?= (isset($_POST['status']) && $_POST['status'] == 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                                    <option value="cancelled" <?= (isset($_POST['status']) && $_POST['status'] == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Lịch trình tổng quan</label>
                                <textarea class="form-control" name="schedule" rows="4"><?= htmlspecialchars($_POST['schedule'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Tạo tour
                                </button>
                                <a href="<?= BASE_URL ?>?action=tours/index" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

