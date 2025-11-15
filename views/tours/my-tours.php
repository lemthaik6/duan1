<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-calendar-check"></i> Tour của tôi</h2>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách tour được phân công (<?= count($tours) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($tours)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã tour</th>
                                <th>Tên tour</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($tour['code']) ?></strong></td>
                                    <td><?= htmlspecialchars($tour['name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($tour['start_date'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($tour['end_date'])) ?></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Bạn chưa được phân công tour nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

