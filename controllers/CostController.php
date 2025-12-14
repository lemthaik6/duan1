<?php

class CostController
{
    private $costModel;
    private $costCategoryModel;
    private $tourModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->costModel = new TourCostModel();
        $this->costCategoryModel = new CostCategoryModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
    }

    public function index()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $costs = [];
        $tour = null;
        $totalCost = 0;
        $costByCategory = [];

        if ($tourId) {
            $tour = $this->tourModel->getById($tourId);
            if ($tour) {
                $costs = $this->costModel->getByTour($tourId);
                $totalCost = $this->costModel->getTotalCost($tourId, true); 
                $costsOnly = $this->costModel->getCostsOnly($tourId);
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Quản lý Chi phí';
        $view = 'costs/index';
        require_once PATH_VIEW_MAIN;
    }
    public function myCosts()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tourId = $_GET['tour_id'] ?? 0;
        $costs = [];
        $tour = null;
        $totalCost = 0;

        // Lấy danh sách các tour được phân công cho HDV này
        $assignments = $this->assignmentModel->getByGuide($user['id']);
        $assignedTours = [];
        foreach ($assignments as $assignment) {
            $assignedTour = $this->tourModel->getById($assignment['tour_id']);
            if ($assignedTour) {
                // Tính tổng chi phí và số lượng chi phí cho mỗi tour
                $assignedCosts = $this->costModel->getByTour($assignedTour['id']);
                $assignedTotalCost = $this->costModel->getTotalCost($assignedTour['id'], false);
                $assignedTour['costs_count'] = count($assignedCosts);
                $assignedTour['total_cost'] = $assignedTotalCost;
                $assignedTours[] = $assignedTour;
            }
        }

        if ($tourId) {
            $tour = $this->tourModel->getById($tourId);
            if ($tour) {
                // Kiểm tra HDV có được phân công tour này không
                $isAssigned = false;
                foreach ($assignments as $assignment) {
                    if ($assignment['tour_id'] == $tourId && $assignment['guide_id'] == $user['id']) {
                        $isAssigned = true;
                        break;
                    }
                }
                
                if (!$isAssigned) {
                    $_SESSION['error'] = 'Bạn không có quyền xem chi phí của tour này';
                    header('Location: ' . BASE_URL . '?action=costs/my-costs');
                    exit;
                }

                $costs = $this->costModel->getByTour($tourId);
                $totalCost = $this->costModel->getTotalCost($tourId, false);
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Chi phí Tour';
        $view = 'costs/my-costs';
        require_once PATH_VIEW_MAIN;
    }
    public function view()
    {
        requireLogin();
        
        $id = $_GET['id'] ?? 0;
        $cost = $this->costModel->getById($id);
        
        if (!$cost) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
            }
            exit;
        }

        $tour = $this->tourModel->getById($cost['tour_id']);
        
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
            }
            exit;
        }

        // Kiểm tra quyền: HDV chỉ có thể xem chi phí của tour được phân công
        if (isGuide()) {
            $user = getCurrentUser();
            
            $assignments = $this->assignmentModel->getByTour($cost['tour_id']);
            $isAssigned = false;
            foreach ($assignments as $assignment) {
                if ($assignment['guide_id'] == $user['id']) {
                    $isAssigned = true;
                    break;
                }
            }
            
            if (!$isAssigned) {
                $_SESSION['error'] = 'Bạn không có quyền xem chi phí của tour này';
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
                exit;
            }
        }

        $title = 'Chi tiết Chi phí - ' . $tour['name'];
        $view = 'costs/view';
        require_once PATH_VIEW_MAIN;
    }

    public function create()
    {
        requireLogin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
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
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
                exit;
            }
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'cost_category_id' => $_POST['cost_category_id'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'amount' => $_POST['amount'] ?? 0,
                'date' => $_POST['date'] ?? date('Y-m-d'),
                'created_by' => getCurrentUser()['id']
            ];

            if (empty($data['cost_category_id']) || empty($data['amount'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->costModel->create($data)) {
                    $success = 'Thêm chi phí thành công!';
                    if (isAdmin()) {
                        header('refresh:2;url=' . BASE_URL . '?action=costs/index&tour_id=' . $tourId);
                    } else {
                        header('refresh:2;url=' . BASE_URL . '?action=costs/my-costs&tour_id=' . $tourId);
                    }
                } else {
                    $error = 'Có lỗi xảy ra khi thêm chi phí';
                }
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Thêm chi phí';
        $view = 'costs/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa chi phí
     */
    public function edit()
    {
        requireLogin();
        
        $id = $_GET['id'] ?? 0;
        $cost = $this->costModel->getById($id);
        
        if (!$cost) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
            }
            exit;
        }

        $tour = $this->tourModel->getById($cost['tour_id']);
        if (!$tour) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
            }
            exit;
        }

        // Kiểm tra quyền: HDV chỉ có thể sửa chi phí do chính mình tạo
        if (isGuide()) {
            $user = getCurrentUser();
            
            // Kiểm tra HDV có được phân công tour này không
            $assignments = $this->assignmentModel->getByTour($cost['tour_id']);
            $isAssigned = false;
            foreach ($assignments as $assignment) {
                if ($assignment['guide_id'] == $user['id']) {
                    $isAssigned = true;
                    break;
                }
            }
            
            if (!$isAssigned) {
                $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa chi phí của tour này';
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
                exit;
            }

            // Kiểm tra chi phí có phải do HDV này tạo không
            if ($cost['created_by'] != $user['id']) {
                $_SESSION['error'] = 'Bạn chỉ có thể chỉnh sửa chi phí do chính mình tạo';
                header('Location: ' . BASE_URL . '?action=costs/my-costs&tour_id=' . $cost['tour_id']);
                exit;
            }
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cost_category_id' => $_POST['cost_category_id'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'amount' => $_POST['amount'] ?? 0,
                'date' => $_POST['date'] ?? date('Y-m-d')
            ];

            if (empty($data['cost_category_id']) || empty($data['amount'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->costModel->update($id, $data)) {
                    $success = 'Cập nhật chi phí thành công!';
                    if (isAdmin()) {
                        header('refresh:2;url=' . BASE_URL . '?action=costs/index&tour_id=' . $cost['tour_id']);
                    } else {
                        header('refresh:2;url=' . BASE_URL . '?action=costs/my-costs&tour_id=' . $cost['tour_id']);
                    }
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật chi phí';
                }
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Chỉnh sửa chi phí';
        $view = 'costs/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa chi phí
     */
    public function delete()
    {
        requireLogin();
        
        $id = $_GET['id'] ?? 0;
        $cost = $this->costModel->getById($id);
        
        if (!$cost) {
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index');
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
            }
            exit;
        }

        // Kiểm tra quyền: HDV chỉ có thể xóa chi phí do chính mình tạo
        if (isGuide()) {
            $user = getCurrentUser();
            
            // Kiểm tra HDV có được phân công tour này không
            $assignments = $this->assignmentModel->getByTour($cost['tour_id']);
            $isAssigned = false;
            foreach ($assignments as $assignment) {
                if ($assignment['guide_id'] == $user['id']) {
                    $isAssigned = true;
                    break;
                }
            }
            
            if (!$isAssigned) {
                $_SESSION['error'] = 'Bạn không có quyền xóa chi phí của tour này';
                header('Location: ' . BASE_URL . '?action=costs/my-costs');
                exit;
            }

            // Kiểm tra chi phí có phải do HDV này tạo không
            if ($cost['created_by'] != $user['id']) {
                $_SESSION['error'] = 'Bạn chỉ có thể xóa chi phí do chính mình tạo';
                header('Location: ' . BASE_URL . '?action=costs/my-costs&tour_id=' . $cost['tour_id']);
                exit;
            }
        }

        if ($this->costModel->delete($id)) {
            $_SESSION['success'] = 'Xóa chi phí thành công!';
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index&tour_id=' . $cost['tour_id']);
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs&tour_id=' . $cost['tour_id']);
            }
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa chi phí';
            if (isAdmin()) {
                header('Location: ' . BASE_URL . '?action=costs/index&tour_id=' . $cost['tour_id']);
            } else {
                header('Location: ' . BASE_URL . '?action=costs/my-costs&tour_id=' . $cost['tour_id']);
            }
        }
        exit;
    }
}

