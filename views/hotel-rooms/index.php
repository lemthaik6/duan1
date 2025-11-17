<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-building"></i> Phân phòng khách sạn</h2>
        <div>
            <a href="<?= BASE_URL ?>?action=customers/print&tour_id=<?= $tour['id'] ?>" class="btn btn-info me-2" target="_blank">
                <i class="bi bi-printer"></i> In danh sách đoàn
            </a>
            <a href="<?= BASE_URL ?>?action=tours/view&id=<?= $tour['id'] ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5><?= htmlspecialchars($tour['name']) ?></h5>
            <p class="mb-0 text-muted">Mã tour: <?= htmlspecialchars($tour['code']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form phân phòng -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Phân phòng mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?action=hotel-rooms/save">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Khách <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">-- Chọn khách --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['id'] ?>">
                                        <?= htmlspecialchars($customer['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tên khách sạn <span class="text-danger">*</span></label>
                            <input type="text" name="hotel_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số phòng</label>
                            <input type="text" name="room_number" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại phòng</label>
                            <select name="room_type" class="form-select">
                                <option value="single">Phòng đơn</option>
                                <option value="double" selected>Phòng đôi</option>
                                <option value="twin">Phòng 2 giường đơn</option>
                                <option value="triple">Phòng 3 người</option>
                                <option value="family">Phòng gia đình</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày check-in <span class="text-danger">*</span></label>
                            <input type="date" name="check_in_date" class="form-control" 
                                   value="<?= $tour['start_date'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày check-out <span class="text-danger">*</span></label>
                            <input type="date" name="check_out_date" class="form-control" 
                                   value="<?= $tour['end_date'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Phân phòng
                        </button>
                    </form>
                </div>
            </div>

            <!-- Thống kê -->
            <?php if ($stats && $stats['total_rooms'] > 0): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Thống kê</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Tổng phòng:</strong> <?= $stats['total_rooms'] ?></p>
                        <p class="mb-1"><strong>Số khách sạn:</strong> <?= $stats['total_hotels'] ?></p>
                        <p class="mb-0"><strong>Phòng đơn:</strong> <?= $stats['single_rooms'] ?></p>
                        <p class="mb-0"><strong>Phòng đôi:</strong> <?= $stats['double_rooms'] ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Danh sách phân phòng -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách phân phòng (<?= count($rooms) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($rooms)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Khách</th>
                                        <th>Khách sạn</th>
                                        <th>Số phòng</th>
                                        <th>Loại phòng</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rooms as $room): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($room['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($room['hotel_name']) ?></td>
                                            <td><?= htmlspecialchars($room['room_number'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php
                                                $roomTypes = [
                                                    'single' => 'Phòng đơn',
                                                    'double' => 'Phòng đôi',
                                                    'twin' => '2 giường đơn',
                                                    'triple' => '3 người',
                                                    'family' => 'Gia đình'
                                                ];
                                                echo $roomTypes[$room['room_type']] ?? $room['room_type'];
                                                ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($room['check_in_date'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($room['check_out_date'])) ?></td>
                                            <td>
                                                <form method="POST" action="<?= BASE_URL ?>?action=hotel-rooms/delete" 
                                                      style="display: inline;" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa phân phòng này?');">
                                                    <input type="hidden" name="id" value="<?= $room['id'] ?>">
                                                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
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
                        <p class="text-muted text-center py-4">Chưa có phân phòng nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

