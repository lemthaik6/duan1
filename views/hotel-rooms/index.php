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
                    <form method="POST" action="<?= BASE_URL ?>?action=hotel-rooms/save" onsubmit="return validateForm()">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                            <small class="text-muted d-block mb-2">Có thể chọn nhiều khách để ở chung phòng (ví dụ: gia đình)</small>
                            <?php if (empty($customers)): ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Tour này chưa có khách hàng
                                </div>
                            <?php else: ?>
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    <?php foreach ($customers as $customer): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="customer_ids[]" 
                                                   value="<?= intval($customer['id']) ?>" id="customer_<?= $customer['id'] ?>">
                                            <label class="form-check-label" for="customer_<?= $customer['id'] ?>">
                                                <?= htmlspecialchars($customer['full_name'] ?? 'N/A') ?>
                                                <?php if (!empty($customer['phone'])): ?>
                                                    <small class="text-muted">(<?= htmlspecialchars($customer['phone']) ?>)</small>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-danger" id="customer-error" style="display: none;">Vui lòng chọn ít nhất 1 khách hàng</small>
                            <?php endif; ?>
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
                                   value="<?= !empty($tour['start_date']) ? htmlspecialchars($tour['start_date']) : '' ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày check-out <span class="text-danger">*</span></label>
                            <input type="date" name="check_out_date" class="form-control" 
                                   value="<?= !empty($tour['end_date']) ? htmlspecialchars($tour['end_date']) : '' ?>" 
                                   required>
                            <small class="text-muted">Phải sau ngày check-in</small>
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
            <?php if ($stats && ($stats['total_rooms'] > 0 || $stats['total_assignments'] > 0)): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Thống kê</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Tổng phòng:</strong> <?= $stats['total_rooms'] ?? 0 ?></p>
                        <p class="mb-1"><strong>Tổng phân phòng:</strong> <?= $stats['total_assignments'] ?? 0 ?></p>
                        <p class="mb-1"><strong>Số khách sạn:</strong> <?= $stats['total_hotels'] ?? 0 ?></p>
                        <hr class="my-2">
                        <p class="mb-1"><strong>Phòng đơn:</strong> <?= $stats['single_rooms'] ?? 0 ?></p>
                        <p class="mb-1"><strong>Phòng đôi:</strong> <?= $stats['double_rooms'] ?? 0 ?></p>
                        <p class="mb-1"><strong>2 giường đơn:</strong> <?= $stats['twin_rooms'] ?? 0 ?></p>
                        <p class="mb-1"><strong>3 người:</strong> <?= $stats['triple_rooms'] ?? 0 ?></p>
                        <p class="mb-0"><strong>Gia đình:</strong> <?= $stats['family_rooms'] ?? 0 ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Danh sách phân phòng -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách phân phòng</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($rooms)): 
                        // Nhóm phòng theo hotel_name, room_number, check_in_date, check_out_date
                        $groupedRooms = [];
                        foreach ($rooms as $room) {
                            // Tạo key để nhóm phòng (xử lý null/empty room_number)
                            $roomNumber = !empty($room['room_number']) ? trim($room['room_number']) : '';
                            $key = trim($room['hotel_name']) . '|' . $roomNumber . '|' . 
                                   $room['check_in_date'] . '|' . $room['check_out_date'];
                            
                            if (!isset($groupedRooms[$key])) {
                                $groupedRooms[$key] = [
                                    'hotel_name' => $room['hotel_name'],
                                    'room_number' => !empty($roomNumber) ? $roomNumber : 'N/A',
                                    'room_type' => $room['room_type'] ?? 'double',
                                    'check_in_date' => $room['check_in_date'],
                                    'check_out_date' => $room['check_out_date'],
                                    'notes' => $room['notes'] ?? '',
                                    'customers' => []
                                ];
                            }
                            $groupedRooms[$key]['customers'][] = [
                                'id' => $room['id'],
                                'customer_name' => $room['customer_name'] ?? 'N/A',
                                'customer_phone' => $room['customer_phone'] ?? ''
                            ];
                        }
                    ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Khách sạn</th>
                                        <th>Số phòng</th>
                                        <th>Loại phòng</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $roomTypes = [
                                        'single' => 'Phòng đơn',
                                        'double' => 'Phòng đôi',
                                        'twin' => '2 giường đơn',
                                        'triple' => '3 người',
                                        'family' => 'Gia đình'
                                    ];
                                    foreach ($groupedRooms as $group): 
                                        $customerCount = count($group['customers']);
                                    ?>
                                        <tr>
                                            <td>
                                                <?php foreach ($group['customers'] as $index => $customer): ?>
                                                    <div>
                                                        <strong><?= htmlspecialchars($customer['customer_name']) ?></strong>
                                                        <?php if (!empty($customer['customer_phone'])): ?>
                                                            <br><small class="text-muted"><?= htmlspecialchars($customer['customer_phone']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($index < $customerCount - 1): ?>
                                                        <hr class="my-1" style="margin: 0.25rem 0;">
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php if ($customerCount > 1): ?>
                                                    <small class="text-info d-block mt-1">
                                                        <i class="bi bi-people"></i> <?= $customerCount ?> người
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($group['hotel_name']) ?></td>
                                            <td><?= htmlspecialchars($group['room_number']) ?></td>
                                            <td><?= $roomTypes[$group['room_type']] ?? $group['room_type'] ?></td>
                                            <td>
                                                <?php 
                                                $checkIn = strtotime($group['check_in_date']);
                                                echo $checkIn ? date('d/m/Y', $checkIn) : 'N/A';
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $checkOut = strtotime($group['check_out_date']);
                                                echo $checkOut ? date('d/m/Y', $checkOut) : 'N/A';
                                                ?>
                                            </td>
                                            <td>
                                                <?php foreach ($group['customers'] as $customer): ?>
                                                    <form method="POST" action="<?= BASE_URL ?>?action=hotel-rooms/delete" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Bạn có chắc muốn xóa phân phòng của <?= htmlspecialchars($customer['customer_name'], ENT_QUOTES) ?>?');">
                                                        <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1" title="Xóa <?= htmlspecialchars($customer['customer_name'], ENT_QUOTES) ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    <br>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                Tổng: <strong><?= count($groupedRooms) ?></strong> phòng, 
                                <strong><?= count($rooms) ?></strong> khách hàng
                            </small>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Chưa có phân phòng nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const checkboxes = document.querySelectorAll('input[name="customer_ids[]"]:checked');
    const errorMsg = document.getElementById('customer-error');
    const checkInDate = document.querySelector('input[name="check_in_date"]').value;
    const checkOutDate = document.querySelector('input[name="check_out_date"]').value;
    
    // Validate customers
    if (checkboxes.length === 0) {
        if (errorMsg) {
            errorMsg.style.display = 'block';
        }
        return false;
    }
    
    if (errorMsg) {
        errorMsg.style.display = 'none';
    }
    
    // Validate dates
    if (checkInDate && checkOutDate) {
        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);
        
        if (checkOut <= checkIn) {
            alert('Ngày check-out phải sau ngày check-in!');
            return false;
        }
    }
    
    return true;
}
</script>

