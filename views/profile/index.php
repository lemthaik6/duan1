<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person"></i> Thông tin cá nhân</h2>
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Họ và tên:</strong><br>
                        <?= htmlspecialchars($user['full_name']) ?>
                    </div>

                    <div class="mb-3">
                        <strong>Tên đăng nhập:</strong><br>
                        <?= htmlspecialchars($user['username']) ?>
                    </div>

                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        <?= htmlspecialchars($user['email'] ?? 'N/A') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Điện thoại:</strong><br>
                        <?= htmlspecialchars($user['phone'] ?? 'N/A') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Vai trò:</strong><br>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                            <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Hướng dẫn viên' ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Trạng thái:</strong><br>
                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                            <?= $user['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

