<?php

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Thông tin cá nhân
     */
    public function index()
    {
        requireLogin();
        
        $user = getCurrentUser();
        $user = $this->userModel->getById($user['id']); // Refresh data
        
        $title = 'Thông tin cá nhân';
        $view = 'profile/index';
        require_once PATH_VIEW_MAIN;
    }
}

