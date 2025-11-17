<?php

class TourCustomerModel extends BaseModel
{
    protected $table = 'tour_customers';

    /**
     * Lấy danh sách khách của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :tour_id ORDER BY full_name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm khách vào tour
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, full_name, phone, email, id_card, notes, special_requests) 
                VALUES 
                (:tour_id, :full_name, :phone, :email, :id_card, :notes, :special_requests)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật thông tin khách
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
     * Xóa khách
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Đếm số khách của tour
     */
    public function countByTour($tourId)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Lấy khách theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}

