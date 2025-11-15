<?php

class VehicleModel extends BaseModel
{
    protected $table = 'vehicles';

    /**
     * Lấy tất cả xe
     */
    public function getAll($status = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($status) {
            $sql .= " WHERE status = :status";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        
        $sql .= " ORDER BY license_plate ASC";
        
        return $stmt->fetchAll();
    }

    /**
     * Lấy xe theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo xe mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (license_plate, vehicle_type, capacity, driver_name, driver_phone, status, notes) 
                VALUES 
                (:license_plate, :vehicle_type, :capacity, :driver_name, :driver_phone, :status, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật xe
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
     * Xóa xe
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy xe sẵn sàng
     */
    public function getAvailable()
    {
        return $this->getAll('available');
    }
}

