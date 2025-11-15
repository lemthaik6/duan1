<?php

class TourController
{
    private $tourModel;
    private $categoryModel;
    private $assignmentModel;
    private $itineraryModel;
    private $customerModel;
    private $costModel;
    private $dailyLogModel;
    private $incidentModel;
    private $userModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
        $this->categoryModel = new TourCategoryModel();
        $this->assignmentModel = new TourAssignmentModel();
        $this->itineraryModel = new ItineraryModel();
        $this->customerModel = new TourCustomerModel();
        $this->costModel = new TourCostModel();
        $this->dailyLogModel = new TourDailyLogModel();
        $this->incidentModel = new TourIncidentModel();
        $this->userModel = new UserModel();
    }

    /**
     * Danh sách tour (Admin)
     */
    public function index()
    {
        requireAdmin();
        
        $filters = [];
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }

        $tours = $this->tourModel->getAll($filters);
        $categories = $this->categoryModel->getAll(true);
        
        $title = 'Quản lý Tour';
        $view = 'tours/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết tour
     */
    public function view()
    {
        requireLogin();
        
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getById($id);
        
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=tours/index');
            } else {
                header('Location: ' . BASE_URL . '?action=tours/my-tours');
            }
            exit;
        }

        // Nếu là Guide, chỉ được xem tour được phân công
        if (isGuide()) {
            $user = getCurrentUser();
            $assignments = $this->assignmentModel->getByTour($id);
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
        }

        $assignments = $this->assignmentModel->getByTour($id);
        $itineraries = $this->itineraryModel->getByTour($id);
        $customers = $this->customerModel->getByTour($id);
        $costs = $this->costModel->getByTour($id);
        $totalCost = $this->costModel->getTotalCost($id);
        $dailyLogs = $this->dailyLogModel->getByTour($id);
        $incidents = $this->incidentModel->getByTour($id);
        
        $title = 'Chi tiết Tour: ' . $tour['name'];
        $view = 'tours/view';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo tour mới (Admin)
     */
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'schedule' => $_POST['schedule'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'internal_price' => !empty($_POST['internal_price']) ? $_POST['internal_price'] : null,
                'priority_level' => $_POST['priority_level'] ?? 'medium',
                'status' => $_POST['status'] ?? 'upcoming',
                'pdf_program_path' => null, // Có thể upload sau
                'created_by' => getCurrentUser()['id']
            ];

            if (empty($data['name']) || empty($data['start_date']) || empty($data['end_date'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->tourModel->create($data)) {
                    $success = 'Tạo tour thành công!';
                    // Redirect sau 2 giây
                    header('refresh:2;url=' . BASE_URL . '?action=tours/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo tour';
                }
            }
        }

        $categories = $this->categoryModel->getAll(true);
        $title = 'Tạo tour mới';
        $view = 'tours/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa tour (Admin)
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getById($id);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'schedule' => $_POST['schedule'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'internal_price' => $_POST['internal_price'] ?? 0,
                'priority_level' => $_POST['priority_level'] ?? 'medium',
                'status' => $_POST['status'] ?? 'upcoming'
            ];

            if ($this->tourModel->update($id, $data)) {
                $success = 'Cập nhật tour thành công!';
                $tour = $this->tourModel->getById($id); // Refresh data
            } else {
                $error = 'Có lỗi xảy ra khi cập nhật tour';
            }
        }

        $categories = $this->categoryModel->getAll(true);
        $title = 'Chỉnh sửa Tour';
        $view = 'tours/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa tour (Admin)
     */
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        
        if ($this->tourModel->delete($id)) {
            $_SESSION['success'] = 'Xóa tour thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa tour';
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }

    /**
     * Tour của tôi (HDV)
     */
    public function myTours()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tours = $this->tourAssignmentModel->getByGuide($user['id']);
        
        $title = 'Tour của tôi';
        $view = 'tours/my-tours';
        require_once PATH_VIEW_MAIN;
    }
}

