<?php

class DailyLogController
{
    private $dailyLogModel;
    private $tourModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->dailyLogModel = new TourDailyLogModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
    }

    /**
     * Danh sách nhật ký
     */
    public function index()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tourId = $_GET['tour_id'] ?? 0;
        $logs = [];
        $tour = null;

        if ($tourId) {
            $tour = $this->tourModel->getById($tourId);
            if ($tour) {
                $logs = $this->dailyLogModel->getByTour($tourId);
            }
        }

        $myTours = $this->assignmentModel->getByGuide($user['id']);
        
        $title = 'Nhật ký Tour';
        $view = 'daily-logs/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo nhật ký mới
     */
    public function create()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tourId || !$tour) {
            header('Location: ' . BASE_URL . '?action=daily-logs/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'guide_id' => $user['id'],
                'date' => $_POST['date'] ?? date('Y-m-d'),
                'activities' => $_POST['activities'] ?? '',
                'customer_status' => $_POST['customer_status'] ?? '',
                'weather' => $_POST['weather'] ?? '',
                'traffic' => $_POST['traffic'] ?? '',
                'notes' => $_POST['notes'] ?? ''
            ];

            if (empty($data['activities'])) {
                $error = 'Vui lòng điền hoạt động trong ngày';
            } else {
                if ($this->dailyLogModel->createOrUpdate($data)) {
                    $success = 'Ghi nhật ký thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=daily-logs/index&tour_id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi ghi nhật ký';
                }
            }
        }

        $title = 'Ghi nhật ký';
        $view = 'daily-logs/create';
        require_once PATH_VIEW_MAIN;
    }
}

