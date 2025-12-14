<?php

class VehicleController
{
    private $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
    }
    public function index()
    {
        requireAdmin();
        
        $status = $_GET['status'] ?? null;
        $vehicles = $this->vehicleModel->getAll($status);
        
        $title = 'Quản lý Xe';
        $view = 'vehicles/index';
        require_once PATH_VIEW_MAIN;
    }
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'license_plate' => $_POST['license_plate'] ?? '',
                'vehicle_type' => $_POST['vehicle_type'] ?? '',
                'capacity' => $_POST['capacity'] ?? 0,
                'driver_name' => $_POST['driver_name'] ?? '',
                'driver_phone' => $_POST['driver_phone'] ?? '',
                'status' => $_POST['status'] ?? 'available',
                'notes' => $_POST['notes'] ?? ''
            ];

            if (empty($data['license_plate']) || empty($data['vehicle_type'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->vehicleModel->create($data)) {
                    $success = 'Tạo xe thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=vehicles/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo xe';
                }
            }
        }

        $title = 'Thêm Xe mới';
        $view = 'vehicles/create';
        require_once PATH_VIEW_MAIN;
    }

    public function view()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $vehicle = $this->vehicleModel->getById($id);
        
        if (!$vehicle) {
            header('Location: ' . BASE_URL . '?action=vehicles/index');
            exit;
        }
        
        $title = 'Chi tiết Xe: ' . $vehicle['license_plate'];
        $view = 'vehicles/view';
        require_once PATH_VIEW_MAIN;
    }

    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $vehicle = $this->vehicleModel->getById($id);
        
        if (!$vehicle) {
            header('Location: ' . BASE_URL . '?action=vehicles/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'license_plate' => $_POST['license_plate'] ?? '',
                'vehicle_type' => $_POST['vehicle_type'] ?? '',
                'capacity' => $_POST['capacity'] ?? 0,
                'driver_name' => $_POST['driver_name'] ?? '',
                'driver_phone' => $_POST['driver_phone'] ?? '',
                'status' => $_POST['status'] ?? 'available',
                'notes' => $_POST['notes'] ?? ''
            ];

            if (empty($data['license_plate']) || empty($data['vehicle_type'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->vehicleModel->update($id, $data)) {
                    $success = 'Cập nhật xe thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=vehicles/index');
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật xe';
                }
            }
        }
        
        $title = 'Chỉnh sửa Xe';
        $view = 'vehicles/edit';
        require_once PATH_VIEW_MAIN;
    }

    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $vehicle = $this->vehicleModel->getById($id);
        
        if (!$vehicle) {
            $_SESSION['error'] = 'Xe không tồn tại';
            header('Location: ' . BASE_URL . '?action=vehicles/index');
            exit;
        }

        if ($this->vehicleModel->delete($id)) {
            $_SESSION['success'] = 'Xóa xe thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa xe';
        }

        header('Location: ' . BASE_URL . '?action=vehicles/index');
        exit;
    }
}

