<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 45px 40px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }
        .brand-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px; color: white;
        }
        h2 { font-weight: 700; color: #1a1a2e; text-align: center; }
        .subtitle { color: #6b7280; text-align: center; font-size: 14px; margin-bottom: 30px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1.5px solid #e5e7eb; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.2); }
        .input-group-text { background: #f9fafb; border: 1.5px solid #e5e7eb; color: #6b7280; border-radius: 10px 0 0 10px; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none; border-radius: 10px;
            padding: 13px; font-weight: 700;
            width: 100%; color: white; font-size: 15px;
            transition: 0.3s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102,126,234,0.5); color: white; }
        .divider { text-align: center; color: #9ca3af; margin: 20px 0; position: relative; }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%; width: 42%; height: 1px; background: #e5e7eb;
        }
        .divider::before { left: 0; } .divider::after { right: 0; }
        .link-register { text-align: center; margin-top: 20px; }
        .link-register a, .forgot-link { color: #667eea; text-decoration: none; }
        .link-register a:hover, .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="brand-icon"><i class="fa-solid fa-store"></i></div>
        <h2>Đăng nhập</h2>
        <p class="subtitle">Chào mừng bạn quay lại MyStore</p>

        <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success rounded-3">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger rounded-3">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['auth_error'])): ?>
        <div class="alert alert-warning rounded-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <?= htmlspecialchars($_SESSION['auth_error']); unset($_SESSION['auth_error']); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-3">
            <?php foreach ($errors as $e): ?>
                <div><i class="fa-solid fa-circle-exclamation me-1"></i><?= htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/project1/User/loginPost">
            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary small">TÊN ĐĂNG NHẬP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control"
                           placeholder="Nhập tên đăng nhập"
                           value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary small">MẬT KHẨU</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control"
                           placeholder="Nhập mật khẩu" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"
                            style="border-radius:0 10px 10px 0; border-left:0;">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <div class="text-end mt-1">
                    <a href="/project1/User/forgotPassword" class="forgot-link small">Quên mật khẩu?</a>
                </div>
            </div>
            <div class="mb-4 d-flex align-items-center gap-2">
                <input type="checkbox" name="remember_me" id="remember" class="form-check-input" style="width:18px;height:18px;">
                <label for="remember" class="text-secondary small mb-0">Ghi nhớ đăng nhập trong 30 ngày</label>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Đăng nhập
            </button>
        </form>

        <div class="divider">hoặc</div>

        <div class="link-register">
            Chưa có tài khoản? <a href="/project1/User/register">Đăng ký ngay</a>
        </div>
        <div class="text-center mt-3">
            <a href="/project1/Product/list" class="text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i>Quay về cửa hàng
            </a>
        </div>
    </div>

    <script>
    function togglePwd() {
        const inp = document.getElementById('passwordInput');
        const ico = document.getElementById('eyeIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            ico.className = 'fa-solid fa-eye-slash';
        } else {
            inp.type = 'password';
            ico.className = 'fa-solid fa-eye';
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
