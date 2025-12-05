<?php

class TourVehicleAssignmentModel extends BaseModel
{
    protected $table = 'vehicle_assignments';

    /**
     * Lấy tất cả xe được phân công cho tour
     */
    public function getByTour($tour_id)
    {
        $sql = "SELECT va.*, v.license_plate, v.vehicle_type, v.capacity, v.driver_name, v.driver_phone
                FROM {$this->table} va
                LEFT JOIN vehicles v ON va.vehicle_id = v.id
                WHERE va.tour_id = :tour_id
                ORDER BY va.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy chi tiết một phân công xe
     */
    public function getById($id)
    {
        $sql = "SELECT va.*, v.license_plate, v.vehicle_type, v.capacity, v.driver_name, v.driver_phone
                FROM {$this->table} va
                LEFT JOIN vehicles v ON va.vehicle_id = v.id
                WHERE va.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo phân công xe cho tour
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, vehicle_id, usage_purpose, start_date, end_date, notes) 
                VALUES 
                (:tour_id, :vehicle_id, :usage_purpose, :start_date, :end_date, :notes)";
        $stmt = $this->pdo->prepare($sql);
        // Loại bỏ status từ data để tránh lỗi
        $dataToExecute = [
            'tour_id' => $data['tour_id'],
            'vehicle_id' => $data['vehicle_id'],
            'usage_purpose' => $data['usage_purpose'] ?? '',
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'notes' => $data['notes'] ?? ''
        ];
        return $stmt->execute($dataToExecute);
    }

    /**
     * Cập nhật phân công xe
     */
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = :$key";
            }
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Xóa phân công xe
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Kiểm tra xe có conflict về thời gian không
     */
    public function checkVehicleConflict($vehicle_id, $start_date, $end_date, $exclude_id = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE vehicle_id = :vehicle_id 
                AND (
                    (start_date <= :end_date AND end_date >= :start_date)
                )";
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [
            'vehicle_id' => $vehicle_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        if ($exclude_id) {
            $params['exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Lấy xe đang hoạt động của tour
     */
    public function getActiveByTour($tour_id)
    {
        $sql = "SELECT va.*, v.license_plate, v.vehicle_type, v.capacity, v.driver_name, v.driver_phone
                FROM {$this->table} va
                LEFT JOIN vehicles v ON va.vehicle_id = v.id
                WHERE va.tour_id = :tour_id
                ORDER BY va.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy số xe được phân công cho tour
     */
    public function countByTour($tour_id)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
