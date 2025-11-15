# HỆ THỐNG QUẢN LÝ TOUR NỘI BỘ

Hệ thống quản lý tour nội bộ dành cho công ty du lịch với 2 vai trò: **Admin** và **Hướng dẫn viên**.

## 🎯 Tính năng chính

### I. PHÂN QUYỀN NGƯỜI DÙNG

#### 1. Admin (Điều hành tour)
- Toàn quyền quản lý dữ liệu
- Tạo/chỉnh sửa/xóa tour
- Phân công hướng dẫn viên
- Theo dõi hoạt động, quản lý báo cáo

#### 2. Hướng dẫn viên (HDV)
- Xem tour được phân công
- Cập nhật tình trạng tour hằng ngày
- Nộp báo cáo, hình ảnh, chi phí, nhật ký hành trình
- Xem lịch cá nhân

### II. CHỨC NĂNG CHO ADMIN

1. **Quản lý tour**
   - Danh mục tour: trong nước, quốc tế, tour theo yêu cầu
   - Thông tin tour: tên, lịch trình, giá gốc nội bộ, cấp độ ưu tiên
   - Quản lý trạng thái: sắp diễn ra – đang diễn ra – đã kết thúc – hủy
   - Upload file PDF chương trình tour

2. **Quản lý lịch trình (Itinerary)**
   - Từng ngày của tour: địa điểm, hoạt động, giờ khởi hành
   - Upload tài liệu: hướng dẫn, timeline, checklist

3. **Quản lý HDV**
   - Danh sách HDV: hồ sơ, năng lực, kinh nghiệm, số tour đã đi
   - Phân công HDV
   - Quản lý chấm công của HDV theo tour

4. **Quản lý xe & tài nguyên**
   - Quản lý xe đưa đón, biển số, tài xế
   - Gán xe cho tour
   - Tình trạng sẵn sàng của xe

5. **Quản lý chi phí – tài chính**
   - Chi phí theo tour: ăn uống, khách sạn, vé tham quan, xăng xe, chi phí phát sinh
   - Tự động tính tổng chi phí tour
   - Xuất báo cáo chi phí cuối tour

6. **Quản lý khách nội bộ**
   - Danh sách khách của tour (admin tự nhập)
   - Thông tin cá nhân, mã tour, ghi chú

7. **Báo cáo – Thống kê**
   - Số tour theo tháng
   - Doanh thu nội bộ hoặc chi phí tour
   - Thống kê số lần đi tour của HDV
   - Báo cáo tổng hợp theo thời gian

8. **Quản lý sự cố & phản hồi**
   - Ghi chú sự cố trong tour
   - Đánh giá HDV (nội bộ)
   - Bàn giao cuối tour

### III. CHỨC NĂNG CHO HƯỚNG DẪN VIÊN

1. **Lịch cá nhân**
   - Xem tour được phân công
   - Xem timeline, lịch trình tour

2. **Cập nhật nhật ký tour**
   - Check-in: đã bắt đầu dẫn tour
   - Cập nhật hoạt động từng ngày
   - Gửi hình ảnh, video (minh chứng)
   - Ghi nhận tình trạng khách

3. **Cập nhật chi phí**
   - Điền chi phí thực tế hằng ngày
   - Upload hóa đơn chụp ảnh
   - Gửi báo cáo tổng hợp cho admin

4. **Báo cáo sự cố**
   - Báo cáo tình hình tour: thời tiết, giao thông, trễ giờ…
   - Báo cáo khách gặp vấn đề
   - Báo cáo thất lạc, mất đồ

5. **Kết thúc tour**
   - Gửi biên bản kết thúc tour
   - Checklist bàn giao
   - Đánh giá tour (nội bộ)

## 📋 Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7 hoặc MariaDB >= 10.2
- Apache/Nginx web server
- phpMyAdmin (để quản lý database)

## 🚀 Cài đặt

### Bước 1: Clone hoặc tải dự án

