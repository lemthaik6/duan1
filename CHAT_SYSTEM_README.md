# 💬 Hệ thống Chat nội bộ

## 📋 Tổng quan

Hệ thống chat nội bộ cho phép nhân viên, HDV và điều hành trao đổi nhanh chóng, chia sẻ file, hình ảnh, cập nhật sự cố và thông báo thay đổi trong quá trình vận hành tour.

## ✨ Tính năng

1. **Tạo nhóm chat**
   - Nhóm theo tour: Tự động thêm HDV và điều hành vào nhóm
   - Nhóm theo phòng ban: Tạo nhóm cho từng phòng ban
   - Nhóm chung: Tạo nhóm tùy chỉnh với các thành viên được chọn

2. **Gửi tin nhắn**
   - Gửi tin nhắn văn bản
   - Chia sẻ hình ảnh (JPEG, PNG, GIF, WebP)
   - Chia sẻ file (PDF, DOC, DOCX)
   - Giới hạn file: 10MB

3. **Real-time chat**
   - Tự động cập nhật tin nhắn mới (polling mỗi 3 giây)
   - Hiển thị số tin nhắn chưa đọc
   - Lưu trữ lịch sử chat để tra cứu

4. **Quản lý nhóm**
   - Xem danh sách nhóm chat
   - Xem thông tin nhóm và thành viên
   - Quyền truy cập: Chỉ thành viên mới có thể xem và gửi tin nhắn

## 🗄️ Cấu trúc Database

Hệ thống sử dụng 3 bảng chính:

1. **chat_groups**: Quản lý các nhóm chat
   - `id`: ID nhóm
   - `name`: Tên nhóm
   - `type`: Loại nhóm (tour, department, general)
   - `tour_id`: ID tour (nếu type = tour)
   - `department`: Tên phòng ban (nếu type = department)
   - `created_by`: Người tạo nhóm
   - `status`: Trạng thái (active, archived)

2. **chat_group_members**: Thành viên trong nhóm
   - `group_id`: ID nhóm
   - `user_id`: ID người dùng
   - `role`: Vai trò (admin, member)
   - `last_read_at`: Thời gian đọc tin nhắn cuối

3. **chat_messages**: Tin nhắn
   - `id`: ID tin nhắn
   - `group_id`: ID nhóm
   - `user_id`: ID người gửi
   - `message`: Nội dung tin nhắn
   - `message_type`: Loại (text, image, file, system)
   - `file_path`: Đường dẫn file (nếu có)
   - `file_name`: Tên file gốc
   - `file_size`: Kích thước file
   - `created_at`: Thời gian tạo
   - `deleted_at`: Thời gian xóa (soft delete)

## 🚀 Cài đặt

### Bước 1: Import Database Schema

Chạy file SQL để tạo các bảng:

```sql
-- Chạy file: database/chat_schema.sql
```

Hoặc import trực tiếp vào phpMyAdmin.

### Bước 2: Tạo thư mục upload

Đảm bảo thư mục `assets/uploads/chat/` có quyền ghi:

```bash
mkdir -p assets/uploads/chat
chmod 777 assets/uploads/chat
```

### Bước 3: Kiểm tra cấu hình

Đảm bảo các constant trong `configs/env.php` đã được cấu hình đúng:
- `BASE_URL`
- `PATH_ASSETS_UPLOADS`
- `BASE_ASSETS_UPLOADS`

## 📁 Cấu trúc Files

```
duan1/
├── controllers/
│   └── ChatController.php          # Controller xử lý chat
├── models/
│   ├── ChatGroupModel.php          # Model quản lý nhóm chat
│   └── ChatMessageModel.php        # Model quản lý tin nhắn
├── views/
│   └── chat/
│       ├── index.php               # Danh sách nhóm chat
│       ├── view.php                # Giao diện chat
│       └── create-group.php        # Form tạo nhóm mới
├── database/
│   └── chat_schema.sql             # SQL schema
└── routes/
    └── index.php                   # Đã thêm route 'chat'
```

