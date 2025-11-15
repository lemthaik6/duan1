<?php

class VehicleController
{
    private $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
    }

    /**
     * Danh sách xe
     */
    public function index()
    {
        requireAdmin();
        
        $status = $_GET['status'] ?? null;
        $vehicles = $this->vehicleModel->getAll($status);
        
        $title = 'Quản lý Xe';
        $view = 'vehicles/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo xe mới
     */
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
}

