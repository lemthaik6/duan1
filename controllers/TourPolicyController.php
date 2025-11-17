<?php

class TourPolicyController
{
    private $policyModel;
    private $tourModel;

    public function __construct()
    {
        $this->policyModel = new TourPolicyModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Danh sách chính sách tour
     */
    public function index()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $policies = $this->policyModel->getByTour($tourId);
        
        $title = 'Quản lý chính sách - ' . $tour['name'];
        $view = 'tour-policies/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo chính sách mới
     */
    public function create()
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
            $data = [
                'tour_id' => $tourId,
                'policy_type' => $_POST['policy_type'] ?? 'booking',
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'priority' => $_POST['priority'] ?? 0
            ];

            if (empty($data['title']) || empty($data['content'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->policyModel->create($data)) {
                    $success = 'Tạo chính sách thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=tour-policies/index&tour_id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi tạo chính sách';
                }
            }
        }
        
        $title = 'Tạo chính sách mới';
        $view = 'tour-policies/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa chính sách
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $policy = $this->policyModel->getById($id);
        
        if (!$policy) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $tour = $this->tourModel->getById($policy['tour_id']);
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'policy_type' => $_POST['policy_type'] ?? 'booking',
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'priority' => $_POST['priority'] ?? 0
            ];

            if (empty($data['title']) || empty($data['content'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->policyModel->update($id, $data)) {
                    $success = 'Cập nhật chính sách thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=tour-policies/index&tour_id=' . $policy['tour_id']);
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật';
                }
            }
        }
        
        $title = 'Chỉnh sửa chính sách';
        $view = 'tour-policies/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa chính sách
     */
    public function delete()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $tourId = $_POST['tour_id'] ?? 0;

            if ($this->policyModel->delete($id)) {
                $_SESSION['success'] = 'Xóa chính sách thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa';
            }
            
            header('Location: ' . BASE_URL . '?action=tour-policies/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
}

