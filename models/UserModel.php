<?php

class UserModel extends BaseModel
{
    protected $table = 'users';
    public function login($username, $password)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
    public function getAll($role = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($role) {
            $sql .= " WHERE role = :role";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['role' => $role]);
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
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO {$this->table} (username, password, full_name, email, phone, role, status) 
                VALUES (:username, :password, :full_name, :email, :phone, :role, :status)";
        $stmt = $this->pdo->prepare($sql);
        $params = [
            'username' => $data['username'],
            'password' => $data['password'],
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'] ?? 'guide',
            'status' => $data['status'] ?? 'active'
        ];
        
        return $stmt->execute($params);
    }

    /**
     * Cập nhật người dùng
     */
    public function update($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

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
     * Xóa người dùng
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lấy danh sách hướng dẫn viên
     */
    public function getGuides()
    {
        return $this->getAll('guide');
    }

    /**
     * Đếm số tour đã đi của HDV
     */
    public function countToursByGuide($guideId)
    {
        $sql = "SELECT COUNT(*) as total FROM tour_assignments 
                WHERE guide_id = :guide_id AND status = 'completed'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}

