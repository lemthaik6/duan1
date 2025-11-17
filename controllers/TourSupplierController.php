<?php

class TourSupplierController
{
    private $tourSupplierModel;
    private $tourModel;
    private $supplierModel;

    public function __construct()
    {
        $this->tourSupplierModel = new TourSupplierModel();
        $this->tourModel = new TourModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Danh sách nhà cung cấp của tour
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

        $suppliers = $this->tourSupplierModel->getByTour($tourId);
        $allSuppliers = $this->supplierModel->getAll(['status' => 'active']);
        
        $title = 'Nhà cung cấp - ' . $tour['name'];
        $view = 'tour-suppliers/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa liên kết nhà cung cấp
     */
    public function unlink()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $tourId = $_POST['tour_id'] ?? 0;

            if ($this->tourSupplierModel->unlink($id)) {
                $_SESSION['success'] = 'Hủy liên kết thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi hủy liên kết';
            }
            
            header('Location: ' . BASE_URL . '?action=tour-suppliers/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
}

