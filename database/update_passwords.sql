-- ============================================
-- CẬP NHẬT PASSWORD CHO TÀI KHOẢN MẶC ĐỊNH
-- Chạy file này sau khi import database để hash lại password
-- ============================================

-- Lưu ý: Các password dưới đây đã được hash bằng password_hash() trong PHP
-- Bạn có thể tạo password hash mới bằng cách chạy:
-- echo password_hash('your_password', PASSWORD_DEFAULT);

-- Cập nhật password cho admin (password: admin123)
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `username` = 'admin';

-- Cập nhật password cho guide1 (password: guide123)
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `username` = 'guide1';

-- Để tạo password hash mới, bạn có thể tạo file PHP tạm:
-- <?php
-- echo password_hash('your_new_password', PASSWORD_DEFAULT);
-- ?>
-- Sau đó chạy file đó và copy hash vào UPDATE statement trên

