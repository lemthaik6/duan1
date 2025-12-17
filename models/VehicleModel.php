<?php

class VehicleModel extends BaseModel
{
    protected $table = 'vehicles';

    public function getAll($status = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($status) {
            $sql .= " WHERE status = :status";
        }
        
        $sql .= " ORDER BY license_plate ASC";
        
        if ($status) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (license_plate, vehicle_type, capacity, driver_name, driver_phone, status, notes) 
                VALUES 
                (:license_plate, :vehicle_type, :capacity, :driver_name, :driver_phone, :status, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }


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

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getAvailable()
    {
        return $this->getAll('available');
    }
}

