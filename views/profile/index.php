<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person"></i> Thông tin cá nhân
            </h2>
            <p class="text-muted mb-0 mt-1">Quản lý thông tin tài khoản của bạn</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check"></i> Thông tin tài khoản
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Avatar -->
                    <div class="text-center mb-5">
                        <div style="width: 120px; height: 120px; background: var(--primary-gradient); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                            <i class="bi bi-person" style="font-size: 3rem; color: white;"></i>
                        </div>
                    </div>

                    <!-- Thông tin -->
                    <div class="row mb-4">
                        <div class="col-6 text-center">
                            <p class="text-muted small mb-1">Họ và tên</p>
                            <h6 class="fw-bold"><?= htmlspecialchars($user['full_name']) ?></h6>
                        </div>
                        <div class="col-6 text-center">
                            <p class="text-muted small mb-1">Tên đăng nhập</p>
                            <h6 class="fw-bold"><?= htmlspecialchars($user['username']) ?></h6>
                        </div>
                    </div>

                    <!-- Chi tiết -->
                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? 'N/A') ?>" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-telephone"></i> Số điện thoại</label>
                        <input type="tel" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? 'N/A') ?>" readonly>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label"><i class="bi bi-shield-lock"></i> Vai trò</label>
                            <div>
                                <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>" style="font-size: 0.9rem; padding: 10px 15px;">
                                    <i class="bi bi-<?= $user['role'] === 'admin' ? 'lock-fill' : 'person-fill' ?>"></i>
                                    <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Hướng dẫn viên' ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label"><i class="bi bi-circle-fill"></i> Trạng thái</label>
                            <div>
                                <span class="badge badge-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>" style="font-size: 0.9rem; padding: 10px 15px;">
                                    <i class="bi bi-<?= $user['status'] === 'active' ? 'check-circle-fill' : 'x-circle-fill' ?>"></i>
                                    <?= $user['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Thêm thông tin bổ sung nếu cần -->
                    <div class="d-grid gap-2 mt-5">
                        <a href="<?= BASE_URL ?>?action=dashboard" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Quay lại Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thêm card hướng dẫn nếu cần -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Thông tin hữu ích
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span class="ms-2">Thông tin tài khoản được cập nhật tự động</span>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-shield-check text-info"></i>
                            <span class="ms-2">Tài khoản của bạn được bảo vệ</span>
                        </li>
                        <li>
                            <i class="bi bi-question-circle text-warning"></i>
                            <span class="ms-2">Liên hệ quản trị viên nếu cần hỗ trợ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

