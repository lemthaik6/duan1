# 🔍 BÁO CÁO KIỂM TRA CODE & DANH SÁCH CẢI THIỆN

**Ngày kiểm tra**: 5 tháng 12, 2025  
**Trạng thái**: Tìm thấy 12 vấn đề chính

---

## 🔴 VẤN ĐỀ NGHIÊM TRỌNG (CRITICAL)

### 1. ⚠️ **Credentials hiển thị trong view login**
**File**: `views/auth/login.php` (dòng 41-43)  
**Vấn đề**: Tên đăng nhập & mật khẩu mẫu hiển thị trong HTML - bảo mật cực kỳ yếu  
```php
<!-- Hiển thị công khai trong trang -->
<strong>Admin:</strong> admin / admin123<br>
<strong>Guide:</strong> guide1 / guide123<br>
```

**Tác hại**: 
- Bất kỳ ai vào trang login đều thấy credentials
- Bị lộ trên web cache, browser history
- Dễ bị tấn công brute force

**Giải pháp**: 
- ✅ Xóa phần credentials khỏi view
- ✅ Tài liệu này chỉ nên có trong file README.md hoặc document bảo mật

---

### 2. 🗝️ **Database credentials để trong file env.php không bảo vệ**
**File**: `configs/env.php`  
**Vấn đề**: 
```php
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tour_management');
```

**Tác hại**: 
- File php có thể bị expose nếu cấu hình web server sai
- Nếu có LFI (Local File Inclusion), attacker có thể đọc được
- Password rỗng - nguy hiểm nếu DB công khai

**Giải pháp**:
- ✅ Đặt password cho MySQL root user
- ✅ Sử dụng .env file thay vì define() constants
- ✅ Không commit credentials lên Git (thêm `.env` vào `.gitignore`)

---

### 3. 🛡️ **Thiếu CSRF token trong tất cả forms**
**File**: Tất cả các view chứa form  
**Vấn đề**: 
```php
<form method="POST" action="">
    <!-- Không có CSRF token -->
    <input type="text" name="username" ...>
</form>
```

**Tác hại**: 
- Dễ bị CSRF (Cross-Site Request Forgery)
- Attacker có thể thực hiện hành động trên website thay bạn
- Vi phạm OWASP Top 10

**Giải pháp**:
- ✅ Tạo hàm `generateCSRFToken()` và `validateCSRFToken()`
- ✅ Thêm `<input type="hidden" name="csrf_token" value="...">` vào mỗi form
- ✅ Validate token ở controller trước khi xử lý POST

---

## 🟠 VẤN ĐỀ TRUNG BÌNH (HIGH PRIORITY)

### 4. 📝 **XSS vulnerability - Không escape output trong một số chỗ**
**File**: Nhiều view  
**Vấn đề**: 
```php
<!-- ❌ Không safe - có thể XSS -->
<?= $tour['description'] ?>
<?= $user['notes'] ?>

<!-- ✅ Đúng cách -->
<?= htmlspecialchars($tour['description'], ENT_QUOTES, 'UTF-8') ?>
```

**Tác hại**: 
- Attacker inject JavaScript để steal cookies, redirect
- Đánh cắp session của user

**Giải pháp**:
- ✅ Tạo hàm helper `e()` để escape
- ✅ Kiểm tra tất cả `<?= $variable ?>` và áp dụng `htmlspecialchars()`
- ✅ Đặc biệt với user input: description, notes, content

---

### 5. ❌ **Upload file không validate MIME type**
**File**: `configs/helper.php` - hàm `upload_file()`  
**Vấn đề**: 
```php
function upload_file($folder, $file)
{
    $targetFile = $folder . '/' . time() . '-' . $file["name"];
    // Không check file type!
    if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
        return $targetFile;
    }
}
```

**Tác hại**: 
- User có thể upload file .exe, .php, .js
- Attacker có thể upload shell và execute code
- RCE (Remote Code Execution)

**Giải pháp**:
- ✅ Whitelist MIME types an toàn: `['image/jpeg', 'image/png', 'application/pdf']`
- ✅ Validate bằng `mime_content_type()` hoặc `finfo_file()`
- ✅ Rename file thành UUID thay vì giữ extension gốc
- ✅ Lưu uploads ngoài webroot hoặc disable PHP execution

---

### 6. 🔐 **Không hash password khi update user**
**File**: `models/UserModel.php` - `update()` function  
**Vấn đề**: 
```php
public function update($id, $data)
{
    if (isset($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    // ✅ Điều này OK, nhưng cần kiểm tra validate password strength
}
```

**Cải thiện**:
- ✅ Validate password phải ≥ 8 ký tự
- ✅ Yêu cầu ít nhất 1 uppercase, 1 lowercase, 1 số, 1 ký tự đặc biệt

---

### 7. 📊 **Không log các hành động quan trọng (Audit log)**
**Vấn đề**: Không có tracking khi:
- Tạo/Sửa/Xóa tour
- Gán khách hàng
- Phân phòng
- Ghi chi phí

**Tác hại**: 
- Không thể kiểm tra ai đã làm gì khi
- Vi phạm compliance, audit requirements
- Khó debug nếu có vấn đề

