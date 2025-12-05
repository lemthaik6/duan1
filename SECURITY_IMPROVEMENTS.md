# 🔒 HƯỚNG DẪN IMPLEMENT SECURITY FIXES

**Ngày**: 5 tháng 12, 2025  
**Status**: ✅ 8/8 vấn đề critical/high được sửa

---

## ✅ NHỮNG GÌ ĐÃ ĐƯỢC SỬA

### 1. ✅ Xóa Credentials khỏi Login View
**File**: `views/auth/login.php`
```php
// ❌ ĐÃ XÓA:
<div class="login-credentials mt-4">
    <strong>Admin:</strong> admin / admin123<br>
    <strong>HDV:</strong> guide1 / guide123
</div>
```
**Tác dụng**: Không lộ thông tin đăng nhập cho bất kỳ ai

---

### 2. ✅ CSRF Token Protection
**File**: `configs/helper.php` - 4 hàm mới

#### a) Tạo CSRF Token:
```php
<?= csrfTokenField() ?>
```

#### b) Validate trong Controller:
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'CSRF token không hợp lệ';
        // Redirect back
        exit;
    }
    // Process form
}
```

#### Ví dụ đầy đủ:
```php
<!-- Form HTML -->
<form method="POST" action="">
    <?= csrfTokenField() ?>
    <input type="text" name="username" required>
    <button type="submit">Submit</button>
</form>
```

```php
// Controller
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'CSRF token không hợp lệ';
        header('Location: ' . BASE_URL . '?action=...&error=csrf');
        exit;
    }
    
    // Safe to process form
    $data = $_POST['data'];
}
```

**Cần áp dụng vào tất cả forms trong các view**.

---

### 3. ✅ File Upload Validation
**File**: `configs/helper.php` - hàm `upload_file()` được cập nhật

#### Cách sử dụng:
```php
try {
    $uploadedFile = upload_file('tours', $_FILES['image']);
    // Upload thành công
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
```

#### Tính năng:
- ✅ Whitelist MIME types (JPG, PNG, PDF)
- ✅ Check file size (max 10MB)
- ✅ Validate MIME type bằng `finfo_file()`
- ✅ Generate UUID filename thay vì giữ extension gốc
- ✅ Throw exception nếu lỗi

#### Allowed MIME types (mặc định):
```
- image/jpeg (JPG)
- image/png (PNG)
- application/pdf (PDF)
```

**Custom MIME types:**
```php
$types = ['image/gif', 'image/webp'];
$file = upload_file('folder', $_FILES['file'], $types);
```

---

### 4. ✅ Session Timeout
**File**: `index.php` - Session config

#### Tính năng:
- ⏱️ Session timeout: **30 phút** inactivity
- 🔄 Session ID regeneration: **mỗi 5 phút**
- 🚪 Auto logout nếu timeout
- 📊 Track last activity time

#### Cách hoạt động:
```
1. User login → Session tạo
2. Sau 5 phút → Session ID regenerate
3. Sau 30 phút inactivity → Session destroy + redirect to login
4. Session recreated nếu có new request
```

---

### 5. ✅ Safe Error Handling
**File**: `models/BaseModel.php` - Exception handling

#### Trước (Unsafe):
```php
❌ die("Kết nối DB thất bại: {$e->getMessage()}");
// Lộ: host, database name, user, version
```

#### Sau (Safe):
```php
✅ error_log('Database connection failed: ' . $e->getMessage());
die('❌ Có lỗi hệ thống. Vui lòng thử lại sau hoặc liên hệ quản trị viên.');
// User không thấy chi tiết, error log tại server
```

**Error logs nằm tại**:
- Linux/Mac: `/var/log/php-errors.log` hoặc do PHP config
- Windows: `C:\xampp\php\logs\php_errors.log` (nếu dùng XAMPP)

---

### 6. ✅ XSS Protection - Helper `e()`
**File**: `configs/helper.php` - hàm `e()`

#### Cách sử dụng:
```php
<!-- ❌ Unsafe -->
<h1><?= $user['name'] ?></h1>

<!-- ✅ Safe -->
<h1><?= e($user['name']) ?></h1>
```

#### Ví dụ:
```php
<?php
// Input: <script>alert('XSS')</script>

// ❌ Echo trực tiếp:
<?= $_POST['name'] ?>
// Output: <script>alert('XSS')</script> ← Script execute!

// ✅ Dùng e():
<?= e($_POST['name']) ?>
// Output: &lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt; ← Safe!
?>
```

**Tip**: Thay tất cả `htmlspecialchars()` bằng `e()` để code ngắn gọn hơn.

---

### 7. ✅ Controller/Method Validation
**File**: `routes/index.php` - Route validation

#### Tính năng:
- ✅ Validate method name (alphanumeric + underscore)
- ✅ Prevent code injection qua URL
- ✅ Generic error messages (không lộ chi tiết)
- ✅ Log errors để debug

#### Ví dụ:
```
✅ ?action=tours/index → Hợp lệ
✅ ?action=tours/create → Hợp lệ
❌ ?action=tours/create';DROP TABLE tours;-- → Blocked!
❌ ?action=tours/create|system('rm -rf /'); → Blocked!
```

---

### 8. ✅ Password Strength Validation
**File**: `configs/helper.php` - Password validation functions

#### Requirements:
- ✓ Minimum 8 characters
- ✓ At least 1 uppercase (A-Z)
- ✓ At least 1 lowercase (a-z)
- ✓ At least 1 number (0-9)
- ✓ At least 1 special character (!@#$%^&*...)

#### Cách sử dụng:

**Option 1: Lấy errors (hiển thị chi tiết)**
```php
$errors = validatePasswordStrength($_POST['password']);
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}
```

**Option 2: Check boolean (đơn giản)**
```php
if (isPasswordStrong($_POST['password'])) {
    // Password OK
} else {
    $_SESSION['error'] = 'Mật khẩu không đủ mạnh';
}
```

#### Ví dụ:
```php
<?php
// ❌ Weak passwords:
validatePasswordStrength('123456');
// Return: ['Mật khẩu phải có ít nhất 8 ký tự', 'phải chứa chữ hoa', ...]

