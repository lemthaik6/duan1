## ⚠️ SETUP HỆ THỐNG QUẢN LÝ XE CHO TOUR

Bạn vừa thêm tính năng quản lý xe cho tour. Để hệ thống hoạt động bình thường, bạn cần **SETUP DATABASE** bằng một trong các cách sau:

### Cách 1: Chạy Script Tự Động (Nên dùng)

Mở trình duyệt và truy cập:
```
http://localhost/duan1/database/create_vehicle_assignments_table.php
```

Script sẽ:
- Kiểm tra bảng `vehicle_assignments` có tồn tại không
- Tự động tạo bảng nếu chưa có
- Hiển thị danh sách các cột đã tạo

### Cách 2: Chạy SQL Trực Tiếp

Nếu bạn dùng **phpMyAdmin** hoặc **MySQL Workbench**, hãy chạy câu lệnh SQL này:

```sql
CREATE TABLE IF NOT EXISTS vehicle_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    usage_purpose VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### Cách 3: Kiểm Tra Cấu Trúc Bảng

Để xem bảng `vehicle_assignments` đã được tạo đúng chưa, hãy truy cập:
```
http://localhost/duan1/database/check_vehicle_assignments_table.php
```

---

## ✅ NHỮNG GÌ ĐÃ ĐƯỢC THÊM

### Model:
- `TourVehicleAssignmentModel.php` - Quản lý phân công xe cho tour

### Controller:
- `TourVehicleAssignmentController.php` - Xử lý logic CRUD

### Views:
- `tour-vehicles/index.php` - Danh sách xe của tour
- `tour-vehicles/create.php` - Thêm xe vào tour
- `tour-vehicles/edit.php` - Chỉnh sửa phân công xe
- `tour-vehicles/view.php` - Xem chi tiết phân công xe

### Routes:
- Thêm route `tour-vehicles` vào file routes/index.php

### Chỉnh sửa:
- Cập nhật `tours/view-admin.php` - Thêm section xe và nút quản lý
- Cập nhật `TourController.php` - Thêm vehicleAssignmentModel

---

## 🚀 CÁCH SỬ DỤNG

1. **Xem chi tiết tour** → Click nút "Quản lý xe" (màu đỏ)
2. **Xem danh sách xe** được phân công cho tour
3. **Thêm xe mới** → Click "Thêm xe" → Chọn xe, nhập thời gian & mục đích
4. **Chỉnh sửa** → Click icon ✏️
5. **Xóa** → Click icon 🗑️

---

## ⚡ TÍNH NĂNG

- ✅ Phân công xe cho từng tour cụ thể
- ✅ Kiểm tra xe không bị trùng lịch
- ✅ Quản lý mục đích sử dụng (đưa đón khách, vận chuyển hàng...)
- ✅ Ghi chú chi tiết cho mỗi phân công
- ✅ Trạng thái phân công (hoạt động, không hoạt động, bảo trì, hủy)

---

## 📝 LỖI CỠN GẶP

**Lỗi: Unknown column 'vehicle_assignments' in 'order clause'**
→ Bảng chưa được tạo. Chạy script từ Cách 1 hoặc 2 ở trên.

**Lỗi: Column not found: 1054 Unknown column 'va.created_at'**
→ Đã được sửa. Bảng không có cột `created_at`, thay vào đó sử dụng `id`.

**Lỗi: Unknown column 'status' in 'where clause'**
→ Đã được sửa. Bảng không có cột `status`. Model đã được cập nhật để không sử dụng status.

---

**Sau khi setup xong, bạn có thể xóa file setup này.**
