<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-building"></i> Quản lý Nhà cung cấp</h2>
        <a href="<?= BASE_URL ?>?action=suppliers/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp
        </a>
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

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="suppliers/index">
                <div class="col-md-4">
                    <label class="form-label">Loại</label>
                    <select name="supplier_type" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="hotel" <?= ($_GET['supplier_type'] ?? '') === 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                        <option value="restaurant" <?= ($_GET['supplier_type'] ?? '') === 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                        <option value="transport" <?= ($_GET['supplier_type'] ?? '') === 'transport' ? 'selected' : '' ?>>Vận chuyển</option>
                        <option value="ticket" <?= ($_GET['supplier_type'] ?? '') === 'ticket' ? 'selected' : '' ?>>Vé</option>
                        <option value="visa" <?= ($_GET['supplier_type'] ?? '') === 'visa' ? 'selected' : '' ?>>Visa</option>
                        <option value="insurance" <?= ($_GET['supplier_type'] ?? '') === 'insurance' ? 'selected' : '' ?>>Bảo hiểm</option>
                        <option value="other" <?= ($_GET['supplier_type'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                        <option value="blacklisted" <?= ($_GET['status'] ?? '') === 'blacklisted' ? 'selected' : '' ?>>Đã chặn</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="<?= BASE_URL ?>?action=suppliers/index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách Nhà cung cấp (<?= count($suppliers) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($suppliers)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên nhà cung cấp</th>
                                <th>Loại</th>
                                <th>Liên hệ</th>
                                <th>Năng lực</th>
                                <th>Đánh giá</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suppliers as $supplier): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($supplier['name']) ?></strong></td>
                                    <td>
                                        <?php
                                        $types = [
                                            'hotel' => 'Khách sạn',
                                            'restaurant' => 'Nhà hàng',
                                            'transport' => 'Vận chuyển',
                                            'ticket' => 'Vé',
                                            'visa' => 'Visa',
                                            'insurance' => 'Bảo hiểm',
                                            'other' => 'Khác'
                                        ];
                                        $typeLabels = [
                                            'hotel' => 'primary',
                                            'restaurant' => 'success',
                                            'transport' => 'info',
                                            'ticket' => 'warning',
                                            'visa' => 'secondary',
                                            'insurance' => 'danger',
                                            'other' => 'dark'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $typeLabels[$supplier['supplier_type']] ?? 'secondary' ?>">
                                            <?= $types[$supplier['supplier_type']] ?? $supplier['supplier_type'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($supplier['phone']): ?>
                                            <i class="bi bi-phone"></i> <?= htmlspecialchars($supplier['phone']) ?><br>
                                        <?php endif; ?>
                                        <?php if ($supplier['email']): ?>
                                            <i class="bi bi-envelope"></i> <?= htmlspecialchars($supplier['email']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($supplier['capacity'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if ($supplier['rating']): ?>
                                            <?php
                                            $rating = (float)$supplier['rating'];
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= $rating):
                                                    echo '<i class="bi bi-star-fill text-warning"></i>';
                                                elseif ($i - 0.5 <= $rating):
                                                    echo '<i class="bi bi-star-half text-warning"></i>';
                                                else:
                                                    echo '<i class="bi bi-star text-muted"></i>';
                                                endif;
                                            endfor;
                                            ?>
                                            <small class="text-muted">(<?= number_format($rating, 1) ?>)</small>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa đánh giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'blacklisted' => 'danger'
                                        ];
                                        $statusText = [
                                            'active' => 'Hoạt động',
                                            'inactive' => 'Không hoạt động',
                                            'blacklisted' => 'Đã chặn'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusClass[$supplier['status']] ?? 'secondary' ?>">
                                            <?= $statusText[$supplier['status']] ?? $supplier['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=suppliers/view&id=<?= $supplier['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=suppliers/edit&id=<?= $supplier['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=suppliers/delete&id=<?= $supplier['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?')" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Không có nhà cung cấp nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

