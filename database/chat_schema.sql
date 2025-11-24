-- Bảng chat_groups: Quản lý các nhóm chat
CREATE TABLE IF NOT EXISTS `chat_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Tên nhóm chat',
  `type` enum('tour','department','general') NOT NULL DEFAULT 'general' COMMENT 'Loại nhóm: tour, phòng ban, chung',
  `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour (nếu type = tour)',
  `department` varchar(100) DEFAULT NULL COMMENT 'Tên phòng ban (nếu type = department)',
  `description` text DEFAULT NULL COMMENT 'Mô tả nhóm',
  `created_by` int(11) NOT NULL COMMENT 'Người tạo nhóm',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','archived') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `idx_tour_id` (`tour_id`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_type` (`type`),
  CONSTRAINT `fk_chat_groups_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_groups_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng chat_group_members: Thành viên trong các nhóm chat
CREATE TABLE IF NOT EXISTS `chat_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL COMMENT 'ID nhóm chat',
  `user_id` int(11) NOT NULL COMMENT 'ID người dùng',
  `role` enum('admin','member') NOT NULL DEFAULT 'member' COMMENT 'Vai trò trong nhóm',
  `joined_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tham gia',
  `last_read_at` datetime DEFAULT NULL COMMENT 'Thời gian đọc tin nhắn cuối',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_group_user` (`group_id`, `user_id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_chat_group_members_group` FOREIGN KEY (`group_id`) REFERENCES `chat_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_group_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng chat_messages: Tin nhắn trong các nhóm
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL COMMENT 'ID nhóm chat',
  `user_id` int(11) NOT NULL COMMENT 'ID người gửi',
  `message` text NOT NULL COMMENT 'Nội dung tin nhắn',
  `message_type` enum('text','file','image','system') NOT NULL DEFAULT 'text' COMMENT 'Loại tin nhắn',
  `file_path` varchar(500) DEFAULT NULL COMMENT 'Đường dẫn file (nếu có)',
  `file_name` varchar(255) DEFAULT NULL COMMENT 'Tên file gốc',
  `file_size` int(11) DEFAULT NULL COMMENT 'Kích thước file (bytes)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL COMMENT 'Thời gian xóa (soft delete)',
  PRIMARY KEY (`id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_chat_messages_group` FOREIGN KEY (`group_id`) REFERENCES `chat_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng chat_files: Quản lý file đã upload trong chat (optional, có thể dùng file_path trong chat_messages)
-- Tạo index để tối ưu truy vấn
CREATE INDEX `idx_chat_messages_group_created` ON `chat_messages` (`group_id`, `created_at` DESC);
CREATE INDEX `idx_chat_group_members_group` ON `chat_group_members` (`group_id`);

