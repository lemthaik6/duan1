<?php

class HomeController
{
    public function index() 
    {
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . '?action=dashboard');
        } else {
            header('Location: ' . BASE_URL . '?action=auth/login');
        }
        exit;
    }
}