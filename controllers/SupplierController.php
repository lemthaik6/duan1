<?php

class SupplierController
{
    private $supplierModel;
    private $tourSupplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->tourSupplierModel = new TourSupplierModel();
    }

    /**
     * Danh sách nhà cung cấp
     */
    public function index()
    {
        requireAdmin();
        
        $filters = [];
        if (isset($_GET['supplier_type'])) {
            $filters['supplier_type'] = $_GET['supplier_type'];
        }
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }

        $suppliers = $this->supplierModel->getAll($filters);
        
        $title = 'Quản lý Nhà cung cấp';
        $view = 'suppliers/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo nhà cung cấp mới
     */
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'supplier_type' => $_POST['supplier_type'] ?? 'other',
                'contact_person' => !empty($_POST['contact_person']) ? $_POST['contact_person'] : null,
                'phone' => !empty($_POST['phone']) ? $_POST['phone'] : null,
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'address' => !empty($_POST['address']) ? $_POST['address'] : null,
                'description' => !empty($_POST['description']) ? $_POST['description'] : null,
                'capacity' => !empty($_POST['capacity']) ? $_POST['capacity'] : null,
                'rating' => !empty($_POST['rating']) ? (float)$_POST['rating'] : null,
                'status' => $_POST['status'] ?? 'active'
            ];

            if (empty($data['name'])) {
                $error = 'Vui lòng nhập tên nhà cung cấp';
            } else {
                if ($this->supplierModel->create($data)) {
                    $success = 'Tạo nhà cung cấp thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=suppliers/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo nhà cung cấp';
                }
            }
        }
        
        $title = 'Tạo nhà cung cấp mới';
        $view = 'suppliers/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết nhà cung cấp
     */
    public function view()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $supplier = $this->supplierModel->getById($id);
        
        if (!$supplier) {
            header('Location: ' . BASE_URL . '?action=suppliers/index');
            exit;
        }

        $tours = $this->tourSupplierModel->getBySupplier($id);
        
        $title = 'Chi tiết nhà cung cấp: ' . $supplier['name'];
        $view = 'suppliers/view';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa nhà cung cấp
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $supplier = $this->supplierModel->getById($id);
        
        if (!$supplier) {
            header('Location: ' . BASE_URL . '?action=suppliers/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'supplier_type' => $_POST['supplier_type'] ?? 'other',
                'contact_person' => !empty($_POST['contact_person']) ? $_POST['contact_person'] : null,
                'phone' => !empty($_POST['phone']) ? $_POST['phone'] : null,
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'address' => !empty($_POST['address']) ? $_POST['address'] : null,
                'description' => !empty($_POST['description']) ? $_POST['description'] : null,
                'capacity' => !empty($_POST['capacity']) ? $_POST['capacity'] : null,
                'rating' => !empty($_POST['rating']) ? (float)$_POST['rating'] : null,
                'status' => $_POST['status'] ?? 'active'
            ];

            if (empty($data['name'])) {
                $error = 'Vui lòng nhập tên nhà cung cấp';
            } else {
                if ($this->supplierModel->update($id, $data)) {
                    $success = 'Cập nhật nhà cung cấp thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=suppliers/index');
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật';
                }
            }
        }
        
        $title = 'Chỉnh sửa nhà cung cấp';
        $view = 'suppliers/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa nhà cung cấp
     */
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        
        if ($this->supplierModel->delete($id)) {
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa';
        }
        
        header('Location: ' . BASE_URL . '?action=suppliers/index');
        exit;
    }

    /**
     * Liên kết nhà cung cấp với tour
     */
    public function linkToTour()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tourId = $_POST['tour_id'] ?? 0;
            $supplierId = $_POST['supplier_id'] ?? 0;
            
            $data = [
                'service_description' => $_POST['service_description'] ?? '',
                'booking_reference' => $_POST['booking_reference'] ?? '',
                'contact_date' => !empty($_POST['contact_date']) ? $_POST['contact_date'] : null,
                'notes' => $_POST['notes'] ?? ''
            ];

            if ($this->tourSupplierModel->link($tourId, $supplierId, $data)) {
                $_SESSION['success'] = 'Liên kết nhà cung cấp thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi liên kết';
            }
            
            header('Location: ' . BASE_URL . '?action=tour-suppliers/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
}

