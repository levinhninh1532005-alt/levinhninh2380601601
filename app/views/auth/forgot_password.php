<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #f093fb, #f5576c); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .auth-card { background: white; border-radius: 20px; padding: 45px 40px; width: 100%; max-width: 440px; box-shadow: 0 25px 60px rgba(0,0,0,0.2); }
        .brand-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; color: white; }
        h2 { font-weight: 700; color: #1a1a2e; text-align: center; }
        .subtitle { color: #6b7280; text-align: center; font-size: 14px; margin-bottom: 30px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1.5px solid #e5e7eb; }
        .form-control:focus { border-color: #f5576c; box-shadow: 0 0 0 3px rgba(245,87,108,0.15); }
        .input-group-text { background: #f9fafb; border: 1.5px solid #e5e7eb; color: #6b7280; border-radius: 10px 0 0 10px; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
        .btn-send { background: linear-gradient(135deg, #f093fb, #f5576c); border: none; border-radius: 10px; padding: 13px; font-weight: 700; width: 100%; color: white; font-size: 15px; transition: 0.3s; }
        .btn-send:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,87,108,0.4); color: white; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="brand-icon"><i class="fa-solid fa-key"></i></div>
        <h2>Quên mật khẩu?</h2>
        <p class="subtitle">Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu</p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-3">
            <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e); ?></div><?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/project1/User/forgotPasswordPost">
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary small">ĐỊA CHỈ EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           placeholder="email@example.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-send">
                <i class="fa-solid fa-paper-plane me-2"></i>Gửi link đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="/project1/User/login" class="text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i>Quay lại đăng nhập
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
