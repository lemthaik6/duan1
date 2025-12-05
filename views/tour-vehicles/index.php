<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-truck"></i> Quản lý Xe - Tour: <?= htmlspecialchars($tour['name']) ?></h2>
        <div>
            <a href="<?= BASE_URL ?>?action=tour-vehicles/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm xe
            </a>
            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> Xóa xe thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> Có lỗi xảy ra
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách xe (<?= count($vehicles) ?>)</h5>
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
                                <th>Mục đích sử dụng</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $v): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($v['license_plate']) ?></strong></td>
                                    <td><?= htmlspecialchars($v['vehicle_type']) ?></td>
                                    <td><?= $v['capacity'] ? $v['capacity'] . ' chỗ' : 'N/A' ?></td>
                                    <td><?= htmlspecialchars($v['driver_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($v['usage_purpose'] ?? 'N/A') ?></td>
                                    <td><?= date('d/m/Y', strtotime($v['start_date'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($v['end_date'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=tour-vehicles/view&id=<?= $v['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=tour-vehicles/edit&id=<?= $v['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=tour-vehicles/delete&id=<?= $v['id'] ?>" 
                                           class="btn btn-sm btn-danger" title="Xóa"
                                           onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có xe nào được phân công cho tour này</p>
            <?php endif; ?>
        </div>
    </div>
</div>
