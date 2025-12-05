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
if (strpos($method, '-') !== false) {
    $methodParts = explode('-', $method);
    $method = $methodParts[0];
    for ($i = 1; $i < count($methodParts); $i++) {
        $method .= ucfirst($methodParts[$i]);
    }
}

$controllers = [
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'tours' => 'TourController',
    'guides' => 'GuideController',
    'vehicles' => 'VehicleController',
    'tour-vehicles' => 'TourVehicleAssignmentController',
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
    'chat' => 'ChatController',
];
if (isset($controllers[$controllerName])) {
    $controllerClass = $controllers[$controllerName];
    
    if (class_exists($controllerClass)) {
        try {
            $controller = new $controllerClass();
            
            // Validate method name - only alphanumeric and underscore
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $method)) {
                die('❌ Yêu cầu không hợp lệ');
            }
            
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                if (method_exists($controller, 'index')) {
                    $controller->index();
                } else {
                    // Log error without exposing details
                    error_log("Method {$method} not found in {$controllerClass}");
                    die('❌ Có lỗi hệ thống');
                }
            }
        } catch (Exception $e) {
            // Log error without exposing details
            error_log("Exception in route: " . $e->getMessage());
            die('❌ Có lỗi hệ thống. Vui lòng thử lại sau.');
        }
    } else {
        error_log("Controller {$controllerClass} not found");
        die('❌ Yêu cầu không hợp lệ');
    }
} else {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=auth/login');
    } else {
        header('Location: ' . BASE_URL . '?action=dashboard');
    }
    exit;
}
