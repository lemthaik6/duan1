<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Xe: <?= htmlspecialchars($vehicle['license_plate']) ?></h2>
        <div>
            <a href="<?= BASE_URL ?>?action=vehicles/edit&id=<?= $vehicle['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=vehicles/delete&id=<?= $vehicle['id'] ?>" 
               class="btn btn-danger"
               onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?')">
                <i class="bi bi-trash"></i> Xóa
            </a>
            <a href="<?= BASE_URL ?>?action=vehicles/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
      
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin xe</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Biển số:</strong><br>
                            <span class="fs-5 fw-bold text-primary"><?= htmlspecialchars($vehicle['license_plate']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Loại xe:</strong><br>
                            <?= htmlspecialchars($vehicle['vehicle_type']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Sức chứa:</strong><br>
                            <?= $vehicle['capacity'] ? $vehicle['capacity'] . ' chỗ' : 'N/A' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <?php
                            $statusClass = [
                                'available' => 'success',
                                'in_use' => 'warning',
                                'maintenance' => 'info',
                                'unavailable' => 'danger'
                            ];
                            $statusText = [
                                'available' => 'Sẵn sàng',
                                'in_use' => 'Đang sử dụng',
                                'maintenance' => 'Bảo trì',
                                'unavailable' => 'Không sẵn sàng'
                            ];
                            ?>
                            <span class="badge bg-<?= $statusClass[$vehicle['status']] ?? 'secondary' ?>">
                                <?= $statusText[$vehicle['status']] ?? $vehicle['status'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tài xế</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Tên tài xế:</strong><br>
                            <?= htmlspecialchars($vehicle['driver_name'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Điện thoại:</strong><br>
                            <?php if ($vehicle['driver_phone']): ?>
                                <a href="tel:<?= htmlspecialchars($vehicle['driver_phone']) ?>" class="text-decoration-none">
                                    <i class="bi bi-phone"></i> <?= htmlspecialchars($vehicle['driver_phone']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

         
            <?php if (!empty($vehicle['notes'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ghi chú</h5>
                    </div>
                    <div class="card-body">
                        <?= nl2br(htmlspecialchars($vehicle['notes'])) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

