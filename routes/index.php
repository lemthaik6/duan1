<?php

$action = $_GET['action'] ?? '/';

if ($action === '/') {
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
    } else {
        header('Location: ' . BASE_URL . '?action=auth/login');
    }
    exit;
}

$parts = explode('/', $action);
$controllerName = $parts[0] ?? 'dashboard';
$method = $parts[1] ?? 'index';

$controllers = [
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'tours' => 'TourController',
    'guides' => 'GuideController',
    'vehicles' => 'VehicleController',
    'costs' => 'CostController',
    'daily-logs' => 'DailyLogController',
    'incidents' => 'IncidentController',
    'attendance' => 'AttendanceController',
    'customers' => 'CustomerController',
    'bookings' => 'BookingController',
    'hotel-rooms' => 'HotelRoomController',
    'categories' => 'CategoryController',
    'tour-policies' => 'TourPolicyController',
    'suppliers' => 'SupplierController',
    'tour-suppliers' => 'TourSupplierController',
    'reports' => 'ReportController',
    'profile' => 'ProfileController',
    'feedbacks' => 'FeedbackController',
    'itineraries' => 'ItineraryController',
];
if (isset($controllers[$controllerName])) {
    $controllerClass = $controllers[$controllerName];
    
    if (class_exists($controllerClass)) {
        try {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                if (method_exists($controller, 'index')) {
                    $controller->index();
                } else {
                    die("Method {$method} không tồn tại trong {$controllerClass}");
                }
            }
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    } else {
        die("Controller {$controllerClass} không tồn tại. Vui lòng kiểm tra lại.");
    }
} else {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=auth/login');
    } else {
        header('Location: ' . BASE_URL . '?action=dashboard');
    }
    exit;
}
