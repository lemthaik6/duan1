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

        // Các trường bắt buộc
        $fields[] = 'tour_id';
        $params['tour_id'] = $data['tour_id'] ?? null;

        $fields[] = 'cost_category_id';
        $params['cost_category_id'] = $data['cost_category_id'] ?? null;

        $fields[] = 'amount';
        $params['amount'] = $data['amount'] ?? 0;

        $fields[] = 'date';
        $params['date'] = $data['date'] ?? date('Y-m-d');

        $fields[] = 'created_by';
        $params['created_by'] = $data['created_by'] ?? null;

        // Các trường không bắt buộc
        if (!empty($data['description'])) {
            $fields[] = 'description';
            $params['description'] = $data['description'];
        }

        if (!empty($data['invoice_image'])) {
            $fields[] = 'invoice_image';
            $params['invoice_image'] = $data['invoice_image'];
        }

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
     * Xóa chi phí
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Tính lợi nhuận theo công thức:
     * profit = (number_of_guests * price_per_guest) - (fixed_cost + (variable_cost_per_guest * number_of_guests))
     * 
     * @param int|null $tourId - ID tour, null để tính cho tất cả
     * @return array ['number_of_guests' => ..., 'total_revenue' => ..., 'fixed_cost' => ..., 
     *               'variable_cost_per_guest' => ..., 'total_variable_cost' => ..., 'profit' => ...]
     */
    public function calculateProfitByFormula($tourId = null)
    {
        // Lấy tổng số khách
        $bookingModel = new BookingModel();
        $totalGuests = $bookingModel->getTotalGuestsByTour($tourId) ?? 0;
        
        // Lấy tổng doanh thu
        $filters = $tourId ? ['tour_id' => $tourId] : [];
        $totalRevenue = $bookingModel->getTotalRevenue($filters);
        
        // Tính giá trung bình per guest
        $pricePerGuest = $totalGuests > 0 ? ($totalRevenue / $totalGuests) : 0;
        
        // Lấy chi phí phát sinh (variable costs)
        $sql = "SELECT SUM(amount) as total FROM {$this->table}";
        $params = [];
        if ($tourId) {
            $sql .= " WHERE tour_id = :tour_id";
            $params['tour_id'] = $tourId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $variableCostsTotal = $result['total'] ?? 0;
        
        // Tính variable cost per guest
        $variableCostPerGuest = $totalGuests > 0 ? ($variableCostsTotal / $totalGuests) : 0;
        
        // Lấy chi phí cố định (internal price - giá gốc nội bộ)
        $sql = "SELECT SUM(internal_price) as total FROM tours";
        $params = [];
        if ($tourId) {
            $sql .= " WHERE id = :tour_id";
            $params['tour_id'] = $tourId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $fixedCost = $result['total'] ?? 0;
        
        // Áp dụng công thức:
        // profit = (number_of_guests * price_per_guest) - (fixed_cost + (variable_cost_per_guest * number_of_guests))
        $profit = ($totalGuests * $pricePerGuest) - ($fixedCost + ($variableCostPerGuest * $totalGuests));
        
        return [
            'number_of_guests' => $totalGuests,
            'price_per_guest' => round($pricePerGuest, 0),
            'total_revenue' => $totalRevenue,
            'fixed_cost' => $fixedCost,
            'variable_cost_per_guest' => round($variableCostPerGuest, 0),
            'total_variable_cost' => $variableCostsTotal,
            'profit' => round($profit, 0),
            'profit_margin' => $totalRevenue > 0 ? round(($profit / $totalRevenue * 100), 2) : 0
        ];
    }

    /**
     * Tính lợi nhuận theo công thức cho tất cả tours hoặc filter theo điều kiện
     * Hữu ích cho báo cáo tổng hợp
     */
    public function calculateProfitByFormulaWithFilters($filters = [])
    {
        // Lấy tổng số khách dựa trên filter
        $bookingModel = new BookingModel();
        $totalGuests = 0;
        $totalRevenue = 0;
        
        // Xây dựng SQL cho khách
        $guestSql = "SELECT SUM(number_of_guests) as total FROM bookings 
                     WHERE status IN ('deposited', 'confirmed', 'completed')";
        $variableCostSql = "SELECT SUM(amount) as total FROM {$this->table}";
        $fixedCostSql = "SELECT SUM(internal_price) as total FROM tours";
        
        $params = [];
        
        // Áp dụng filter tour_id
        if (!empty($filters['tour_id'])) {
            $guestSql .= " AND tour_id = :tour_id";
            $variableCostSql .= " WHERE tour_id = :tour_id";
            $fixedCostSql .= " WHERE id = :tour_id";
            $params['tour_id'] = $filters['tour_id'];
        }
        
        // Áp dụng filter ngày
        if (!empty($filters['date_from'])) {
            $guestSql .= " AND booking_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $guestSql .= " AND booking_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Lấy tổng số khách
        $stmt = $this->pdo->prepare($guestSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $totalGuests = $result['total'] ?? 0;
        
        // Lấy tổng doanh thu
        $revenueSql = "SELECT SUM(total_amount) as total FROM bookings 
                       WHERE status IN ('confirmed', 'completed')";
        if (!empty($filters['tour_id'])) {
            $revenueSql .= " AND tour_id = :tour_id";
        }
        if (!empty($filters['date_from'])) {
            $revenueSql .= " AND booking_date >= :date_from";
        }
        if (!empty($filters['date_to'])) {
            $revenueSql .= " AND booking_date <= :date_to";
        }
        
        $stmt = $this->pdo->prepare($revenueSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $totalRevenue = $result['total'] ?? 0;
        
        // Tính giá trung bình per guest
        $pricePerGuest = $totalGuests > 0 ? ($totalRevenue / $totalGuests) : 0;
        
        // Lấy chi phí phát sinh
        $stmt = $this->pdo->prepare($variableCostSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $variableCostsTotal = $result['total'] ?? 0;
        
        // Tính variable cost per guest
        $variableCostPerGuest = $totalGuests > 0 ? ($variableCostsTotal / $totalGuests) : 0;
        
        // Lấy chi phí cố định
        $stmt = $this->pdo->prepare($fixedCostSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $fixedCost = $result['total'] ?? 0;
        
        // Áp dụng công thức: profit = (number_of_guests * price_per_guest) - (fixed_cost + (variable_cost_per_guest * number_of_guests))
        $profit = ($totalGuests * $pricePerGuest) - ($fixedCost + ($variableCostPerGuest * $totalGuests));
        
        return [
            'number_of_guests' => $totalGuests,
            'price_per_guest' => round($pricePerGuest, 0),
            'total_revenue' => round($totalRevenue, 0),
            'fixed_cost' => round($fixedCost, 0),
            'variable_cost_per_guest' => round($variableCostPerGuest, 0),
            'total_variable_cost' => round($variableCostsTotal, 0),
            'profit' => round($profit, 0),
            'profit_margin' => $totalRevenue > 0 ? round(($profit / $totalRevenue * 100), 2) : 0
        ];
    }
}