**Giải pháp**:
- ✅ Tạo table `audit_logs` (id, user_id, action, table_name, record_id, changes, created_at)
- ✅ Log mỗi lần create/update/delete
- ✅ Lưu user_id, thời gian, và changes (before/after)

---

## 🟡 VẤN ĐỀ NHẸ (MEDIUM PRIORITY)

### 8. 🧹 **SQL injection risk - Validation input yếu**
**File**: `routes/index.php`  
**Vấn đề**: 
```php
$action = $_GET['action'] ?? '/';
$parts = explode('/', $action);
$controllerName = $parts[0] ?? 'dashboard';
$method = $parts[1] ?? 'index';
// Không whitelist controller & method!
```

**Tác hại**: 
- Có thể bypass controller whitelist
- Exploit unexpected methods

**Giải pháp**:
- ✅ Validate $controllerName & $method với whitelist
- ✅ Chỉ cho phép các controller/method đã đăng ký

---

### 9. ⏱️ **Session timeout không được set**
**File**: `index.php` - không có session timeout config  
**Vấn đề**: 
- Nếu user đăng nhập rồi bỏ, session vẫn active vĩnh viễn
- Security risk nếu dùng máy công cộng

**Giải pháp**:
- ✅ Set `session.gc_maxlifetime = 1800` (30 phút) trong php.ini hoặc code
- ✅ Implement "Remember me" feature đúng cách

---

### 10. 🚨 **Error messages quá chi tiết - lộ thông tin database**
**File**: `models/BaseModel.php`  
**Vấn đề**: 
```php
die("Kết nối cơ sở dữ liệu thất bại: {$e->getMessage()}");
```

**Tác hại**: 
- Exception message có thể lộ tên database, host, version
- Attacker biết dùng DB nào để targeted attack

**Giải pháp**:
- ✅ Log error details vào file, không hiển thị user
- ✅ Hiển thị message generic: "Có lỗi hệ thống, vui lòng thử lại sau"

---

### 11. 📱 **Validation không consistent - frontend & backend**
**Vấn đề**: 
- Một số field chỉ validate ở frontend (HTML5)
- Attacker có thể bypass bằng modify request

**Giải pháp**:
- ✅ LUÔN validate ở backend, frontend chỉ UX
- ✅ Kiểm tra: required, type, length, format (email, date, etc)
- ✅ Sanitize input trước khi vào database

---

### 12. 📦 **Dependency management không rõ ràng**
**Vấn đề**: 
- Không có `composer.json`
- Bootstrap, Bootstrap Icons đơn từ CDN
- Nếu CDN down, website break

**Giải pháp**:
- ✅ Sử dụng Composer cho dependencies
- ✅ Download thư viện locally hoặc dùng Composer
- ✅ Version control dependencies

---

## ✅ NHỮNG ĐIỂM TỐT

### Những gì làm đúng:
- ✅ Sử dụng PDO với prepared statements (ngăn SQL injection)
- ✅ Hash password bằng `password_hash()`
- ✅ Kiểm tra quyền (admin/guide) ở controller
- ✅ Validate required fields, date ranges
- ✅ Escape output bằng `htmlspecialchars()` ở các chỗ chính

---

## 📋 DANH SÁCH CẢI THIỆN TỰA PRIORITY

| # | Vấn đề | Độ ưu tiên | Độ khó | Thời gian |
|---|--------|-----------|--------|----------|
| 1 | Xóa credentials khỏi view | 🔴 Critical | ⭐ Dễ | 5 phút |
| 2 | Thêm CSRF token | 🔴 Critical | ⭐⭐ Trung bình | 30 phút |
| 3 | Validate upload MIME type | 🔴 Critical | ⭐⭐ Trung bình | 20 phút |
| 4 | Thêm audit log | 🟠 High | ⭐⭐⭐ Khó | 2 giờ |
| 5 | Escape XSS output | 🟠 High | ⭐⭐ Trung bình | 1 giờ |
| 6 | Session timeout | 🟠 High | ⭐ Dễ | 10 phút |
| 7 | Error handling an toàn | 🟠 High | ⭐⭐ Trung bình | 20 phút |
| 8 | Validate controller/method | 🟡 Medium | ⭐ Dễ | 10 phút |
| 9 | Password strength validation | 🟡 Medium | ⭐⭐ Trung bình | 15 phút |
| 10 | Protect env.php | 🟡 Medium | ⭐ Dễ | 5 phút |
| 11 | Backend form validation | 🟡 Medium | ⭐⭐ Trung bình | 1 giờ |
| 12 | Dependency management | 🟡 Medium | ⭐ Dễ | 15 phút |

---

## 🚀 BƯỚC TIẾP THEO

1. **Tuần 1** (Bảo mật cơ bản):
   - Fix CSRF token
   - Xóa credentials
   - Validate upload
   - Session timeout

2. **Tuần 2** (Bảo mật advanced):
   - Audit logging
   - XSS protection
   - Error handling

3. **Tuần 3** (Code quality):
   - Validation backend
   - Password strength
   - Dependency management

---

**Tài liệu này sẽ được cập nhật khi có fix mới.**
