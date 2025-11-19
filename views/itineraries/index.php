<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-calendar-event"></i> Quản lý Lịch trình Tour</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=itineraries/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm lịch trình
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
            <p class="mb-0 text-muted">
                Thời gian: <?= date('d/m/Y', strtotime($tour['start_date'])) ?> - <?= date('d/m/Y', strtotime($tour['end_date'])) ?>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách lịch trình (<?= count($itineraries) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($itineraries)): ?>
                <div class="timeline">
                    <?php foreach ($itineraries as $itinerary): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-size: 1.2rem; font-weight: bold;">
                                            <?= $itinerary['day_number'] ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">
                                                    <i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($itinerary['date'])) ?>
                                                </h5>
                                                <p class="mb-1">
                                                    <strong><i class="bi bi-geo-alt"></i> Địa điểm:</strong> 
                                                    <?= htmlspecialchars($itinerary['location']) ?>
                                                </p>
                                            </div>
                                            <div>
                                                <a href="<?= BASE_URL ?>?action=itineraries/edit&id=<?= $itinerary['id'] ?>" 
                                                   class="btn btn-sm btn-warning" title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>?action=itineraries/delete&id=<?= $itinerary['id'] ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Bạn có chắc muốn xóa lịch trình này?')" 
                                                   title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <p class="mb-2">
                                            <strong><i class="bi bi-list-check"></i> Hoạt động:</strong><br>
                                            <?= nl2br(htmlspecialchars($itinerary['activities'])) ?>
                                        </p>
                                        <?php if ($itinerary['departure_time']): ?>
                                            <p class="mb-2">
                                                <strong><i class="bi bi-clock"></i> Giờ khởi hành:</strong> 
                                                <?= date('H:i', strtotime($itinerary['departure_time'])) ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($itinerary['notes']): ?>
                                            <p class="mb-0 text-muted">
                                                <small><i class="bi bi-sticky"></i> <strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($itinerary['notes'])) ?></small>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">
                    Chưa có lịch trình nào. 
                    <a href="<?= BASE_URL ?>?action=itineraries/create&tour_id=<?= $tour['id'] ?>">Thêm lịch trình đầu tiên</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

