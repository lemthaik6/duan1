# 📘 Hướng Dẫn Git Workflow - Quản Lý Code Team

## 🎯 Tổng Quan Chiến Lược

### Cấu Trúc Nhánh (Branch Structure)

```
main (Production)
  └─ develop (Integration/Staging)
      ├─ feature/lemthai (Member 1 - Chat features)
      ├─ feature/member2 (Member 2 - Booking features)
      ├─ feature/member3 (Member 3 - Report features)
      └─ feature/member4 (Member 4 - Other features)
```

### Quy Trình Làm Việc

```
1. Mỗi member làm việc trên nhánh feature riêng
2. Mỗi ngày push code lên nhánh feature của họ
3. Bạn (Lead) tổng hợp tất cả nhánh vào develop
4. Test develop thoroughly
5. Merge develop vào main khi stable
```

---

## 📋 Chi Tiết Các Nhánh

### 1️⃣ **main** - Production Branch
- 🔒 **Quy tắc**: Chỉ bạn merge vào
- 📌 **Mục đích**: Code sản phẩm hoàn thiện, ready to deploy
- ✅ **Điều kiện merge**: 
  - Code đã test đầy đủ
  - Không có bug
  - Tất cả features hoàn thiện

### 2️⃣ **develop** - Integration Branch  
- 🔄 **Quy tắc**: Bạn merge các feature vào đây
- 📌 **Mục đích**: Tổng hợp code từ tất cả member
- ✅ **Điều kiện merge từ feature vào develop**:
  - Code đã test cơ bản
  - Không conflict lớn
  - Có comment/documentation

### 3️⃣ **feature/[member-name]** - Member Branches
- 👥 **Quy tắc**: Mỗi member làm việc riêng
- 📌 **Mục đích**: Phát triển feature độc lập
- ✅ **Điều kiện push hàng ngày**:
  - Code compilable (không error)
  - Có message commit rõ ràng

---

## 🛠️ Hướng Dẫn Chi Tiết

### Bước 1️⃣: Tạo Nhánh develop (Nếu chưa có)

```powershell
# Đứng trên nhánh main
git checkout main

# Tạo nhánh develop từ main
git checkout -b develop

# Đẩy nhánh develop lên GitHub
git push -u origin develop

# Thiết lập develop là default branch (optional)
# Làm trên GitHub: Settings > Branches > Default branch > develop
```

---

### Bước 2️⃣: Yêu Cầu Member Push Code Hàng Ngày

**📧 Gửi cho mỗi member:**

```
Hôm nay và hàng ngày, vui lòng:
1. Thực hiện công việc trên nhánh feature/[tên-của-bạn]
2. Cuối ngày, thực hiện:
   git add .
   git commit -m "ngày 26/11: Thêm chức năng X, sửa bug Y"
   git push
3. Không cần merge hay pull request, chỉ push code
4. Thông báo cho tôi khi công việc hoàn thành
```

---

### Bước 3️⃣: Tổng Hợp Code Hàng Ngày (Bạn - Lead)

**Lệnh tổng hợp - Chạy vào cuối ngày:**

```powershell
# Vào thư mục project
cd c:\laragon\www\duan1

# 1. Cập nhật tất cả nhánh từ GitHub
git fetch origin

# 2. Chuyển sang nhánh develop
git checkout develop

# 3. Kéo code mới nhất từ GitHub
git pull origin develop

# 4. Merge feature/lemthai vào develop
git merge origin/feature/lemthai -m "Merge feature/lemthai - ngày 26/11"

# 5. Merge feature/member2 vào develop
git merge origin/feature/member2 -m "Merge feature/member2 - ngày 26/11"

# 6. Merge feature/member3 vào develop (nếu có)
git merge origin/feature/member3 -m "Merge feature/member3 - ngày 26/11"

# 7. Nếu có conflict, giải quyết thủ công rồi:
# git add .
# git commit -m "Resolve conflicts"

# 8. Đẩy develop lên GitHub (tổng hợp tất cả nhánh)
git push origin develop
```

**Output thành công:**
```
Merge made by the 'recursive' strategy.
 files changed, insertions(+), deletions(-)
```

---

### Bước 4️⃣: Xử Lý Conflict (Nếu Có)

Khi merge, nếu có conflict:

```
Auto-merging file.php
CONFLICT (content): Merge conflict in file.php
Automatic merge failed; fix conflicts and then commit the result.
```

**Cách giải quyết:**

