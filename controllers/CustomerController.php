<?php

class CustomerController
{
    private $customerModel;
    private $tourModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->customerModel = new TourCustomerModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
    }
    public function index()
    {
        requireLogin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=tours/index');
            } else {
                header('Location: ' . BASE_URL . '?action=tours/my-tours');
            }
            exit;
        }
        if (isGuide()) {
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
        }

        $customers = $this->customerModel->getByTour($tourId);
        
        $title = 'Danh sách khách - ' . $tour['name'];
        if (isAdmin()) {
            $view = 'customers/index-admin';
        } else {
            $view = 'customers/index-guide';
        }
        
        require_once PATH_VIEW_MAIN;
    }

    public function print()
    {
        requireLogin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $customers = $this->customerModel->getByTour($tourId);
        
        $title = 'In danh sách đoàn - ' . $tour['name'];
        $view = 'customers/print';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Thêm khách hàng mới vào tour
     */
    public function create()
    {
        requireLogin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=tours/index');
            } else {
                header('Location: ' . BASE_URL . '?action=tours/my-tours');
            }
            exit;
        }

        // Nếu là Guide, kiểm tra có được phân công không
        if (isGuide()) {
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
                $_SESSION['error'] = 'Bạn không có quyền thêm khách vào tour này';
                header('Location: ' . BASE_URL . '?action=tours/my-tours');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'full_name' => $_POST['full_name'] ?? '',
                'phone' => $_POST['phone'] ?? null,
                'email' => $_POST['email'] ?? null,
                'id_card' => $_POST['id_card'] ?? null,
                'notes' => $_POST['notes'] ?? null,
                'special_requests' => $_POST['special_requests'] ?? null
            ];

            // Validate
            if (empty($data['full_name'])) {
                $_SESSION['error'] = 'Vui lòng nhập họ và tên khách hàng';
            } else {
                if ($this->customerModel->create($data)) {
                    $_SESSION['success'] = 'Thêm khách hàng thành công!';
                    header('Location: ' . BASE_URL . '?action=customers/index&tour_id=' . $tourId);
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi thêm khách hàng';
                }
            }
        }

        $title = 'Thêm khách hàng - ' . $tour['name'];
        $view = 'customers/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa khách hàng (Admin only)
     */
    public function delete()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $tourId = $_POST['tour_id'] ?? 0;

            $customer = $this->customerModel->getById($id);
            
            if (!$customer || $customer['tour_id'] != $tourId) {
                $_SESSION['error'] = 'Không tìm thấy khách hàng';
            } else {
                if ($this->customerModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa khách hàng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa khách hàng';
                }
            }
            
            header('Location: ' . BASE_URL . '?action=customers/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }

    /**
     * Cập nhật yêu cầu đặc biệt của khách (HDV)
     */
    public function updateSpecialRequests()
    {
        requireGuide();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $tourId = $_POST['tour_id'] ?? 0;
            $specialRequests = $_POST['special_requests'] ?? '';

            $customer = $this->customerModel->getById($id);
            
            if (!$customer || $customer['tour_id'] != $tourId) {
                $_SESSION['error'] = 'Không tìm thấy khách';
                header('Location: ' . BASE_URL . '?action=customers/index&tour_id=' . $tourId);
                exit;
            }

            // Kiểm tra HDV có được phân công tour này không
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
                $_SESSION['error'] = 'Bạn không có quyền cập nhật thông tin khách của tour này';
                header('Location: ' . BASE_URL . '?action=tours/my-tours');
                exit;
            }

            if ($this->customerModel->update($id, ['special_requests' => $specialRequests])) {
                $_SESSION['success'] = 'Cập nhật yêu cầu đặc biệt thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
            }
            
            header('Location: ' . BASE_URL . '?action=customers/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/my-tours');
        exit;
    }

}

