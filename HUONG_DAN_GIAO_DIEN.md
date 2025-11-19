# 📱 HƯỚNG DẪN CÁC FILE GIAO DIỆN (VIEWS)

## 📋 MỤC LỤC
1. [Layout & Template](#layout--template)
2. [Authentication](#authentication)
3. [Dashboard](#dashboard)
4. [Tour Management](#tour-management)
5. [Customer Management](#customer-management)
6. [Guide Management](#guide-management)
7. [Booking Management](#booking-management)
8. [Cost Management](#cost-management)
9. [Daily Logs](#daily-logs)
10. [Attendance](#attendance)
11. [Incidents](#incidents)
12. [Feedbacks](#feedbacks)
13. [Reports](#reports)
14. [Suppliers](#suppliers)
15. [Vehicles](#vehicles)
16. [Categories](#categories)
17. [Hotel Rooms](#hotel-rooms)
18. [Tour Policies](#tour-policies)
19. [Tour Suppliers](#tour-suppliers)
20. [Profile](#profile)

---

## 🎨 LAYOUT & TEMPLATE

### `views/main.php`
**Chức năng**: File template chính của hệ thống
- **Mô tả**: 
  - Chứa cấu trúc HTML cơ bản (DOCTYPE, head, body)
  - Load Bootstrap CSS/JS và Bootstrap Icons
  - Load thư viện QR Code
  - Chứa các style CSS chung
  - Include header nếu user đã đăng nhập
  - Render các view con thông qua biến `$view`
- **Sử dụng**: Được gọi bởi tất cả các controller thông qua `require_once PATH_VIEW_MAIN`

### `views/layouts/header.php`
**Chức năng**: Header navigation bar
- **Mô tả**:
  - Hiển thị menu điều hướng chính
  - Menu khác nhau cho Admin và Guide
  - Dropdown menu thông tin user và đăng xuất
  - Responsive với Bootstrap navbar
- **Menu Admin**: Tour, HDV, Xe, Booking, Báo cáo
- **Menu Guide**: Tour của tôi, Nhật ký, Chi phí, Sự cố, Phản hồi

---

## 🔐 AUTHENTICATION

### `views/auth/login.php`
**Chức năng**: Trang đăng nhập
- **Mô tả**:
  - Form đăng nhập với email và password
  - Hiển thị thông báo lỗi nếu đăng nhập thất bại
  - Redirect về trang chủ sau khi đăng nhập thành công
  - Validation phía client và server
- **Controller**: `AuthController::login()`

---

## 📊 DASHBOARD

### `views/dashboard/admin.php`
**Chức năng**: Trang chủ dành cho Admin
- **Mô tả**:
  - Thống kê tổng quan: Tổng tour, Tour sắp tới, Tour đang diễn ra, Tour hoàn thành
  - Danh sách tour sắp tới (5 tour gần nhất)
  - Danh sách booking gần đây
  - Thống kê nhanh về khách hàng, HDV, booking
- **Dữ liệu**: Lấy từ `TourModel`, `BookingModel`, `UserModel`

### `views/dashboard/guide.php`
**Chức năng**: Trang chủ dành cho Guide (HDV)
- **Mô tả**:
  - Hiển thị tour đang được phân công
  - Tour sắp tới của HDV
  - Thống kê tour theo trạng thái
  - Công cụ nhanh: Điểm danh, Ghi nhật ký, Báo cáo sự cố
- **Dữ liệu**: Lấy từ `TourAssignmentModel`, `TourModel`

---

## 🗺️ TOUR MANAGEMENT

### `views/tours/index.php`
**Chức năng**: Danh sách tất cả tour (Admin only)
- **Mô tả**:
  - Hiển thị danh sách tour dạng bảng
  - Bộ lọc theo trạng thái và danh mục
  - Các cột: Mã tour, Tên, Danh mục, Ngày bắt đầu/kết thúc, Trạng thái, Thao tác
  - Nút: Xem, Sửa, Xóa
  - Nút "Tạo tour mới"
- **Controller**: `TourController::index()`

### `views/tours/create.php`
**Chức năng**: Form tạo tour mới (Admin only)
- **Mô tả**:
  - Form nhập thông tin tour:
    - Tên tour (bắt buộc)
    - Danh mục (dropdown)
    - Mô tả (textarea)
    - Lịch trình tổng quan (textarea)
    - Ngày bắt đầu/kết thúc (date picker)
    - Giá nội bộ (number)
    - Cấp độ ưu tiên (dropdown: low, medium, high, urgent)
    - Trạng thái (dropdown: upcoming, ongoing, completed, cancelled)
  - Validation và hiển thị lỗi
  - Tự động tạo mã tour (TOUR + Năm + Số thứ tự)
- **Controller**: `TourController::create()`

### `views/tours/edit.php`
**Chức năng**: Form chỉnh sửa tour (Admin only)
- **Mô tả**:
  - Tương tự form create nhưng đã có dữ liệu sẵn
  - Không cho sửa mã tour (code)
  - Cập nhật thông tin tour
- **Controller**: `TourController::edit()`

### `views/tours/view-admin.php`
**Chức năng**: Chi tiết tour dành cho Admin
- **Mô tả**:
  - **Phần chính (col-md-8)**:
    - Thông tin tour: Mã, tên, danh mục, trạng thái, ngày, giá, mô tả
    - Lịch trình chi tiết: Hiển thị từ bảng `itineraries`
    - Danh sách nhật ký tour (3 mục gần nhất)
    - Danh sách sự cố (3 mục gần nhất)
  - **Sidebar (col-md-4)**:
    - **QR Code**: Mã QR để quét xem thông tin tour công khai
    - **Hướng dẫn viên**: Danh sách HDV được phân công, nút phân công
    - **Khách**: Danh sách khách (5 người đầu), nút xem tất cả
    - **Chi phí**: Tổng chi phí tour (giá gốc + chi phí phát sinh)
  - **Nút hành động**: Chính sách, Nhà cung cấp, Phân phòng, Chỉnh sửa
- **Controller**: `TourController::view()`

### `views/tours/view-guide.php`
**Chức năng**: Chi tiết tour dành cho Guide (HDV)
- **Mô tả**:
  - Tương tự `view-admin.php` nhưng:
    - Không có phần quản lý HDV
    - Không có phần chi phí chi tiết (chỉ tổng)
    - Có thêm phần "Công cụ" với các nút nhanh:
      - Điểm danh khách
      - Quản lý khách
      - Ghi nhật ký
      - Báo cáo sự cố
      - Gửi phản hồi đánh giá
    - Có QR Code để chia sẻ thông tin tour
  - HDV chỉ xem tour được phân công
- **Controller**: `TourController::view()`

### `views/tours/view.php`
**Chức năng**: Chi tiết tour (view chung, có thể dùng cho cả admin và guide)
- **Mô tả**: View cơ bản, có thể được sử dụng làm fallback

### `views/tours/my-tours.php`
**Chức năng**: Danh sách tour của HDV (Guide only)
- **Mô tả**:
  - Hiển thị danh sách tour được phân công cho HDV hiện tại
  - Lọc theo trạng thái tour
  - Hiển thị: Mã tour, Tên, Ngày bắt đầu/kết thúc, Trạng thái
  - Nút "Xem chi tiết" để vào trang view-guide
- **Controller**: `TourController::myTours()`

### `views/tours/public-view.php`
**Chức năng**: Trang xem tour công khai (không cần đăng nhập)
- **Mô tả**:
  - Trang đơn giản, không có header/footer của hệ thống
  - Hiển thị thông tin tour: Tên, mã, danh mục, trạng thái, ngày, mô tả
  - Hiển thị lịch trình tour
  - Hiển thị thông tin hướng dẫn viên
  - Truy cập qua URL: `?action=tours/public-view&code={mã_tour}`
  - Được sử dụng trong QR Code
- **Controller**: `TourController::publicView()`

### `views/tours/public-not-found.php`
**Chức năng**: Trang báo lỗi khi tour không tồn tại
- **Mô tả**: Hiển thị thông báo khi mã tour không hợp lệ hoặc không tồn tại

---

## 👥 CUSTOMER MANAGEMENT

### `views/customers/index.php`
**Chức năng**: View chung, redirect đến index-admin hoặc index-guide tùy role

### `views/customers/index-admin.php`
**Chức năng**: Danh sách khách của tour (Admin)
- **Mô tả**:
  - Hiển thị danh sách khách trong tour
  - Bảng với các cột: STT, Họ tên, Điện thoại, Email, CMND/CCCD, Ghi chú
  - Nút "Thêm khách" và "In danh sách"
  - Nút "Xóa" cho từng khách (Admin only)
- **Controller**: `CustomerController::index()`

### `views/customers/index-guide.php`
**Chức năng**: Danh sách khách của tour (Guide)
- **Mô tả**:
  - Tương tự index-admin nhưng:
    - Không có nút xóa
    - Có nút "Cập nhật" yêu cầu đặc biệt cho từng khách
    - Modal để cập nhật `special_requests`
- **Controller**: `CustomerController::index()`

### `views/customers/create.php`
**Chức năng**: Form thêm khách vào tour
- **Mô tả**:
  - Form nhập: Họ tên (bắt buộc), Điện thoại, Email, CMND/CCCD, Ghi chú
  - Yêu cầu đặc biệt (special_requests) - HDV có thể cập nhật sau
  - Lưu vào bảng `tour_customers`
- **Controller**: `CustomerController::create()`

### `views/customers/print.php`
**Chức năng**: In danh sách khách của tour
- **Mô tả**:
  - Layout tối ưu cho in ấn
  - Hiển thị thông tin tour và danh sách khách
  - Có thể in trực tiếp từ trình duyệt
- **Controller**: `CustomerController::print()`

---

## 👨‍🏫 GUIDE MANAGEMENT

### `views/guides/index.php`
**Chức năng**: Danh sách hướng dẫn viên (Admin only)
- **Mô tả**:
  - Bảng danh sách HDV với: Tên, Email, Điện thoại, Số tour đã làm, Trạng thái
  - Nút: Xem, Sửa, Xóa
  - Nút "Thêm HDV mới"
- **Controller**: `GuideController::index()`

### `views/guides/create.php`
**Chức năng**: Form tạo HDV mới (Admin only)
- **Mô tả**:
  - Form nhập: Họ tên, Email, Điện thoại, Mật khẩu, Xác nhận mật khẩu
  - Tự động set role = 'guide'
- **Controller**: `GuideController::create()`

### `views/guides/edit.php`
**Chức năng**: Form chỉnh sửa thông tin HDV (Admin only)
- **Mô tả**: Tương tự create nhưng đã có dữ liệu, có thể đổi mật khẩu
- **Controller**: `GuideController::edit()`

### `views/guides/view.php`
**Chức năng**: Chi tiết HDV (Admin only)
- **Mô tả**:
  - Hiển thị thông tin HDV: Tên, email, điện thoại
  - Danh sách tour đã được phân công
  - Lịch sử tour đã làm
- **Controller**: `GuideController::view()`

### `views/guides/assign.php`
**Chức năng**: Phân công HDV cho tour (Admin only)
- **Mô tả**:
  - Form chọn HDV từ danh sách
  - Hiển thị tour cần phân công
  - Có thể phân công nhiều HDV cho 1 tour
  - Lưu vào bảng `tour_assignments`
- **Controller**: `GuideController::assign()`

---

## 📅 BOOKING MANAGEMENT

### `views/bookings/index.php`
**Chức năng**: Danh sách booking (Admin only)
- **Mô tả**:
  - Bảng danh sách booking với: Mã booking, Tour, Khách, Số lượng, Giá, Cọc, Trạng thái, Ngày tạo
  - Bộ lọc theo trạng thái
  - Nút: Xem, Sửa trạng thái
- **Controller**: `BookingController::index()`

### `views/bookings/create.php`
**Chức năng**: Form tạo booking mới (Admin only)
- **Mô tả**:
  - Form nhập: Chọn tour, Loại khách (lẻ/đoàn), Tên khách, Số lượng, Giá, Cọc
  - Tự động tạo mã booking
  - Validation số lượng không vượt quá capacity của tour
- **Controller**: `BookingController::create()`

### `views/bookings/view.php`
**Chức năng**: Chi tiết booking (Admin only)
- **Mô tả**:
  - Hiển thị đầy đủ thông tin booking
  - Lịch sử thay đổi trạng thái
  - Có thể cập nhật trạng thái từ đây
- **Controller**: `BookingController::view()`

---

## 💰 COST MANAGEMENT

### `views/costs/index.php`
**Chức năng**: Danh sách chi phí của tour (Admin only)
- **Mô tả**:
  - Hiển thị tất cả chi phí của tour
  - Bảng với: Loại chi phí, Mô tả, Số tiền, Ngày, Người tạo
  - Tổng chi phí
  - Nút "Thêm chi phí"
- **Controller**: `CostController::index()`

### `views/costs/my-costs.php`
**Chức năng**: Chi phí do HDV tạo (Guide only)
- **Mô tả**:
  - Tương tự index nhưng chỉ hiển thị chi phí do HDV hiện tại tạo
  - HDV có thể thêm chi phí mới
- **Controller**: `CostController::myCosts()`

### `views/costs/create.php`
**Chức năng**: Form thêm chi phí
- **Mô tả**:
  - Form nhập: Loại chi phí (dropdown), Mô tả, Số tiền, Ngày
  - Lưu vào bảng `tour_costs`
- **Controller**: `CostController::create()`

---

## 📝 DAILY LOGS

### `views/daily-logs/index.php`
**Chức năng**: Danh sách nhật ký tour
- **Mô tả**:
  - Hiển thị nhật ký theo tour
  - Bảng với: Ngày, Hoạt động, Tình trạng khách, Thời tiết, Giao thông
  - HDV chỉ xem nhật ký tour được phân công
  - Nút "Ghi nhật ký mới"
- **Controller**: `DailyLogController::index()`

### `views/daily-logs/create.php`
**Chức năng**: Form ghi nhật ký mới
- **Mô tả**:
  - Form nhập: Ngày, Hoạt động, Tình trạng khách, Thời tiết, Giao thông, Ghi chú
  - Lưu vào bảng `tour_daily_logs`
- **Controller**: `DailyLogController::create()`

---

## ✅ ATTENDANCE

### `views/attendance/index.php`
**Chức năng**: Điểm danh khách (Guide only)
- **Mô tả**:
  - Hiển thị danh sách khách của tour
  - Checkbox để đánh dấu có mặt/vắng mặt
  - Lưu vào bảng `customer_attendance`
  - Có thể điểm danh theo ngày
- **Controller**: `AttendanceController::index()`

---

## ⚠️ INCIDENTS

### `views/incidents/index.php`
**Chức năng**: View chung, redirect đến index-admin hoặc index-guide

### `views/incidents/index-admin.php`
**Chức năng**: Danh sách sự cố (Admin)
- **Mô tả**:
  - Hiển thị tất cả sự cố của tất cả tour
  - Bảng với: Tour, Tiêu đề, Mức độ, Ngày, Trạng thái xử lý
  - Nút xem chi tiết
- **Controller**: `IncidentController::index()`

### `views/incidents/index-guide.php`
**Chức năng**: Danh sách sự cố (Guide)
- **Mô tả**:
  - Chỉ hiển thị sự cố của tour được phân công
  - Có thể tạo sự cố mới
- **Controller**: `IncidentController::index()`

### `views/incidents/create.php`
**Chức năng**: Form báo cáo sự cố
- **Mô tả**:
  - Form nhập: Tiêu đề, Mô tả, Mức độ (thấp/trung bình/cao/khẩn cấp), Ngày xảy ra
  - Lưu vào bảng `tour_incidents`
- **Controller**: `IncidentController::create()`

---

## 💬 FEEDBACKS

### `views/feedbacks/index.php`
**Chức năng**: Danh sách phản hồi đánh giá
- **Mô tả**:
  - Hiển thị phản hồi từ khách hàng
  - HDV có thể xem phản hồi tour được phân công
  - Admin xem tất cả
- **Controller**: `FeedbackController::index()`

### `views/feedbacks/create.php`
**Chức năng**: Form tạo phản hồi
- **Mô tả**:
  - Form nhập: Tiêu đề, Nội dung, Đánh giá (sao), Tour
  - Lưu vào bảng `tour_feedbacks`
- **Controller**: `FeedbackController::create()`

### `views/feedbacks/view.php`
**Chức năng**: Chi tiết phản hồi
- **Mô tả**: Hiển thị đầy đủ thông tin phản hồi

### `views/feedbacks/edit.php`
**Chức năng**: Chỉnh sửa phản hồi (Admin only)

### `views/feedbacks/admin.php`
**Chức năng**: Quản lý phản hồi (Admin only)

---

## 📊 REPORTS

### `views/reports/index.php`
**Chức năng**: Báo cáo & Thống kê (Admin only)
- **Mô tả**:
  - **Doanh thu**: Tổng doanh thu từ bookings
  - **Chi phí**: Tổng chi phí từ tour_costs
  - **Lợi nhuận**: Doanh thu - Chi phí
  - **Thống kê booking**: Theo trạng thái (pending, confirmed, completed...)
  - **Thống kê tour**: Theo tháng, theo trạng thái
  - Biểu đồ và bảng thống kê
- **Controller**: `ReportController::index()`

---

## 🏢 SUPPLIERS

### `views/suppliers/index.php`
**Chức năng**: Danh sách nhà cung cấp (Admin only)
- **Mô tả**:
  - Bảng danh sách nhà cung cấp
  - Bộ lọc theo loại (khách sạn, nhà hàng, vận chuyển...) và trạng thái
  - Cột: Tên, Loại, Liên hệ, Năng lực, Đánh giá, Trạng thái
  - Nút: Xem, Sửa, Xóa
- **Controller**: `SupplierController::index()`

### `views/suppliers/create.php`
**Chức năng**: Form tạo nhà cung cấp mới
- **Mô tả**:
  - Form nhập: Tên, Loại, Người liên hệ, Điện thoại, Email, Địa chỉ, Năng lực, Đánh giá, Mô tả
- **Controller**: `SupplierController::create()`

### `views/suppliers/view.php`
**Chức năng**: Chi tiết nhà cung cấp
- **Mô tả**:
  - Hiển thị đầy đủ thông tin nhà cung cấp
  - Danh sách tour đã cung cấp dịch vụ
- **Controller**: `SupplierController::view()`

### `views/suppliers/edit.php`
**Chức năng**: Form chỉnh sửa nhà cung cấp
- **Mô tả**: Tương tự create nhưng đã có dữ liệu
- **Controller**: `SupplierController::edit()`

---

## 🚗 VEHICLES

### `views/vehicles/index.php`
**Chức năng**: Danh sách xe (Admin only)
- **Mô tả**:
  - Bảng danh sách xe với: Biển số, Loại xe, Sức chứa, Tài xế, Điện thoại, Trạng thái
  - Bộ lọc theo trạng thái (sẵn sàng, đang sử dụng, bảo trì, không sẵn sàng)
  - Nút: Xem, Sửa
- **Controller**: `VehicleController::index()`

### `views/vehicles/create.php`
**Chức năng**: Form thêm xe mới
- **Mô tả**:
  - Form nhập: Biển số, Loại xe, Sức chứa, Tên tài xế, Điện thoại tài xế, Trạng thái, Ghi chú
- **Controller**: `VehicleController::create()`

### `views/vehicles/view.php`
**Chức năng**: Chi tiết xe
- **Mô tả**:
  - Hiển thị thông tin xe và tài xế
  - Nút chỉnh sửa
- **Controller**: `VehicleController::view()`

### `views/vehicles/edit.php`
**Chức năng**: Form chỉnh sửa xe
- **Mô tả**: Tương tự create nhưng đã có dữ liệu
- **Controller**: `VehicleController::edit()`

---

## 📂 CATEGORIES

### `views/categories/index.php`
**Chức năng**: Danh sách danh mục tour (Admin only)
- **Mô tả**:
  - Bảng danh sách danh mục: Tên, Mô tả, Số tour, Trạng thái
  - Nút: Sửa, Xóa
  - Nút "Thêm danh mục"
- **Controller**: `CategoryController::index()`

### `views/categories/create.php`
**Chức năng**: Form tạo danh mục mới
- **Mô tả**: Form nhập tên và mô tả danh mục
- **Controller**: `CategoryController::create()`

### `views/categories/edit.php`
**Chức năng**: Form chỉnh sửa danh mục
- **Mô tả**: Tương tự create nhưng đã có dữ liệu
- **Controller**: `CategoryController::edit()`

---

## 🏨 HOTEL ROOMS

### `views/hotel-rooms/index.php`
**Chức năng**: Phân phòng khách sạn cho tour (Admin only)
- **Mô tả**:
  - Hiển thị danh sách khách của tour
  - Form phân phòng: Chọn khách, Chọn phòng, Ngày check-in/check-out
  - Lưu vào bảng `hotel_room_assignments`
- **Controller**: `HotelRoomController::index()`

---

## 📋 TOUR POLICIES

### `views/tour-policies/index.php`
**Chức năng**: Danh sách chính sách của tour (Admin only)
- **Mô tả**:
  - Hiển thị các chính sách: Đặt tour, Hủy tour, Hoàn tiền, Đổi lịch
  - Có thể tạo và chỉnh sửa chính sách
- **Controller**: `TourPolicyController::index()`

### `views/tour-policies/create.php`
**Chức năng**: Form tạo chính sách mới
- **Mô tả**: Form nhập loại chính sách và nội dung
- **Controller**: `TourPolicyController::create()`

### `views/tour-policies/edit.php`
**Chức năng**: Form chỉnh sửa chính sách
- **Mô tả**: Tương tự create nhưng đã có dữ liệu
- **Controller**: `TourPolicyController::edit()`

---

## 🔗 TOUR SUPPLIERS

### `views/tour-suppliers/index.php`
**Chức năng**: Quản lý nhà cung cấp của tour (Admin only)
- **Mô tả**:
  - Hiển thị danh sách nhà cung cấp đã liên kết với tour
  - Form liên kết nhà cung cấp mới: Chọn nhà cung cấp, Mô tả dịch vụ, Mã booking, Ngày liên hệ, Ghi chú
  - Lưu vào bảng `tour_suppliers`
- **Controller**: `TourSupplierController::index()`

---

## 👤 PROFILE

### `views/profile/index.php`
**Chức năng**: Thông tin cá nhân
- **Mô tả**:
  - Hiển thị thông tin user hiện tại
  - Form chỉnh sửa: Họ tên, Email, Điện thoại, Đổi mật khẩu
  - Cả Admin và Guide đều có thể sử dụng
- **Controller**: `ProfileController::index()`

---

## 📌 LƯU Ý CHUNG

### Cấu trúc View
- Tất cả view đều được render thông qua `views/main.php`
- View được load thông qua biến `$view` trong controller
- Path: `PATH_VIEW . $view . '.php'`

### Phân quyền
- **Admin**: Có quyền truy cập tất cả chức năng
- **Guide (HDV)**: Chỉ xem và quản lý tour được phân công
- **Public**: Chỉ xem được trang `public-view.php` qua QR code

### Bootstrap & Icons
- Sử dụng Bootstrap 5.3.3
- Sử dụng Bootstrap Icons 1.11.0
- Responsive design cho mobile

### QR Code
- Sử dụng thư viện `qrcode.js` (CDN)
- Fallback: API online nếu thư viện không tải được
- URL format: `BASE_URL?action=tours/public-view&code={mã_tour}`

---

## 🔄 FLOW XỬ LÝ

1. **User request** → `routes/index.php` → **Controller**
2. **Controller** xử lý logic → Gọi **Model** để lấy dữ liệu
3. **Controller** set biến `$view` và `$title`
4. **Controller** require `PATH_VIEW_MAIN` (main.php)
5. **main.php** include header (nếu đã login)
6. **main.php** require view file từ `PATH_VIEW . $view . '.php'`
7. **View** render HTML với dữ liệu từ controller
8. **main.php** đóng footer

---

## 📝 GHI CHÚ

- Tất cả view đều sử dụng `htmlspecialchars()` để bảo mật XSS
- Form đều có validation phía client và server
- Thông báo lỗi/thành công sử dụng session flash messages
- Date format: `d/m/Y` (ngày/tháng/năm)
- Currency format: `number_format()` với dấu phẩy ngăn cách hàng nghìn

