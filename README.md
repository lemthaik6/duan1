# HỆ THỐNG QUẢN LÝ TOUR NỘI BỘ

Hệ thống quản lý tour nội bộ dành cho công ty du lịch với 2 vai trò: **Admin** và **Hướng dẫn viên**. Cung cấp giải pháp toàn diện từ quản lý tour, chi phí, nhân sự đến báo cáo thống kê.

**Phiên bản:** 2.0 (UI/UX Optimized)

## 🎯 Tính năng chính

### I. PHÂN QUYỀN NGƯỜI DÙNG

#### 1. Admin (Điều hành tour)
- Toàn quyền quản lý dữ liệu hệ thống
- Tạo, chỉnh sửa, xóa tour
- Phân công hướng dẫn viên
- Quản lý chi phí, xe, khách
- Theo dõi hoạt động, xem báo cáo chi tiết
- Kiểm duyệt báo cáo từ HDV

#### 2. Hướng dẫn viên (HDV)
- Xem danh sách tour được phân công
- Cập nhật nhật ký tour hằng ngày
- Báo cáo chi phí thực tế
- Báo cáo sự cố, thời tiết, tình hình tour
- Điểm danh khách hàng
- Xem báo cáo doanh thu

### II. CHỨC NĂNG CHO ADMIN

#### 1. **Quản lý Tour** 📍
   - Tạo, chỉnh sửa, xóa tour
   - Danh mục tour: trong nước, quốc tế, tour theo yêu cầu
   - Thông tin tour: tên, lịch trình, giá gốc nội bộ, cấp độ ưu tiên
   - Quản lý trạng thái: sắp diễn ra → đang diễn ra → đã kết thúc → hủy
   - Upload file PDF chương trình tour
   - Xem chi tiết tour với tất cả thông tin liên quan

#### 2. **Quản lý Lịch trình (Itinerary)** 📅
   - Tạo lịch trình chi tiết từng ngày của tour
   - Ghi nhận địa điểm, hoạt động, giờ khởi hành
   - Upload tài liệu: hướng dẫn, timeline, checklist
   - Chỉnh sửa và xóa lịch trình

#### 3. **Quản lý Hướng dẫn viên** 👥
   - Danh sách HDV: hồ sơ, năng lực, kinh nghiệm
   - Phân công HDV cho tour
   - Xem số tour đã dẫn của mỗi HDV
   - Quản lý chấm công HDV theo tour

#### 4. **Quản lý Xe & Tài nguyên** 🚗
   - Quản lý xe (tạo, sửa, **xóa**)
   - Thông tin: biển số, loại xe, sức chứa, tài xế
   - Trạng thái xe: sẵn sàng, đang sử dụng, bảo trì, không sẵn sàng
   - Gán xe cho tour
   - Xem chi tiết và lịch sử sử dụng xe

#### 5. **Quản lý Chi phí – Tài chính** 💰
   - Thêm, chỉnh sửa, **xem chi tiết**, **xóa** chi phí tour
   - Loại chi phí: ăn uống, khách sạn, vé tham quan, xăng xe, chi phí dịch vụ
   - Tự động tính tổng chi phí tour
   - Thống kê chi phí theo loại
   - Xuất báo cáo chi phí cuối tour
   - Xem lịch sử chi phí chi tiết

#### 6. **Quản lý Khách Nội bộ** 👫
   - Tạo danh sách khách của tour
   - Thông tin cá nhân: tên, email, điện thoại
   - Ghi chú riêng cho từng khách
   - Quản lý khách dễ dàng

#### 7. **Điểm danh Khách hàng** ✅
   - Điểm danh từng khách với trạng thái: có mặt, vắng mặt, đi muộn
   - **Nút "Điểm danh tất cả"** - Tích danh sách khách hàng chưa điểm danh trong 1 lần click
   - Ghi chú thời gian check-in, ghi chú riêng
   - Xem thống kê điểm danh: tổng, có mặt, vắng mặt, đi muộn

#### 8. **Báo cáo & Thống kê** 📊
   - **Báo cáo Tổng hợp**: số tour theo tháng, doanh thu, chi phí
   - **Danh sách Hướng dẫn viên**: thống kê tour dẫn, nhật ký, sự cố, đánh giá
   - **Nhật ký Hành trình**: danh sách nhật ký từ HDV với **tên tour rõ ràng**, dễ phân biệt
   - **Sự cố**: báo cáo sự cố với **thông tin tour**, mức độ, trạng thái
   - **Đánh giá**: báo cáo đánh giá HDV với **thông tin tour**, điểm rating, bình luận
   - Lọc theo tour, thời gian, trạng thái

