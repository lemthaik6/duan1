<?php

/**
 * Kiểm tra đăng nhập
 */
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

/**
 * Lấy thông tin user hiện tại
 */
function getCurrentUser()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Kiểm tra quyền admin
 */
function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Kiểm tra quyền hướng dẫn viên
 */
function isGuide()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'guide';
}

/**
 * Yêu cầu đăng nhập
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=auth/login');
        exit;
    }
}

/**
 * Yêu cầu quyền admin
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
        exit;
    }
}

/**
 * Yêu cầu quyền hướng dẫn viên
 */
function requireGuide()
{
    requireLogin();
    if (!isGuide()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
        exit;
    }
}

