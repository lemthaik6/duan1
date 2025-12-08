<?php

class TourAssignmentModel extends BaseModel
{
    protected $table = 'tour_assignments';

    public function assign($tourId, $guideId, $assignedBy, $notes = null)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, guide_id, assigned_by, notes) 
                VALUES (:tour_id, :guide_id, :assigned_by, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'tour_id' => $tourId,
            'guide_id' => $guideId,
            'assigned_by' => $assignedBy,
            'notes' => $notes
        ]);
    }

    public function getByTour($tourId)
    {
        $sql = "SELECT ta.*, u.full_name as guide_name, u.email as guide_email, u.phone as guide_phone,
                admin.full_name as assigned_by_name
                FROM {$this->table} ta
                LEFT JOIN users u ON ta.guide_id = u.id
                LEFT JOIN users admin ON ta.assigned_by = admin.id
                WHERE ta.tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy tour được phân công cho HDV
     */
    public function getByGuide($guideId, $status = null)
    {
        $sql = "SELECT ta.*, t.*, tc.name as category_name
                FROM {$this->table} ta
                INNER JOIN tours t ON ta.tour_id = t.id
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE ta.guide_id = :guide_id";

        $params = ['guide_id' => $guideId];

        if ($status) {
            $sql .= " AND ta.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY t.start_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Cập nhật trạng thái phân công
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * Xóa phân công
     */
    public function remove($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

