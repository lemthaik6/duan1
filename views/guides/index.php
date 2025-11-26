<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-people"></i> Quản lý Hướng dẫn viên</h2>
        <a href="<?= BASE_URL ?>?action=guides/create" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Thêm HDV mới
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Danh sách Hướng dẫn viên (<?= count($guides) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($guides)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Tên đăng nhập</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Số tour đã đi</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($guides as $guide): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($guide['full_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($guide['username']) ?></td>
                                    <td><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= $guide['total_tours'] ?? 0 ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $guide['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= $guide['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=guides/view&id=<?= $guide['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=guides/edit&id=<?= $guide['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= BASE_URL ?>?action=guides/delete" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa hướng dẫn viên này?');">
                                            <input type="hidden" name="id" value="<?= $guide['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Chưa có hướng dẫn viên nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

