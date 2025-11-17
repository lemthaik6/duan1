<?php

class BookingController
{
    private $bookingModel;
    private $tourModel;
    private $customerModel;
    private $statusHistoryModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new TourCustomerModel();
    }

    /**
     * Danh sách booking (Admin)
     */
    public function index()
    {
        requireAdmin();
        
        $filters = [];
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['tour_id'])) {
            $filters['tour_id'] = $_GET['tour_id'];
        }

        $bookings = $this->bookingModel->getAll($filters);
        $tours = $this->tourModel->getAll();
        
        $title = 'Quản lý Booking';
        $view = 'bookings/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo booking mới
     */
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tourId = $_POST['tour_id'] ?? 0;
            $numberOfGuests = $_POST['number_of_guests'] ?? 1;
            $totalAmount = $_POST['total_amount'] ?? 0;
            $depositAmount = $_POST['deposit_amount'] ?? 0;

            $data = [
                'tour_id' => $tourId,
                'booking_type' => $numberOfGuests > 2 ? 'group' : 'individual',
                'customer_name' => $_POST['customer_name'] ?? '',
                'customer_phone' => $_POST['customer_phone'] ?? '',
                'customer_email' => $_POST['customer_email'] ?? '',
                'customer_address' => $_POST['customer_address'] ?? '',
                'number_of_guests' => $numberOfGuests,
                'total_amount' => $totalAmount,
                'deposit_amount' => $depositAmount,
                'remaining_amount' => $totalAmount - $depositAmount,
                'status' => $depositAmount > 0 ? 'deposited' : 'pending',
                'booking_date' => $_POST['booking_date'] ?? date('Y-m-d'),
                'special_requests' => $_POST['special_requests'] ?? '',
                'notes' => $_POST['notes'] ?? '',
                'created_by' => getCurrentUser()['id']
            ];

            if (empty($data['customer_name']) || empty($tourId)) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            } else {
                if ($this->bookingModel->create($data)) {
                    $success = 'Tạo booking thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=bookings/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo booking';
                }
            }
        }

        $tours = $this->tourModel->getAll(['status' => 'upcoming']);
        
        $title = 'Tạo Booking mới';
        $view = 'bookings/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết booking
     */
    public function view()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        $statusHistory = $this->bookingModel->getStatusHistory($id);
        $customers = $this->customerModel->getByTour($booking['tour_id']);
        
        $title = 'Chi tiết Booking: ' . $booking['booking_code'];
        $view = 'bookings/view';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Cập nhật trạng thái booking
     */
    public function updateStatus()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';

            if ($this->bookingModel->updateStatus($id, $status, getCurrentUser()['id'], $notes)) {
                $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
            }
            
            header('Location: ' . BASE_URL . '?action=bookings/view&id=' . $id);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=bookings/index');
        exit;
    }
}



