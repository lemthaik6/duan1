<?php

class ItineraryController
{
    private $itineraryModel;
    private $tourModel;

    public function __construct()
    {
        $this->itineraryModel = new ItineraryModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Danh sách lịch trình của tour
     */
    public function index()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }
        
        $itineraries = $this->itineraryModel->getByTour($tourId);
        
        $title = 'Quản lý Lịch trình - ' . $tour['name'];
        $view = 'itineraries/index';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Tạo lịch trình mới
     */
    public function create()
    {
        requireAdmin();
        
        $tourId = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $tourId,
                'day_number' => $_POST['day_number'] ?? 1,
                'date' => $_POST['date'] ?? '',
                'location' => $_POST['location'] ?? '',
                'activities' => $_POST['activities'] ?? '',
                'departure_time' => !empty($_POST['departure_time']) ? $_POST['departure_time'] : null,
                'notes' => $_POST['notes'] ?? null
            ];

            if (empty($data['date']) || empty($data['location']) || empty($data['activities'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc (Ngày, Địa điểm, Hoạt động)';
            } else {
                if ($this->itineraryModel->create($data)) {
                    $success = 'Thêm lịch trình thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=itineraries/index&tour_id=' . $tourId);
                } else {
                    $error = 'Có lỗi xảy ra khi thêm lịch trình';
                }
            }
        }
        
        $title = 'Thêm lịch trình mới - ' . $tour['name'];
        $view = 'itineraries/create';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Chỉnh sửa lịch trình
     */
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $itinerary = $this->itineraryModel->getById($id);
        
        if (!$itinerary) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $tour = $this->tourModel->getById($itinerary['tour_id']);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'day_number' => $_POST['day_number'] ?? 1,
                'date' => $_POST['date'] ?? '',
                'location' => $_POST['location'] ?? '',
                'activities' => $_POST['activities'] ?? '',
                'departure_time' => !empty($_POST['departure_time']) ? $_POST['departure_time'] : null,
                'notes' => $_POST['notes'] ?? null
            ];

            if (empty($data['date']) || empty($data['location']) || empty($data['activities'])) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc (Ngày, Địa điểm, Hoạt động)';
            } else {
                if ($this->itineraryModel->update($id, $data)) {
                    $success = 'Cập nhật lịch trình thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=itineraries/index&tour_id=' . $itinerary['tour_id']);
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật lịch trình';
                }
            }
        }
        
        $title = 'Chỉnh sửa lịch trình - ' . $tour['name'];
        $view = 'itineraries/edit';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xóa lịch trình
     */
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $itinerary = $this->itineraryModel->getById($id);
        
        if ($itinerary) {
            $tourId = $itinerary['tour_id'];
            if ($this->itineraryModel->delete($id)) {
                $_SESSION['success'] = 'Xóa lịch trình thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa lịch trình';
            }
            header('Location: ' . BASE_URL . '?action=itineraries/index&tour_id=' . $tourId);
        } else {
            header('Location: ' . BASE_URL . '?action=tours/index');
        }
        exit;
    }
}

