<?php
function isLoggedIn()
{
    return isset($_SESSION['user']);
}
function getCurrentUser()
{
    return $_SESSION['user'] ?? null;
}
function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}
function isGuide()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'guide';
}
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '?action=auth/login');
        exit;
    }
}
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
        exit;
    }
}
function requireGuide()
{
    requireLogin();
    if (!isGuide()) {
        header('Location: ' . BASE_URL . '?action=dashboard');
        exit;
    }
}

