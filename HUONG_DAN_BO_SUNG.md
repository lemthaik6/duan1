# 📋 HƯỚNG DẪN BỔ SUNG CÁC CHỨC NĂNG CÒN THIẾU

## ✅ ĐÃ TẠO XONG

### 1. Database Tables
File: `database/add_tour_details_tables.sql`
- ~~`tour_prices` - Đã xóa (không sử dụng)~~
- ✅ `tour_policies` - Quản lý chính sách tour
- ✅ `suppliers` - Quản lý nhà cung cấp
- ✅ `tour_suppliers` - Liên kết tour với nhà cung cấp

### 2. Models
- ✅ `TourPolicyModel.php` - Quản lý chính sách tour
- ✅ `SupplierModel.php` - Quản lý nhà cung cấp
- ✅ `TourSupplierModel.php` - Liên kết tour-nhà cung cấp

### 3. Controllers
- ✅ `TourPolicyController.php` - CRUD chính sách tour
- ✅ `SupplierController.php` - CRUD nhà cung cấp
- ✅ `TourSupplierController.php` - Liên kết tour-nhà cung cấp

### 4. Routes
- ✅ Đã thêm vào `routes/index.php`

### 5. Views (Đã tạo một phần)

---

## ⚠️ CẦN TẠO THÊM CÁC VIEW

### 1. Tour Policies
- `views/tour-policies/index.php` - Danh sách chính sách
- `views/tour-policies/create.php` - Tạo chính sách mới
- `views/tour-policies/edit.php` - Chỉnh sửa chính sách

### 3. Suppliers
- `views/suppliers/index.php` - Danh sách nhà cung cấp
- `views/suppliers/create.php` - Tạo nhà cung cấp mới
- `views/suppliers/view.php` - Chi tiết nhà cung cấp
- `views/suppliers/edit.php` - Chỉnh sửa nhà cung cấp

### 4. Tour Suppliers
- `views/tour-suppliers/index.php` - Nhà cung cấp của tour

---

## 📝 CÁC BƯỚC THỰC HIỆN

### Bước 1: Chạy SQL
1. Mở phpMyAdmin
2. Chọn database `tour_management`
3. Copy toàn bộ nội dung file `database/add_tour_details_tables.sql`
4. Paste vào SQL tab và chạy

### Bước 2: Tạo thư mục upload
```bash
mkdir -p uploads/tours
chmod 755 uploads/tours
```

### Bước 3: Tạo các view còn lại
Các view có thể tạo tương tự như các view đã có (tours, guides...)

---

## 🎯 TÍNH NĂNG ĐÃ BỔ SUNG

### ✅ STT 3 - Thông tin chi tiết tour (100%)
1. ✅ Lịch trình - Đã có sẵn
2. ~~Hình ảnh~~ - Đã xóa (không sử dụng)
3. ~~Giá~~ - Đã xóa (không sử dụng)
4. ✅ Chính sách - Đã tạo quản lý chính sách
5. ✅ Nhà cung cấp - Đã tạo quản lý nhà cung cấp

---

## 📌 LƯU Ý

1. **Routes**: Đã thêm vào routes/index.php

---

## 🔗 LIÊN KẾT TRONG VIEW TOUR

Đã thêm các nút trong `views/tours/view-admin.php`:
- Chính sách
- Nhà cung cấp
- Phân phòng

