<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-cash-stack"></i> Chi phí Tour</h2>
        <?php if ($tour): ?>
            <a href="<?= BASE_URL ?>?action=costs/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm chi phí
            </a>
        <?php endif; ?>
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

    <!-- Danh sách tour được phân công -->
    <?php if (!empty($assignedTours)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách tour được phân công</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($assignedTours as $assignedTour): 
                        $isSelected = ($tour && $tour['id'] == $assignedTour['id']);
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 <?= $isSelected ? 'border-primary' : '' ?>" 
                                 style="cursor: pointer; transition: all 0.3s; <?= $isSelected ? 'box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);' : '' ?>"
                                 onclick="window.location.href='<?= BASE_URL ?>?action=costs/my-costs&tour_id=<?= $assignedTour['id'] ?>'"
                                 onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='<?= $isSelected ? '0 0 0 2px rgba(59, 130, 246, 0.25)' : 'none' ?>'">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">
                                        <i class="bi bi-map"></i> <?= htmlspecialchars($assignedTour['name']) ?>
                                    </h6>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-tag"></i> Mã: <?= htmlspecialchars($assignedTour['code']) ?>
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar"></i> 
                                        <?= date('d/m/Y', strtotime($assignedTour['start_date'])) ?> - 
                                        <?= date('d/m/Y', strtotime($assignedTour['end_date'])) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <small class="text-muted">Tổng chi phí:</small>
                                            <div class="fw-bold text-primary">
                                                <?= number_format($assignedTour['total_cost'] ?? 0, 0, ',', '.') ?> đ
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">Số mục:</small>
                                            <div class="fw-bold">
                                                <?= $assignedTour['costs_count'] ?? 0 ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($isSelected): ?>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">Đang xem</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Bạn chưa được phân công tour nào
        </div>
    <?php endif; ?>

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
        <?php if (!empty($assignedTours)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Vui lòng chọn một tour ở trên để xem chi tiết chi phí
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

