<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-plus-circle"></i> Tạo Booking mới</h2>
        <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-secondary">
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
            <h5 class="mb-0">Thông tin Booking</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tour <span class="text-danger">*</span></label>
                        <select name="tour_id" class="form-select" required>
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?= $tour['id'] ?>">
                                    <?= htmlspecialchars($tour['name']) ?> (<?= htmlspecialchars($tour['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ngày đặt tour</label>
                        <input type="date" name="booking_date" class="form-control" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="customer_phone" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="customer_email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số lượng khách</label>
                        <input type="number" name="number_of_guests" class="form-control" 
                               value="1" min="1" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="customer_address" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tổng tiền (VNĐ)</label>
                        <input type="number" name="total_amount" class="form-control" 
                               value="0" min="0" step="1000" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Số tiền đặt cọc (VNĐ)</label>
                        <input type="number" name="deposit_amount" class="form-control" 
                               value="0" min="0" step="1000">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Loại booking</label>
                        <input type="text" class="form-control" value="Tự động (Lẻ/Đoàn)" disabled>
                        <small class="text-muted">Tự động dựa trên số lượng khách</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Yêu cầu đặc biệt</label>
                        <textarea name="special_requests" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Tạo booking
                        </button>
                        <a href="<?= BASE_URL ?>?action=bookings/index" class="btn btn-secondary">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

