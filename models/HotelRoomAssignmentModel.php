<?php

class HotelRoomAssignmentModel extends BaseModel
{
    protected $table = 'hotel_room_assignments';

    /**
     * Lấy phân phòng theo tour
     */
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
     */
    public function upsert($data)
    {
        // Kiểm tra xem đã có phân phòng chưa
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
            // Cập nhật
            $sql = "UPDATE {$this->table} 
                    SET hotel_name = :hotel_name, room_number = :room_number, 
                        room_type = :room_type, check_out_date = :check_out_date,
                        notes = :notes
                    WHERE id = :id";
            $data['id'] = $existing['id'];
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } else {
            // Tạo mới
            $sql = "INSERT INTO {$this->table} 
                    (tour_id, customer_id, hotel_name, room_number, room_type, 
                     check_in_date, check_out_date, notes, assigned_by) 
                    VALUES 
                    (:tour_id, :customer_id, :hotel_name, :room_number, :room_type, 
                     :check_in_date, :check_out_date, :notes, :assigned_by)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        }
    }

    /**
     * Xóa phân phòng
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy thống kê phân phòng theo tour
     */
    public function getStatsByTour($tourId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_rooms,
                    COUNT(DISTINCT hotel_name) as total_hotels,
                    SUM(CASE WHEN room_type = 'single' THEN 1 ELSE 0 END) as single_rooms,
                    SUM(CASE WHEN room_type = 'double' THEN 1 ELSE 0 END) as double_rooms,
                    SUM(CASE WHEN room_type = 'twin' THEN 1 ELSE 0 END) as twin_rooms
                FROM {$this->table}
                WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetch();
    }
}

