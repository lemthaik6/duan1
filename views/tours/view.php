<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Tour</h2>
        <div>
            <?php if (isGuide()): ?>
                <a href="<?= BASE_URL ?>?action=attendance/index&tour_id=<?= $tour['id'] ?>" class="btn btn-success">
                    <i class="bi bi-person-check"></i> Điểm danh khách
                </a>
            <?php endif; ?>
            <?php if (isAdmin()): ?>
                <a href="<?= BASE_URL ?>?action=tours/edit&id=<?= $tour['id'] ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>?action=<?= isAdmin() ? 'tours/index' : 'tours/my-tours' ?>" class="btn btn-secondary">
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
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Hướng dẫn viên - Chỉ Admin thấy -->
            <?php if (isAdmin()): ?>
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
            <?php endif; ?>

            <!-- Khách -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-check"></i> Khách (<?= count($customers) ?>)</h5>
                    <?php if (isGuide() || isAdmin()): ?>
                        <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-list"></i> Xem tất cả
                        </a>
                    <?php endif; ?>
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
                    <?php if (isAdmin()): ?>
                        <a href="<?= BASE_URL ?>?action=costs/index&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                            Xem chi tiết
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>?action=costs/my-costs&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                            Xem chi tiết
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

