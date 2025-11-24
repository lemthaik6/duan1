# 📘 Hướng Dẫn Sử Dụng UI Components

Hướng dẫn chi tiết để sử dụng các component được tối ưu hóa trong dự án.

## 🎨 Stat Cards (Thẻ Thống Kê)

### Cơ Bản
```html
<div class="card stat-card bg-primary">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <p class="stat-label">Tên thống kê</p>
                <h3 class="stat-value">123</h3>
            </div>
            <i class="bi bi-icon-name stat-icon"></i>
        </div>
    </div>
</div>
```

### Các Kiểu Màu
```html
<!-- Primary (Xanh) -->
<div class="card stat-card bg-primary">...</div>

<!-- Success (Xanh lá) -->
<div class="card stat-card bg-success">...</div>

<!-- Warning (Cam) -->
<div class="card stat-card bg-warning">...</div>

<!-- Danger (Đỏ) -->
<div class="card stat-card bg-danger">...</div>

<!-- Info (Xanh lam) -->
<div class="card stat-card bg-info">...</div>
```

### Responsive Layout
```html
<!-- 4 columns trên desktop -->
<div class="row g-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-primary">...</div>
    </div>
</div>

<!-- 3 columns trên tablet -->
<!-- 1 column trên mobile (Bootstrap responsive) -->
```

---

## 🔘 Buttons (Nút Bấm)

### Các Loại Button
```html
<!-- Primary -->
<button class="btn btn-primary">
    <i class="bi bi-icon"></i> Primary Button
</button>

<!-- Secondary -->
<button class="btn btn-secondary">
    <i class="bi bi-icon"></i> Secondary Button
</button>

<!-- Success -->
<button class="btn btn-success">
    <i class="bi bi-check"></i> Success Button
</button>

<!-- Danger -->
<button class="btn btn-danger">
    <i class="bi bi-trash"></i> Delete Button
</button>

<!-- Warning -->
<button class="btn btn-warning">
    <i class="bi bi-exclamation"></i> Warning Button
</button>

<!-- Info -->
<button class="btn btn-info">
    <i class="bi bi-info-circle"></i> Info Button
</button>

<!-- Outline -->
<button class="btn btn-outline-primary">Outline Button</button>
```

### Button Sizes
```html
<!-- Small -->
<button class="btn btn-sm btn-primary">Small Button</button>

<!-- Default (không cần class thêm) -->
<button class="btn btn-primary">Default Button</button>

<!-- Large -->
<button class="btn btn-lg btn-primary">Large Button</button>
```

### Button Groups
```html
<div class="d-grid gap-2">
    <a href="#" class="btn btn-primary">
        <i class="bi bi-plus"></i> Thêm mới
    </a>
    <a href="#" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>
```

---

## 🏷️ Badges (Nhãn)

### Các Kiểu Badge
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-secondary">Secondary</span>
<span class="badge badge-light">Light</span>
```

### Sử Dụng trong Table
```html
<table class="table">
    <tbody>
        <tr>
            <td>
                <span class="badge badge-success">Hoạt động</span>
            </td>
        </tr>
    </tbody>
</table>
```

### Status Badges
```html
<!-- Trạng thái Tour -->
<span class="badge badge-info">Sắp diễn ra</span>
<span class="badge badge-warning">Đang diễn ra</span>
<span class="badge badge-success">Đã hoàn thành</span>
<span class="badge badge-danger">Đã hủy</span>
```

---

## 📋 Tables (Bảng)

### Cơ Bản
```html
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Cột 1</th>
                <th>Cột 2</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Dữ liệu 1</td>
                <td>Dữ liệu 2</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### Table Features
- Hover effects tự động
- Responsive trên mobile
- Smooth animations
- Professional styling

---

## 📦 Cards (Thẻ)

### Card Cơ Bản
```html
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-icon"></i> Tiêu đề
        </h5>
    </div>
    <div class="card-body">
        <!-- Nội dung -->
    </div>
</div>
```

### Card với Hover Effects
```html
<!-- Cards tự động có hover effects -->
<div class="card">
    <!-- Khi hover: shadow tăng & transform translateY(-4px) -->
</div>
```

