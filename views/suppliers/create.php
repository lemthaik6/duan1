<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Thêm nhà cung cấp mới</h2>
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
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Loại <span class="text-danger">*</span></label>
                        <select name="supplier_type" class="form-select" required>
                            <option value="hotel">Khách sạn</option>
                            <option value="restaurant">Nhà hàng</option>
                            <option value="transport">Vận chuyển</option>
                            <option value="ticket">Vé</option>
                            <option value="visa">Visa</option>
                            <option value="insurance">Bảo hiểm</option>
                            <option value="other" selected>Khác</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Người liên hệ</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" selected>Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                            <option value="blacklisted">Đã chặn</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Năng lực cung cấp</label>
                        <input type="text" name="capacity" class="form-control" 
                               placeholder="Ví dụ: 50 phòng, 2 xe 45 chỗ...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Đánh giá (0-5)</label>
                        <input type="number" name="rating" class="form-control" 
                               min="0" max="5" step="0.1">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Tạo nhà cung cấp
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