#### 9. **Quản lý Sự cố & Phản hồi** ⚠️
   - Xem báo cáo sự cố từ HDV
   - Mức độ sự cố: thấp, vừa, cao, rất cao
   - Trạng thái xử lý: đang xử lý, đã giải quyết, đã đóng
   - Ghi chú giải quyết
   - Xem đánh giá HDV (nội bộ)

### III. CHỨC NĂNG CHO HƯỚNG DẪN VIÊN

#### 1. **Lịch cá nhân** 📋
   - Xem danh sách tour được phân công
   - Xem thời gian, trạng thái tour
   - Truy cập chi tiết từng tour
   - Xem lịch trình, thông tin tour

#### 2. **Cập nhật Nhật ký tour** 📝
   - Check-in: đã bắt đầu dẫn tour
   - Cập nhật hoạt động từng ngày
   - Gửi hình ảnh, video (minh chứng)
   - Ghi nhận tình trạng khách
   - Xem lịch sử nhật ký

#### 3. **Cập nhật Chi phí** 💵
   - Điền chi phí thực tế hằng ngày
   - Các loại chi phí: ăn uống, khách sạn, vé tham quan, xăng xe, dịch vụ
   - Upload hóa đơn (chụp ảnh)
   - Gửi báo cáo tổng hợp cho admin
   - Chỉnh sửa, **xóa** chi phí của riêng mình

#### 4. **Báo cáo Sự cố** 🚨
   - Báo cáo tình hình tour: thời tiết, giao thông, trễ giờ
   - Báo cáo khách gặp vấn đề
   - Báo cáo thất lạc, mất đồ
   - Ghi lại mức độ sự cố
   - Cập nhật trạng thái

#### 5. **Điểm danh Khách hàng** ✅
   - Điểm danh khách có mặt, vắng mặt, đi muộn
   - **Nút "Điểm danh tất cả"** - Tích danh sách khách chưa điểm danh
   - Xem thống kê điểm danh

#### 6. **Kết thúc tour** 🏁
   - Gửi biên bản kết thúc tour
   - Checklist bàn giao
   - Đánh giá tour (nội bộ)

### IV. TÍNH NĂNG GÓP PHẦN CẢI THIỆN HIỆU NĂNG 🎨

