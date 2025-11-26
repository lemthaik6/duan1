<?php

class TourCostModel extends BaseModel
{
    protected $table = 'tour_costs';

    /**
     * Lấy chi phí theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT tc.*, cc.name as category_name, u.full_name as created_by_name
                FROM {$this->table} tc
                LEFT JOIN cost_categories cc ON tc.cost_category_id = cc.id
                LEFT JOIN users u ON tc.created_by = u.id
                WHERE tc.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

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
     * Tính tổng chi phí tour (bao gồm giá gốc nội bộ + chi phí phát sinh)
     */
    public function getTotalCost($tourId, $includeInternalPrice = true)
    {
        // Tính tổng chi phí phát sinh
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        $result = $stmt->fetch();
        $costsTotal = $result['total'] ?? 0;
        
        // Nếu cần bao gồm giá gốc nội bộ
        if ($includeInternalPrice) {
            $sql = "SELECT internal_price FROM tours WHERE id = :tour_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['tour_id' => $tourId]);
            $tour = $stmt->fetch();
            $internalPrice = $tour['internal_price'] ?? 0;
            
            return $costsTotal + $internalPrice;
        }
        
        return $costsTotal;
    }

    /**
     * Tính tổng chi phí theo tour hoặc tất cả (bao gồm giá gốc nội bộ)
     */
    public function getTotalCostByTour($tourId = null, $includeInternalPrice = true)
    {
        // Tính tổng chi phí phát sinh
        $sql = "SELECT SUM(amount) as total FROM {$this->table}";
        $params = [];
        
        if ($tourId) {
            $sql .= " WHERE tour_id = :tour_id";
            $params['tour_id'] = $tourId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $costsTotal = $result['total'] ?? 0;
        
        // Nếu cần bao gồm giá gốc nội bộ
        if ($includeInternalPrice) {
            $sql = "SELECT SUM(internal_price) as total FROM tours";
            $params = [];
            
            if ($tourId) {
                $sql .= " WHERE id = :tour_id";
                $params['tour_id'] = $tourId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            $internalPriceTotal = $result['total'] ?? 0;
            
            return $costsTotal + $internalPriceTotal;
        }
        
        return $costsTotal;
    }
    
    /**
     * Tính chỉ chi phí phát sinh (không bao gồm giá gốc)
     */
    public function getCostsOnly($tourId)
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
        // Xây dựng SQL động để phù hợp với dữ liệu thực tế
        $fields = [];
        $params = [];

        $fields[] = 'tour_id';
        $params['tour_id'] = $data['tour_id'];

        $fields[] = 'cost_category_id';
        $params['cost_category_id'] = $data['cost_category_id'];

        if (!empty($data['description'])) {
            $fields[] = 'description';
            $params['description'] = $data['description'];
        }

        $fields[] = 'amount';
        $params['amount'] = $data['amount'];

        $fields[] = 'date';
        $params['date'] = $data['date'] ?? date('Y-m-d');

        if (!empty($data['invoice_image'])) {
            $fields[] = 'invoice_image';
            $params['invoice_image'] = $data['invoice_image'];
        }

        $fields[] = 'created_by';
        $params['created_by'] = $data['created_by'];

        $placeholders = implode(', ', array_map(fn($f) => ":$f", $fields));
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                VALUES (" . $placeholders . ")";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
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

