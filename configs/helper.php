<?php

// ==================== DEBUG ====================
if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

// ==================== FILE UPLOAD ====================
if (!function_exists('upload_file')) {
    /**
     * Upload file với validation MIME type
     * Whitelist: JPG, PNG, PDF
     */
    function upload_file($folder, $file, $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'])
    {
        // Validate file exists
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('File upload không hợp lệ');
        }

        // Validate file size (max 10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception('File quá lớn. Tối đa 10MB');
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            throw new Exception('Định dạng file không được hỗ trợ. Chỉ JPG, PNG, PDF');
        }

        // Generate safe filename (UUID + extension)
        $originalName = basename($file['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $safeFileName = bin2hex(random_bytes(16)) . '.' . $ext;
        
        $targetFile = $folder . '/' . $safeFileName;

        if (move_uploaded_file($file['tmp_name'], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }

        throw new Exception('Upload file không thành công!');
    }
}

// ==================== CSRF TOKEN ====================
if (!function_exists('generateCSRFToken')) {
    /**
     * Tạo CSRF token mới
     */
    function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('getCSRFToken')) {
    /**
     * Lấy CSRF token hiện tại
     */
    function getCSRFToken()
    {
        return $_SESSION['csrf_token'] ?? '';
    }
}

if (!function_exists('validateCSRFToken')) {
    /**
     * Validate CSRF token từ POST request
     */
    function validateCSRFToken($token)
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('csrfTokenField')) {
    /**
     * Hiển thị CSRF token field trong form
     */
    function csrfTokenField()
    {
        $token = generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
if (!function_exists('e')) {
    /**
     * Escape output để ngăn XSS
     */
    function e($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

// ==================== PASSWORD VALIDATION ====================
if (!function_exists('validatePasswordStrength')) {
    /**
     * Validate password strength
     * Requirements:
     * - Minimum 8 characters
     * - At least 1 uppercase letter
     * - At least 1 lowercase letter
     * - At least 1 number
     * - At least 1 special character
     * 
     * Returns array of errors (empty if valid)
     */
    function validatePasswordStrength($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ hoa (A-Z)';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ thường (a-z)';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 số (0-9)';
        }
        
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\\\\/]/', $password)) {
            $errors[] = 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (!@#$%^&*...)';
        }
        
        return $errors;
    }
}

if (!function_exists('isPasswordStrong')) {
    /**
     * Check if password is strong (returns boolean)
     */
    function isPasswordStrong($password)
    {
        return empty(validatePasswordStrength($password));
    }
}