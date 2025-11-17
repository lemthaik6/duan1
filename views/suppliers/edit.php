<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa nhà cung cấp</h2>
        <a href="<?= BASE_URL ?>?action=suppliers/index" class="btn btn-secondary">
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin nhà cung cấp</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="<?= htmlspecialchars($supplier['name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Loại <span class="text-danger">*</span></label>
                        <select name="supplier_type" class="form-select" required>
                            <option value="hotel" <?= $supplier['supplier_type'] === 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                            <option value="restaurant" <?= $supplier['supplier_type'] === 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                            <option value="transport" <?= $supplier['supplier_type'] === 'transport' ? 'selected' : '' ?>>Vận chuyển</option>
                            <option value="ticket" <?= $supplier['supplier_type'] === 'ticket' ? 'selected' : '' ?>>Vé</option>
                            <option value="visa" <?= $supplier['supplier_type'] === 'visa' ? 'selected' : '' ?>>Visa</option>
                            <option value="insurance" <?= $supplier['supplier_type'] === 'insurance' ? 'selected' : '' ?>>Bảo hiểm</option>
                            <option value="other" <?= $supplier['supplier_type'] === 'other' ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Người liên hệ</label>
                        <input type="text" name="contact_person" class="form-control" 
                               value="<?= htmlspecialchars($supplier['contact_person'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" 
                               value="<?= htmlspecialchars($supplier['phone'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($supplier['email'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $supplier['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= $supplier['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                            <option value="blacklisted" <?= $supplier['status'] === 'blacklisted' ? 'selected' : '' ?>>Đã chặn</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($supplier['address'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Năng lực cung cấp</label>
                        <input type="text" name="capacity" class="form-control" 
                               value="<?= htmlspecialchars($supplier['capacity'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Đánh giá (0-5)</label>
                        <input type="number" name="rating" class="form-control" 
                               value="<?= $supplier['rating'] ?? '' ?>" min="0" max="5" step="0.1">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($supplier['description'] ?? '') ?></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Cập nhật
                        </button>
                        <a href="<?= BASE_URL ?>?action=suppliers/index" class="btn btn-secondary">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

