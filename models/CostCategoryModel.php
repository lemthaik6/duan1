<?php

class CostCategoryModel extends BaseModel
{
    protected $table = 'cost_categories';

    /**
     * Lấy tất cả loại chi phí
     */
    public function getAll($activeOnly = false)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($activeOnly) {
            $sql .= " WHERE status = 'active'";
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Lấy loại chi phí theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}

