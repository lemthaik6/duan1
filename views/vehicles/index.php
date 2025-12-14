<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-truck"></i> Quản lý Xe</h2>
        <a href="<?= BASE_URL ?>?action=vehicles/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm xe mới
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="vehicles/index">
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="available" <?= ($_GET['status'] ?? '') === 'available' ? 'selected' : '' ?>>Sẵn sàng</option>
                        <option value="in_use" <?= ($_GET['status'] ?? '') === 'in_use' ? 'selected' : '' ?>>Đang sử dụng</option>
                        <option value="maintenance" <?= ($_GET['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
                        <option value="unavailable" <?= ($_GET['status'] ?? '') === 'unavailable' ? 'selected' : '' ?>>Không sẵn sàng</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách Xe (<?= count($vehicles) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($vehicles)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Biển số</th>
                                <th>Loại xe</th>
                                <th>Sức chứa</th>
                                <th>Tài xế</th>
                                <th>Điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($vehicle['license_plate']) ?></strong></td>
                                    <td><?= htmlspecialchars($vehicle['vehicle_type']) ?></td>
                                    <td><?= $vehicle['capacity'] ? $vehicle['capacity'] . ' chỗ' : 'N/A' ?></td>
                                    <td><?= htmlspecialchars($vehicle['driver_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($vehicle['driver_phone'] ?? 'N/A') ?></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=vehicles/view&id=<?= $vehicle['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=vehicles/edit&id=<?= $vehicle['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=vehicles/delete&id=<?= $vehicle['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có xe nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

