<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Xe: <?= htmlspecialchars($assignment['license_plate']) ?></h2>
        <div>
            <a href="<?= BASE_URL ?>?action=tour-vehicles/edit&id=<?= $assignment['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=tour-vehicles/index&tour_id=<?= $assignment['tour_id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thông tin chính -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Thông tin xe</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Biển số:</strong><br>
                            <span class="fs-5 fw-bold text-primary"><?= htmlspecialchars($assignment['license_plate']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Loại xe:</strong><br>
                            <?= htmlspecialchars($assignment['vehicle_type']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Sức chứa:</strong><br>
                            <?= $assignment['capacity'] ? $assignment['capacity'] . ' chỗ' : 'N/A' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Tài xế:</strong><br>
                            <?= htmlspecialchars($assignment['driver_name'] ?? 'N/A') ?>
                        </div>
                        <?php if ($assignment['driver_phone']): ?>
                            <div class="col-md-6">
                                <strong>Điện thoại tài xế:</strong><br>
                                <a href="tel:<?= htmlspecialchars($assignment['driver_phone']) ?>" class="text-decoration-none">
                                    <i class="bi bi-phone"></i> <?= htmlspecialchars($assignment['driver_phone']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Thời gian và mục đích</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Ngày bắt đầu:</strong><br>
                            <?= date('d/m/Y', strtotime($assignment['start_date'])) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày kết thúc:</strong><br>
                            <?= date('d/m/Y', strtotime($assignment['end_date'])) ?>
                        </div>
                        <div class="col-md-12">
                            <strong>Mục đích sử dụng:</strong><br>
                            <?= htmlspecialchars($assignment['usage_purpose'] ?? 'Không có') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Ghi chú</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php if (!empty($assignment['notes'])): ?>
                            <div class="col-md-12">
                                <strong>Ghi chú:</strong><br>
                                <?= nl2br(htmlspecialchars($assignment['notes'])) ?>
                            </div>
                        <?php else: ?>
                            <div class="col-md-12">
                                <p class="text-muted">Không có ghi chú</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>?action=tour-vehicles/edit&id=<?= $assignment['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Chỉnh sửa
                        </a>
                        <a href="<?= BASE_URL ?>?action=tour-vehicles/delete&id=<?= $assignment['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('Bạn chắc chắn muốn xóa phân công xe này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
