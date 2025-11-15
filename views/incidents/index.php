<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-exclamation-triangle"></i> Báo cáo Sự cố</h2>
        <?php if ($tour && isGuide()): ?>
            <a href="<?= BASE_URL ?>?action=incidents/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Báo cáo sự cố
            </a>
        <?php endif; ?>
    </div>

    <?php if (isGuide()): ?>
        <!-- Chọn tour -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <input type="hidden" name="action" value="incidents/index">
                    <div class="col-md-8">
                        <label class="form-label">Chọn tour</label>
                        <select name="tour_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($myTours as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ($tourId ?? 0) == $t['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php 
    $tourId = $_GET['tour_id'] ?? 0;
    if (isset($tour) && $tour): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5><?= htmlspecialchars($tour['name']) ?></h5>
                <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Danh sách sự cố (<?= count($incidents) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($incidents)): ?>
                    <?php foreach ($incidents as $incident): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($incident['title']) ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($incident['incident_date'])) ?>
                                            | Báo cáo bởi: <?= htmlspecialchars($incident['reported_by_name'] ?? 'N/A') ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?php
                                        $severityClass = [
                                            'low' => 'info',
                                            'medium' => 'warning',
                                            'high' => 'danger',
                                            'critical' => 'dark'
                                        ];
                                        $severityText = [
                                            'low' => 'Thấp',
                                            'medium' => 'Trung bình',
                                            'high' => 'Cao',
                                            'critical' => 'Nghiêm trọng'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $severityClass[$incident['severity']] ?? 'secondary' ?>">
                                            <?= $severityText[$incident['severity']] ?? $incident['severity'] ?>
                                        </span>
                                        <span class="badge bg-<?= $incident['status'] === 'resolved' ? 'success' : 'warning' ?>">
                                            <?= $incident['status'] === 'resolved' ? 'Đã xử lý' : 'Đang xử lý' ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <strong>Loại:</strong> <?= htmlspecialchars($incident['incident_type']) ?><br>
                                    <strong>Mô tả:</strong><br>
                                    <?= nl2br(htmlspecialchars($incident['description'])) ?>
                                </div>

                                <?php if ($incident['resolution']): ?>
                                    <div class="alert alert-success mt-2">
                                        <strong>Giải pháp:</strong><br>
                                        <?= nl2br(htmlspecialchars($incident['resolution'])) ?>
                                        <?php if ($incident['resolved_by_name']): ?>
                                            <br><small>Xử lý bởi: <?= htmlspecialchars($incident['resolved_by_name']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Chưa có sự cố nào</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Vui lòng chọn tour để xem sự cố
        </div>
    <?php endif; ?>
</div>

