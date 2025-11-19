<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil"></i> Chỉnh sửa lịch trình</h2>
        <a href="<?= BASE_URL ?>?action=itineraries/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
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

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin lịch trình</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Số ngày <span class="text-danger">*</span></label>
                            <input type="number" name="day_number" class="form-control" 
                                   value="<?= htmlspecialchars($itinerary['day_number']) ?>" min="1" required>
                            <small class="text-muted">Ngày thứ mấy trong tour (ví dụ: 1, 2, 3...)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" 
                                   value="<?= date('Y-m-d', strtotime($itinerary['date'])) ?>" required>
                            <small class="text-muted">Ngày thực tế của lịch trình</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa điểm <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" 
                                   value="<?= htmlspecialchars($itinerary['location']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hoạt động <span class="text-danger">*</span></label>
                            <textarea name="activities" class="form-control" rows="5" required><?= htmlspecialchars($itinerary['activities']) ?></textarea>
                            <small class="text-muted">Mô tả chi tiết các hoạt động trong ngày</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giờ khởi hành</label>
                            <input type="time" name="departure_time" class="form-control" 
                                   value="<?= $itinerary['departure_time'] ? date('H:i', strtotime($itinerary['departure_time'])) : '' ?>">
                            <small class="text-muted">Giờ xuất phát (nếu có)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($itinerary['notes'] ?? '') ?></textarea>
                            <small class="text-muted">Lưu ý đặc biệt, thông tin cần thiết cho HDV...</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật lịch trình
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

