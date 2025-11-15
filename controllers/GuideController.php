<?php

class GuideController
{
    private $userModel;
    private $assignmentModel;
    private $tourModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->assignmentModel = new TourAssignmentModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Danh sách HDV
     */
    public function index()
    {
        requireAdmin();
        
        $guides = $this->userModel->getGuides();
        
        // Thêm số tour đã đi cho mỗi HDV
        foreach ($guides as &$guide) {
            $guide['total_tours'] = $this->userModel->countToursByGuide($guide['id']);
        }
        
        $title = 'Quản lý Hướng dẫn viên';
        $view = 'guides/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo HDV mới
     */
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'role' => 'guide',
                'status' => $_POST['status'] ?? 'active'
            ];

            if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->userModel->create($data)) {
                    $success = 'Tạo HDV thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=guides/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo HDV';
                }
            }
        }

        $title = 'Thêm Hướng dẫn viên';
        $view = 'guides/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết HDV
     */
    public function view()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $guide = $this->userModel->getById($id);
        
        if (!$guide || $guide['role'] !== 'guide') {
            header('Location: ' . BASE_URL . '?action=guides/index');
            exit;
        }

        $totalTours = $this->userModel->countToursByGuide($id);
        $tours = $this->assignmentModel->getByGuide($id);
        
        $title = 'Chi tiết Hướng dẫn viên';
        $view = 'guides/view';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa HDV
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $guide = $this->userModel->getById($id);
        
        if (!$guide || $guide['role'] !== 'guide') {
            header('Location: ' . BASE_URL . '?action=guides/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'status' => $_POST['status'] ?? 'active'
            ];

            // Chỉ cập nhật password nếu có nhập mới
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            if (empty($data['full_name'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->userModel->update($id, $data)) {
                    $success = 'Cập nhật HDV thành công!';
                    $guide = $this->userModel->getById($id); // Refresh data
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật HDV';
                }
            }
        }

        $title = 'Chỉnh sửa Hướng dẫn viên';
        $view = 'guides/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Phân công HDV cho tour
     */
    public function assign()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guideId = $_POST['guide_id'] ?? 0;
            $notes = $_POST['notes'] ?? '';

            if (empty($guideId)) {
                $error = 'Vui lòng chọn hướng dẫn viên';
            } else {
                if ($this->assignmentModel->assign($tourId, $guideId, getCurrentUser()['id'], $notes)) {
                    $success = 'Phân công HDV thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=tours/view&id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi phân công HDV';
                }
            }
        }

        $guides = $this->userModel->getGuides();
        $assignments = $this->assignmentModel->getByTour($tourId);
        
        $title = 'Phân công HDV cho tour';
        $view = 'guides/assign';
        require_once PATH_VIEW_MAIN;
    }
}

