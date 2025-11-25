<?php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function login()
    {
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . '?action=dashboard');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin';
            } else {
                $user = $this->userModel->login($username, $password);
                
                if ($user) {
                    $_SESSION['user'] = $user;
                    header('Location: ' . BASE_URL . '?action=dashboard');
                    exit;
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
                }
            }
        }
        $title = 'Đăng nhập';
        $view = 'auth/login';
        require_once PATH_VIEW_MAIN;
    }
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '?action=auth/login');
        exit;
    }
}

