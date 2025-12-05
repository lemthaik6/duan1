<?php

class HotelRoomAssignmentModel extends BaseModel
{
    protected $table = 'hotel_room_assignments';
    public function getByTour($tourId)
    {
        $sql = "SELECT h.*, tc.full_name as customer_name, tc.phone as customer_phone,
                u.full_name as assigned_by_name
                FROM {$this->table} h
                INNER JOIN tour_customers tc ON h.customer_id = tc.id
                LEFT JOIN users u ON h.assigned_by = u.id
                WHERE h.tour_id = :tour_id
                ORDER BY h.check_in_date ASC, tc.full_name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }
   
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy phân phòng theo khách
     */
    public function getByCustomer($customerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE customer_id = :customer_id ORDER BY check_in_date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }
    /**
     * Tạo hoặc cập nhật phân phòng
     * Logic: Nếu đã có phân phòng cho customer trong tour với cùng check_in_date thì update
     * Nếu không thì tạo mới
     */
    public function upsert($data)
    {
        // Validate required fields
        if (empty($data['tour_id']) || empty($data['customer_id']) || 
            empty($data['hotel_name']) || empty($data['check_in_date']) || 
            empty($data['check_out_date'])) {
            return false;
        }
        
        // Kiểm tra xem đã có phân phòng chưa (theo tour, customer và check_in_date)
        $sql = "SELECT id FROM {$this->table} 
                WHERE tour_id = :tour_id AND customer_id = :customer_id 
                AND check_in_date = :check_in_date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $data['tour_id'],
            'customer_id' => $data['customer_id'],
            'check_in_date' => $data['check_in_date']
        ]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Cập nhật phân phòng hiện có
            $sql = "UPDATE {$this->table} 
                    SET hotel_name = :hotel_name, 
                        room_type = :room_type, check_out_date = :check_out_date,
                        notes = :notes, assigned_by = :assigned_by
                    WHERE id = :id";
            $data['id'] = $existing['id'];
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } else {
            // Tạo mới phân phòng
            $sql = "INSERT INTO {$this->table} 
                    (tour_id, customer_id, hotel_name, room_type, 
                     check_in_date, check_out_date, notes, assigned_by) 
                    VALUES 
                    (:tour_id, :customer_id, :hotel_name, :room_type, 
                     :check_in_date, :check_out_date, :notes, :assigned_by)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        }
    }
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy thống kê phân phòng theo tour
     * Đếm số phòng unique (nhóm theo hotel_name, room_number, check_in_date, check_out_date)
     */
    public function getStatsByTour($tourId)
    {
        // Đếm số records (số khách hàng được phân phòng)
        $sql = "SELECT 
                    COUNT(*) as total_assignments,
                    COUNT(DISTINCT hotel_name) as total_hotels,
                    SUM(CASE WHEN room_type = 'single' THEN 1 ELSE 0 END) as single_rooms,
                    SUM(CASE WHEN room_type = 'double' THEN 1 ELSE 0 END) as double_rooms,
                    SUM(CASE WHEN room_type = 'twin' THEN 1 ELSE 0 END) as twin_rooms,
                    SUM(CASE WHEN room_type = 'triple' THEN 1 ELSE 0 END) as triple_rooms,
                    SUM(CASE WHEN room_type = 'family' THEN 1 ELSE 0 END) as family_rooms
                FROM {$this->table}
                WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        $stats = $stmt->fetch();
        
        // Đếm số phòng unique (nhóm theo hotel_name, check_in_date, check_out_date)
        $sqlUnique = "SELECT COUNT(DISTINCT CONCAT(
                    COALESCE(hotel_name, ''), '|',
                    check_in_date, '|',
                    check_out_date
                )) as total_rooms
                FROM {$this->table}
                WHERE tour_id = :tour_id";
        $stmtUnique = $this->pdo->prepare($sqlUnique);
        $stmtUnique->execute(['tour_id' => $tourId]);
        $uniqueStats = $stmtUnique->fetch();
        
        // Merge kết quả
        if ($stats && $uniqueStats) {
            $stats['total_rooms'] = $uniqueStats['total_rooms'] ?? 0;
        }
        
        return $stats ?: [
            'total_rooms' => 0,
            'total_assignments' => 0,
            'total_hotels' => 0,
            'single_rooms' => 0,
            'double_rooms' => 0,
            'twin_rooms' => 0,
            'triple_rooms' => 0,
            'family_rooms' => 0
        ];
    }
}

