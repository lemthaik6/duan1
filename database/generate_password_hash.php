<?php
/**
 * Script tạo password hash
 * Chạy file này để tạo hash cho password mới
 * 
 * Cách sử dụng:
 * php generate_password_hash.php
 * hoặc mở file trong trình duyệt
 */

if (php_sapi_name() === 'cli') {
    // Chạy từ command line
    echo "Nhập password cần hash: ";
    $password = trim(fgets(STDIN));
} else {
    // Chạy từ trình duyệt
    $password = $_GET['password'] ?? 'admin123';
}

if (empty($password)) {
    die("Password không được để trống!\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "\n========================================\n";
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "========================================\n\n";

echo "SQL để cập nhật:\n";
echo "UPDATE `users` SET `password` = '" . $hash . "' WHERE `username` = 'your_username';\n\n";