```powershell
# 1. Mở file có conflict (VS Code sẽ đánh dấu)
# Chọn "Accept Current Change" hoặc "Accept Incoming Change" hoặc sửa thủ công

# 2. Sau khi sửa xong:
git add .
git commit -m "Resolve merge conflicts between features"
git push origin develop
```

**Ví dụ file conflict:**
```php
<<<<<<< HEAD (develop)
// Code từ develop hiện tại
function getUser() {
    return $this->user;
}
=======
// Code từ feature/member2
function getUser() {
    return $this->getUserData();
}
>>>>>>> feature/member2

// => Bạn chọn code nào hoặc combine cả hai
```

---

### Bước 5️⃣: Test Trước Khi Đẩy Main

Sau khi tổng hợp tất cả code vào develop:

```powershell
# 1. Đứng trên develop
git checkout develop

# 2. Kiểm tra status
git status

# 3. Chạy test (nếu có)
php vendor/bin/phpunit

# 4. Kiểm tra browser
# - Đăng nhập
# - Test các feature chính
# - Kiểm tra không có error
```

---

### Bước 6️⃣: Merge Develop Vào Main (Release)

**Khi code đã test xong và ready:**

```powershell
# 1. Chuyển sang main
git checkout main

# 2. Kéo code mới nhất
git pull origin main

# 3. Merge develop vào main
git merge develop -m "Release v1.0 - ngày 26/11"

# 4. Đẩy main lên GitHub
git push origin main

# 5. Tạo tag (version) - Optional nhưng tốt
git tag -a v1.0 -m "Release version 1.0"
git push origin v1.0
```

---

## 📊 So Sánh Nhánh

### Xem Diff Giữa Các Nhánh

```powershell
# Xem thay đổi của feature/lemthai so với develop
git diff develop feature/lemthai

# Xem thay đổi của develop so với main
git diff main develop

# Xem file nào thay đổi
git diff --name-only main develop

# Xem chi tiết từng file
git diff develop feature/lemthai -- file.php
```

---

## 🔄 Workflow Thực Tế Hàng Ngày

### Sáng (9:00 AM)
```powershell
# Member: Push code từ hôm qua
git push

# Bạn: Cập nhật tất cả nhánh
git fetch origin
git log --oneline --all --graph  # Xem toàn bộ branches
```

### Chiều (5:30 PM - Cuối Ngày)
```powershell
# Member: Commit công việc hôm nay
git add .
git commit -m "ngày 26/11: Hoàn thành feature X"
git push

# Bạn: Tổng hợp
git fetch origin
git checkout develop
git pull origin develop
git merge origin/feature/lemthai
git merge origin/feature/member2
git merge origin/feature/member3
git push origin develop
```

### Thứ Sáu Chiều (Release Day)
```powershell
# Bạn: Test develop
# ... Test hết sức trong 1-2 tiếng ...

# Nếu ổn:
git checkout main
git merge develop
git push origin main
git tag -a v1.0.1 -m "Weekly release"
git push origin v1.0.1

# Thông báo cho team: "Release thành công!"
```

---

## 📈 Lệnh Hữu Ích

### 1. Xem Tất Cả Nhánh Và Commit

```powershell
# Xem tất cả nhánh (local + remote)
git branch -a

# Xem graph của tất cả branches
git log --oneline --all --graph --decorate

# Xem nhánh nào chưa merge vào develop
git branch -a --no-merged develop

# Xem nhánh nào đã merge vào develop
git branch -a --merged develop
```

### 2. Cleanup Nhánh Cũ

```powershell
# Xóa nhánh local đã merge
git branch -d feature/old-feature

# Xóa nhánh remote (cẩn thận!)
git push origin --delete feature/old-feature

# Dọn dẹp branches đã xóa remote
git fetch --prune
```

### 3. Rollback Nếu Merge Sai

```powershell
# Undo merge gần nhất
git reset --hard HEAD~1

# Hoặc revert merge cụ thể
git revert -m 1 <commit-hash>

# Push lại
git push origin develop
```

---

## ⚠️ Quy Tắc & Best Practices

### ✅ PHẢI LÀM:
- ✅ Commit message rõ ràng, mô tả công việc
- ✅ Push code hàng ngày
- ✅ Thông báo cho lead khi push
- ✅ Test code trước khi push
- ✅ Comment code khó hiểu
- ✅ Kéo develop mới nhất trước khi bắt đầu feature mới

