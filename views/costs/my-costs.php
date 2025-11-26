<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-cash-stack"></i> Chi phí Tour</h2>
        <?php if ($tour): ?>
            <a href="<?= BASE_URL ?>?action=costs/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm chi phí
            </a>
        <?php endif; ?>
    </div>

    <?php if ($tour): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5><?= htmlspecialchars($tour['name']) ?></h5>
                <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
            </div>
        </div>

        <div class="card bg-primary text-white mb-4">
            <div class="card-body text-center">
                <h6>Tổng chi phí</h6>
                <h2><?= number_format($totalCost, 0, ',', '.') ?> VNĐ</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách chi phí (<?= count($costs) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($costs)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Loại chi phí</th>
                                    <th>Mô tả</th>
                                    <th>Số tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $currentUser = getCurrentUser();
                                foreach ($costs as $cost): 
                                    // HDV chỉ có thể sửa/xóa chi phí do chính mình tạo
                                    $canEdit = ($cost['created_by'] == $currentUser['id']);
                                ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($cost['date'])) ?></td>
                                        <td><?= htmlspecialchars($cost['category_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($cost['description'] ?? '') ?></td>
                                        <td><strong><?= number_format($cost['amount'], 0, ',', '.') ?> VNĐ</strong></td>
                                        <td>
                                            <?php if ($canEdit): ?>
                                                <a href="<?= BASE_URL ?>?action=costs/edit&id=<?= $cost['id'] ?>" 
                                                   class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>?action=costs/delete&id=<?= $cost['id'] ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   title="Xóa"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa chi phí này?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">Chỉ xem</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Chưa có chi phí nào</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Vui lòng chọn tour để xem chi phí
        </div>
    <?php endif; ?>
</div>

