<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết nhà cung cấp: <?= htmlspecialchars($supplier['name']) ?></h2>
        <div>
            <a href="<?= BASE_URL ?>?action=suppliers/edit&id=<?= $supplier['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=suppliers/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thông tin chính -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Tên:</strong><br>
                            <?= htmlspecialchars($supplier['name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Loại:</strong><br>
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
                            ?>
                            <span class="badge bg-primary"><?= $types[$supplier['supplier_type']] ?? $supplier['supplier_type'] ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Người liên hệ:</strong><br>
                            <?= htmlspecialchars($supplier['contact_person'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong><br>
                            <?= htmlspecialchars($supplier['phone'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <?= htmlspecialchars($supplier['email'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
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
                        </div>
                        <div class="col-12">
                            <strong>Địa chỉ:</strong><br>
                            <?= htmlspecialchars($supplier['address'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Năng lực:</strong><br>
                            <?= htmlspecialchars($supplier['capacity'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Đánh giá:</strong><br>
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
                                <span class="ms-2"><?= number_format($rating, 1) ?>/5.0</span>
                            <?php else: ?>
                                <span class="text-muted">Chưa đánh giá</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($supplier['description']): ?>
                            <div class="col-12">
                                <strong>Mô tả:</strong><br>
                                <?= nl2br(htmlspecialchars($supplier['description'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Danh sách tour -->
            <?php if (!empty($tours)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Các tour đã cung cấp dịch vụ (<?= count($tours) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mã tour</th>
                                        <th>Tên tour</th>
                                        <th>Ngày khởi hành</th>
                                        <th>Ngày kết thúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tours as $tour): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tour['tour_code']) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['tour_id'] ?>">
                                                    <?= htmlspecialchars($tour['tour_name']) ?>
                                                </a>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($tour['start_date'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($tour['end_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

