<?php

class HomeController
{
    public function index() 
    {
        // Redirect về login hoặc dashboard tùy vào trạng thái đăng nhập
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . '?action=dashboard');
        } else {
            header('Location: ' . BASE_URL . '?action=auth/login');
        }
        exit;
    }
}