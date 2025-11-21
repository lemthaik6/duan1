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
        
        // Lọc doanh thu
        $revenueFilters = [];
        if ($tourId) {
            $revenueFilters['tour_id'] = $tourId;
        }
        if ($month) {
            $revenueFilters['date_from'] = "$year-$month-01";
            $revenueFilters['date_to'] = "$year-$month-" . date('t', strtotime("$year-$month-01"));
        }
        $totalRevenue = $this->bookingModel->getTotalRevenue($revenueFilters);
        
        // Tính tổng chi phí (bao gồm giá gốc nội bộ)
        $totalCost = $this->costModel->getTotalCostByTour($tourId, true);
        
        // Tính lợi nhuận
        $profit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($profit / $totalRevenue * 100) : 0;
        
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
}

