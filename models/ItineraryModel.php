<?php

class ItineraryModel extends BaseModel
{
    protected $table = 'itineraries';

    /**
     * Lấy lịch trình tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :tour_id ORDER BY day_number ASC, date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Tạo lịch trình
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, day_number, date, location, activities, departure_time, notes) 
                VALUES 
                (:tour_id, :day_number, :date, :location, :activities, :departure_time, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật lịch trình
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
     * Xóa lịch trình
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Xóa tất cả lịch trình của tour
     */
    public function deleteByTour($tourId)
    {
        $sql = "DELETE FROM {$this->table} WHERE tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['tour_id' => $tourId]);
    }
}

