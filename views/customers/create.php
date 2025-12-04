<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person-plus"></i> Thêm khách hàng</h2>
        <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin tour -->
    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

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

    <!-- Form thêm khách hàng -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thông tin khách hàng</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>?action=customers/create&tour_id=<?= $tour['id'] ?>" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="full_name">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required 
                               placeholder="Nhập họ và tên đầy đủ" 
                               value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               placeholder="Nhập số điện thoại" 
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="Nhập email" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="id_card">CMND/CCCD</label>
                        <input type="text" id="id_card" name="id_card" class="form-control" 
                               placeholder="Nhập số CMND/CCCD" 
                               value="<?= htmlspecialchars($_POST['id_card'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="special_requests">Yêu cầu đặc biệt</label>
                    <textarea id="special_requests" name="special_requests" class="form-control" rows="3" 
                              placeholder="Ví dụ: Ăn chay, dị ứng hải sản, bệnh tim mạch, cần xe lăn..."><?= htmlspecialchars($_POST['special_requests'] ?? '') ?></textarea>
                    <small class="text-muted">Nhập các yêu cầu đặc biệt của khách (ăn chay, bệnh lý, dị ứng, v.v.)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2" 
                              placeholder="Nhập ghi chú về khách hàng (nếu có)"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Thêm khách hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

