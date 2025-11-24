<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Tạo nhóm chat mới
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Loại nhóm <span class="text-danger">*</span></label>
                            <select name="type" id="groupType" class="form-select" required onchange="toggleTypeFields()">
                                <option value="general">Nhóm chung</option>
                                <option value="tour">Nhóm theo tour</option>
                                <option value="department">Nhóm theo phòng ban</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tên nhóm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required 
                                   placeholder="Nhập tên nhóm chat">
                        </div>

                        <!-- Tour selection (only for tour type) -->
                        <div class="mb-3" id="tourField" style="display: none;">
                            <label class="form-label">Chọn tour</label>
                            <select name="tour_id" class="form-select">
                                <option value="">-- Chọn tour --</option>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= $tour['id'] ?>">
                                        <?= htmlspecialchars($tour['name']) ?> (<?= htmlspecialchars($tour['code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">HDV và điều hành sẽ tự động được thêm vào nhóm</small>
                        </div>

                        <!-- Department field (only for department type) -->
                        <div class="mb-3" id="departmentField" style="display: none;">
                            <label class="form-label">Tên phòng ban</label>
                            <input type="text" name="department" class="form-control" 
                                   placeholder="Ví dụ: Phòng Điều hành, Phòng Kinh doanh...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Mô tả về nhóm chat (tùy chọn)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thêm thành viên</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($allUsers as $user): ?>
                                    <?php if ($user['id'] != getCurrentUser()['id']): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="member_ids[]" 
                                                   value="<?= $user['id'] ?>" 
                                                   id="user_<?= $user['id'] ?>">
                                            <label class="form-check-label" for="user_<?= $user['id'] ?>">
                                                <?= htmlspecialchars($user['full_name']) ?>
                                                <small class="text-muted">
                                                    (<?= $user['role'] === 'admin' ? 'Admin' : 'HDV' ?>)
                                                </small>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-muted">Bạn có thể thêm thành viên sau</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Tạo nhóm
                            </button>
                            <a href="<?= BASE_URL ?>?action=chat/index" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTypeFields() {
    const type = document.getElementById('groupType').value;
    const tourField = document.getElementById('tourField');
    const departmentField = document.getElementById('departmentField');
    
    if (type === 'tour') {
        tourField.style.display = 'block';
        departmentField.style.display = 'none';
        document.querySelector('[name="tour_id"]').required = true;
        document.querySelector('[name="department"]').required = false;
    } else if (type === 'department') {
        tourField.style.display = 'none';
        departmentField.style.display = 'block';
        document.querySelector('[name="tour_id"]').required = false;
        document.querySelector('[name="department"]').required = true;
    } else {
        tourField.style.display = 'none';
        departmentField.style.display = 'none';
        document.querySelector('[name="tour_id"]').required = false;
        document.querySelector('[name="department"]').required = false;
    }
}
</script>

