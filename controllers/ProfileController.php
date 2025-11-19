<?php

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
    {
        requireLogin();
        
        $user = getCurrentUser();
        $user = $this->userModel->getById($user['id']);
        
        $title = 'Thông tin cá nhân';
        $view = 'profile/index';
        require_once PATH_VIEW_MAIN;
    }
}

