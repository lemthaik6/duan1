<div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-people"></i> Danh sách khách</h2>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?action=customers/create&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Thêm khách hàng
            </a>
            <a href="<?= BASE_URL ?>?action=customers/print&tour_id=<?= $tour['id'] ?>" class="btn btn-info" target="_blank">
                <i class="bi bi-printer"></i> In danh sách đoàn
            </a>
            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin tour -->
    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Khách trong tour (<?= count($customers) ?>)</h5>
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

    <?php if (!empty($customers)): ?>
                <div class="table-responsive table-responsive-no-scroll">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ và tên</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>CMND/CCCD</th>
                                <th>Yêu cầu đặc biệt</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
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
                                    <td>
                                        <?php if (isset($customer['notes']) && $customer['notes']): ?>
                                            <small><?= htmlspecialchars($customer['notes']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?= $customer['id'] ?>">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
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

<!-- Delete Modals Container - Must be outside container-fluid for proper z-index stacking -->
<?php if (!empty($customers)): ?>
    <?php foreach ($customers as $customer): ?>
    <div class="modal fade" id="deleteModal<?= $customer['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $customer['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="<?= BASE_URL ?>?action=customers/delete">
                    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel<?= $customer['id'] ?>">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa khách hàng <strong><?= htmlspecialchars($customer['full_name']) ?></strong>?</p>
                        <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