## 🎯 Sử dụng

### Tạo nhóm chat mới

1. Vào menu **Chat nội bộ**
2. Click **Tạo nhóm chat mới**
3. Chọn loại nhóm:
   - **Nhóm theo tour**: Chọn tour, HDV và điều hành sẽ tự động được thêm
   - **Nhóm theo phòng ban**: Nhập tên phòng ban
   - **Nhóm chung**: Tạo nhóm tùy chỉnh
4. Chọn thành viên (tùy chọn)
5. Click **Tạo nhóm**

### Gửi tin nhắn

1. Chọn nhóm chat từ danh sách
2. Nhập tin nhắn vào ô input
3. Click **Gửi** hoặc nhấn Enter

### Chia sẻ file/hình ảnh

1. Trong giao diện chat, click icon **📎** (paperclip)
2. Chọn file hoặc hình ảnh
3. File sẽ tự động được upload và gửi

### Xem tin nhắn chưa đọc

Số tin nhắn chưa đọc được hiển thị bằng badge đỏ trên thẻ nhóm chat.

## 🔧 API Endpoints

### GET `/chat/index`
Danh sách nhóm chat của user hiện tại.

### GET `/chat/view?group_id={id}`
Xem chi tiết nhóm chat và tin nhắn.

### GET `/chat/create-group`
Form tạo nhóm chat mới.

### POST `/chat/create-group`
Tạo nhóm chat mới.

### POST `/chat/send-message`
Gửi tin nhắn văn bản (AJAX).

**Parameters:**
- `group_id`: ID nhóm
- `message`: Nội dung tin nhắn

### POST `/chat/upload-file`
Upload file/hình ảnh (AJAX).

**Parameters:**
- `group_id`: ID nhóm
- `file`: File upload

### GET `/chat/get-messages?group_id={id}&after_time={datetime}`
Lấy tin nhắn mới sau một thời điểm (AJAX - cho polling).

## 🔐 Bảo mật

- Chỉ thành viên nhóm mới có thể xem và gửi tin nhắn
- File upload được giới hạn 10MB
- Chỉ cho phép các loại file: hình ảnh (JPEG, PNG, GIF, WebP), PDF, DOC, DOCX
- Tin nhắn sử dụng soft delete
- XSS protection với `htmlspecialchars()`

## 📱 Responsive

Giao diện chat được thiết kế responsive, hoạt động tốt trên:
- Desktop
- Tablet
- Mobile

## 🔄 Real-time Updates

Hệ thống sử dụng **polling** để cập nhật tin nhắn mới:
- Tự động kiểm tra tin nhắn mới mỗi 3 giây
- Cập nhật thời gian đọc cuối khi user vào nhóm
- Hiển thị số tin nhắn chưa đọc

## 📝 Ghi chú

- Lịch sử chat được lưu trữ vĩnh viễn (trừ khi bị xóa)
- File được lưu trong `assets/uploads/chat/{group_id}/`
- Tên file được đổi thành: `{timestamp}_{original_name}` để tránh trùng lặp
- Admin có thể xóa tin nhắn của bất kỳ ai
- User chỉ có thể xóa tin nhắn của chính mình

## 🐛 Troubleshooting

### Lỗi upload file
- Kiểm tra quyền ghi của thư mục `assets/uploads/chat/`
- Kiểm tra kích thước file (tối đa 10MB)
- Kiểm tra loại file có được phép không

### Tin nhắn không hiển thị
- Kiểm tra console browser để xem lỗi JavaScript
- Kiểm tra network tab để xem request có thành công không
- Đảm bảo user là thành viên của nhóm

### Không tạo được nhóm
- Kiểm tra database connection
- Kiểm tra các bảng đã được tạo chưa
- Kiểm tra log lỗi PHP

---

**Chúc bạn sử dụng hệ thống chat thành công! 🎉**

