<?php

$action = $_GET['action'] ?? '/';

// Xử lý route gốc
if ($action === '/') {
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
    } else {
        header('Location: ' . BASE_URL . '?action=auth/login');
    }
    exit;
}

// Tách action thành controller và method
$parts = explode('/', $action);
$controllerName = $parts[0] ?? 'dashboard';
$method = $parts[1] ?? 'index';

// Mapping controllers
$controllers = [
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'tours' => 'TourController',
    'guides' => 'GuideController',
    'vehicles' => 'VehicleController',
    'costs' => 'CostController',
    'daily-logs' => 'DailyLogController',
    'incidents' => 'IncidentController',
    'reports' => 'ReportController',
    'profile' => 'ProfileController',
];

// Kiểm tra và xử lý controller
if (isset($controllers[$controllerName])) {
    $controllerClass = $controllers[$controllerName];
    
    if (class_exists($controllerClass)) {
        try {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                // Nếu method không tồn tại, thử gọi index
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
    // Nếu không tìm thấy controller, redirect về login hoặc dashboard
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=auth/login');
    } else {
        header('Location: ' . BASE_URL . '?action=dashboard');
    }
    exit;
}
