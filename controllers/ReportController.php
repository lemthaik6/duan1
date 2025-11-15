<?php

class ReportController
{
    private $tourModel;
    private $userModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
        $this->userModel = new UserModel();
    }

    /**
     * Báo cáo và thống kê
     */
    public function index()
    {
        requireAdmin();
        
        $year = $_GET['year'] ?? date('Y');
        $stats = $this->tourModel->getStatsByMonth($year);
        $tours = $this->tourModel->getAll();
        
        $title = 'Báo cáo & Thống kê';
        $view = 'reports/index';
        require_once PATH_VIEW_MAIN;
    }
}

