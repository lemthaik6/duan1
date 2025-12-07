<?php

class ReportController
{
    private $tourModel;
    private $userModel;
    private $bookingModel;
    private $costModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
        $this->costModel = new TourCostModel();
    }
    public function index()
    {
        requireAdmin();
        
        $year = intval($_GET['year'] ?? date('Y'));
        $tourId = !empty($_GET['tour_id']) ? intval($_GET['tour_id']) : null;
        $month = !empty($_GET['month']) ? intval($_GET['month']) : null;
        
        $stats = $this->tourModel->getStatsByMonth($year);
        $tours = $this->tourModel->getAll();
        
        // Xây dựng filter cho tính lợi nhuận
        $profitFilters = [];
        if ($tourId) {
            $profitFilters['tour_id'] = $tourId;
        }
        if ($month) {
            $profitFilters['date_from'] = "$year-$month-01";
            $profitFilters['date_to'] = "$year-$month-" . date('t', strtotime("$year-$month-01"));
        }
        
        // Tính lợi nhuận theo công thức: profit = (number_of_guests * price_per_guest) - (fixed_cost + (variable_cost_per_guest * number_of_guests))
        $profitData = $this->costModel->calculateProfitByFormulaWithFilters($profitFilters);
        
        $totalRevenue = $profitData['total_revenue'];
        $totalCost = $profitData['fixed_cost'] + $profitData['total_variable_cost'];
        $profit = $profitData['profit'];
        $profitMargin = $profitData['profit_margin'];
        
        // Thống kê booking
        $bookingStats = [
            'total' => $this->bookingModel->countByStatus(null),
            'pending' => $this->bookingModel->countByStatus('pending'),
            'deposited' => $this->bookingModel->countByStatus('deposited'),
            'confirmed' => $this->bookingModel->countByStatus('confirmed'),
            'completed' => $this->bookingModel->countByStatus('completed'),
            'cancelled' => $this->bookingModel->countByStatus('cancelled'),
        ];
        
        // Thống kê từ HDV
        $dailyLogModel = new TourDailyLogModel();
        $incidentModel = new TourIncidentModel();
        $feedbackModel = new TourFeedbackModel();
        $assignmentModel = new TourAssignmentModel();
        
        $guideStats = [
            'total_guides' => count($this->userModel->getAll('guide')),
            'active_assignments' => count($assignmentModel->getByTour(null)),
            'total_daily_logs' => 0,
            'total_incidents' => 0,
            'total_feedbacks' => 0,
            'avg_rating' => 0
        ];
        
        // Tính tổng số nhật ký, sự cố, phản hồi
        if ($tourId) {
            $guideStats['total_daily_logs'] = count($dailyLogModel->getByTour($tourId));
            $guideStats['total_incidents'] = count($incidentModel->getByTour($tourId));
            $guideStats['total_feedbacks'] = count($feedbackModel->getByTour($tourId));
        } else {
            // Lấy tất cả tours để tính tổng
            $allTours = $this->tourModel->getAll();
            foreach ($allTours as $tour) {
                $guideStats['total_daily_logs'] += count($dailyLogModel->getByTour($tour['id']));
                $guideStats['total_incidents'] += count($incidentModel->getByTour($tour['id']));
                $guideStats['total_feedbacks'] += count($feedbackModel->getByTour($tour['id']));
            }
        }
        
        // Tính điểm đánh giá trung bình
        $allFeedbacks = $feedbackModel->getAll();
        if (!empty($allFeedbacks)) {
            $totalRating = 0;
            $count = 0;
            foreach ($allFeedbacks as $fb) {
                if (!empty($fb['rating'])) {
                    $totalRating += $fb['rating'];
                    $count++;
                }
            }
            $guideStats['avg_rating'] = $count > 0 ? round($totalRating / $count, 1) : 0;
        }
        
        // Thống kê theo tháng (doanh thu)
        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthFilters = [
                'date_from' => "$year-$m-01",
                'date_to' => "$year-$m-" . date('t', strtotime("$year-$m-01"))
            ];
            $monthlyRevenue[$m] = $this->bookingModel->getTotalRevenue($monthFilters);
        }
        
        $title = 'Báo cáo & Thống kê';
        $view = 'reports/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết danh sách hướng dẫn viên
     */
    public function guidesList()
    {
        requireAdmin();
        
        $userModel = new UserModel();
        $guideUsers = $userModel->getAll('guide');
        
        // Lấy thống kê cho mỗi HDV
        $guides = [];
        foreach ($guideUsers as $guide) {
            $dailyLogModel = new TourDailyLogModel();
            $incidentModel = new TourIncidentModel();
            $feedbackModel = new TourFeedbackModel();
            $assignmentModel = new TourAssignmentModel();
            
            // Lấy tất cả tour của HDV
            $guideAssignments = $assignmentModel->getByGuide($guide['id']);
            $tourIds = array_column($guideAssignments, 'tour_id');
            
            $totalDailyLogs = 0;
            $totalIncidents = 0;
            $totalFeedbacks = 0;
            $totalRating = 0;
            $feedbackCount = 0;
            
            if (!empty($tourIds)) {
                foreach ($tourIds as $tourId) {
                    $totalDailyLogs += count($dailyLogModel->getByTour($tourId));
                    $totalIncidents += count($incidentModel->getByTour($tourId));
                    $tourFeedbacks = $feedbackModel->getByTour($tourId);
                    $totalFeedbacks += count($tourFeedbacks);
                    
                    foreach ($tourFeedbacks as $fb) {
                        if (!empty($fb['rating'])) {
                            $totalRating += $fb['rating'];
                            $feedbackCount++;
                        }
                    }
                }
            }
            
            $avgRating = $feedbackCount > 0 ? round($totalRating / $feedbackCount, 1) : 0;
            
            $guides[] = [
                'id' => $guide['id'],
                'full_name' => $guide['full_name'],
                'email' => $guide['email'],
                'phone' => $guide['phone'],
                'total_daily_logs' => $totalDailyLogs,
                'total_incidents' => $totalIncidents,
                'total_feedbacks' => $totalFeedbacks,
                'avg_rating' => $avgRating,
                'tour_count' => count($tourIds)
            ];
        }
        
        $title = 'Danh sách Hướng dẫn viên';
        $view = 'reports/guides-list';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết nhật ký hành trình
     */
    public function dailyLogs()
    {
        requireAdmin();
        
        $tourId = !empty($_GET['tour_id']) ? intval($_GET['tour_id']) : null;
        $guideId = !empty($_GET['guide_id']) ? intval($_GET['guide_id']) : null;
        
        $dailyLogModel = new TourDailyLogModel();
        
        $dailyLogs = [];
        if ($tourId) {
            $dailyLogs = $dailyLogModel->getByTour($tourId);
        } else {
            // Lấy tất cả nhật ký từ tất cả tour
            $allTours = $this->tourModel->getAll();
            foreach ($allTours as $tour) {
                $tourDailyLogs = $dailyLogModel->getByTour($tour['id']);
                $dailyLogs = array_merge($dailyLogs, $tourDailyLogs);
            }
            // Sắp xếp theo ngày giảm dần
            usort($dailyLogs, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        $tours = $this->tourModel->getAll();
        
        $title = 'Nhật ký Hành trình';
        $view = 'reports/daily-logs';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết sự cố
     */
    public function incidents()
    {
        requireAdmin();
        
        $tourId = !empty($_GET['tour_id']) ? intval($_GET['tour_id']) : null;
        $status = !empty($_GET['status']) ? $_GET['status'] : null;
        
        $incidentModel = new TourIncidentModel();
        
        $incidents = [];
        if ($tourId) {
            $incidents = $incidentModel->getByTour($tourId);
        } else {
            // Lấy tất cả sự cố từ tất cả tour
            $allTours = $this->tourModel->getAll();
            foreach ($allTours as $tour) {
                $tourIncidents = $incidentModel->getByTour($tour['id']);
                $incidents = array_merge($incidents, $tourIncidents);
            }
            // Sắp xếp theo ngày giảm dần
            usort($incidents, function($a, $b) {
                return strtotime($b['incident_date'] ?? $b['created_at']) - strtotime($a['incident_date'] ?? $a['created_at']);
            });
        }
        
        // Lọc theo status nếu có
        if ($status && $status !== 'all') {
            $incidents = array_filter($incidents, function($incident) use ($status) {
                return $incident['status'] === $status;
            });
        }
        
        $tours = $this->tourModel->getAll();
        
        $title = 'Sự cố';
        $view = 'reports/incidents';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xem chi tiết báo cáo đánh giá
     */
    public function feedbacks()
    {
        requireAdmin();
        
        $tourId = !empty($_GET['tour_id']) ? intval($_GET['tour_id']) : null;
        $guideId = !empty($_GET['guide_id']) ? intval($_GET['guide_id']) : null;
        $rating = !empty($_GET['rating']) ? intval($_GET['rating']) : null;
        
        $feedbackModel = new TourFeedbackModel();
        
        $feedbacks = [];
        if ($tourId) {
            $feedbacks = $feedbackModel->getByTour($tourId);
        } else if ($guideId) {
            $feedbacks = $feedbackModel->getByGuide($guideId);
        } else {
            $feedbacks = $feedbackModel->getAll();
        }
        
        // Lọc theo rating nếu có
        if ($rating && $rating > 0) {
            $feedbacks = array_filter($feedbacks, function($feedback) use ($rating) {
                return (int)$feedback['rating'] === $rating;
            });
        }
        
        $tours = $this->tourModel->getAll();
        $users = $this->userModel->getAll('guide');
        
        // Tính thống kê
        $stats = [
            'total' => count($feedbacks),
            'avg_rating' => 0,
            'rating_5' => 0,
            'rating_4' => 0,
            'rating_3' => 0,
            'rating_2' => 0,
            'rating_1' => 0,
        ];
        
        $totalRating = 0;
        $ratingCount = 0;
        foreach ($feedbacks as $fb) {
            if (!empty($fb['rating'])) {
                $totalRating += $fb['rating'];
                $ratingCount++;
                $stats['rating_' . $fb['rating']]++;
            }
        }
        
        $stats['avg_rating'] = $ratingCount > 0 ? round($totalRating / $ratingCount, 1) : 0;
        
        $title = 'Báo cáo Đánh giá';
        $view = 'reports/feedbacks';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa nhật ký hành trình
     */
    public function deleteLog()
    {
        requireAdmin();
        
        $logId = !empty($_POST['log_id']) ? intval($_POST['log_id']) : null;
        
        if (!$logId) {
            $_SESSION['error'] = 'Không tìm thấy nhật ký';
            header('Location: ' . BASE_URL . '?action=reports/daily-logs');
            exit;
        }
        
        $dailyLogModel = new TourDailyLogModel();
        
        if ($dailyLogModel->delete($logId)) {
            $_SESSION['success'] = 'Xóa nhật ký thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa nhật ký';
        }
        
        header('Location: ' . BASE_URL . '?action=reports/daily-logs');
        exit;
    }

    /**
     * Xóa sự cố
     */
    public function deleteIncident()
    {
        requireAdmin();
        
        $incidentId = !empty($_POST['incident_id']) ? intval($_POST['incident_id']) : null;
        
        if (!$incidentId) {
            $_SESSION['error'] = 'Không tìm thấy sự cố';
            header('Location: ' . BASE_URL . '?action=reports/incidents');
            exit;
        }
        
        $incidentModel = new TourIncidentModel();
        
        if ($incidentModel->delete($incidentId)) {
            $_SESSION['success'] = 'Xóa sự cố thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa sự cố';
        }
        
        header('Location: ' . BASE_URL . '?action=reports/incidents');
        exit;
    }

    /**
     * Xóa đánh giá
     */
    public function deleteFeedback()
    {
        requireAdmin();
        
        $feedbackId = !empty($_POST['feedback_id']) ? intval($_POST['feedback_id']) : null;
        
        if (!$feedbackId) {
            $_SESSION['error'] = 'Không tìm thấy đánh giá';
            header('Location: ' . BASE_URL . '?action=reports/feedbacks');
            exit;
        }
        
        $feedbackModel = new TourFeedbackModel();
        
        if ($feedbackModel->delete($feedbackId)) {
            $_SESSION['success'] = 'Xóa đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa đánh giá';
        }
        
        header('Location: ' . BASE_URL . '?action=reports/feedbacks');
        exit;
    }
}

