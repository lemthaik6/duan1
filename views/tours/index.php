<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-map"></i> Quản lý Tour</h2>
        <a href="<?= BASE_URL ?>?action=tours/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo tour mới
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="tours/index">
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="upcoming" <?= ($_GET['status'] ?? '') === 'upcoming' ? 'selected' : '' ?>>Sắp diễn ra</option>
                        <option value="ongoing" <?= ($_GET['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Đang diễn ra</option>
                        <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">Tất cả</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($_GET['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="<?= BASE_URL ?>?action=tours/index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách tour -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách Tour (<?= count($tours) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($tours)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã tour</th>
                                <th>Tên tour</th>
                                <th>Danh mục</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Ưu tiên</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($tour['code']) ?></strong></td>
                                    <td><?= htmlspecialchars($tour['name']) ?></td>
                                    <td><?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?></td>
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
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=tours/edit&id=<?= $tour['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?action=tours/delete&id=<?= $tour['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa tour này?')" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Không có tour nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

