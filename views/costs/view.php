<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-eye"></i> Chi tiết Chi phí</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=costs/edit&id=<?= $cost['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL ?>?action=costs/index&tour_id=<?= $cost['tour_id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin chi phí</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour</label>
                            <div class="p-2 bg-light rounded">
                                <strong><?= htmlspecialchars($tour['name']) ?></strong><br>
                                <small class="text-muted">Mã: <?= htmlspecialchars($tour['code']) ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày</label>
                            <div class="p-2 bg-light rounded">
                                <strong><?= date('d/m/Y', strtotime($cost['date'])) ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại chi phí</label>
                            <div class="p-2 bg-light rounded">
                                <strong><?= htmlspecialchars($cost['category_name'] ?? 'N/A') ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số tiền</label>
                            <div class="p-2 bg-light rounded">
                                <h4 class="mb-0 text-primary"><?= number_format($cost['amount'], 0, ',', '.') ?> VNĐ</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Mô tả</label>
                            <div class="p-2 bg-light rounded">
                                <?= htmlspecialchars($cost['description'] ?? 'Không có mô tả') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Người tạo</label>
                            <div class="p-2 bg-light rounded">
                                <strong><?= htmlspecialchars($cost['created_by_name'] ?? 'N/A') ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày tạo</label>
                            <div class="p-2 bg-light rounded">
                                <strong><?= date('d/m/Y H:i', strtotime($cost['created_at'] ?? 'now')) ?></strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Ghi chú</label>
                            <div class="p-2 bg-light rounded">
                                <?= htmlspecialchars($cost['notes'] ?? 'Không có ghi chú') ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>?action=costs/edit&id=<?= $cost['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Chỉnh sửa
                        </a>
                        <a href="<?= BASE_URL ?>?action=costs/delete&id=<?= $cost['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa chi phí này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                        <a href="<?= BASE_URL ?>?action=costs/index&tour_id=<?= $cost['tour_id'] ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
