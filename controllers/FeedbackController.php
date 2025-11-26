<?php

class FeedbackController
{
    private $feedbackModel;
    private $tourModel;
    private $assignmentModel;
    private $supplierModel;
    private $tourSupplierModel;

    public function __construct()
    {
        $this->feedbackModel = new TourFeedbackModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
        $this->supplierModel = new SupplierModel();
        $this->tourSupplierModel = new TourSupplierModel();
    }
    public function index()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $feedbacks = $this->feedbackModel->getByGuide($user['id']);
        
        $title = 'Phản hồi đánh giá của tôi';
        $view = 'feedbacks/index';
        require_once PATH_VIEW_MAIN;
    }

    public function create()
    {
        requireGuide();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $feedbackType = $_GET['type'] ?? 'tour_evaluation'; 
        
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }

        $user = getCurrentUser();
        $assignments = $this->assignmentModel->getByTour($tourId);
        $isAssigned = false;
        foreach ($assignments as $assignment) {
            if ($assignment['guide_id'] == $user['id']) {
                $isAssigned = true;
                break;
            }
        }
        
        if (!$isAssigned) {
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'rated_by' => $user['id'],
                'feedback_type' => $_POST['feedback_type'] ?? 'tour_evaluation',
                'rating' => !empty($_POST['rating']) ? $_POST['rating'] : null,
                'content' => $_POST['content'] ?? ''
            ];

            if (empty($data['content'])) {
                $error = 'Vui lòng nhập nội dung phản hồi';
            } else {
                if ($this->feedbackModel->create($data)) {
                    $success = 'Gửi phản hồi thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=feedbacks/index');
                } else {
                    $error = 'Có lỗi xảy ra khi gửi phản hồi';
                }
            }
        }

        $suppliers = $this->tourSupplierModel->getByTour($tourId);
        
        $title = 'Gửi phản hồi đánh giá';
        $view = 'feedbacks/create';
        require_once PATH_VIEW_MAIN;
    }

    public function view()
    {
        requireLogin();
        
        $id = $_GET['id'] ?? 0;
        $feedback = $this->feedbackModel->getById($id);
        
        if (!$feedback) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=feedbacks/admin');
            } else {
                header('Location: ' . BASE_URL . '?action=feedbacks/index');
            }
            exit;
        }
        if (isGuide() && $feedback['rated_by'] != getCurrentUser()['id']) {
            header('Location: ' . BASE_URL . '?action=feedbacks/index');
            exit;
        }
        
        $title = 'Chi tiết phản hồi';
        $view = 'feedbacks/view';
        require_once PATH_VIEW_MAIN;
    }

    public function edit()
    {
        requireGuide();
        
        $id = $_GET['id'] ?? 0;
        $feedback = $this->feedbackModel->getById($id);
        $user = getCurrentUser();
        
        if (!$feedback || $feedback['rated_by'] != $user['id']) {
            header('Location: ' . BASE_URL . '?action=feedbacks/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'rating' => !empty($_POST['rating']) ? $_POST['rating'] : null,
                'content' => $_POST['content'] ?? ''
            ];

            if (empty($data['content'])) {
                $error = 'Vui lòng nhập nội dung phản hồi';
            } else {
                if ($this->feedbackModel->update($id, $data)) {
                    $success = 'Cập nhật phản hồi thành công!';
                    $feedback = $this->feedbackModel->getById($id);
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật';
                }
            }
        }

        $tour = $this->tourModel->getById($feedback['tour_id']);
        $suppliers = $this->tourSupplierModel->getByTour($feedback['tour_id']);
        
        $title = 'Chỉnh sửa phản hồi';
        $view = 'feedbacks/edit';
        require_once PATH_VIEW_MAIN;
    }
    public function delete()
    {
        requireGuide();
        
        $id = $_GET['id'] ?? 0;
        $feedback = $this->feedbackModel->getById($id);
        $user = getCurrentUser();
        
        if (!$feedback || $feedback['rated_by'] != $user['id']) {
            header('Location: ' . BASE_URL . '?action=feedbacks/index');
            exit;
        }
        
        if ($this->feedbackModel->delete($id)) {
            $_SESSION['success'] = 'Xóa phản hồi thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa';
        }
        
        header('Location: ' . BASE_URL . '?action=feedbacks/index');
        exit;
    }
    public function admin()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $guideId = $_GET['guide_id'] ?? 0;
        $feedbacks = [];

        if ($guideId) {
            $feedbacks = $this->feedbackModel->getByGuide($guideId);
        } elseif ($tourId) {
            $feedbacks = $this->feedbackModel->getByTour($tourId);
        } else {
            $feedbacks = $this->feedbackModel->getAll();
        }
        
        $tours = $this->tourModel->getAll();
        
        $title = 'Quản lý Phản hồi đánh giá';
        $view = 'feedbacks/admin';
        require_once PATH_VIEW_MAIN;
    }
}

