<?php

class ChatController
{
    private $chatGroupModel;
    private $chatMessageModel;
    private $tourModel;
    private $userModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->chatGroupModel = new ChatGroupModel();
        $this->chatMessageModel = new ChatMessageModel();
        $this->tourModel = new TourModel();
        $this->userModel = new UserModel();
        $this->assignmentModel = new TourAssignmentModel();
    }
    public function index()
    {
        requireLogin();

        $user = getCurrentUser();
        $groups = $this->chatGroupModel->getGroupsByUser($user['id']);
        foreach ($groups as &$group) {
            $group['unread_count'] = $this->chatGroupModel->countUnreadMessages($group['id'], $user['id']);
        }

        $title = 'Chat nội bộ';
        $view = 'chat/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết nhóm chat
     */
    public function view()
    {
        requireLogin();

        $user = getCurrentUser();
        $groupId = $_GET['group_id'] ?? 0;

        if (!$groupId) {
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        $group = $this->chatGroupModel->getById($groupId, $user['id']);

        if (!$group) {
            $_SESSION['error'] = 'Nhóm chat không tồn tại hoặc bạn không có quyền truy cập';
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        // Cập nhật thời gian đọc cuối
        $this->chatGroupModel->updateLastRead($groupId, $user['id']);

        // Lấy tin nhắn
        $messages = $this->chatMessageModel->getByGroup($groupId, 100);
        $members = $this->chatGroupModel->getMembers($groupId);

        // Danh sách tất cả user để thêm thành viên (chỉ dùng khi người xem có quyền)
        $allUsers = $this->userModel->getAll();

        $title = 'Chat: ' . htmlspecialchars($group['name']);
        $view = 'chat/view';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Thêm thành viên vào nhóm (POST)
     */
    public function addMember()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        $user = getCurrentUser();
        $groupId = $_POST['group_id'] ?? 0;
        $newUserId = $_POST['user_id'] ?? 0;

        $group = $this->chatGroupModel->getById($groupId);
        if (!$group) {
            $_SESSION['error'] = 'Nhóm không tồn tại';
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        // Chỉ creator nhóm hoặc admin (site) hoặc member có role admin mới được thêm
        $memberRow = $this->chatGroupModel->getMember($groupId, $user['id']);
        $isGroupAdmin = $memberRow !== null && $memberRow['role'] === 'admin';

        if (!isAdmin() && !$isGroupAdmin && (!isset($group['created_by']) || $group['created_by'] != $user['id'])) {
            $_SESSION['error'] = 'Bạn không có quyền thêm thành viên';
            header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
            exit;
        }

        if (empty($newUserId)) {
            $_SESSION['error'] = 'Vui lòng chọn thành viên để thêm';
            header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
            exit;
        }

        if ($this->chatGroupModel->addMember($groupId, $newUserId)) {
            $_SESSION['success'] = 'Thêm thành viên thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm thành viên';
        }

        header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
        exit;
    }

    /**
     * Xóa (soft-delete) nhóm chat (POST)
     */
    public function deleteGroup()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        $user = getCurrentUser();
        $groupId = $_POST['group_id'] ?? 0;

        $group = $this->chatGroupModel->getById($groupId);
        if (!$group) {
            $_SESSION['error'] = 'Nhóm không tồn tại';
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        // Chỉ có admin site hoặc người tạo nhóm mới được xóa
        $memberRow = $this->chatGroupModel->getMember($groupId, $user['id']);
        $isGroupAdmin = $memberRow !== null && $memberRow['role'] === 'admin';

        if (!isAdmin() && !$isGroupAdmin && (!isset($group['created_by']) || $group['created_by'] != $user['id'])) {
            $_SESSION['error'] = 'Bạn không có quyền xóa nhóm';
            header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
            exit;
        }

        if ($this->chatGroupModel->deleteGroup($groupId)) {
            $_SESSION['success'] = 'Xóa nhóm chat thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa nhóm';
        }

        header('Location: ' . BASE_URL . '?action=chat/index');
        exit;
    }

    /**
     * Tạo nhóm chat mới
     */
    public function createGroup()
    {
        requireLogin();

        $user = getCurrentUser();
        $error = null;
        $success = null;

        // Lấy danh sách tour (cho admin) hoặc tour được phân công (cho guide)
        $tours = [];
        if (isAdmin()) {
            $tours = $this->tourModel->getAll();
        } else {
            $assignments = $this->assignmentModel->getByGuide($user['id']);
            foreach ($assignments as $assignment) {
                $tour = $this->tourModel->getById($assignment['tour_id']);
                if ($tour) {
                    $tours[] = $tour;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? 'general';
            $name = trim($_POST['name'] ?? '');
            $tourId = $_POST['tour_id'] ?? null;
            $department = $_POST['department'] ?? null;
            $description = trim($_POST['description'] ?? '');
            $memberIds = $_POST['member_ids'] ?? [];

            if (empty($name)) {
                $error = 'Vui lòng nhập tên nhóm';
            } else {
                // Tạo nhóm
                $data = [
                    'name' => $name,
                    'type' => $type,
                    'tour_id' => $tourId ?: null,
                    'department' => $department ?: null,
                    'description' => $description,
                    'created_by' => $user['id']
                ];

                $groupId = $this->chatGroupModel->create($data);

                if ($groupId) {
                    // Thêm người tạo làm admin
                    $this->chatGroupModel->addMember($groupId, $user['id'], 'admin');

                    // Thêm các thành viên
                    if (!empty($memberIds)) {
                        foreach ($memberIds as $memberId) {
                            if ($memberId != $user['id']) {
                                $this->chatGroupModel->addMember($groupId, $memberId);
                            }
                        }
                    }

                    // Nếu là nhóm tour, tự động thêm HDV và điều hành
                    if ($type === 'tour' && $tourId) {
                        $assignments = $this->assignmentModel->getByTour($tourId);
                        foreach ($assignments as $assignment) {
                            $this->chatGroupModel->addMember($groupId, $assignment['guide_id']);
                        }

                        // Thêm admin vào nhóm tour
                        $admins = $this->userModel->getAll('admin');
                        foreach ($admins as $admin) {
                            if ($admin['id'] != $user['id']) {
                                $this->chatGroupModel->addMember($groupId, $admin['id']);
                            }
                        }
                    }

                    $success = 'Tạo nhóm chat thành công!';
                    header('refresh:1;url=' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
                } else {
                    $error = 'Có lỗi xảy ra khi tạo nhóm chat';
                }
            }
        }

        // Lấy danh sách user để chọn thành viên
        $allUsers = $this->userModel->getAll();

        $title = 'Tạo nhóm chat mới';
        $view = 'chat/create-group';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Gửi tin nhắn (AJAX)
     */
    public function sendMessage()
    {
        requireLogin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $user = getCurrentUser();
        $groupId = $_POST['group_id'] ?? 0;
        $message = trim($_POST['message'] ?? '');

        // Kiểm tra quyền truy cập
        if (!$this->chatGroupModel->isMember($groupId, $user['id'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền gửi tin nhắn trong nhóm này']);
            exit;
        }

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập nội dung tin nhắn']);
            exit;
        }

        $data = [
            'group_id' => $groupId,
            'user_id' => $user['id'],
            'message' => $message,
            'message_type' => 'text'
        ];

        $messageId = $this->chatMessageModel->create($data);

        if ($messageId) {
            $newMessage = $this->chatMessageModel->getById($messageId);
            echo json_encode([
                'success' => true,
                'message' => $newMessage
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi tin nhắn']);
        }
        exit;
    }

    /**
     * Upload file/hình ảnh (AJAX)
     */
    public function uploadFile()
    {
        requireLogin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $user = getCurrentUser();
        $groupId = $_POST['group_id'] ?? 0;

        // Kiểm tra quyền truy cập
        if (!$this->chatGroupModel->isMember($groupId, $user['id'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền gửi file trong nhóm này']);
            exit;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Lỗi upload file']);
            exit;
        }

        $file = $_FILES['file'];
        $fileSize = $file['size'];
        $fileName = $file['name'];
        $fileType = $file['type'];

        // Giới hạn kích thước file (10MB)
        if ($fileSize > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File quá lớn. Tối đa 10MB']);
            exit;
        }

        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 
                        'application/pdf', 'application/msword', 
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Loại file không được phép']);
            exit;
        }

        try {
            // Tạo thư mục chat nếu chưa có
            $chatFolder = 'chat/' . $groupId;
            $fullPath = PATH_ASSETS_UPLOADS . $chatFolder;
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            // Upload file
            $targetFile = $chatFolder . '/' . time() . '_' . basename($fileName);
            $targetPath = PATH_ASSETS_UPLOADS . $targetFile;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Xác định loại tin nhắn
                $messageType = strpos($fileType, 'image/') === 0 ? 'image' : 'file';

                // Tạo tin nhắn
                $data = [
                    'group_id' => $groupId,
                    'user_id' => $user['id'],
                    'message' => $fileName,
                    'message_type' => $messageType,
                    'file_path' => $targetFile,
                    'file_name' => $fileName,
                    'file_size' => $fileSize
                ];

                $messageId = $this->chatMessageModel->create($data);

                if ($messageId) {
                    $newMessage = $this->chatMessageModel->getById($messageId);
                    echo json_encode([
                        'success' => true,
                        'message' => $newMessage
                    ]);
                } else {
                    // Xóa file nếu không tạo được tin nhắn
                    unlink($targetPath);
                    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi lưu tin nhắn']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi upload file']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Lấy tin nhắn mới (AJAX - cho polling)
     */
    public function getMessages()
    {
        requireLogin();

        header('Content-Type: application/json');

        $user = getCurrentUser();
        $groupId = $_GET['group_id'] ?? 0;
        $afterTime = $_GET['after_time'] ?? null;

        // Kiểm tra quyền truy cập
        if (!$this->chatGroupModel->isMember($groupId, $user['id'])) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
            exit;
        }

        // Nếu không có afterTime, lấy từ last_read_at của user
        if (!$afterTime) {
            $memberInfo = $this->chatGroupModel->getMember($groupId, $user['id']);
            if ($memberInfo && $memberInfo['last_read_at']) {
                $afterTime = $memberInfo['last_read_at'];
            } else {
                // Nếu chưa có, lấy từ 1 giờ trước
                $afterTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
            }
        }

        $messages = $this->chatMessageModel->getNewMessages($groupId, $afterTime);

        // Cập nhật thời gian đọc
        $this->chatGroupModel->updateLastRead($groupId, $user['id']);

        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
        exit;
    }

    /**
     * Xóa thành viên khỏi nhóm (POST)
     */
    public function removeMember()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        $user = getCurrentUser();
        $groupId = $_POST['group_id'] ?? 0;
        $memberToRemove = $_POST['member_id'] ?? 0;

        $group = $this->chatGroupModel->getById($groupId);
        if (!$group) {
            $_SESSION['error'] = 'Nhóm không tồn tại';
            header('Location: ' . BASE_URL . '?action=chat/index');
            exit;
        }

        // Chỉ creator nhóm hoặc admin (site) hoặc member có role admin mới được xóa
        $memberRow = $this->chatGroupModel->getMember($groupId, $user['id']);
        $isGroupAdmin = $memberRow !== null && $memberRow['role'] === 'admin';

        if (!isAdmin() && !$isGroupAdmin && (!isset($group['created_by']) || $group['created_by'] != $user['id'])) {
            $_SESSION['error'] = 'Bạn không có quyền xóa thành viên';
            header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
            exit;
        }

        // Không cho phép xóa chính mình khỏi nhóm
        if ($memberToRemove == $user['id'] && !isAdmin()) {
            $_SESSION['error'] = 'Không thể xóa chính bạn. Hãy yêu cầu admin xóa!';
            header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
            exit;
        }

        if ($this->chatGroupModel->removeMember($groupId, $memberToRemove)) {
            $_SESSION['success'] = 'Xóa thành viên thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thành viên';
        }

        header('Location: ' . BASE_URL . '?action=chat/view&group_id=' . $groupId);
        exit;
    }

    /**
     * Xóa tin nhắn (POST - AJAX)
     */
    public function deleteMessage()
    {
        requireLogin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $user = getCurrentUser();
        $messageId = $_POST['message_id'] ?? 0;
        $groupId = $_POST['group_id'] ?? 0;

        // Kiểm tra quyền truy cập nhóm
        if (!$this->chatGroupModel->isMember($groupId, $user['id'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền']);
            exit;
        }

        if ($this->chatMessageModel->delete($messageId, $user['id'])) {
            echo json_encode(['success' => true, 'message' => 'Xóa tin nhắn thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa tin nhắn']);
        }
        exit;
    }
}