### Card Variations
```html
<!-- Card thông thường -->
<div class="card">...</div>

<!-- Card với list group -->
<div class="card">
    <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action">
            Item 1
        </a>
    </div>
</div>
```

---

## ⚠️ Alerts (Cảnh báo)

### Các Loại Alert
```html
<!-- Success -->
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle"></i> Success message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Danger -->
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle"></i> Error message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Warning -->
<div class="alert alert-warning alert-dismissible fade show">
    <i class="bi bi-exclamation-diamond"></i> Warning message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Info -->
<div class="alert alert-info alert-dismissible fade show">
    <i class="bi bi-info-circle"></i> Info message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

### Features
- Auto-dismiss sau 5 giây
- Slide-down animation
- Color-coded
- Dismissible buttons

---

## 📝 Forms (Biểu mẫu)

### Form Basic
```html
<form method="POST" action="">
    <div class="mb-3">
        <label for="name" class="form-label">Tên</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="mb-3">
        <label for="message" class="form-label">Thông điệp</label>
        <textarea class="form-control" id="message" name="message" rows="4"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Gửi</button>
</form>
```

### Form with Icons
```html
<div class="input-group mb-3">
    <span class="input-group-text">
        <i class="bi bi-person-fill"></i>
    </span>
    <input type="text" class="form-control" placeholder="Username">
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <i class="bi bi-lock-fill"></i>
    </span>
    <input type="password" class="form-control" placeholder="Password">
</div>
```

### Form Validation
```html
<!-- Invalid state -->
<input class="form-control is-invalid" type="text">

<!-- Valid state -->
<input class="form-control is-valid" type="text">
```

---

## 🎯 Layout Patterns

### Header with Action
```html
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-icon"></i> Tiêu đề
    </h2>
    <a href="#" class="btn btn-primary">
        <i class="bi bi-plus"></i> Thêm mới
    </a>
</div>
```

### Two Column Layout
```html
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Main content -->
    </div>
    <div class="col-lg-4">
        <!-- Sidebar -->
    </div>
</div>
```

### Grid Layout
```html
<div class="row g-4">
    <div class="col-md-3 col-sm-6">
        <!-- Column 1 -->
    </div>
    <div class="col-md-3 col-sm-6">
        <!-- Column 2 -->
    </div>
    <div class="col-md-3 col-sm-6">
        <!-- Column 3 -->
    </div>
    <div class="col-md-3 col-sm-6">
        <!-- Column 4 -->
    </div>
</div>
```

---

## 🎨 Utility Classes

### Spacing
```html
<!-- Margin bottom -->
<div class="mb-4">...</div>  <!-- 1.5rem -->

<!-- Padding -->
<div class="p-3">...</div>   <!-- 1rem -->

<!-- Gap between items -->
<div class="d-flex gap-2">...</div>
```

### Display
```html
<!-- Flexbox -->
<div class="d-flex justify-content-between align-items-center">...</div>

<!-- Grid -->
<div class="d-grid gap-2">...</div>

<!-- Text alignment -->
<div class="text-center">...</div>
<div class="text-end">...</div>
```

### Text
```html
<!-- Bold -->
<strong class="fw-bold">Text</strong>

<!-- Muted -->
<span class="text-muted">Text</span>

<!-- Colors -->
<span class="text-primary">Text</span>
<span class="text-success">Text</span>
<span class="text-danger">Text</span>
```

---

## 💻 CSS Variables

### Available Variables
```css
--primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
--success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
--danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
--warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
--info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);

--primary-color: #667eea;
--secondary-color: #764ba2;
--success-color: #56ab2f;
--danger-color: #f5576c;

--border-radius: 10px;
--box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
--transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
```

### Usage
```css
.my-element {
    background: var(--primary-gradient);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    transition: var(--transition);
}
```

---

## 🚀 Best Practices

✅ **Do's:**
- Sử dụng semantic HTML
- Thêm icons cho buttons
- Sử dụng responsive classes
- Theo responsive grid system

❌ **Don'ts:**
- Không override CSS variables tùy tiện
- Không mix inline styles với classes
- Không sử dụng deprecated Bootstrap classes
- Không bỏ qua accessibility

---

**Version**: 1.0
**Last Updated**: November 24, 2025
