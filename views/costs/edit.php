<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa chi phí</h2>
        <a href="<?= BASE_URL ?>?action=costs/my-costs&tour_id=<?= $cost['tour_id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tour: <?= htmlspecialchars($tour['name']) ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

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
                            <label class="form-label">Loại chi phí <span class="text-danger">*</span></label>
                            <select name="cost_category_id" class="form-select" required>
                                <option value="">Chọn loại chi phí</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                        <?= ($cost['cost_category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số tiền (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" 
                                   value="<?= htmlspecialchars($cost['amount']) ?>" 
                                   min="0" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày</label>
                            <input type="date" class="form-control" name="date" 
                                   value="<?= htmlspecialchars($cost['date']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($cost['description'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL ?>?action=costs/my-costs&tour_id=<?= $cost['tour_id'] ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
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

