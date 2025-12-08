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
                        <!-- CSRF Token -->
                        <?= csrfTokenField() ?>
                        
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
                </div>
            </div>
        </div>

        <!-- Main area phải - Gradient background -->
        <div class="col-md-8 login-main-area">
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    
    .login-container {
        min-height: 100vh;
        overflow: hidden;
        background: #ffffff;
    }

    .login-container .row {
        min-height: 100vh;
    }

    /* Sidebar trái */
    .login-sidebar {
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 40px;
        position: relative;
        box-shadow: 0 20px 50px rgba(59, 130, 246, 0.15);
    }

    .login-content {
        width: 100%;
        max-width: 420px;
    }

    .login-title-vertical {
        margin-bottom: 50px;
        text-align: center;
    }

    .login-title-vertical h1 {
        font-size: 2.2rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        text-align: center;
        line-height: 1.5;
        letter-spacing: -0.5px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .login-form-wrapper {
        width: 100%;
    }

    .login-form .form-label {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 10px;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .login-form .input-group {
        margin-bottom: 24px;
    }

    .login-form .input-group-text {
        background: white;
        border: 2px solid #e5e7eb;
        border-right: none;
        color: #1e90ff;
        font-weight: 600;
        padding: 12px 14px;
    }

    .login-form .form-control {
        border: 2px solid #e5e7eb;
        border-left: none;
        padding: 12px 16px;
        font-weight: 500;
        border-radius: 0 8px 8px 0;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        color: #0f172a;
    }

    .login-form .form-control::placeholder {
        color: #9ca3af;
    }

    .login-form .form-control:focus {
        border-color: #1e90ff;
        border-left: none;
        box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
        background-color: white;
    }

    .login-form .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    .btn-login {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
        padding: 14px 28px;
        font-weight: 700;
        font-size: 1.05rem;
        border-radius: 10px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 30px;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-login:active {
        transform: translateY(-2px);
    }

    .btn-login i {
        font-size: 1.3rem;
    }

    .login-credentials {
        text-align: center;
        padding: 24px;
        background: #f0f8ff;
        border-radius: 10px;
        border-left: 4px solid #1e90ff;
    }

    .login-credentials small {
        font-weight: 500;
        line-height: 1.8;
        color: #334155;
    }

    .login-credentials strong {
        color: #1e90ff;
        font-weight: 700;
    }

    /* Main area phải - Gradient */
    .login-main-area {
        background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 0;
        overflow: hidden;
    }

    .login-main-area::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        bottom: -100px;
        right: -100px;
        animation: float 6s ease-in-out infinite;
    }

    .login-main-area::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        top: -150px;
        left: -50px;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(20px);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-sidebar {
            padding: 40px 20px;
            box-shadow: none;
        }

        .login-content {
            max-width: 100%;
        }

        .login-title-vertical h1 {
            font-size: 1.8rem;
            margin-bottom: 35px;
        }

        .btn-login {
            padding: 12px 24px;
            font-size: 1rem;
        }

        .login-main-area {
            min-height: 200px;
        }

        .login-credentials {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .login-sidebar {
            padding: 30px 15px;
        }

        .login-title-vertical h1 {
            font-size: 1.5rem;
        }

        .login-form .form-label {
            font-size: 0.9rem;
        }

        .btn-login {
            padding: 11px 20px;
            font-size: 0.95rem;
            gap: 8px;
        }

        .btn-login i {
            font-size: 1.1rem;
        }
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

