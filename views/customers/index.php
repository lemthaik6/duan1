<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-people"></i> Danh sách khách</h2>
        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
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

    <!-- Danh sách khách -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Khách trong tour (<?= count($customers) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($customers)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ và tên</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>CMND/CCCD</th>
                                <th>Yêu cầu đặc biệt</th>
                                <?php if (isGuide()): ?>
                                    <th>Thao tác</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($customer['full_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($customer['id_card'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if (isset($customer['special_requests']) && $customer['special_requests']): ?>
                                            <span class="text-info"><?= htmlspecialchars($customer['special_requests']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isGuide()): ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#specialRequestModal<?= $customer['id'] ?>">
                                                <i class="bi bi-pencil"></i> Cập nhật
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>

                                <!-- Modal cập nhật yêu cầu đặc biệt -->
                                <?php if (isGuide()): ?>
                                    <div class="modal fade" id="specialRequestModal<?= $customer['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="<?= BASE_URL ?>?action=customers/updateSpecialRequests">
                                                    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cập nhật yêu cầu đặc biệt</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Khách hàng</label>
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($customer['full_name']) ?>" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Yêu cầu đặc biệt</label>
                                                            <textarea name="special_requests" class="form-control" rows="4" 
                                                                      placeholder="Ví dụ: Ăn chay, dị ứng hải sản, bệnh tim mạch, cần xe lăn..."><?= htmlspecialchars($customer['special_requests'] ?? '') ?></textarea>
                                                            <small class="text-muted">Nhập các yêu cầu đặc biệt của khách (ăn chay, bệnh lý, dị ứng, v.v.)</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có khách trong tour này</p>
            <?php endif; ?>
        </div>
    </div>
</div>

