<?php

class DashboardController
{
    private $tourModel;
    private $userModel;
    private $tourCostModel;
    private $tourAssignmentModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
        $this->userModel = new UserModel();
        $this->tourCostModel = new TourCostModel();
        $this->tourAssignmentModel = new TourAssignmentModel();
    }

    public function index()
    {
        requireLogin();
        
        $user = getCurrentUser();
        $title = 'Trang chủ';

        if (isAdmin()) {
            $totalTours = count($this->tourModel->getAll());
            $upcomingTours = count($this->tourModel->getByStatus('upcoming'));
            $ongoingTours = count($this->tourModel->getByStatus('ongoing'));
            $completedTours = count($this->tourModel->getByStatus('completed'));
            $totalGuides = count($this->userModel->getGuides());
            
            $recentTours = array_slice($this->tourModel->getAll(), 0, 5);
            
            $view = 'dashboard/admin';
        } else {
            $myTours = $this->tourAssignmentModel->getByGuide($user['id']);
            $upcomingTours = array_filter($myTours, fn($t) => $t['status'] === 'upcoming');
            $ongoingTours = array_filter($myTours, fn($t) => $t['status'] === 'ongoing');
            $completedTours = array_filter($myTours, fn($t) => $t['status'] === 'completed');
            
            // Thống kê từ HDV (cá nhân)
            $dailyLogModel = new TourDailyLogModel();
            $incidentModel = new TourIncidentModel();
            $feedbackModel = new TourFeedbackModel();

            $userId = $user['id'];
            $totalDailyLogs = count($dailyLogModel->getByGuide($userId));
            $totalIncidents = count($incidentModel->getByReporter($userId));

            $guideFeedbacks = $feedbackModel->getByGuide($userId);
            $totalFeedbacks = count($guideFeedbacks);
            $avgRating = 0;
            if (!empty($guideFeedbacks)) {
                $sum = 0;
                $cnt = 0;
                foreach ($guideFeedbacks as $fb) {
                    if (!empty($fb['rating'])) {
                        $sum += $fb['rating'];
                        $cnt++;
                    }
                }
                $avgRating = $cnt > 0 ? round($sum / $cnt, 1) : 0;
            }

            $view = 'dashboard/guide';
        }

        require_once PATH_VIEW_MAIN;
    }
}

