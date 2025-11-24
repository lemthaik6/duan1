<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-chat-dots"></i> Chat nội bộ</h2>
        <a href="<?= BASE_URL ?>?action=chat/create-group" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo nhóm chat mới
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <?php if (empty($groups)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-chat-dots" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Chưa có nhóm chat nào. Hãy tạo nhóm chat mới để bắt đầu!</p>
                        <a href="<?= BASE_URL ?>?action=chat/create-group" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tạo nhóm chat mới
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($groups as $group): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">
                                                <i class="bi bi-<?= $group['type'] === 'tour' ? 'map' : ($group['type'] === 'department' ? 'building' : 'people') ?>"></i>
                                                <?= htmlspecialchars($group['name']) ?>
                                            </h5>
                                            <?php if ($group['type'] === 'tour' && $group['tour_name']): ?>
                                                <small class="text-muted">
                                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($group['tour_name']) ?> (<?= htmlspecialchars($group['tour_code']) ?>)
                                                </small>
                                            <?php elseif ($group['type'] === 'department' && $group['department']): ?>
                                                <small class="text-muted">
                                                    <i class="bi bi-building"></i> <?= htmlspecialchars($group['department']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($group['unread_count'] > 0): ?>
                                            <span class="badge bg-danger rounded-pill"><?= $group['unread_count'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($group['description']): ?>
                                        <p class="card-text text-muted small mb-2">
                                            <?= htmlspecialchars($group['description']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($group['last_message']): ?>
                                        <p class="card-text small text-muted mb-2">
                                            <i class="bi bi-chat-left"></i> 
                                            <?= htmlspecialchars(mb_substr($group['last_message'], 0, 50)) ?>
                                            <?= mb_strlen($group['last_message']) > 50 ? '...' : '' ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> 
                                            <?= $group['last_message_time'] ? date('d/m/Y H:i', strtotime($group['last_message_time'])) : '' ?>
                                        </small>
                                    <?php else: ?>
                                        <p class="card-text small text-muted mb-0">
                                            <i>Chưa có tin nhắn</i>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-transparent border-top">
                                    <a href="<?= BASE_URL ?>?action=chat/view&group_id=<?= $group['id'] ?>" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-arrow-right-circle"></i> Mở chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}
</style>

