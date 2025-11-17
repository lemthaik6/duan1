<?php

class SupplierModel extends BaseModel
{
    protected $table = 'suppliers';

    /**
     * Lấy tất cả nhà cung cấp
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['supplier_type'])) {
            $sql .= " AND supplier_type = :supplier_type";
            $params['supplier_type'] = $filters['supplier_type'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY name ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy nhà cung cấp theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo nhà cung cấp mới
     */
    public function create($data)
    {
        // Đảm bảo tất cả các field đều có giá trị (kể cả null)
        $params = [
            'name' => $data['name'],
            'supplier_type' => $data['supplier_type'] ?? 'other',
            'contact_person' => $data['contact_person'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
            'capacity' => $data['capacity'] ?? null,
            'rating' => !empty($data['rating']) ? (float)$data['rating'] : null,
            'status' => $data['status'] ?? 'active'
        ];

        $sql = "INSERT INTO {$this->table} 
                (name, supplier_type, contact_person, phone, email, address, 
                 description, capacity, rating, status) 
                VALUES 
                (:name, :supplier_type, :contact_person, :phone, :email, :address, 
                 :description, :capacity, :rating, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Cập nhật nhà cung cấp
     */
    public function update($id, $data)
    {
        // Xử lý các giá trị null/empty
        $params = [];
        $fields = [];
        
        foreach ($data as $key => $value) {
            // Chuyển empty string thành null cho các field có thể null
            if (in_array($key, ['contact_person', 'phone', 'email', 'address', 'description', 'capacity'])) {
                $value = ($value === '' || $value === null) ? null : $value;
            }
            // Xử lý rating
            if ($key === 'rating') {
                $value = !empty($value) ? (float)$value : null;
            }
            
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $params['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Xóa nhà cung cấp
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

