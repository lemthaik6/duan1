<?php

class AttendanceController
{
    private $attendanceModel;
    private $customerModel;
    private $tourModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->attendanceModel = new CustomerAttendanceModel();
        $this->customerModel = new TourCustomerModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
    }
    public function index()
    {
        requireGuide();
        $tourId = $_GET['tour_id'] ?? 0;
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }
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
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_POST['customer_id'] ?? 0;
            $status = $_POST['status'] ?? 'present';
            $notes = $_POST['notes'] ?? '';
            $checkInTime = $_POST['check_in_time'] ?? date('Y-m-d H:i:s');
            if (empty($customerId)) {
                $error = 'Vui lòng chọn khách';
            } else {
                $data = [
                    'tour_id' => $tourId,
                    'customer_id' => $customerId,
                    'date' => $date,
                    'status' => $status,
                    'check_in_time' => $checkInTime,
                    'notes' => $notes,
                    'recorded_by' => $user['id']
                ];

                if ($this->attendanceModel->upsert($data)) {
                    $success = 'Điểm danh thành công!';
                } else {
                    $error = 'Có lỗi xảy ra khi điểm danh';
                }
            }
        }

        $customers = $this->customerModel->getByTour($tourId);
        $attendance = $this->attendanceModel->getByTourAndDate($tourId, $date);
        $attendanceMap = [];
        foreach ($attendance as $att) {
            $attendanceMap[$att['customer_id']] = $att;
        }

        $stats = $this->attendanceModel->getStatsByTour($tourId, $date);
        
        $title = 'Điểm danh khách - ' . $tour['name'];
        $view = 'attendance/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Điểm danh tất cả khách hàng có trạng thái "Có mặt"
     */
    public function attendanceAll()
    {
        requireGuide();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại';
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }

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
            $_SESSION['error'] = 'Bạn không được phân công tour này';
            header('Location: ' . BASE_URL . '?action=tours/my-tours');
            exit;
        }

        $customers = $this->customerModel->getByTour($tourId);
        $attendance = $this->attendanceModel->getByTourAndDate($tourId, $date);
        $attendanceMap = [];
        foreach ($attendance as $att) {
            $attendanceMap[$att['customer_id']] = $att;
        }

        $successCount = 0;
        $currentTime = date('Y-m-d H:i:s');

        // Điểm danh tất cả khách chưa được điểm danh
        foreach ($customers as $customer) {
            // Nếu chưa điểm danh, thêm điểm danh với trạng thái "Có mặt"
            if (!isset($attendanceMap[$customer['id']])) {
                $data = [
                    'tour_id' => $tourId,
                    'customer_id' => $customer['id'],
                    'date' => $date,
                    'status' => 'present',
                    'check_in_time' => $currentTime,
                    'notes' => 'Điểm danh tự động',
                    'recorded_by' => $user['id']
                ];

                if ($this->attendanceModel->upsert($data)) {
                    $successCount++;
                }
            }
        }

        $_SESSION['success'] = "Đã điểm danh $successCount khách hàng!";
        header('Location: ' . BASE_URL . '?action=attendance/index&tour_id=' . $tourId . '&date=' . $date);
        exit;
    }
}

