<?php

class TourCostModel extends BaseModel
{
    protected $table = 'tour_costs';

    /**
     * Lấy tất cả chi phí của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT tc.*, cc.name as category_name, u.full_name as created_by_name
                FROM {$this->table} tc
                LEFT JOIN cost_categories cc ON tc.cost_category_id = cc.id
                LEFT JOIN users u ON tc.created_by = u.id
                WHERE tc.tour_id = :tour_id
                ORDER BY tc.date DESC, tc.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Tính tổng chi phí tour
     */
    public function getTotalCost($tourId)
    {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Tính chi phí theo loại
     */
    public function getCostByCategory($tourId)
    {
        $sql = "SELECT cc.name, SUM(tc.amount) as total
                FROM {$this->table} tc
                LEFT JOIN cost_categories cc ON tc.cost_category_id = cc.id
                WHERE tc.tour_id = :tour_id
                GROUP BY cc.id, cc.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm chi phí
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, cost_category_id, description, amount, date, invoice_image, created_by) 
                VALUES 
                (:tour_id, :cost_category_id, :description, :amount, :date, :invoice_image, :created_by)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật chi phí
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
     * Xóa chi phí
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

