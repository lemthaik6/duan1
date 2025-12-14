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
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['tour_id'])) {
            $filters['tour_id'] = intval($_GET['tour_id']);
        }
        if (!empty($_GET['booking_date_from'])) {
            $filters['booking_date_from'] = $_GET['booking_date_from'];
        }
        if (!empty($_GET['booking_date_to'])) {
            $filters['booking_date_to'] = $_GET['booking_date_to'];
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = trim($_GET['search']);
        }

        $bookings = $this->bookingModel->getAll($filters);
        $tours = $this->tourModel->getAll();
        
        // Thống kê nhanh
        $quickStats = [
            'total' => count($bookings),
            'pending' => 0,
            'deposited' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'total_revenue' => 0
        ];
        
        foreach ($bookings as $booking) {
            $quickStats[$booking['status']] = ($quickStats[$booking['status']] ?? 0) + 1;
            if (in_array($booking['status'], ['confirmed', 'completed'])) {
                $quickStats['total_revenue'] += $booking['total_amount'];
            }
        }
        
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
        $costModel = new TourCostModel();
        
        // Tính toán chi phí cho từng tour
        $tourCosts = [];
        foreach ($tours as $tour) {
            $tourCosts[$tour['id']] = [
                'internal_price' => $tour['internal_price'] ?? 0,
                'total_cost' => $costModel->getCostsOnly($tour['id']) ?? 0
            ];
        }
        
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
        
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }
        
        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        $statusHistory = $this->bookingModel->getStatusHistory($id);
        $customers = $this->customerModel->getByTour($booking['tour_id']);
        
        // Lấy thông tin từ HDV (nếu tour đã có HDV)
        $guideInfo = [];
        $dailyLogModel = new TourDailyLogModel();
        $incidentModel = new TourIncidentModel();
        $feedbackModel = new TourFeedbackModel();
        $costModel = new TourCostModel();
        $assignmentModel = new TourAssignmentModel();
        $assignments = $assignmentModel->getByTour($booking['tour_id']);
        if (!empty($assignments)) {
            $guide = $assignments[0];
            $guideInfo['guide'] = $guide;
            $guideInfo['daily_logs'] = $dailyLogModel->getByTour($booking['tour_id']);
            $guideInfo['incidents'] = $incidentModel->getByTour($booking['tour_id']);
            $guideInfo['feedbacks'] = $feedbackModel->getByTour($booking['tour_id']);
            $guideInfo['costs'] = $costModel->getByTour($booking['tour_id']);
        }
        
        $title = 'Chi tiết Booking: ' . $booking['booking_code'];
        $view = 'bookings/view';
        require_once PATH_VIEW_MAIN;
    }
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

    /**
     * Xuất báo giá hợp đồng
     */
    public function exportQuote()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        $tour = $this->tourModel->getById($booking['tour_id']);
        
        // Load trực tiếp view (không qua main layout)
        require_once PATH_VIEW . 'bookings/quote.php';
        exit;
    }

    /**
     * Xuất hóa đơn
     */
    public function exportInvoice()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        $tour = $this->tourModel->getById($booking['tour_id']);
        
        // Load trực tiếp view (không qua main layout)
        require_once PATH_VIEW . 'bookings/invoice.php';
        exit;
    }

    /**
     * Xóa booking
     */
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking cần xóa';
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location: ' . BASE_URL . '?action=bookings/index');
            exit;
        }

        if ($this->bookingModel->delete($id)) {
            $_SESSION['success'] = 'Xóa booking thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa booking';
        }
        
        header('Location: ' . BASE_URL . '?action=bookings/index');
        exit;
    }

    /**
     * API: Tính giá per guest cho tour (bao gồm chi phí dịch vụ chia đều)
     */
    public function calculatePricePerGuest()
    {
        header('Content-Type: application/json');
        
        $tourId = $_GET['tour_id'] ?? 0;
        if (!$tourId) {
            echo json_encode(['error' => 'Tour ID required']);
            exit;
        }

        $tour = $this->tourModel->getById($tourId);
        if (!$tour) {
            echo json_encode(['error' => 'Tour not found']);
            exit;
        }

        // Lấy chi phí dịch vụ
        $costModel = new TourCostModel();
        $totalCost = $costModel->getCostsOnly($tourId);

        // Lấy tổng số khách đã booking tour này
        $existingGuests = $this->bookingModel->getTotalGuestsByTour($tourId);

        // Nếu chưa có khách nào, giả định 1 khách để tính
        $tourGuestCount = max($existingGuests, 1);

        // Giá per khách = Giá tour nội bộ + (Chi phí dịch vụ ÷ Số khách tour)
        $internalPrice = $tour['internal_price'] ?? 0;
        $additionalCostPerGuest = $totalCost / $tourGuestCount;
        $pricePerGuest = $internalPrice + $additionalCostPerGuest;

        echo json_encode([
            'success' => true,
            'price_per_guest' => $pricePerGuest,
            'internal_price' => $internalPrice,
            'total_cost' => $totalCost,
            'tour_guest_count' => $tourGuestCount,
            'additional_cost_per_guest' => $additionalCostPerGuest
        ]);
        exit;
    }
}