#### UI/UX Optimization (v2.0)
- **Color Scheme Hiện đại**: Blue-Purple theme (#2563eb, #7c3aed)
- **Gradient Design**: Buttons, cards, headers
- **Animation System**: 40+ animations, smooth transitions
- **Responsive Design**: Tối ưu cho desktop, tablet, mobile
- **Dark Mode Ready**: CSS variables cho theme switching
- **Better Accessibility**: Contrast ratio, font sizes
- **Performance**: GPU acceleration, optimized CSS

## 📋 Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7 hoặc MariaDB >= 10.2
- Apache/Nginx web server
- phpMyAdmin (để quản lý database)
- Modern browser (Chrome, Firefox, Safari, Edge)

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
│   ├── css/
│   │   ├── style.css          # CSS chính với 24+ variables
│   │   ├── animations.css      # 40+ animations
│   │   ├── pages.css           # CSS trang con
│   │   └── reports.css         # CSS báo cáo
│   ├── js/
│   │   └── custom.js           # Custom JavaScript
│   └── uploads/                # Thư mục upload file
├── configs/
│   ├── env.php                 # Cấu hình môi trường
│   ├── auth.php                # Xác thực
│   └── helper.php              # Hàm helper
├── controllers/                # Controllers (20+)
│   ├── TourController.php
│   ├── AttendanceController.php
│   ├── CostController.php
│   ├── VehicleController.php
│   ├── ReportController.php
│   └── ...
├── database/
│   ├── tour_management.sql     # File SQL database
│   └── README.md               # Hướng dẫn database
├── models/                     # Models (20+)
│   ├── BaseModel.php
│   ├── UserModel.php
│   ├── TourModel.php
│   └── ...
├── routes/
│   └── index.php               # Định tuyến
├── views/                      # Views (15+ folders)
│   ├── layouts/
│   ├── tours/
│   ├── attendance/
│   ├── costs/
│   ├── vehicles/
│   ├── reports/
│   └── ...
├── document/                   # Tài liệu
├── FINAL_REPORT.md             # Báo cáo hoàn thành
├── README_UI_OPTIMIZATION.md   # Hướng dẫn UI/UX
├── ANIMATIONS_GUIDE.md         # Hướng dẫn animations
└── index.php                   # Entry point
```

## 🗄️ Cấu trúc Database

Hệ thống có 13 bảng chính:

| Bảng | Mô tả |
|------|-------|
| `users` | Người dùng (Admin, HDV) |
| `tour_categories` | Danh mục tour |
| `tours` | Thông tin tour |
| `itineraries` | Lịch trình tour (từng ngày) |
| `tour_assignments` | Phân công HDV |
| `vehicles` | Quản lý xe |
| `vehicle_assignments` | Gán xe cho tour |
| `cost_categories` | Loại chi phí |
| `tour_costs` | Chi phí tour |
| `tour_customers` | Khách nội bộ |
| `customer_attendance` | Điểm danh khách |
| `tour_daily_logs` | Nhật ký hàng ngày |
| `tour_incidents` | Sự cố |
| `tour_feedbacks` | Đánh giá |
| `hotel_room_assignments` | Phân phòng khách sạn |
| `tour_policies` | Chính sách tour |

Xem chi tiết trong file `database/README.md`

## 🔧 Models & Controllers

**20+ Models:**
- `UserModel`, `TourModel`, `TourCategoryModel`, `TourAssignmentModel`
- `TourCostModel`, `CostCategoryModel`, `ItineraryModel`, `TourCustomerModel`
- `TourDailyLogModel`, `VehicleModel`, `TourIncidentModel`, `TourFeedbackModel`
- `CustomerAttendanceModel`, `ChatGroupModel`, `ChatMessageModel`
- `BookingModel`, `HotelRoomAssignmentModel`, `TourPolicyModel`, v.v.

**20+ Controllers:**
- `TourController`, `AttendanceController`, `CostController`, `VehicleController`
- `ReportController`, `AuthController`, `ProfileController`, v.v.

## 🎨 CSS & Animations

**Tệp CSS:**
- `style.css` - 974+ dòng, 24+ CSS variables, modern gradients
- `animations.css` - 600+ dòng, 40+ animations, hover effects
- `pages.css` - Styling trang chuyên biệt
- `reports.css` - Styling báo cáo

**Animations:**
- Fade, Scale, Slide, Bounce, Pulse
- Hover effects cho buttons, cards
- Page transitions
- Loading animations

## 📝 Ghi chú

- Dự án sử dụng **PHP thuần** (không framework)
- Database sử dụng **MySQL/MariaDB**
- File upload lưu trong `assets/uploads/`
- Password được hash bằng `password_hash()` của PHP
- **Responsive Design**: Tối ưu cho tất cả màn hình
- **UI/UX Optimized**: Giao diện hiện đại, dễ sử dụng

## 🔐 Bảo mật

- Hash password bằng `password_hash()`
- Sử dụng PDO với prepared statements
- Validate input từ người dùng
- Kiểm tra quyền truy cập theo role
- CSRF protection ready
- SQL Injection prevention

## ✨ Những tính năng nổi bật được thêm gần đây

- ✅ **Chi tiết Chi phí**: Xem chi tiết, chỉnh sửa, xóa chi phí dễ dàng
- ✅ **Xóa Xe**: Quản lý xe hoàn chỉnh với chức năng xóa
- ✅ **Điểm danh Tất cả**: Một nút click điểm danh hết danh sách khách
- ✅ **Tour Info trong Báo cáo**: Hiển thị tên tour rõ ràng trong nhật ký, sự cố, đánh giá
- ✅ **UI/UX Modernization**: Color scheme, animations, responsive
- ✅ **Performance Optimization**: CSS variables, GPU acceleration

## 📞 Hỗ trợ & Troubleshooting

Nếu có vấn đề:
1. Kiểm tra cấu hình database trong `configs/env.php`
2. Kiểm tra quyền ghi của thư mục `assets/uploads/`
3. Kiểm tra log lỗi PHP
4. Xem file `FINAL_REPORT.md` để biết chi tiết hoàn thành
5. Xem file `README_UI_OPTIMIZATION.md` để biết về giao diện

## 📄 Dokumentation

- 📚 `README.md` - Tài liệu chính (file này)
- 📚 `FINAL_REPORT.md` - Báo cáo hoàn thành dự án
- 📚 `README_UI_OPTIMIZATION.md` - Hướng dẫn UI/UX
- 📚 `ANIMATIONS_GUIDE.md` - Hướng dẫn animation
- 📚 `UI_CHANGES_SUMMARY.md` - Tóm tắt thay đổi UI
- 📚 `OPTIMIZATION_CHECKLIST.txt` - Checklist tối ưu

---

**Phiên bản:** 2.0 (UI/UX Optimized)  
**Ngày cập nhật:** December 14, 2025  
**Status:** ✅ Production Ready

**Chúc bạn phát triển dự án thành công! 🎉**

