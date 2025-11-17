<?php

class CostController
{
    private $costModel;
    private $costCategoryModel;
    private $tourModel;

    public function __construct()
    {
        $this->costModel = new TourCostModel();
        $this->costCategoryModel = new CostCategoryModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Danh sách chi phí (Admin)
     */
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
                $totalCost = $this->costModel->getTotalCost($tourId, true); // Bao gồm giá gốc nội bộ
                $costsOnly = $this->costModel->getCostsOnly($tourId); // Chỉ chi phí phát sinh
                $costByCategory = $this->costModel->getCostByCategory($tourId);
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Quản lý Chi phí';
        $view = 'costs/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chi phí của tôi (HDV)
     */
    public function myCosts()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tourId = $_GET['tour_id'] ?? 0;
        $costs = [];
        $tour = null;
        $totalCost = 0;

        if ($tourId) {
            $tour = $this->tourModel->getById($tourId);
            if ($tour) {
                $costs = $this->costModel->getByTour($tourId);
                // HDV chỉ xem chi phí phát sinh, không bao gồm giá gốc nội bộ
                $totalCost = $this->costModel->getTotalCost($tourId, false);
            }
        }

        $categories = $this->costCategoryModel->getAll(true);
        $title = 'Chi phí Tour';
        $view = 'costs/my-costs';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Thêm chi phí
     */
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

        // Nếu là Guide, chỉ được thêm chi phí cho tour được phân công
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
}

