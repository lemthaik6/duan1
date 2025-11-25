<?php

class ChatMessageModel extends BaseModel
{
    protected $table = 'chat_messages';

    public function getByGroup($groupId, $limit = 50, $offset = 0)
    {
        $sql = "SELECT cm.*, u.full_name as user_name, u.role as user_role
                FROM {$this->table} cm
                INNER JOIN users u ON cm.user_id = u.id
                WHERE cm.group_id = :group_id AND cm.deleted_at IS NULL
                ORDER BY cm.created_at ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':group_id', $groupId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getNewMessages($groupId, $afterTime)
    {
        $sql = "SELECT cm.*, u.full_name as user_name, u.role as user_role
                FROM {$this->table} cm
                INNER JOIN users u ON cm.user_id = u.id
                WHERE cm.group_id = :group_id 
                AND cm.created_at > :after_time 
                AND cm.deleted_at IS NULL
                ORDER BY cm.created_at ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'group_id' => $groupId,
            'after_time' => $afterTime
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Tạo tin nhắn mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (group_id, user_id, message, message_type, file_path, file_name, file_size) 
                VALUES 
                (:group_id, :user_id, :message, :message_type, :file_path, :file_name, :file_size)";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'group_id' => $data['group_id'],
            'user_id' => $data['user_id'],
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text',
            'file_path' => $data['file_path'] ?? null,
            'file_name' => $data['file_name'] ?? null,
            'file_size' => $data['file_size'] ?? null
        ]);

        if ($result) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * Lấy tin nhắn theo ID
     */
    public function getById($id)
    {
        $sql = "SELECT cm.*, u.full_name as user_name, u.role as user_role
                FROM {$this->table} cm
                INNER JOIN users u ON cm.user_id = u.id
                WHERE cm.id = :id AND cm.deleted_at IS NULL";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Xóa tin nhắn (soft delete)
     */
    public function delete($id, $userId)
    {
        // Chỉ cho phép xóa tin nhắn của chính mình hoặc admin
        $message = $this->getById($id);
        if (!$message) {
            return false;
        }

        // Kiểm tra quyền
        $user = getCurrentUser();
        if ($message['user_id'] != $userId && $user['role'] != 'admin') {
            return false;
        }

        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Đếm tổng số tin nhắn trong nhóm
     */
    public function countByGroup($groupId)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE group_id = :group_id AND deleted_at IS NULL";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['group_id' => $groupId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}

