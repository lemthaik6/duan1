<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-file-text"></i> Quản lý chính sách tour</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=tour-policies/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm chính sách
            </a>
            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách chính sách (<?= count($policies) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($policies)): ?>
                <?php
                $policyTypes = [
                    'booking' => 'Chính sách đặt tour',
                    'cancellation' => 'Chính sách hủy tour',
                    'refund' => 'Chính sách hoàn tiền',
                    'reschedule' => 'Chính sách đổi lịch',
                    'terms' => 'Điều khoản'
                ];
                $groupedPolicies = [];
                foreach ($policies as $policy) {
                    $groupedPolicies[$policy['policy_type']][] = $policy;
                }
                ?>
                <?php foreach ($groupedPolicies as $type => $typePolicies): ?>
                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="bi bi-file-earmark-text"></i> <?= $policyTypes[$type] ?? $type ?>
                        </h6>
                        <?php foreach ($typePolicies as $policy): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6><?= htmlspecialchars($policy['title']) ?></h6>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($policy['content'])) ?></p>
                                        </div>
                                        <div class="ms-3">
                                            <a href="<?= BASE_URL ?>?action=tour-policies/edit&id=<?= $policy['id'] ?>" 
                                               class="btn btn-sm btn-warning" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?= BASE_URL ?>?action=tour-policies/delete" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa chính sách này?');">
                                                <input type="hidden" name="id" value="<?= $policy['id'] ?>">
                                                <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có chính sách nào. <a href="<?= BASE_URL ?>?action=tour-policies/create&tour_id=<?= $tour['id'] ?>">Thêm chính sách</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

