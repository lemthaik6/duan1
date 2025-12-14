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
     * Tính tổng chi phí tour (bao gồm giá gốc nội bộ + chi phí dịch vụ)
     */
    public function getTotalCost($tourId, $includeInternalPrice = true)
    {
        // Tính tổng chi phí dịch vụ
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
        // Tính tổng chi phí dịch vụ
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
     * Tính chỉ chi phí dịch vụ (không bao gồm giá gốc)
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
     * profit = total_revenue - (((internal_price - service_cost_per_guest) * number_of_guests) + total_service_cost)
     * 
     * @param int|null $tourId - ID tour, null để tính cho tất cả
     * @return array ['number_of_guests' => ..., 'total_revenue' => ..., 'total_cost' => ..., 'profit' => ...]
     */
    public function calculateProfitByFormula($tourId = null)
    {
        // Lấy tổng số khách
        $bookingModel = new BookingModel();
        $totalGuests = $bookingModel->getTotalGuestsByTour($tourId) ?? 0;
        
        // Lấy tổng doanh thu
        $filters = $tourId ? ['tour_id' => $tourId] : [];
        $totalRevenue = $bookingModel->getTotalRevenue($filters);
        
        // Lấy tổng chi phí dịch vụ
        $sql = "SELECT SUM(amount) as total FROM {$this->table}";
        $params = [];
        if ($tourId) {
            $sql .= " WHERE tour_id = :tour_id";
            $params['tour_id'] = $tourId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $totalServiceCost = $result['total'] ?? 0;
        
        // Tính chi phí dịch vụ per guest
        $serviceCostPerGuest = $totalGuests > 0 ? ($totalServiceCost / $totalGuests) : 0;
        
        // Lấy giá nội bộ
        $sql = "SELECT AVG(internal_price) as avg_price FROM tours";
        $params = [];
        if ($tourId) {
            $sql = "SELECT internal_price as avg_price FROM tours WHERE id = :tour_id";
            $params['tour_id'] = $tourId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $internalPrice = $result['avg_price'] ?? 0;
        
        // Tính chi phí per guest: (giá nội bộ - chi phí dịch vụ per guest)
        $costPerGuest = $internalPrice - $serviceCostPerGuest;
        
        // Tính tổng chi phí: (chi phí per guest × số người) + tổng chi phí dịch vụ
        $totalCost = ($costPerGuest * $totalGuests) + $totalServiceCost;
        
        // Áp dụng công thức: profit = total_revenue - total_cost
        $profit = $totalRevenue - $totalCost;
        
        return [
            'number_of_guests' => $totalGuests,
            'total_revenue' => $totalRevenue,
            'total_service_cost' => $totalServiceCost,
            'service_cost_per_guest' => round($serviceCostPerGuest, 0),
            'internal_price' => round($internalPrice, 0),
            'cost_per_guest' => round($costPerGuest, 0),
            'total_cost' => round($totalCost, 0),
            'profit' => round($profit, 0),
            'profit_margin' => $totalRevenue > 0 ? round(($profit / $totalRevenue * 100), 2) : 0
        ];
    }

    /**
     * Tính lợi nhuận theo công thức cho tất cả tours hoặc filter theo điều kiện
     * profit = total_revenue - (((internal_price - service_cost_per_guest) * number_of_guests) + total_service_cost)
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
        $serviceCostSql = "SELECT SUM(amount) as total FROM {$this->table}";
        
        $params = [];
        
        // Áp dụng filter tour_id
        if (!empty($filters['tour_id'])) {
            $guestSql .= " AND tour_id = :tour_id";
            $serviceCostSql .= " WHERE tour_id = :tour_id";
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
        
        // Lấy tổng chi phí dịch vụ
        $stmt = $this->pdo->prepare($serviceCostSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $totalServiceCost = $result['total'] ?? 0;
        
        // Tính chi phí dịch vụ per guest
        $serviceCostPerGuest = $totalGuests > 0 ? ($totalServiceCost / $totalGuests) : 0;
        
        // Lấy giá nội bộ trung bình từ tour
        $internalPriceSql = "SELECT AVG(internal_price) as avg_price FROM tours";
        if (!empty($filters['tour_id'])) {
            $internalPriceSql = "SELECT internal_price as avg_price FROM tours WHERE id = :tour_id";
        }
        $stmt = $this->pdo->prepare($internalPriceSql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        $internalPrice = $result['avg_price'] ?? 0;
        
        // Tính chi phí per guest: (giá nội bộ - chi phí dịch vụ per guest)
        $costPerGuest = $internalPrice - $serviceCostPerGuest;
        
        // Tính tổng chi phí: (chi phí per guest × số người) + tổng chi phí dịch vụ
        $totalCost = ($costPerGuest * $totalGuests) + $totalServiceCost;
        
        // Áp dụng công thức: profit = total_revenue - total_cost
        $profit = $totalRevenue - $totalCost;
        
        return [
            'number_of_guests' => $totalGuests,
            'total_revenue' => round($totalRevenue, 0),
            'total_service_cost' => round($totalServiceCost, 0),
            'service_cost_per_guest' => round($serviceCostPerGuest, 0),
            'internal_price' => round($internalPrice, 0),
            'cost_per_guest' => round($costPerGuest, 0),
            'total_cost' => round($totalCost, 0),
            'fixed_cost' => round($totalCost, 0), // Để compatibility với view hiện tại
            'total_variable_cost' => round($totalCost, 0),
            'profit' => round($profit, 0),
            'profit_margin' => $totalRevenue > 0 ? round(($profit / $totalRevenue * 100), 2) : 0
        ];
    }
}

