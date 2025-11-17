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

    /**
     * Báo cáo và thống kê
     */
    public function index()
    {
        requireAdmin();
        
        $year = $_GET['year'] ?? date('Y');
        $tourId = $_GET['tour_id'] ?? null;
        
        $stats = $this->tourModel->getStatsByMonth($year);
        $tours = $this->tourModel->getAll();
        
        // Tính tổng doanh thu
        $revenueFilters = [];
        if ($tourId) {
            $revenueFilters['tour_id'] = $tourId;
        }
        $totalRevenue = $this->bookingModel->getTotalRevenue($revenueFilters);
        
        // Tính tổng chi phí (bao gồm giá gốc nội bộ)
        $totalCost = $this->costModel->getTotalCostByTour($tourId, true);
        
        // Tính lợi nhuận
        $profit = $totalRevenue - $totalCost;
        
        // Thống kê booking
        $bookingStats = [
            'pending' => $this->bookingModel->countByStatus('pending'),
            'deposited' => $this->bookingModel->countByStatus('deposited'),
            'confirmed' => $this->bookingModel->countByStatus('confirmed'),
            'completed' => $this->bookingModel->countByStatus('completed'),
            'cancelled' => $this->bookingModel->countByStatus('cancelled'),
        ];
        
        $title = 'Báo cáo & Thống kê';
        $view = 'reports/index';
        require_once PATH_VIEW_MAIN;
    }
}

