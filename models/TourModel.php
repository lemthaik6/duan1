<?php

class TourModel extends BaseModel
{
    protected $table = 'tours';

    /**
     * Lấy tất cả tour với thông tin danh mục
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT t.*, tc.name as category_name, u.full_name as created_by_name
                FROM {$this->table} t
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND t.start_date >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND t.end_date <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY t.start_date DESC, t.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy tour theo ID với đầy đủ thông tin
     */
    public function getById($id)
    {
        $sql = "SELECT t.*, tc.name as category_name, u.full_name as created_by_name
                FROM {$this->table} t
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE t.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo tour mới
     */
    public function create($data)
    {
        // Tạo mã tour tự động nếu chưa có
        if (empty($data['code'])) {
            $data['code'] = $this->generateTourCode();
        }

        $sql = "INSERT INTO {$this->table} 
                (code, name, category_id, description, schedule, start_date, end_date, 
                 internal_price, priority_level, status, pdf_program_path, created_by) 
                VALUES 
                (:code, :name, :category_id, :description, :schedule, :start_date, :end_date, 
                 :internal_price, :priority_level, :status, :pdf_program_path, :created_by)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Đảm bảo tất cả tham số đều có giá trị
        $params = [
            'code' => $data['code'],
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'schedule' => $data['schedule'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'internal_price' => $data['internal_price'] ?? null,
            'priority_level' => $data['priority_level'],
            'status' => $data['status'],
            'pdf_program_path' => $data['pdf_program_path'] ?? null,
            'created_by' => $data['created_by']
        ];
        
        return $stmt->execute($params);
    }

    /**
     * Cập nhật tour
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
     * Xóa tour
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Tạo mã tour tự động
     */
    private function generateTourCode()
    {
        $prefix = 'TOUR';
        $year = date('Y');
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code LIKE :pattern";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pattern' => $prefix . $year . '%']);
        $result = $stmt->fetch();
        $number = ($result['count'] ?? 0) + 1;
        return $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Lấy tour theo trạng thái
     */
    public function getByStatus($status)
    {
        return $this->getAll(['status' => $status]);
    }

    /**
     * Thống kê tour theo tháng
     */
    public function getStatsByMonth($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $sql = "SELECT 
                    MONTH(start_date) as month,
                    COUNT(*) as total_tours,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tours
                FROM {$this->table}
                WHERE YEAR(start_date) = :year
                GROUP BY MONTH(start_date)
                ORDER BY month";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['year' => $year]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy tour được phân công cho HDV
     */
    public function getToursByGuide($guideId, $status = null)
    {
        $sql = "SELECT t.*, tc.name as category_name, ta.status as assignment_status
                FROM {$this->table} t
                INNER JOIN tour_assignments ta ON t.id = ta.tour_id
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE ta.guide_id = :guide_id";

        $params = ['guide_id' => $guideId];

        if ($status) {
            $sql .= " AND t.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY t.start_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

