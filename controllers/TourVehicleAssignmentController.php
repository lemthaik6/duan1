<?php

class TourVehicleAssignmentController
{
    private $vehicleAssignmentModel;
    private $tourModel;
    private $vehicleModel;

    public function __construct()
    {
        $this->vehicleAssignmentModel = new TourVehicleAssignmentModel();
        $this->tourModel = new TourModel();
        $this->vehicleModel = new VehicleModel();
    }

    /**
     * Hiển thị danh sách xe của tour
     */
    public function index()
    {
        requireAdmin();
        
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tour_id);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $vehicles = $this->vehicleAssignmentModel->getByTour($tour_id);
        
        $title = 'Quản lý Xe - Tour: ' . $tour['name'];
        $view = 'tour-vehicles/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Trang thêm xe cho tour
     */
    public function create()
    {
        requireAdmin();
        
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tour_id);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tour_id,
                'vehicle_id' => $_POST['vehicle_id'] ?? 0,
                'usage_purpose' => $_POST['usage_purpose'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'notes' => $_POST['notes'] ?? ''
            ];

            if (empty($data['vehicle_id'])) {
                $error = 'Vui lòng chọn xe';
            } elseif (empty($data['start_date']) || empty($data['end_date'])) {
                $error = 'Vui lòng chọn ngày bắt đầu và ngày kết thúc';
            } elseif ($data['start_date'] > $data['end_date']) {
                $error = 'Ngày bắt đầu phải trước ngày kết thúc';
            } else {
                // Kiểm tra xe có conflict không
                if ($this->vehicleAssignmentModel->checkVehicleConflict($data['vehicle_id'], $data['start_date'], $data['end_date'])) {
                    $error = 'Xe này đã được phân công vào thời gian này. Vui lòng chọn xe khác hoặc thay đổi thời gian.';
                } elseif ($this->vehicleAssignmentModel->create($data)) {
                    $success = 'Thêm xe vào tour thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=tour-vehicles/index&tour_id=' . $tour_id);
                } else {
                    $error = 'Có lỗi xảy ra khi thêm xe';
                }
            }
        }

        $vehicles = $this->vehicleModel->getAvailable();
        
        $title = 'Thêm Xe vào Tour: ' . $tour['name'];
        $view = 'tour-vehicles/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa phân công xe
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $assignment = $this->vehicleAssignmentModel->getById($id);
        
        if (!$assignment) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $tour_id = $assignment['tour_id'];
        $tour = $this->tourModel->getById($tour_id);
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'usage_purpose' => $_POST['usage_purpose'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'notes' => $_POST['notes'] ?? ''
            ];

            if (empty($data['start_date']) || empty($data['end_date'])) {
                $error = 'Vui lòng chọn ngày bắt đầu và ngày kết thúc';
            } elseif ($data['start_date'] > $data['end_date']) {
                $error = 'Ngày bắt đầu phải trước ngày kết thúc';
            } else {
                if ($this->vehicleAssignmentModel->update($id, $data)) {
                    $success = 'Cập nhật thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=tour-vehicles/index&tour_id=' . $tour_id);
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật';
                }
            }
        }

        $title = 'Chỉnh sửa Xe: ' . $assignment['license_plate'];
        $view = 'tour-vehicles/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa phân công xe
     */
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $assignment = $this->vehicleAssignmentModel->getById($id);
        
        if (!$assignment) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $tour_id = $assignment['tour_id'];

        if ($this->vehicleAssignmentModel->delete($id)) {
            header('Location: ' . BASE_URL . '?action=tour-vehicles/index&tour_id=' . $tour_id . '&deleted=1');
        } else {
            header('Location: ' . BASE_URL . '?action=tour-vehicles/index&tour_id=' . $tour_id . '&error=1');
        }
        exit;
    }

    /**
     * Xem chi tiết phân công xe
     */
    public function view()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $assignment = $this->vehicleAssignmentModel->getById($id);
        
        if (!$assignment) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $title = 'Chi tiết Xe: ' . $assignment['license_plate'];
        $view = 'tour-vehicles/view';
        require_once PATH_VIEW_MAIN;
    }
}
