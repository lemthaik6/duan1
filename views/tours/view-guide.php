<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Tour</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=attendance/index&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
                <i class="bi bi-person-check"></i> Điểm danh khách
            </a>
            <a href="<?= BASE_URL ?>?action=tours/my-tours" class="btn btn-secondary">
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

            <!-- Lịch trình -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Lịch trình chi tiết</h5>
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
                        <p class="text-muted">Chưa có lịch trình</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nhật ký tour -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-journal-text"></i> Nhật ký tour</h5>
                    <a href="<?= BASE_URL ?>?action=daily-logs/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ghi nhật ký
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($dailyLogs)): ?>
                        <?php foreach (array_slice($dailyLogs, 0, 3) as $log): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <strong><?= date('d/m/Y', strtotime($log['date'])) ?></strong>
                                <p class="mb-1 mt-2"><?= nl2br(htmlspecialchars(substr($log['activities'], 0, 150))) ?><?= strlen($log['activities']) > 150 ? '...' : '' ?></p>
                            </div>
                        <?php endforeach; ?>
                        <a href="<?= BASE_URL ?>?action=daily-logs/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                            Xem tất cả nhật ký
                        </a>
                    <?php else: ?>
                        <p class="text-muted">Chưa có nhật ký</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sự cố -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Sự cố</h5>
                    <a href="<?= BASE_URL ?>?action=incidents/create&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-danger">
                        <i class="bi bi-plus-circle"></i> Báo cáo sự cố
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($incidents)): ?>
                        <?php foreach (array_slice($incidents, 0, 3) as $incident): ?>
                            <div class="mb-2 pb-2 border-bottom">
                                <strong><?= htmlspecialchars($incident['title']) ?></strong>
                                <br><small class="text-muted">
                                    <?php 
                                    $dateToShow = !empty($incident['incident_date']) ? $incident['incident_date'] : ($incident['created_at'] ?? '');
                                    if ($dateToShow): ?>
                                        <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($dateToShow)) ?>
                                        <?php if (!empty($incident['created_at'])): ?>
                                            | <i class="bi bi-clock"></i> <?= date('H:i', strtotime($incident['created_at'])) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="bi bi-calendar"></i> Chưa có ngày
                                    <?php endif; ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                        <a href="<?= BASE_URL ?>?action=incidents/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-danger">
                            Xem tất cả sự cố
                        </a>
                    <?php else: ?>
                        <p class="text-muted">Chưa có sự cố nào</p>
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
                    <div id="qrcode-guide" class="mb-3 d-flex justify-content-center"></div>
                    <p class="text-muted small mb-2">Quét mã QR để xem thông tin tour</p>
                    <a href="<?= BASE_URL ?>?action=tours/public-view&code=<?= urlencode($tour['code']) ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right"></i> Mở liên kết
                    </a>
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
                <div class="card-body text-center">
                    <h3 class="text-primary"><?= number_format($totalCost, 0, ',', '.') ?> VNĐ</h3>
                    <a href="<?= BASE_URL ?>?action=costs/my-costs&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                        Xem chi tiết
                    </a>
                </div>
            </div>

            <!-- Công cụ nhanh -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-tools"></i> Công cụ</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>?action=attendance/index&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
                            <i class="bi bi-person-check"></i> Điểm danh khách
                        </a>
                        <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-info">
                            <i class="bi bi-people"></i> Quản lý khách
                        </a>
                        <a href="<?= BASE_URL ?>?action=daily-logs/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-journal-plus"></i> Ghi nhật ký
                        </a>
                        <a href="<?= BASE_URL ?>?action=incidents/create&tour_id=<?= $tour['id'] ?>" class="btn btn-danger">
                            <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
                        </a>
                        <a href="<?= BASE_URL ?>?action=feedbacks/create&tour_id=<?= $tour['id'] ?>" class="btn btn-info">
                            <i class="bi bi-chat-left-text"></i> Gửi phản hồi đánh giá
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tạo QR code cho tour
document.addEventListener('DOMContentLoaded', function() {
    const qrUrl = '<?= BASE_URL ?>?action=tours/public-view&code=<?= urlencode($tour['code']) ?>';
    const qrContainer = document.getElementById('qrcode-guide');
    
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

