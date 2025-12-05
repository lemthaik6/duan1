<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Tour</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=itineraries/index&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
                <i class="bi bi-calendar-event"></i> Lịch trình
            </a>
            <a href="<?= BASE_URL ?>?action=tour-policies/index&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-file-text"></i> Chính sách
            </a>
            <a href="<?= BASE_URL ?>?action=tour-suppliers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-building"></i> Nhà cung cấp
            </a>
            <a href="<?= BASE_URL ?>?action=hotel-rooms/index&tour_id=<?= $tour['id'] ?>" class="btn btn-info">
                <i class="bi bi-building"></i> Phân phòng
            </a>
            <a href="<?= BASE_URL ?>?action=tour-vehicles/index&tour_id=<?= $tour['id'] ?>" class="btn btn-danger">
                <i class="bi bi-truck"></i> Quản lý xe
            </a>
            <a href="<?= BASE_URL ?>?action=tours/edit&id=<?= $tour['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=tours/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thông tin chính -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin tour</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Mã tour:</strong><br>
                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($tour['code']) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Tên tour:</strong><br>
                            <?= htmlspecialchars($tour['name']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Danh mục:</strong><br>
                            <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <?php
                            $statusClass = [
                                'upcoming' => 'info',
                                'ongoing' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusText = [
                                'upcoming' => 'Sắp diễn ra',
                                'ongoing' => 'Đang diễn ra',
                                'completed' => 'Đã hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                            ?>
                            <span class="badge bg-<?= $statusClass[$tour['status']] ?? 'secondary' ?>">
                                <?= $statusText[$tour['status']] ?? $tour['status'] ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày bắt đầu:</strong><br>
                            <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày kết thúc:</strong><br>
                            <?= date('d/m/Y', strtotime($tour['end_date'])) ?>
                        </div>
                        <?php if ($tour['internal_price']): ?>
                            <div class="col-md-6">
                                <strong>Giá nội bộ:</strong><br>
                                <?= number_format($tour['internal_price'], 0, ',', '.') ?> VNĐ
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <strong>Cấp độ ưu tiên:</strong><br>
                            <?php
                            $priorityClass = [
                                'low' => 'secondary',
                                'medium' => 'info',
                                'high' => 'warning',
                                'urgent' => 'danger'
                            ];
                            $priorityText = [
                                'low' => 'Thấp',
                                'medium' => 'Trung bình',
                                'high' => 'Cao',
                                'urgent' => 'Khẩn cấp'
                            ];
                            ?>
                            <span class="badge bg-<?= $priorityClass[$tour['priority_level']] ?? 'secondary' ?>">
                                <?= $priorityText[$tour['priority_level']] ?? $tour['priority_level'] ?>
                            </span>
                        </div>
                        <?php if ($tour['description']): ?>
                            <div class="col-12">
                                <strong>Mô tả:</strong><br>
                                <?= nl2br(htmlspecialchars($tour['description'])) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($tour['schedule']): ?>
                            <div class="col-12">
                                <strong>Lịch trình tổng quan:</strong><br>
                                <?= nl2br(htmlspecialchars($tour['schedule'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Lịch trình chi tiết</h5>
                    <a href="<?= BASE_URL ?>?action=itineraries/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-gear"></i> Quản lý
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($itineraries)): ?>
                        <div class="timeline">
                            <?php foreach ($itineraries as $index => $itinerary): ?>
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <?= $itinerary['day_number'] ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6><?= date('d/m/Y', strtotime($itinerary['date'])) ?></h6>
                                        <p class="mb-1"><strong>Địa điểm:</strong> <?= htmlspecialchars($itinerary['location']) ?></p>
                                        <p class="mb-1"><strong>Hoạt động:</strong> <?= nl2br(htmlspecialchars($itinerary['activities'])) ?></p>
                                        <?php if ($itinerary['departure_time']): ?>
                                            <p class="mb-1"><strong>Giờ khởi hành:</strong> <?= date('H:i', strtotime($itinerary['departure_time'])) ?></p>
                                        <?php endif; ?>
                                        <?php if ($itinerary['notes']): ?>
                                            <p class="mb-0 text-muted"><small><?= nl2br(htmlspecialchars($itinerary['notes'])) ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Chưa có lịch trình. 
                            <a href="<?= BASE_URL ?>?action=itineraries/index&tour_id=<?= $tour['id'] ?>">Thêm lịch trình</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- QR Code -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-qr-code"></i> Mã QR Tour</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qrcode" class="mb-3 d-flex justify-content-center"></div>
                    <p class="text-muted small mb-2">Quét mã QR để xem thông tin tour</p>
                    <a href="<?= BASE_URL ?>?action=tours/public-view&code=<?= urlencode($tour['code']) ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right"></i> Mở liên kết
                    </a>
                </div>
            </div>

            <!-- Hướng dẫn viên -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Hướng dẫn viên</h5>
                    <a href="<?= BASE_URL ?>?action=guides/assign&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-person-plus"></i> Phân công
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $assignment): ?>
                            <div class="mb-2 pb-2 border-bottom">
                                <strong><?= htmlspecialchars($assignment['guide_name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($assignment['guide_email'] ?? '') ?></small>
                                <?php if ($assignment['guide_phone']): ?>
                                    <br><small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($assignment['guide_phone']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa phân công HDV</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Xe vận chuyển -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Xe vận chuyển</h5>
                    <a href="<?= BASE_URL ?>?action=tour-vehicles/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-danger">
                        <i class="bi bi-gear"></i> Quản lý
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($vehicleAssignments)): ?>
                        <?php foreach ($vehicleAssignments as $vehicle): ?>
                            <div class="mb-2 pb-2 border-bottom">
                                <strong><?= htmlspecialchars($vehicle['license_plate']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?= htmlspecialchars($vehicle['vehicle_type']) ?> - 
                                    <?= $vehicle['capacity'] ?> chỗ
                                </small>
                                <?php if ($vehicle['driver_name']): ?>
                                    <br><small class="text-info"><i class="bi bi-person"></i> <?= htmlspecialchars($vehicle['driver_name']) ?></small>
                                <?php endif; ?>
                                <?php if ($vehicle['usage_purpose']): ?>
                                    <br><small class="text-secondary"><i class="bi bi-tag"></i> <?= htmlspecialchars($vehicle['usage_purpose']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa phân công xe</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Khách -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Khách (<?= count($customers) ?>)</h5>
                    <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-list"></i> Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($customers)): ?>
                        <?php foreach (array_slice($customers, 0, 5) as $customer): ?>
                            <div class="mb-2 pb-2 border-bottom">
                                <strong><?= htmlspecialchars($customer['full_name']) ?></strong>
                                <?php if ($customer['phone']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($customer['phone']) ?></small>
                                <?php endif; ?>
                                <?php if (isset($customer['special_requests']) && $customer['special_requests']): ?>
                                    <br><small class="text-info"><i class="bi bi-info-circle"></i> <?= htmlspecialchars($customer['special_requests']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <?php if (count($customers) > 5): ?>
                            <small class="text-muted">... và <?= count($customers) - 5 ?> khách khác</small>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa có khách</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Chi phí -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Tổng chi phí</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $internalPrice = $tour['internal_price'] ?? 0;
                    $costsOnly = $costsOnly ?? 0;
                    $totalCost = $totalCost ?? 0;
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giá gốc nội bộ:</span>
                            <strong><?= number_format($internalPrice, 0, ',', '.') ?> đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Chi phí phát sinh:</span>
                            <strong><?= number_format($costsOnly, 0, ',', '.') ?> đ</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng chi phí:</strong>
                            <h4 class="text-primary mb-0"><?= number_format($totalCost, 0, ',', '.') ?> đ</h4>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>?action=costs/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-list"></i> Xem chi tiết chi phí
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tạo QR code cho tour
document.addEventListener('DOMContentLoaded', function() {
    const qrUrl = '<?= BASE_URL ?>?action=tours/public-view&code=<?= urlencode($tour['code']) ?>';
    const qrContainer = document.getElementById('qrcode');
    
    if (typeof QRCode !== 'undefined') {
        // Tạo canvas element
        const canvas = document.createElement('canvas');
        qrContainer.appendChild(canvas);
        
        QRCode.toCanvas(canvas, qrUrl, {
            width: 200,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function (error) {
            if (error) {
                console.error('Lỗi tạo QR code:', error);
                // Fallback: sử dụng API online
                qrContainer.innerHTML = 
                    '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + 
                    encodeURIComponent(qrUrl) + '" alt="QR Code" class="img-fluid">';
            }
        });
    } else {
        // Fallback: sử dụng API online nếu thư viện không tải được
        qrContainer.innerHTML = 
            '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + 
            encodeURIComponent(qrUrl) + '" alt="QR Code" class="img-fluid">';
    }
});
</script>

