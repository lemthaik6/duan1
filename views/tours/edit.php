<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa Tour</h2>
        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
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
                                <label class="form-label">Mã tour</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($tour['code']) ?>" disabled>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Tên tour <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?= htmlspecialchars($tour['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                                <?= $tour['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cấp độ ưu tiên</label>
                                <select name="priority_level" class="form-select">
                                    <option value="low" <?= $tour['priority_level'] === 'low' ? 'selected' : '' ?>>Thấp</option>
                                    <option value="medium" <?= $tour['priority_level'] === 'medium' ? 'selected' : '' ?>>Trung bình</option>
                                    <option value="high" <?= $tour['priority_level'] === 'high' ? 'selected' : '' ?>>Cao</option>
                                    <option value="urgent" <?= $tour['priority_level'] === 'urgent' ? 'selected' : '' ?>>Khẩn cấp</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" 
                                       value="<?= $tour['start_date'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" 
                                       value="<?= $tour['end_date'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá nội bộ (VNĐ)</label>
                                <input type="number" class="form-control" name="internal_price" 
                                       value="<?= $tour['internal_price'] ?>" min="0" step="1000">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="upcoming" <?= $tour['status'] === 'upcoming' ? 'selected' : '' ?>>Sắp diễn ra</option>
                                    <option value="ongoing" <?= $tour['status'] === 'ongoing' ? 'selected' : '' ?>>Đang diễn ra</option>
                                    <option value="completed" <?= $tour['status'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                    <option value="cancelled" <?= $tour['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Lịch trình tổng quan</label>
                                <textarea class="form-control" name="schedule" rows="4"><?= htmlspecialchars($tour['schedule'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Cập nhật
                                </button>
                                <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
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

