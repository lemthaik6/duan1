<?php

class IncidentController
{
    private $incidentModel;
    private $tourModel;
    private $assignmentModel;

    public function __construct()
    {
        $this->incidentModel = new TourIncidentModel();
        $this->tourModel = new TourModel();
        $this->assignmentModel = new TourAssignmentModel();
    }

    public function index()
    {
        requireLogin();
        
        $tourId = isset($_GET['tour_id']) ? $_GET['tour_id'] : 0;
        $incidents = [];
        $tour = null;

        if ($tourId) {
            $tour = $this->tourModel->getById($tourId);
            if ($tour) {
                $incidents = $this->incidentModel->getByTour($tourId);
            }
        }

        if (isGuide()) {
            $user = getCurrentUser();
            $myTours = $this->assignmentModel->getByGuide($user['id']);
        } else {
            $myTours = [];
        }
        $title = 'Báo cáo Sự cố';
        if (isAdmin()) {
            $view = 'incidents/index-admin';
        } else {
            $view = 'incidents/index-guide';
        }
        
        require_once PATH_VIEW_MAIN;
    }
    public function create()
    {
        requireGuide();
        
        $user = getCurrentUser();
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tourId || !$tour) {
            header('Location: ' . BASE_URL . '?action=incidents/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'reported_by' => $user['id'],
                'incident_type' => $_POST['incident_type'] ?? 'other',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'incident_date' => $_POST['incident_date'] ?? date('Y-m-d'),
                'severity' => $_POST['severity'] ?? 'medium',
                'status' => 'reported'
            ];
            if (empty($data['title']) || empty($data['description'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->incidentModel->create($data)) {
                    $success = 'Báo cáo sự cố thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=incidents/index&tour_id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi báo cáo sự cố';
                }
            }
        }

        $title = 'Báo cáo Sự cố';
        $view = 'incidents/create';
        require_once PATH_VIEW_MAIN;
    }

    public function edit()
    {
        requireGuide();
        
        $incidentId = $_GET['id'] ?? 0;
        $tourId = $_GET['tour_id'] ?? 0;
        $incident = $this->incidentModel->getById($incidentId);
        $tour = $this->tourModel->getById($tourId);
        
        if (!$incident || !$tour || $incident['tour_id'] != $tourId) {
            header('Location: ' . BASE_URL . '?action=incidents/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $incidentId,
                'incident_type' => $_POST['incident_type'] ?? 'other',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'severity' => $_POST['severity'] ?? $incident['severity'],
                'resolution' => $_POST['resolution'] ?? '',
                'status' => $_POST['status'] ?? $incident['status']
            ];

            if (empty($data['title']) || empty($data['description'])) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->incidentModel->update($incidentId, $data)) {
                    $success = 'Cập nhật sự cố thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=incidents/index&tour_id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật sự cố';
                }
            }
        }

        $title = 'Sửa Báo cáo Sự cố';
        $view = 'incidents/edit';
        require_once PATH_VIEW_MAIN;
    }

    public function delete()
    {
        requireGuide();
        
        $incidentId = $_POST['id'] ?? 0;
        $tourId = $_POST['tour_id'] ?? 0;

        $incident = $this->incidentModel->getById($incidentId);
        
        if ($incident && $incident['tour_id'] == $tourId) {
            $this->incidentModel->delete($incidentId);
            header('Location: ' . BASE_URL . '?action=incidents/index&tour_id=' . $tourId);
            exit;
        }

        header('Location: ' . BASE_URL . '?action=incidents/index');
        exit;
    }
}