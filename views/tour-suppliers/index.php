<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-building"></i> Nhà cung cấp tour</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=suppliers/index" class="btn btn-info">
                <i class="bi bi-list"></i> Quản lý nhà cung cấp
            </a>
            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
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

    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form liên kết nhà cung cấp -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liên kết nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?action=suppliers/linkToTour">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Chọn nhà cung cấp --</option>
                                <?php foreach ($allSuppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>">
                                        <?= htmlspecialchars($supplier['name']) ?> 
                                        (<?php
                                        $types = [
                                            'hotel' => 'Khách sạn',
                                            'restaurant' => 'Nhà hàng',
                                            'transport' => 'Vận chuyển',
                                            'ticket' => 'Vé',
                                            'visa' => 'Visa',
                                            'insurance' => 'Bảo hiểm',
                                            'other' => 'Khác'
                                        ];
                                        echo $types[$supplier['supplier_type']] ?? $supplier['supplier_type'];
                                        ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả dịch vụ</label>
                            <textarea name="service_description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã đặt chỗ</label>
                            <input type="text" name="booking_reference" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày liên hệ</label>
                            <input type="date" name="contact_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-link"></i> Liên kết
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách nhà cung cấp -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách nhà cung cấp (<?= count($suppliers) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($suppliers)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Loại</th>
                                        <th>Tên nhà cung cấp</th>
                                        <th>Liên hệ</th>
                                        <th>Dịch vụ</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <tr>
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
                                                <strong><?= htmlspecialchars($supplier['supplier_name']) ?></strong>
                                                <?php if ($supplier['contact_person']): ?>
                                                    <br><small class="text-muted">Người liên hệ: <?= htmlspecialchars($supplier['contact_person']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($supplier['phone']): ?>
                                                    <i class="bi bi-phone"></i> <?= htmlspecialchars($supplier['phone']) ?><br>
                                                <?php endif; ?>
                                                <?php if ($supplier['email']): ?>
                                                    <i class="bi bi-envelope"></i> <?= htmlspecialchars($supplier['email']) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($supplier['service_description']): ?>
                                                    <?= htmlspecialchars($supplier['service_description']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                                <?php if ($supplier['booking_reference']): ?>
                                                    <br><small class="text-info">Mã: <?= htmlspecialchars($supplier['booking_reference']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="<?= BASE_URL ?>?action=tour-suppliers/unlink" 
                                                      style="display: inline;" 
                                                      onsubmit="return confirm('Bạn có chắc muốn hủy liên kết?');">
                                                    <input type="hidden" name="id" value="<?= $supplier['id'] ?>">
                                                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-unlink"></i> Hủy liên kết
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Chưa có nhà cung cấp nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