```bash
cd C:\laragon\www\duan1
```

### Bước 2: Cấu hình database

1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Tạo database mới: `tour_management`
3. Import file `database/tour_management.sql`
4. Cập nhật file `configs/env.php` nếu cần:
   ```php
   define('DB_NAME', 'tour_management');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   ```

### Bước 3: Cấu hình URL

Cập nhật `BASE_URL` trong file `configs/env.php`:
```php
define('BASE_URL', 'http://localhost/duan1/');
```

### Bước 4: Tạo thư mục upload

Đảm bảo thư mục `assets/uploads/` có quyền ghi.

## 👤 Tài khoản mặc định

Sau khi import database:

**Admin:**
- Username: `admin`
- Password: `admin123` (cần hash lại trong thực tế)

**Hướng dẫn viên:**
- Username: `guide1`
- Password: `guide123` (cần hash lại trong thực tế)

⚠️ **LƯU Ý:** Đổi password ngay sau khi cài đặt!

## 📁 Cấu trúc dự án

```
duan1/
├── assets/
│   └── uploads/          # Thư mục upload file
├── configs/
│   ├── env.php           # Cấu hình môi trường
│   └── helper.php        # Hàm helper
├── controllers/          # Controllers
├── database/
│   ├── tour_management.sql  # File SQL database
│   └── README.md         # Hướng dẫn database
├── models/               # Models
│   ├── BaseModel.php
│   ├── UserModel.php
│   ├── TourModel.php
│   └── ...
├── routes/               # Định tuyến
├── views/                # Views
└── index.php            # Entry point
```

## 🗄️ Cấu trúc Database

Hệ thống có 17 bảng chính:

1. `users` - Người dùng (Admin, HDV)
2. `tour_categories` - Danh mục tour
3. `tours` - Thông tin tour
4. `itineraries` - Lịch trình tour (từng ngày)
5. `tour_assignments` - Phân công HDV
6. `vehicles` - Quản lý xe
7. `vehicle_assignments` - Gán xe cho tour
8. `cost_categories` - Loại chi phí
9. `tour_costs` - Chi phí tour
10. `tour_customers` - Khách nội bộ
11. `tour_daily_logs` - Nhật ký hàng ngày
12. `tour_images` - Hình ảnh tour
13. `tour_incidents` - Sự cố
14. `tour_attendance` - Chấm công HDV
15. `tour_reports` - Báo cáo
16. `tour_feedbacks` - Đánh giá
17. `tour_documents` - Tài liệu tour

Xem chi tiết trong file `database/README.md`

## 🔧 Models đã tạo

- `UserModel` - Quản lý người dùng
- `TourModel` - Quản lý tour
- `TourCategoryModel` - Quản lý danh mục tour
- `TourAssignmentModel` - Quản lý phân công HDV
- `TourCostModel` - Quản lý chi phí
- `CostCategoryModel` - Quản lý loại chi phí
- `ItineraryModel` - Quản lý lịch trình
- `TourCustomerModel` - Quản lý khách
- `TourDailyLogModel` - Quản lý nhật ký
- `VehicleModel` - Quản lý xe
- `TourIncidentModel` - Quản lý sự cố

## 📝 Ghi chú

- Dự án sử dụng PHP thuần (không framework)
- Database sử dụng MySQL/MariaDB
- File upload lưu trong `assets/uploads/`
- Password được hash bằng `password_hash()` của PHP

## 🔐 Bảo mật

- Hash password bằng `password_hash()`
- Sử dụng PDO với prepared statements
- Validate input từ người dùng
- Kiểm tra quyền truy cập theo role

## 📞 Hỗ trợ

Nếu có vấn đề, vui lòng kiểm tra:
1. Cấu hình database trong `configs/env.php`
2. Quyền ghi của thư mục `assets/uploads/`
3. Log lỗi PHP

---

**Chúc bạn phát triển dự án thành công! 🎉**