// ✅ Strong password:
isPasswordStrong('MyPass123!');
// Return: true
?>
```

#### Nơi áp dụng:
- `controllers/AuthController.php` - Tạo user, change password
- `controllers/ProfileController.php` - Change password

---

## 📋 IMPLEMENT CHECKLIST

### Phase 1: Immediate (Done ✅)
- [x] Remove credentials from login view
- [x] Add CSRF token functions
- [x] Update upload validation
- [x] Session timeout config
- [x] Safe error handling
- [x] Add e() escape function
- [x] Validate routes
- [x] Password strength validation

### Phase 2: Next (TODO)
- [ ] **Add CSRF token to ALL forms** in views:
  - [ ] `views/auth/login.php` (auth form)
  - [ ] `views/tours/create.php`, `edit.php`
  - [ ] `views/customers/create.php`, `edit.php`
  - [ ] `views/vehicles/create.php`, `edit.php`
  - [ ] ALL other POST forms

- [ ] **Validate CSRF token in ALL controllers** before processing POST:
  - [ ] `AuthController::login()`
  - [ ] `TourController::create()`, `update()`, `delete()`
  - [ ] ALL POST methods

- [ ] **Update AuthController** to use password strength validation:
  - [ ] Check password strength khi create user
  - [ ] Check password strength khi change password
  - [ ] Display password requirements to user

- [ ] **Create .env file** (xóa credentials từ env.php):
  - [ ] Tạo `.env` file
  - [ ] Thêm `.env` vào `.gitignore`
  - [ ] Load `.env` trong `index.php`

### Phase 3: Advanced (Optional)
- [ ] Create audit logging system
- [ ] Add rate limiting cho login
- [ ] Two-factor authentication (2FA)
- [ ] API rate limiting
- [ ] Security headers (CSP, X-Frame-Options, etc.)

---

## 🔧 QUICK START GUIDE

### 1. Thêm CSRF token vào form
```php
<form method="POST" action="">
    <?= csrfTokenField() ?>
    <!-- form fields -->
</form>
```

### 2. Validate CSRF token trong controller
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'CSRF token không hợp lệ';
        // Redirect
        exit;
    }
    // Process...
}
```

### 3. Escape output
```php
<!-- ❌ Trước -->
<?= $user['name'] ?>

<!-- ✅ Sau -->
<?= e($user['name']) ?>
```

### 4. Upload file
```php
try {
    $file = upload_file('tours', $_FILES['image']);
    // Use $file
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
```

### 5. Validate password
```php
$errors = validatePasswordStrength($_POST['password']);
if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
}
```

---

## 🚀 TESTING

### Test CSRF Protection:
```bash
# Simulate request without CSRF token
curl -X POST http://localhost/duan1/?action=tours/create \
  -d "name=Test&category_id=1" \
  # Result: Should get 'CSRF token không hợp lệ'
```

### Test Session Timeout:
```
1. Login
2. Không hoạt động 30 phút
3. Click button bất kỳ
4. Should redirect to login
```

### Test File Upload:
```php
// Test with invalid MIME type
$file = ['name' => 'shell.php', 'tmp_name' => '/tmp/..', 'size' => 1000];
upload_file('tours', $file);
// Result: Exception - Định dạng file không được hỗ trợ
```

### Test Password Validation:
```php
validatePasswordStrength('weak');
// Result: ['Mật khẩu phải có ít nhất 8 ký tự', '...']

isPasswordStrong('Strong123!');
// Result: true
```

---

## 📚 TÀI LIỆU THAM KHẢO

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [OWASP CSRF Prevention](https://owasp.org/www-community/attacks/csrf)
- [PHP Security](https://www.php.net/manual/en/security.php)
- [Password Security](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

---

**Ghi chú**: Document này sẽ được cập nhật khi có cải thiện mới.
