<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-person-check"></i> Phân công HDV cho tour</h2>
        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin tour</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Mã tour:</strong> <?= htmlspecialchars($tour['code']) ?></p>
                    <p class="mb-1"><strong>Tên tour:</strong> <?= htmlspecialchars($tour['name']) ?></p>
                    <p class="mb-0"><strong>Ngày bắt đầu:</strong> <?= date('d/m/Y', strtotime($tour['start_date'])) ?></p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Phân công hướng dẫn viên</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Chọn hướng dẫn viên <span class="text-danger">*</span></label>
                            <select name="guide_id" class="form-select" required>
                                <option value="">-- Chọn HDV --</option>
                                <?php foreach ($guides as $guide): ?>
                                    <?php
                                    // Kiểm tra xem HDV đã được phân công chưa
                                    $isAssigned = false;
                                    foreach ($assignments as $assignment) {
                                        if ($assignment['guide_id'] == $guide['id']) {
                                            $isAssigned = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    <option value="<?= $guide['id'] ?>" <?= $isAssigned ? 'disabled' : '' ?>>
                                        <?= htmlspecialchars($guide['full_name']) ?>
                                        <?php if ($guide['email']): ?>
                                            (<?= htmlspecialchars($guide['email']) ?>)
                                        <?php endif; ?>
                                        <?= $isAssigned ? ' - Đã được phân công' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($guides)): ?>
                                <small class="text-muted">Chưa có hướng dẫn viên nào. <a href="<?= BASE_URL ?>?action=guides/create">Tạo HDV mới</a></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Phân công HDV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách HDV đã được phân công -->
            <?php if (!empty($assignments)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-check"></i> HDV đã được phân công (<?= count($assignments) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Họ và tên</th>
                                        <th>Email</th>
                                        <th>Điện thoại</th>
                                        <th>Ngày phân công</th>
                                        <th>Ghi chú</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assignments as $assignment): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($assignment['guide_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($assignment['guide_email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($assignment['guide_phone'] ?? 'N/A') ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($assignment['assigned_at'])) ?></td>
                                            <td><?= htmlspecialchars($assignment['notes'] ?? '') ?></td>
                                            <td>
                                                <form method="POST" action="<?= BASE_URL ?>?action=guides/removeAssignment" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy phân công này?');">
                                                    <input type="hidden" name="id" value="<?= $assignment['id'] ?>">
                                                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Hủy
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

