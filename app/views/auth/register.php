<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh; display: flex; align-items: center;
            justify-content: center; font-family: 'Segoe UI', sans-serif; padding: 30px 0;
        }
        .auth-card {
            background: white; border-radius: 20px; padding: 40px;
            width: 100%; max-width: 500px; box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }
        .brand-icon {
            width: 65px; height: 65px;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 18px; font-size: 26px; color: white;
        }
        h2 { font-weight: 700; color: #1a1a2e; text-align: center; }
        .subtitle { color: #6b7280; text-align: center; font-size: 14px; margin-bottom: 25px; }
        .form-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; }
        .form-control { border-radius: 10px; padding: 11px 14px; border: 1.5px solid #e5e7eb; }
        .form-control:focus { border-color: #11998e; box-shadow: 0 0 0 3px rgba(17,153,142,0.15); }
        .input-group-text { background: #f9fafb; border: 1.5px solid #e5e7eb; color: #6b7280; border-radius: 10px 0 0 10px; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
        .btn-register {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none; border-radius: 10px; padding: 13px;
            font-weight: 700; width: 100%; color: white; font-size: 15px; transition: 0.3s;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(17,153,142,0.4); color: white; }
        .strength-bar { height: 4px; border-radius: 2px; margin-top: 6px; transition: 0.3s; }
        .strength-weak   { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="brand-icon"><i class="fa-solid fa-user-plus"></i></div>
        <h2>Tạo tài khoản</h2>
        <p class="subtitle">Tham gia cộng đồng MyStore ngay hôm nay</p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-3">
            <?php foreach ($errors as $e): ?>
                <div><i class="fa-solid fa-circle-exclamation me-1"></i><?= htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/project1/User/registerPost" novalidate>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Họ và tên</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="full_name" class="form-control"
                               placeholder="Nguyễn Văn A"
                               value="<?= htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                        <input type="text" name="username" class="form-control"
                               placeholder="username" required
                               value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    <small class="text-muted">Ít nhất 4 ký tự, chỉ chữ/số/_</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                        <input type="tel" name="phone" class="form-control"
                               placeholder="0901234567"
                               value="<?= htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               placeholder="email@example.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="pwdInput" class="form-control"
                               placeholder="Ít nhất 6 ký tự" required
                               oninput="checkStrength(this.value)">
                    </div>
                    <div class="strength-bar" id="strengthBar"></div>
                    <small id="strengthText" class="text-muted"></small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="confirm_password" id="confirmPwd" class="form-control"
                               placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-register">
                    <i class="fa-solid fa-user-plus me-2"></i>Đăng ký
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            Đã có tài khoản? <a href="/project1/User/login" style="color:#11998e; text-decoration:none; font-weight:600;">Đăng nhập</a>
        </div>
        <div class="text-center mt-2">
            <a href="/project1/Product/list" class="text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i>Quay về cửa hàng
            </a>
        </div>
    </div>

    <script>
    function checkStrength(val) {
        const bar  = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        if (!val) { bar.className = 'strength-bar'; text.textContent = ''; return; }
        const hasUpper = /[A-Z]/.test(val);
        const hasNum   = /[0-9]/.test(val);
        const hasSpec  = /[^a-zA-Z0-9]/.test(val);
        const score    = (val.length >= 8 ? 1 : 0) + hasUpper + hasNum + hasSpec;
        if (score <= 1) { bar.className = 'strength-bar strength-weak'; text.textContent = 'Yếu'; text.style.color='#ef4444'; }
        else if (score <= 2) { bar.className = 'strength-bar strength-medium'; text.textContent = 'Trung bình'; text.style.color='#f59e0b'; }
        else { bar.className = 'strength-bar strength-strong'; text.textContent = 'Mạnh'; text.style.color='#10b981'; }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
