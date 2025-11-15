<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-cash-stack"></i> Quản lý Chi phí</h2>
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

        <!-- Tổng chi phí -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Tổng chi phí</h6>
                        <h2><?= number_format($totalCost, 0, ',', '.') ?> VNĐ</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Chi phí theo loại</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($costByCategory)): ?>
                            <?php foreach ($costByCategory as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?= htmlspecialchars($item['name']) ?></span>
                                    <strong><?= number_format($item['total'], 0, ',', '.') ?> VNĐ</strong>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Chưa có chi phí</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách chi phí -->
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
                                    <th>Người tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($costs as $cost): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($cost['date'])) ?></td>
                                        <td><?= htmlspecialchars($cost['category_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($cost['description'] ?? '') ?></td>
                                        <td><strong><?= number_format($cost['amount'], 0, ',', '.') ?> VNĐ</strong></td>
                                        <td><?= htmlspecialchars($cost['created_by_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info" title="Xem">
                                                <i class="bi bi-eye"></i>
                                            </a>
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

