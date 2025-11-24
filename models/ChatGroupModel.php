<?php

class ChatGroupModel extends BaseModel
{
    protected $table = 'chat_groups';

    /**
     * Lấy tất cả nhóm chat của user
     */
    public function getGroupsByUser($userId, $type = null)
    {
        $sql = "SELECT DISTINCT cg.*, 
                (SELECT COUNT(*) FROM chat_messages cm 
                 WHERE cm.group_id = cg.id AND cm.deleted_at IS NULL) as message_count,
                (SELECT cm.message FROM chat_messages cm 
                 WHERE cm.group_id = cg.id AND cm.deleted_at IS NULL 
                 ORDER BY cm.created_at DESC LIMIT 1) as last_message,
                (SELECT cm.created_at FROM chat_messages cm 
                 WHERE cm.group_id = cg.id AND cm.deleted_at IS NULL 
                 ORDER BY cm.created_at DESC LIMIT 1) as last_message_time,
                t.name as tour_name, t.code as tour_code
                FROM {$this->table} cg
                INNER JOIN chat_group_members cgm ON cg.id = cgm.group_id
                LEFT JOIN tours t ON cg.tour_id = t.id
                WHERE cgm.user_id = :user_id AND cg.status = 'active'";

        $params = ['user_id' => $userId];

        if ($type) {
            $sql .= " AND cg.type = :type";
            $params['type'] = $type;
        }

        $sql .= " ORDER BY last_message_time DESC, cg.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy nhóm chat theo ID với thông tin đầy đủ
     */
    public function getById($id, $userId = null)
    {
        $sql = "SELECT cg.*, t.name as tour_name, t.code as tour_code,
                u.full_name as created_by_name
                FROM {$this->table} cg
                LEFT JOIN tours t ON cg.tour_id = t.id
                LEFT JOIN users u ON cg.created_by = u.id
                WHERE cg.id = :id AND cg.status = 'active'";

        $params = ['id' => $id];

        // Kiểm tra user có trong nhóm không
        if ($userId) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM chat_group_members cgm 
                WHERE cgm.group_id = cg.id AND cgm.user_id = :user_id
            )";
            $params['user_id'] = $userId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Tạo nhóm chat mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (name, type, tour_id, department, description, created_by, status) 
                VALUES 
                (:name, :type, :tour_id, :department, :description, :created_by, 'active')";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'tour_id' => $data['tour_id'] ?? null,
            'department' => $data['department'] ?? null,
            'description' => $data['description'] ?? null,
            'created_by' => $data['created_by']
        ]);

        if ($result) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * Thêm thành viên vào nhóm
     */
    public function addMember($groupId, $userId, $role = 'member')
    {
        $sql = "INSERT INTO chat_group_members (group_id, user_id, role) 
                VALUES (:group_id, :user_id, :role)
                ON DUPLICATE KEY UPDATE role = :role";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId,
            'role' => $role
        ]);
    }

    /**
     * Lấy danh sách thành viên trong nhóm
     */
    public function getMembers($groupId)
    {
        $sql = "SELECT cgm.*, u.full_name, u.email, u.phone, u.role as user_role
                FROM chat_group_members cgm
                INNER JOIN users u ON cgm.user_id = u.id
                WHERE cgm.group_id = :group_id
                ORDER BY cgm.role DESC, cgm.joined_at ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll();
    }

    /**
     * Xóa thành viên khỏi nhóm
     */
    public function removeMember($groupId, $userId)
    {
        $sql = "DELETE FROM chat_group_members 
                WHERE group_id = :group_id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);
    }

    /**
     * Kiểm tra user có trong nhóm không
     */
    public function isMember($groupId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM chat_group_members 
                WHERE group_id = :group_id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Lấy nhóm chat theo tour
     */
    public function getByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tour_id AND type = 'tour' AND status = 'active'
                ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetch();
    }

    /**
     * Cập nhật thời gian đọc tin nhắn cuối
     */
    public function updateLastRead($groupId, $userId)
    {
        $sql = "UPDATE chat_group_members 
                SET last_read_at = NOW() 
                WHERE group_id = :group_id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);
    }

    /**
     * Đếm số tin nhắn chưa đọc
     */
    public function countUnreadMessages($groupId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM chat_messages cm
                LEFT JOIN chat_group_members cgm ON cgm.group_id = cm.group_id AND cgm.user_id = :user_id
                WHERE cm.group_id = :group_id 
                AND cm.user_id != :user_id
                AND cm.deleted_at IS NULL
                AND (cgm.last_read_at IS NULL OR cm.created_at > cgm.last_read_at)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}

