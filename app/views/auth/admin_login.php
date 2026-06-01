<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 45px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .admin-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: white;
            box-shadow: 0 10px 30px rgba(78,115,223,0.4);
        }
        h2 {
            color: white;
            text-align: center;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .subtitle {
            color: rgba(255,255,255,0.5);
            text-align: center;
            font-size: 14px;
            margin-bottom: 35px;
        }
        .form-label {
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.12);
            border-color: #4e73df;
            color: white;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.2);
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.3);
        }
        .input-group-text {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.5);
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
        .btn-login {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            width: 100%;
            color: white;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78,115,223,0.5);
            color: white;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }
        .back-link a:hover {
            color: rgba(255,255,255,0.9);
        }
        .alert-danger {
            background: rgba(231,74,59,0.2);
            border: 1px solid rgba(231,74,59,0.4);
            color: #ff8a80;
            border-radius: 10px;
        }
        .employee-badge {
            background: rgba(255,193,7,0.15);
            border: 1px solid rgba(255,193,7,0.3);
            border-radius: 10px;
            padding: 10px 15px;
            color: rgba(255,193,7,0.9);
            font-size: 13px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="login-card">

        <div class="admin-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2>Quản trị viên</h2>
        <p class="subtitle">Chỉ dành cho nhân viên có thẩm quyền</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="employee-badge">
            <i class="fa-solid fa-id-badge me-2"></i>
            Yêu cầu mã nhân viên để truy cập
        </div>

        <form method="POST" action="/project1/Auth/adminLoginPost">

            <div class="mb-3">
                <label class="form-label">Mã nhân viên</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                    <input type="text"
                           name="employee_code"
                           class="form-control"
                           placeholder="Nhập mã nhân viên (VD: NV001)"
                           value="<?php echo htmlspecialchars($_POST['employee_code'] ?? ''); ?>"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text"
                           name="username"
                           class="form-control"
                           placeholder="Tên đăng nhập admin"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Mật khẩu"
                           required>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fa-solid fa-right-to-bracket me-2"></i>
                Đăng nhập
            </button>

        </form>

        <div class="back-link">
            <a href="/project1/Product/list">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Quay về trang khách hàng
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
