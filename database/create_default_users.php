<?php
/**
 * Script tạo tài khoản mặc định với password đã hash đúng
 * Chạy file này một lần để tạo tài khoản: http://localhost/duan1/database/create_default_users.php
 */

require_once __DIR__ . '/../configs/env.php';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    // Hash password
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $guidePassword = password_hash('guide123', PASSWORD_DEFAULT);

    // Xóa tài khoản cũ nếu có
    $pdo->exec("DELETE FROM users WHERE username IN ('admin', 'guide1')");

    // Tạo tài khoản admin
    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, role, status) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    
    // Admin
    $stmt->execute([
        'admin',
        $adminPassword,
        'Quản trị viên',
        'admin@example.com',
        'admin',
        'active'
    ]);

    // HDV
    $stmt->execute([
        'guide1',
        $guidePassword,
        'Hướng dẫn viên 1',
        'guide1@example.com',
        'guide',
        'active'
    ]);

    echo "<h2 style='color: green;'>✓ Tạo tài khoản thành công!</h2>";
    echo "<p><strong>Tài khoản Admin:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong></li>";
    echo "<li>Password: <strong>admin123</strong></li>";
    echo "</ul>";
    
    echo "<p><strong>Tài khoản HDV:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>guide1</strong></li>";
    echo "<li>Password: <strong>guide123</strong></li>";
    echo "</ul>";
    
    echo "<p style='color: orange;'><strong>Lưu ý:</strong> Xóa file này sau khi chạy xong để bảo mật!</p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Lỗi: " . $e->getMessage() . "</h2>";
    echo "<p>Vui lòng kiểm tra:</p>";
    echo "<ul>";
    echo "<li>Database đã được tạo chưa?</li>";
    echo "<li>Cấu hình trong configs/env.php đúng chưa?</li>";
    echo "<li>DB_NAME = 'tour_management'</li>";
    echo "</ul>";
}

