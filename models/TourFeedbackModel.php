<?php

class TourFeedbackModel extends BaseModel
{
    protected $table = 'tour_feedbacks';

    /**
     * Lấy tất cả phản hồi của tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT tf.*, u.full_name as rated_by_name, t.name as tour_name
                FROM {$this->table} tf
                LEFT JOIN users u ON tf.rated_by = u.id
                LEFT JOIN tours t ON tf.tour_id = t.id
                WHERE tf.tour_id = :tour_id
                ORDER BY tf.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }
    public function getByGuide($guideId)
    {
        $sql = "SELECT tf.*, t.name as tour_name, t.code as tour_code
                FROM {$this->table} tf
                LEFT JOIN tours t ON tf.tour_id = t.id
                WHERE tf.rated_by = :guide_id
                ORDER BY tf.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll();
    }
    public function getByType($tourId, $feedbackType)
    {
        $sql = "SELECT tf.*, u.full_name as rated_by_name
                FROM {$this->table} tf
                LEFT JOIN users u ON tf.rated_by = u.id
                WHERE tf.tour_id = :tour_id AND tf.feedback_type = :feedback_type
                ORDER BY tf.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $tourId,
            'feedback_type' => $feedbackType
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy phản hồi theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT tf.*, u.full_name as rated_by_name, t.name as tour_name
                FROM {$this->table} tf
                LEFT JOIN users u ON tf.rated_by = u.id
                LEFT JOIN tours t ON tf.tour_id = t.id
                WHERE tf.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Tạo phản hồi mới
     */
    public function create($data)
    {
        // Đảm bảo tất cả các field đều có giá trị
        $params = [
            'tour_id' => $data['tour_id'],
            'rated_by' => $data['rated_by'],
            'feedback_type' => $data['feedback_type'],
            'rating' => !empty($data['rating']) ? (int)$data['rating'] : null,
            'content' => $data['content']
        ];

        $sql = "INSERT INTO {$this->table} 
                (tour_id, rated_by, feedback_type, rating, content) 
                VALUES 
                (:tour_id, :rated_by, :feedback_type, :rating, :content)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Cập nhật phản hồi
     */
    public function update($id, $data)
    {
        $params = [];
        $fields = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'rating' && !empty($value)) {
                $value = (int)$value;
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
     * Xóa phản hồi
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy tất cả phản hồi (Admin)
     */
    public function getAll()
    {
        $sql = "SELECT tf.*, u.full_name as rated_by_name, t.name as tour_name, t.code as tour_code
                FROM {$this->table} tf
                LEFT JOIN users u ON tf.rated_by = u.id
                LEFT JOIN tours t ON tf.tour_id = t.id
                ORDER BY tf.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

