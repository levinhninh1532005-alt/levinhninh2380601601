<?php
/**
 * CHẠY FILE NÀY MỘT LẦN ĐỂ TẠO TÀI KHOẢN ADMIN
 * Truy cập: http://localhost/project1/setup_admin.php
 * 
 * XÓA FILE NÀY sau khi đã tạo xong tài khoản!
 */

require_once 'app/config/database.php';
session_start();

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db           = (new Database())->getConnection();
    $username     = trim($_POST['username']);
    $email        = trim($_POST['email']);
    $password     = $_POST['password'];
    $full_name    = trim($_POST['full_name']);

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra đã tồn tại chưa
    $check = $db->prepare("SELECT id FROM account WHERE username = :u OR email = :e");
    $check->execute([':u' => $username, ':e' => $email]);

    if ($check->fetch()) {
        $message = "Tài khoản hoặc email đã tồn tại!";
    } else {
        $stmt = $db->prepare("
            INSERT INTO account (username, email, password, full_name, role, status, email_verified_at)
            VALUES (:username, :email, :password, :full_name, 'admin', 'active', NOW())
        ");
        $stmt->execute([
            ':username'  => $username,
            ':email'     => $email,
            ':password'  => $hashed,
            ':full_name' => $full_name,
        ]);
        $success = true;
        $message = "✅ Tạo tài khoản admin thành công! Hãy xóa file setup_admin.php ngay bây giờ.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo tài khoản Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
<div class="card shadow p-4" style="max-width:450px; width:100%;">
    <h4 class="mb-4 text-center fw-bold">⚙️ Tạo tài khoản Admin</h4>

    <?php if ($message): ?>
        <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Họ tên</label>
            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@mystore.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Mật khẩu</label>
            <input type="password" name="password" class="form-control" placeholder="Ít nhất 8 ký tự" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Tạo tài khoản Admin</button>
    </form>
    <?php endif; ?>

    <div class="mt-3 p-3 bg-warning bg-opacity-25 rounded small">
        <strong>⚠️ Lưu ý bảo mật:</strong> Xóa file <code>setup_admin.php</code> ngay sau khi tạo xong tài khoản.
    </div>
</div>
</body>
</html>
