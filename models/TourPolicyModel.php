<?php

class TourPolicyModel extends BaseModel
{
    protected $table = 'tour_policies';

    /**
     * Lấy tất cả chính sách của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tour_id 
                ORDER BY priority DESC, policy_type ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

  
    public function getByType($tourId, $policyType)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tour_id AND policy_type = :policy_type 
                ORDER BY priority DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $tourId,
            'policy_type' => $policyType
        ]);
        return $stmt->fetchAll();
    }

   
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, policy_type, title, content, priority) 
                VALUES 
                (:tour_id, :policy_type, :title, :content, :priority)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật chính sách
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
     * Xóa chính sách
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

