<?php

class HotelRoomController
{
    private $roomModel;
    private $tourModel;
    private $customerModel;

    public function __construct()
    {
        $this->roomModel = new HotelRoomAssignmentModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new TourCustomerModel();
    }

    /**
     * Quản lý phân phòng khách sạn
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

        $rooms = $this->roomModel->getByTour($tourId);
        $customers = $this->customerModel->getByTour($tourId);
        $stats = $this->roomModel->getStatsByTour($tourId);
        
        $title = 'Phân phòng khách sạn - ' . $tour['name'];
        $view = 'hotel-rooms/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo/cập nhật phân phòng
     */
    public function save()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'] ?? 0,
                'customer_id' => $_POST['customer_id'] ?? 0,
                'hotel_name' => $_POST['hotel_name'] ?? '',
                'room_number' => $_POST['room_number'] ?? '',
                'room_type' => $_POST['room_type'] ?? 'double',
                'check_in_date' => $_POST['check_in_date'] ?? '',
                'check_out_date' => $_POST['check_out_date'] ?? '',
                'notes' => $_POST['notes'] ?? '',
                'assigned_by' => getCurrentUser()['id']
            ];

            if (empty($data['hotel_name']) || empty($data['check_in_date']) || empty($data['check_out_date'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->roomModel->upsert($data)) {
                    $_SESSION['success'] = 'Phân phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi phân phòng';
                }
            }
            
            header('Location: ' . BASE_URL . '?action=hotel-rooms/index&tour_id=' . $data['tour_id']);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }

    /**
     * Xóa phân phòng
     */
    public function delete()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $tourId = $_POST['tour_id'] ?? 0;

            if ($this->roomModel->delete($id)) {
                $_SESSION['success'] = 'Xóa phân phòng thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa';
            }
            
            header('Location: ' . BASE_URL . '?action=hotel-rooms/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
}