### ❌ KHÔNG LÀM:
- ❌ Push trực tiếp vào main
- ❌ Merge không tối ưu (rebase khi cần)
- ❌ Commit với message như "fix", "update", "asdf"
- ❌ Push code chưa test
- ❌ Xóa nhánh feature của người khác
- ❌ Force push (-f) nếu không chắc

---

## 🎓 Commit Message Convention

**Format:**
```
[Loại công việc] [Ngắn gọn mô tả]

[Chi tiết (optional)]

Ví dụ:
feat: Thêm chức năng chat groups
fix: Sửa bug upload avatar
docs: Cập nhật README
refactor: Tối ưu code ChatController
test: Thêm unit test cho ChatModel
```

**Ví dụ thực tế:**
```powershell
git commit -m "feat: Thêm feature tạo group chat mới

- Tạo ChatGroupModel.php
- Thêm trang create-group.php
- Thêm API endpoint /chat/group/create
- Test trên Chrome & Firefox"
```

---

## 📞 Quy Trình Hỗ Trợ Lỗi

### Nếu Member Muốn Pull Code Mới Nhất Từ Develop

```powershell
# Member làm việc trên feature/member2
git fetch origin
git rebase origin/develop

# Hoặc merge (nếu thích)
git merge origin/develop

# Giải quyết conflict (nếu có)
git add .
git rebase --continue

# Push lại
git push origin feature/member2
```

---

## 📊 Giám Sát Hàng Ngày

**Tạo file checklist:**

```
📋 GIT DAILY CHECKLIST (26/11/2025)

Member Updates:
☐ lemthai - Last push: today
☐ member2 - Last push: today  
☐ member3 - Last push: today

Develop Status:
☐ All features merged into develop
☐ No conflicts
☐ Code tested
☐ Ready to merge to main: YES/NO

Production (main):
☐ Last release: v1.0 (25/11)
☐ Next release: planned for Friday
```

---

## 🚀 Quy Trình Release (Hàng Tuần)

### **Thứ Năm Chiều (Release Day)**

```powershell
# 1. Tổng hợp lần cuối
git checkout develop
git fetch origin
git pull origin develop

# Merge tất cả feature nếu chưa merge
git merge origin/feature/lemthai
git merge origin/feature/member2
# ... merge các feature khác ...

# 2. Test 2 tiếng
# ... Testing QA ...

# 3. Nếu pass:
git checkout main
git pull origin main
git merge develop

# 4. Push & Tag
git push origin main
git tag -a v1.1 -m "Release week 26/11"
git push origin v1.1

# 5. Thông báo
echo "✅ Release v1.1 thành công! Deploy to production."
```

### **Thứ Sáu Sáng (Ngay sau release)**

```powershell
# Reset develop từ main mới
git checkout develop
git reset --hard origin/main
git push origin develop --force-with-lease

# Thông báo member kéo develop mới
echo "⚠️ develop đã reset. Mọi người pull develop mới nhất"
```

---

## 📝 Hạn Chế Cấu Hình (GitHub Settings)

**Để bảo vệ main branch:**

1. Vào **GitHub > Settings > Branches**
2. Click **Add rule** cho main branch
3. Kích chọn:
   - ✅ Require pull request reviews before merging
   - ✅ Require status checks to pass
   - ✅ Require branches to be up to date
   - ✅ Dismiss stale pull request approvals
   - ✅ Restrict who can push to matching branches (chỉ bạn)

**Hiệu quả:**
- Chỉ bạn push vào main
- Không ai merge trực tiếp vào main
- Phải đi qua Pull Request (PR)

---

## 🎯 Tóm Tắt Workflow

```
┌─────────────────────────────────────────────────────┐
│ WORKFLOW HÀNG NGÀY                                  │
├─────────────────────────────────────────────────────┤
│                                                     │
│ Member A:      Member B:      Member C:            │
│ feature/a      feature/b      feature/c            │
│   ↓              ↓              ↓                    │
│   └──────────────┼──────────────┘                    │
│                  ↓                                   │
│            develop (Lead merge)                     │
│                  ↓                                   │
│           Test & Validate                          │
│                  ↓                                   │
│         (Thứ 5 chiều)                              │
│                  ↓                                   │
│              main (Production)                      │
│                  ↓                                   │
│            Deploy to Server                         │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

**✅ Vậy là bạn có workflow chuyên nghiệp như team lớn!**

Bắt đầu hôm nay với lệnh:
```powershell
# Tạo nhánh develop (nếu chưa có)
git checkout main
git checkout -b develop
git push -u origin develop
```

Rồi báo với team để push code lên nhánh feature của họ! 🚀
