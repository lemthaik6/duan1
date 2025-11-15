# HƯỚNG DẪN CÀI ĐẶT DATABASE

## Bước 1: Tạo Database

1. Mở phpMyAdmin (thường là http://localhost/phpmyadmin)
2. Tạo database mới với tên: `tour_management` (hoặc tên bạn muốn)
3. Chọn collation: `utf8mb4_unicode_ci`

## Bước 2: Import Database

1. Trong phpMyAdmin, chọn database vừa tạo
2. Click tab "Import"
3. Chọn file `tour_management.sql`
4. Click "Go" để import

Hoặc bạn có thể chạy lệnh SQL trực tiếp:
- Copy toàn bộ nội dung file `tour_management.sql`
- Paste vào tab SQL trong phpMyAdmin và chạy

## Bước 3: Cấu hình kết nối

Mở file `configs/env.php` và cập nhật:

```php
define('DB_NAME', 'tour_management'); // Tên database bạn đã tạo
```

## Bước 4: Tài khoản mặc định

Sau khi import, hệ thống có 2 tài khoản mặc định:

**Admin:**
- Username: `admin`
- Password: `admin123` (cần hash lại trong thực tế)

**Hướng dẫn viên:**
- Username: `guide1`
- Password: `guide123` (cần hash lại trong thực tế)

⚠️ **LƯU Ý QUAN TRỌNG:** 
- Các password mặc định chỉ là ví dụ, bạn cần hash lại bằng `password_hash()` trong PHP
- Nên đổi password ngay sau khi cài đặt

## Cấu trúc Database

### Các bảng chính:

1. **users** - Người dùng (Admin, Hướng dẫn viên)
2. **tour_categories** - Danh mục tour
3. **tours** - Thông tin tour
4. **itineraries** - Lịch trình tour (từng ngày)
5. **tour_assignments** - Phân công HDV
6. **vehicles** - Quản lý xe
7. **vehicle_assignments** - Gán xe cho tour
8. **cost_categories** - Loại chi phí
9. **tour_costs** - Chi phí tour
10. **tour_customers** - Khách nội bộ
11. **tour_daily_logs** - Nhật ký hàng ngày
12. **tour_images** - Hình ảnh tour
13. **tour_incidents** - Sự cố
14. **tour_attendance** - Chấm công HDV
15. **tour_reports** - Báo cáo
16. **tour_feedbacks** - Đánh giá
17. **tour_documents** - Tài liệu tour

## Quan hệ giữa các bảng

- `tours` → `tour_categories` (nhiều tour thuộc 1 danh mục)
- `tours` → `users` (admin tạo tour)
- `tour_assignments` → `tours` + `users` (phân công HDV)
- `itineraries` → `tours` (lịch trình từng ngày)
- `tour_costs` → `tours` + `cost_categories` (chi phí tour)
- `tour_daily_logs` → `tours` + `users` (nhật ký HDV)
- Và nhiều quan hệ khác...

