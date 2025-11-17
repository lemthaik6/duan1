<div class="login-container">
    <div class="row g-0 h-100">
        <!-- Sidebar trái - Form đăng nhập -->
        <div class="col-md-4 login-sidebar">
            <div class="login-content">
                <!-- Tiêu đề dọc -->
                <div class="login-title-vertical">
                    <h1 class="fw-bold">Hệ thống Quản lý tour nội bộ</h1>
                </div>

                <!-- Form đăng nhập -->
                <div class="login-form-wrapper">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="login-form">
                        <div class="mb-4">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Nhập" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Nhập" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            <i class="bi bi-arrow-right"></i> Đăng nhập
                        </button>
                    </form>

                    <!-- Thông tin đăng nhập mẫu -->
                    <div class="login-credentials mt-4">
                        <small class="text-muted">
                            <strong>Admin:</strong> admin / admin123<br>
                            <strong>HDV:</strong> guide1 / guide123
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main area phải - Gradient background -->
        <div class="col-md-8 login-main-area">
        </div>
    </div>
</div>

<style>
    .login-container {
        min-height: 100vh;
        overflow: hidden;
    }

    .login-container .row {
        min-height: 100vh;
    }

    /* Sidebar trái */
    .login-sidebar {
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
    }

    .login-content {
        width: 100%;
        max-width: 400px;
    }

    .login-title-vertical {
        margin-bottom: 60px;
        text-align: center;
    }

    .login-title-vertical h1 {
        font-size: 2rem;
        color: #667eea;
        margin: 0;
        text-align: center;
        line-height: 1.4;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .login-form-wrapper {
        width: 100%;
    }

    .login-form .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .login-form .input-group-text {
        background: #f8f9fa;
        border-right: none;
        color: #667eea;
    }

    .login-form .form-control {
        border-left: none;
        padding: 12px 15px;
    }

    .login-form .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 16px 24px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin: 30px auto;
        gap: 10px;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-login i {
        font-size: 1.3rem;
    }

    .login-credentials {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    /* Main area phải - Gradient */
    .login-main-area {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 0;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .login-sidebar {
            padding: 20px;
        }

        .login-title-vertical h1 {
            font-size: 1.8rem;
            margin-bottom: 40px;
        }

        .btn-login {
            height: 80px;
            font-size: 1rem;
        }

        .login-main-area {
            min-height: 300px;
        }
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

