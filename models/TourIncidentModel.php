<?php

class TourIncidentModel extends BaseModel
{
    protected $table = 'tour_incidents';

    /**
     * Lấy tất cả sự cố của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT ti.*, 
                reporter.full_name as reported_by_name,
                resolver.full_name as resolved_by_name
                FROM {$this->table} ti
                LEFT JOIN users reporter ON ti.reported_by = reporter.id
                LEFT JOIN users resolver ON ti.resolved_by = resolver.id
                WHERE ti.tour_id = :tour_id
                ORDER BY ti.incident_date DESC, ti.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Tạo sự cố mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, reported_by, incident_type, title, description, incident_date, severity, status) 
                VALUES 
                (:tour_id, :reported_by, :incident_type, :title, :description, :incident_date, :severity, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật sự cố
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
     * Giải quyết sự cố
     */
    public function resolve($id, $resolvedBy, $resolution)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'resolved', resolved_by = :resolved_by, resolution = :resolution, resolved_at = NOW() 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'resolved_by' => $resolvedBy,
            'resolution' => $resolution
        ]);
    }
}

