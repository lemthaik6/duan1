<?php 

// ==================== SESSION CONFIG ====================
// Set session timeout 30 minutes
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.cookie_lifetime', 1800);
session_start();

// Regenerate session ID periodically (every request)
if (empty($_SESSION['session_created'])) {
    $_SESSION['session_created'] = time();
} elseif (time() - $_SESSION['session_created'] > 300) {
    // Regenerate every 5 minutes
    session_regenerate_id(true);
    $_SESSION['session_created'] = time();
}

// Check session timeout (30 minutes)
if (isset($_SESSION['user']) && isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 1800) {
        session_destroy();
        header('Location: ' . (defined('BASE_URL') ? BASE_URL . '?action=auth/login' : '/duan1/?action=auth/login'));
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

spl_autoload_register(function ($class) {    
    $fileName = "$class.php";

    $fileModel              = PATH_MODEL . $fileName;
    $fileController         = PATH_CONTROLLER . $fileName;

    if (is_readable($fileModel)) {
        require_once $fileModel;
    } 
    else if (is_readable($fileController)) {
        require_once $fileController;
    }
});

require_once './configs/env.php';
require_once './configs/helper.php';
require_once './configs/auth.php';

require_once './routes/index.php';
