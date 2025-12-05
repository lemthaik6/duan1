<?php

class CustomerAttendanceModel extends BaseModel
{
    protected $table = 'customer_attendance';

    public function getByTourAndDate($tourId, $date)
    {
        $sql = "SELECT ca.*, tc.full_name, tc.phone, tc.email
                FROM {$this->table} ca
                INNER JOIN tour_customers tc ON ca.customer_id = tc.id
                WHERE ca.tour_id = :tour_id AND ca.date = :date
                ORDER BY tc.full_name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId, 'date' => $date]);
        return $stmt->fetchAll();
    }

    public function getByTour($tourId)
    {
        $sql = "SELECT ca.*, tc.full_name, tc.phone
                FROM {$this->table} ca
                INNER JOIN tour_customers tc ON ca.customer_id = tc.id
                WHERE ca.tour_id = :tour_id
                ORDER BY ca.date DESC, tc.full_name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }
    public function upsert($data)
    {
        $sql = "SELECT id FROM {$this->table} 
                WHERE tour_id = :tour_id AND customer_id = :customer_id AND date = :date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $data['tour_id'],
            'customer_id' => $data['customer_id'],
            'date' => $data['date']
        ]);
        $existing = $stmt->fetch();

        if ($existing) {
            // UPDATE: Chỉ sử dụng các tham số cần thiết
            $sql = "UPDATE {$this->table} 
                    SET status = :status, check_in_time = :check_in_time, 
                        notes = :notes, recorded_by = :recorded_by
                    WHERE id = :id";
            $updateParams = [
                'status' => $data['status'],
                'check_in_time' => $data['check_in_time'],
                'notes' => $data['notes'],
                'recorded_by' => $data['recorded_by'],
                'id' => $existing['id']
            ];
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($updateParams);
        } else {
            // INSERT: Sử dụng tất cả các tham số
            $sql = "INSERT INTO {$this->table} 
                    (tour_id, customer_id, date, status, check_in_time, notes, recorded_by) 
                    VALUES 
                    (:tour_id, :customer_id, :date, :status, :check_in_time, :notes, :recorded_by)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        }
    }
    public function getStatsByTour($tourId, $date = null)
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late
                FROM {$this->table}
                WHERE tour_id = :tour_id";
        
        $params = ['tour_id' => $tourId];
        
        if ($date) {
            $sql .= " AND date = :date";
            $params['date'] = $date;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}

