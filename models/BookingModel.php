<?php

class BookingModel extends BaseModel
{
    protected $table = 'bookings';

    /**
     * Tạo mã booking tự động
     */
    private function generateBookingCode()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return $prefix . $date . $random;
    }

    /**
     * Lấy tất cả booking
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT b.*, t.name as tour_name, t.code as tour_code, 
                u.full_name as created_by_name
                FROM {$this->table} b
                INNER JOIN tours t ON b.tour_id = t.id
                LEFT JOIN users u ON b.created_by = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND b.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['tour_id'])) {
            $sql .= " AND b.tour_id = :tour_id";
            $params['tour_id'] = $filters['tour_id'];
        }

        if (!empty($filters['booking_date_from'])) {
            $sql .= " AND b.booking_date >= :booking_date_from";
            $params['booking_date_from'] = $filters['booking_date_from'];
        }

        if (!empty($filters['booking_date_to'])) {
            $sql .= " AND b.booking_date <= :booking_date_to";
            $params['booking_date_to'] = $filters['booking_date_to'];
        }

        $sql .= " ORDER BY b.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy booking theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT b.*, t.name as tour_name, t.code as tour_code, 
                t.start_date, t.end_date,
                u.full_name as created_by_name
                FROM {$this->table} b
                INNER JOIN tours t ON b.tour_id = t.id
                LEFT JOIN users u ON b.created_by = u.id
                WHERE b.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo booking mới
     */
    public function create($data)
    {
        if (empty($data['booking_code'])) {
            $data['booking_code'] = $this->generateBookingCode();
        }

        $sql = "INSERT INTO {$this->table} 
                (booking_code, tour_id, booking_type, customer_name, customer_phone, 
                 customer_email, customer_address, number_of_guests, total_amount, 
                 deposit_amount, remaining_amount, status, booking_date, 
                 special_requests, notes, created_by) 
                VALUES 
                (:booking_code, :tour_id, :booking_type, :customer_name, :customer_phone, 
                 :customer_email, :customer_address, :number_of_guests, :total_amount, 
                 :deposit_amount, :remaining_amount, :status, :booking_date, 
                 :special_requests, :notes, :created_by)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật booking
     */
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật trạng thái booking và lưu lịch sử
     */
    public function updateStatus($id, $newStatus, $changedBy, $notes = null)
    {
        // Lấy trạng thái cũ
        $booking = $this->getById($id);
        $oldStatus = $booking['status'] ?? null;

        // Cập nhật trạng thái
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute(['id' => $id, 'status' => $newStatus]);

        if ($result && $oldStatus != $newStatus) {
            // Lưu lịch sử thay đổi
            $historySql = "INSERT INTO booking_status_history 
                          (booking_id, old_status, new_status, changed_by, notes) 
                          VALUES 
                          (:booking_id, :old_status, :new_status, :changed_by, :notes)";
            $historyStmt = $this->pdo->prepare($historySql);
            $historyStmt->execute([
                'booking_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy,
                'notes' => $notes
            ]);
        }

        return $result;
    }

    /**
     * Lấy lịch sử thay đổi trạng thái
     */
    public function getStatusHistory($bookingId)
    {
        $sql = "SELECT h.*, u.full_name as changed_by_name
                FROM booking_status_history h
                LEFT JOIN users u ON h.changed_by = u.id
                WHERE h.booking_id = :booking_id
                ORDER BY h.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetchAll();
    }

    /**
     * Đếm booking theo trạng thái
     */
    public function countByStatus($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($status) {
            $sql .= " WHERE status = :status";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Tính tổng doanh thu
     */
    public function getTotalRevenue($filters = [])
    {
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table} WHERE status IN ('confirmed', 'completed')";
        
        $params = [];
        if (!empty($filters['tour_id'])) {
            $sql .= " AND tour_id = :tour_id";
            $params['tour_id'] = $filters['tour_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND booking_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND booking_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}

