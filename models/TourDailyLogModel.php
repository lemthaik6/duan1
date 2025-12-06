<?php

class TourDailyLogModel extends BaseModel
{
    protected $table = 'tour_daily_logs';

    /**
     * Lấy nhật ký tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT tdl.*, u.full_name as guide_name
                FROM {$this->table} tdl
                LEFT JOIN users u ON tdl.guide_id = u.id
                WHERE tdl.tour_id = :tour_id
                ORDER BY tdl.date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy nhật ký theo ngày
     */
    public function getByDate($tourId, $guideId, $date)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tour_id AND guide_id = :guide_id AND date = :date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $tourId,
            'guide_id' => $guideId,
            'date' => $date
        ]);
        return $stmt->fetch();
    }

    /**
     * Tạo hoặc cập nhật nhật ký
     */
    public function createOrUpdate($data)
    {
        $existing = $this->getByDate($data['tour_id'], $data['guide_id'], $data['date']);
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    /**
     * Tạo nhật ký mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, guide_id, date, activities, customer_status, weather, traffic, notes) 
                VALUES 
                (:tour_id, :guide_id, :date, :activities, :customer_status, :weather, :traffic, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật nhật ký
     */
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'tour_id' && $key !== 'guide_id' && $key !== 'date') {
                $fields[] = "$key = :$key";
            }
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Xóa nhật ký
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy nhật ký theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}