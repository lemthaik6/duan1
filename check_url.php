<?php
/**
 * File kiểm tra URL và đường dẫn
 * Truy cập: http://localhost/duan1/check_url.php
 */

echo "<h2>Thông tin hệ thống</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

echo "<hr>";
echo "<h2>Kiểm tra file routes/index.php</h2>";
$routesFile = __DIR__ . '/routes/index.php';
if (file_exists($routesFile)) {
    echo "<p style='color: green;'>✓ File routes/index.php tồn tại</p>";
    $content = file_get_contents($routesFile);
    if (strpos($content, 'match') !== false) {
        echo "<p style='color: red;'>✗ File có chứa 'match' expression (cần sửa)</p>";
    } else {
        echo "<p style='color: green;'>✓ File không có 'match' expression</p>";
    }
} else {
    echo "<p style='color: red;'>✗ File routes/index.php không tồn tại</p>";
}

echo "<hr>";
echo "<h2>Hướng dẫn</h2>";
echo "<p>1. Đảm bảo bạn đang truy cập: <strong>http://localhost/duan1/</strong></p>";
echo "<p>2. KHÔNG truy cập: http://localhost/BaseExam/</p>";
echo "<p>3. Nếu vẫn lỗi, kiểm tra file routes/index.php trong thư mục BaseExam và xóa hoặc sửa nó</p>";

