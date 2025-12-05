<?php
require_once __DIR__ . '/../configs/env.php';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    echo "<h3>Kiểm tra cấu trúc bảng vehicle_assignments:</h3>";
    
    // Kiểm tra xem bảng có tồn tại không
    $checkTable = $pdo->query("SHOW TABLES LIKE 'vehicle_assignments'");
    if ($checkTable->rowCount() == 0) {
        echo "<p><strong style='color: red;'>❌ Bảng vehicle_assignments không tồn tại!</strong></p>";
        echo "<p>Tạo bảng này bằng cách chạy SQL sau:</p>";
        echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
        echo htmlspecialchars("CREATE TABLE vehicle_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    usage_purpose VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    notes TEXT,
    status VARCHAR(50) DEFAULT 'active',
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);");
        echo "</pre>";
    } else {
        echo "<p><strong style='color: green;'>✓ Bảng vehicle_assignments tồn tại</strong></p>";
        
        // Lấy thông tin các cột
        $result = $pdo->query("DESCRIBE vehicle_assignments");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; margin-top: 10px;'>";
        echo "<thead><tr><th>Tên cột</th><th>Kiểu dữ liệu</th><th>Nullable</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>";
        echo "<tbody>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($col['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Lỗi kết nối:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
