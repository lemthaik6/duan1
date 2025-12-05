<?php
/**
 * Script tạo bảng vehicle_assignments nếu chưa có
 * Chạy file này một lần: http://localhost/duan1/database/create_vehicle_assignments_table.php
 */

require_once __DIR__ . '/../configs/env.php';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    // Kiểm tra xem bảng đã tồn tại chưa
    $checkTable = $pdo->query("SHOW TABLES LIKE 'vehicle_assignments'");
    
    if ($checkTable->rowCount() > 0) {
        echo "<h2 style='color: blue;'>ℹ️ Bảng vehicle_assignments đã tồn tại</h2>";
        
        // Kiểm tra các cột
        $result = $pdo->query("DESCRIBE vehicle_assignments");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'Field');
        
        echo "<p>Các cột hiện có:</p>";
        echo "<ul>";
        foreach ($columnNames as $col) {
            echo "<li>" . htmlspecialchars($col) . "</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<h2 style='color: green;'>✓ Tạo bảng vehicle_assignments thành công!</h2>";
        
        // Tạo bảng
        $sql = "CREATE TABLE vehicle_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tour_id INT NOT NULL,
            vehicle_id INT NOT NULL,
            usage_purpose VARCHAR(255),
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            notes TEXT,
            FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
            FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
        ) CHARACTER SET utf8 COLLATE utf8_general_ci";
        
        $pdo->exec($sql);
        
        echo "<p>Bảng đã được tạo với các cột:</p>";
        echo "<ul>";
        echo "<li>id - INT (Khóa chính)</li>";
        echo "<li>tour_id - INT (Khóa ngoài - liên kết đến tours)</li>";
        echo "<li>vehicle_id - INT (Khóa ngoài - liên kết đến vehicles)</li>";
        echo "<li>usage_purpose - VARCHAR(255) (Mục đích sử dụng)</li>";
        echo "<li>start_date - DATE (Ngày bắt đầu)</li>";
        echo "<li>end_date - DATE (Ngày kết thúc)</li>";
        echo "<li>notes - TEXT (Ghi chú)</li>";
        echo "</ul>";
    }

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Lỗi: " . htmlspecialchars($e->getMessage()) . "</h2>";
    echo "<p>Vui lòng kiểm tra:</p>";
    echo "<ul>";
    echo "<li>Database đã được tạo chưa?</li>";
    echo "<li>Cấu hình trong configs/env.php đúng chưa?</li>";
    echo "<li>DB_NAME = 'tour_management'</li>";
    echo "</ul>";
}
?>
