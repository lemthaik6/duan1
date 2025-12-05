<?php

class BaseModel
{
    protected $table;
    protected $pdo;
    
    public function __construct()
    {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);

        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $e) {
            // Log error details to file for debugging
            error_log('Database connection failed: ' . $e->getMessage());
            
            // Show generic message to user (don't expose DB details)
            die('❌ Có lỗi hệ thống. Vui lòng thử lại sau hoặc liên hệ quản trị viên.');
        }
    }
    
    public function __destruct()
    {
        $this->pdo = null;
    }
}
