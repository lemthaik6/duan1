<?php

class TourSupplierModel extends BaseModel
{
    protected $table = 'tour_suppliers';

    /**
     * Lấy tất cả nhà cung cấp của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT ts.*, s.name as supplier_name, s.supplier_type, 
                s.contact_person, s.phone, s.email, s.address
                FROM {$this->table} ts
                INNER JOIN suppliers s ON ts.supplier_id = s.id
                WHERE ts.tour_id = :tour_id
                ORDER BY s.supplier_type ASC, s.name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy tour của nhà cung cấp
     */
    public function getBySupplier($supplierId)
    {
        $sql = "SELECT ts.*, t.name as tour_name, t.code as tour_code, 
                t.start_date, t.end_date
                FROM {$this->table} ts
                INNER JOIN tours t ON ts.tour_id = t.id
                WHERE ts.supplier_id = :supplier_id
                ORDER BY t.start_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['supplier_id' => $supplierId]);
        return $stmt->fetchAll();
    }

    /**
     * Liên kết tour với nhà cung cấp
     */
    public function link($tourId, $supplierId, $data = [])
    {
        // Kiểm tra đã liên kết chưa
        $sql = "SELECT id FROM {$this->table} 
                WHERE tour_id = :tour_id AND supplier_id = :supplier_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $tourId,
            'supplier_id' => $supplierId
        ]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Cập nhật
            $params = [
                'id' => $existing['id'],
                'service_description' => $data['service_description'] ?? null,
                'booking_reference' => $data['booking_reference'] ?? null,
                'contact_date' => !empty($data['contact_date']) ? $data['contact_date'] : null,
                'notes' => $data['notes'] ?? null
            ];
            
            $sql = "UPDATE {$this->table} 
                    SET service_description = :service_description,
                        booking_reference = :booking_reference,
                        contact_date = :contact_date,
                        notes = :notes
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } else {
            // Tạo mới
            $params = [
                'tour_id' => $tourId,
                'supplier_id' => $supplierId,
                'service_description' => $data['service_description'] ?? null,
                'booking_reference' => $data['booking_reference'] ?? null,
                'contact_date' => !empty($data['contact_date']) ? $data['contact_date'] : null,
                'notes' => $data['notes'] ?? null
            ];
            
            $sql = "INSERT INTO {$this->table} 
                    (tour_id, supplier_id, service_description, booking_reference, contact_date, notes) 
                    VALUES 
                    (:tour_id, :supplier_id, :service_description, :booking_reference, :contact_date, :notes)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }
    }

    /**
     * Xóa liên kết
     */
    public function unlink($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

