<div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-people"></i> Danh sách khách</h2>
        <a href="<?= BASE_URL ?>?action=customers/print&tour_id=<?= $tour['id'] ?>" class="btn btn-info" target="_blank">
            <i class="bi bi-printer"></i> In danh sách đoàn
        </a>
        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin tour -->
    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <!-- Danh sách khách -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Khách trong tour (<?= count($customers) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($customers)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ và tên</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>CMND/CCCD</th>
                                <th>Yêu cầu đặc biệt</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($customer['full_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($customer['id_card'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if (isset($customer['special_requests']) && $customer['special_requests']): ?>
                                            <span class="text-info"><?= htmlspecialchars($customer['special_requests']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($customer['notes']) && $customer['notes']): ?>
                                            <small><?= htmlspecialchars($customer['notes']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có khách trong tour này</p>
            <?php endif; ?>
        </div>
    </div>
</div>

