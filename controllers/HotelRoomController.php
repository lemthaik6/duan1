<?php

class HotelRoomController
{
    private $roomModel;
    private $tourModel;
    private $customerModel;

    public function __construct()
    {
        $this->roomModel = new HotelRoomAssignmentModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new TourCustomerModel();
    }
    public function index()
    {
        requireAdmin();
        
        $tourId = intval($_GET['tour_id'] ?? 0);
        
        if ($tourId <= 0) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }
        
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?action=tours/index');
            exit;
        }

        $rooms = $this->roomModel->getByTour($tourId) ?: [];
        $customers = $this->customerModel->getByTour($tourId) ?: [];
        $stats = $this->roomModel->getStatsByTour($tourId);
        
        $title = 'Phân phòng khách sạn - ' . htmlspecialchars($tour['name'] ?? 'Tour');
        $view = 'hotel-rooms/index';
        require_once PATH_VIEW_MAIN;
    }
    public function save()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy và chuẩn hóa customer_ids (có thể là string nếu chỉ có 1 giá trị)
            $customerIds = $_POST['customer_ids'] ?? [];
            if (!is_array($customerIds)) {
                $customerIds = [$customerIds];
            }
            // Lọc bỏ giá trị rỗng và validate
            $customerIds = array_filter(array_map('intval', $customerIds), function($id) {
                return $id > 0;
            });
            
            $tourId = intval($_POST['tour_id'] ?? 0);
            $hotelName = trim($_POST['hotel_name'] ?? '');
            $roomType = $_POST['room_type'] ?? 'double';
            $checkInDate = $_POST['check_in_date'] ?? '';
            $checkOutDate = $_POST['check_out_date'] ?? '';
            $notes = trim($_POST['notes'] ?? '');
            
            $user = getCurrentUser();
            if (!$user || !isset($user['id'])) {
                $_SESSION['error'] = 'Phiên đăng nhập không hợp lệ';
                header('Location: ' . BASE_URL . '?action=tours/index');
                exit;
            }
            $assignedBy = $user['id'];

            // Validate
            if (empty($customerIds)) {
                $_SESSION['error'] = 'Vui lòng chọn ít nhất 1 khách hàng';
            } elseif (empty($hotelName)) {
                $_SESSION['error'] = 'Vui lòng nhập tên khách sạn';
            } elseif (empty($checkInDate) || empty($checkOutDate)) {
                $_SESSION['error'] = 'Vui lòng chọn ngày check-in và check-out';
            } elseif (!strtotime($checkInDate) || !strtotime($checkOutDate)) {
                $_SESSION['error'] = 'Ngày check-in/check-out không hợp lệ';
            } elseif (strtotime($checkInDate) >= strtotime($checkOutDate)) {
                $_SESSION['error'] = 'Ngày check-out phải sau ngày check-in';
            } elseif ($tourId <= 0) {
                $_SESSION['error'] = 'Tour không hợp lệ';
            } else {
                $tour = $this->tourModel->getById($tourId);
                if (!$tour) {
                    $_SESSION['error'] = 'Tour không tồn tại';
                } else {
                    $tourCustomers = $this->customerModel->getByTour($tourId);
                    $validCustomerIds = array_column($tourCustomers, 'id');
                    $invalidCustomers = array_diff($customerIds, $validCustomerIds);
                    
                    if (!empty($invalidCustomers)) {
                        $_SESSION['error'] = 'Một số khách hàng không thuộc tour này';
                    } else {
                        $successCount = 0;
                        $errorCount = 0;
                        foreach ($customerIds as $customerId) {
                            $data = [
                                'tour_id' => $tourId,
                                'customer_id' => $customerId,
                                'hotel_name' => $hotelName,
                                'room_type' => $roomType,
                                'check_in_date' => $checkInDate,
                                'check_out_date' => $checkOutDate,
                                'notes' => $notes,
                                'assigned_by' => $assignedBy
                            ];
                            
                            if ($this->roomModel->upsert($data)) {
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                        }
                        
                        if ($successCount > 0) {
                            $message = "Phân phòng thành công cho {$successCount} khách hàng";
                            if ($errorCount > 0) {
                                $message .= " ({$errorCount} khách hàng có lỗi)";
                            }
                            $_SESSION['success'] = $message;
                        } else {
                            $_SESSION['error'] = 'Có lỗi xảy ra khi phân phòng';
                        }
                    }
                }
            }         
            header('Location: ' . BASE_URL . '?action=hotel-rooms/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
    public function delete()
    {
        requireAdmin();  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $tourId = intval($_POST['tour_id'] ?? 0);

            if ($id <= 0) {
                $_SESSION['error'] = 'ID phân phòng không hợp lệ';
            } elseif ($tourId <= 0) {
                $_SESSION['error'] = 'Tour ID không hợp lệ';
            } else {
                $room = $this->roomModel->getById($id);
                if (!$room) {
                    $_SESSION['error'] = 'Phân phòng không tồn tại';
                } elseif ($room['tour_id'] != $tourId) {
                    $_SESSION['error'] = 'Phân phòng không thuộc tour này';
                } else {
                    if ($this->roomModel->delete($id)) {
                        $_SESSION['success'] = 'Xóa phân phòng thành công!';
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi xóa';
                    }
                }
            }
            
            header('Location: ' . BASE_URL . '?action=hotel-rooms/index&tour_id=' . $tourId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '?action=tours/index');
        exit;
    }
}

