<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="bi bi-airplane-fill"></i> Thông tin Tour</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <h4 class="text-primary"><?= htmlspecialchars($tour['name']) ?></h4>
                                    <p class="text-muted mb-0">Mã tour: <strong><?= htmlspecialchars($tour['code']) ?></strong></p>
                                </div>

                                <div class="row g-3 mb-4">
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
                                </div>

                                <?php if (!empty($itineraries)): ?>
                                    <div class="mb-4">
                                        <h5><i class="bi bi-calendar-event"></i> Lịch trình</h5>
                                        <div class="timeline">
                                            <?php foreach ($itineraries as $itinerary): ?>
                                                <div class="d-flex mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <?= $itinerary['day_number'] ?>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6><?= date('d/m/Y', strtotime($itinerary['date'])) ?></h6>
                                                        <p class="mb-1"><strong>Địa điểm:</strong> <?= htmlspecialchars($itinerary['location']) ?></p>
                                                        <p class="mb-1"><?= nl2br(htmlspecialchars($itinerary['activities'])) ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($assignments)): ?>
                                    <div class="mb-4">
                                        <h5><i class="bi bi-people"></i> Hướng dẫn viên</h5>
                                        <?php foreach ($assignments as $assignment): ?>
                                            <div class="mb-2">
                                                <strong><?= htmlspecialchars($assignment['guide_name']) ?></strong>
                                                <?php if ($assignment['guide_phone']): ?>
                                                    <br><small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($assignment['guide_phone']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

