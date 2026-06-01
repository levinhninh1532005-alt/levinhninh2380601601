<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/auth_helper.php');
require_once('app/helpers/mail_helper.php');

class UserController {

    private $db;
    private $accountModel;

    public function __construct() {
        $this->db           = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        // Kiểm tra remember me cookie nếu chưa đăng nhập
        $this->checkRememberMe();
    }

    // ==============================
    // ĐĂNG KÝ
    // ==============================
    public function register() {
        if (isLoggedIn()) {
            header('Location: /project1/Product/list');
            exit;
        }
        include 'app/views/auth/register.php';
    }

    public function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/register');
            exit;
        }

        $username   = trim($_POST['username'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $confirm    = $_POST['confirm_password'] ?? '';
        $full_name  = trim($_POST['full_name'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $errors     = [];

        // Validate
        if (strlen($username) < 4) $errors[] = 'Tên đăng nhập phải có ít nhất 4 ký tự.';
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) $errors[] = 'Tên đăng nhập chỉ được chứa chữ, số và dấu gạch dưới.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
        if (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if ($password !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';
        if ($this->accountModel->findByUsername($username)) $errors[] = 'Tên đăng nhập đã tồn tại.';
        if ($this->accountModel->findByEmail($email)) $errors[] = 'Email đã được sử dụng.';

        if (!empty($errors)) {
            include 'app/views/auth/register.php';
            return;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $created = $this->accountModel->create([
            'username'  => $username,
            'email'     => $email,
            'password'  => $hashed,
            'full_name' => $full_name,
            'phone'     => $phone,
        ]);

        if ($created) {
            $_SESSION['flash_success'] = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
            header('Location: /project1/User/login');
        } else {
            $errors[] = 'Đã xảy ra lỗi. Vui lòng thử lại.';
            include 'app/views/auth/register.php';
        }
    }

    // ==============================
    // ĐĂNG NHẬP
    // ==============================
    public function login() {
        if (isLoggedIn()) {
            header('Location: /project1/Product/list');
            exit;
        }
        include 'app/views/auth/login.php';
    }

    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/login');
            exit;
        }

        $username   = trim($_POST['username'] ?? '');
        $password   = $_POST['password'] ?? '';
        $remember   = isset($_POST['remember_me']);
        $errors     = [];

        $user = $this->accountModel->findByUsername($username);

        if (!$user) {
            $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng.';

        } elseif ($user->status === 'locked') {
            if ($user->locked_until && strtotime($user->locked_until) > time()) {
                $until = date('H:i d/m/Y', strtotime($user->locked_until));
                $errors[] = "Tài khoản của bạn đã bị khóa đến $until. Vui lòng liên hệ quản trị viên.";
            } elseif ($user->locked_until && strtotime($user->locked_until) <= time()) {
                $this->accountModel->setStatus($user->id, 'active');
                $user->status = 'active';
            } else {
                $errors[] = "Tài khoản của bạn đã bị khóa vĩnh viễn. Vui lòng liên hệ quản trị viên để được hỗ trợ.";
            }
        }

        if (empty($errors) && (!$user || !password_verify($password, $user->password))) {
            if ($user) $this->accountModel->incrementFailedLogin($user->id);
            $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }

        if (!empty($errors)) {
            include 'app/views/auth/login.php';
            return;
        }

        // Đăng nhập thành công
        $this->setUserSession($user);
        $this->accountModel->updateLastLogin($user->id);
        $this->accountModel->logActivity($user->id, 'login', 'Đăng nhập thành công');

        // Remember Me
        if ($remember) {
            $rememberToken = bin2hex(random_bytes(32));
            $this->accountModel->setRememberToken($user->id, $rememberToken);
            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 3600), '/', '', false, true);
        }

        $_SESSION['flash_success'] = 'Chào mừng ' . ($user->full_name ?: $user->username) . '!';
        header('Location: /project1/Product/list');
        exit;
    }

    // ==============================
    // ĐĂNG XUẤT
    // ==============================
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->accountModel->clearRememberToken($_SESSION['user_id']);
            $this->accountModel->logActivity($_SESSION['user_id'], 'logout', 'Đăng xuất');
        }
        // Xóa cookie remember
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        // Xóa session user
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'],
              $_SESSION['user_role'], $_SESSION['user_avatar'], $_SESSION['user_full_name']);
        $_SESSION['flash_success'] = 'Đã đăng xuất thành công.';
        header('Location: /project1/Product/list');
        exit;
    }

    // ==============================
    // QUÊN MẬT KHẨU
    // ==============================
    public function forgotPassword() {
        include 'app/views/auth/forgot_password.php';
    }

    public function forgotPasswordPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/forgotPassword');
            exit;
        }
        $email  = trim($_POST['email'] ?? '');
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        }

        $user = $this->accountModel->findByEmail($email);
        // Luôn hiện thông báo thành công dù email có tồn tại hay không (bảo mật)
        if ($user && $user->status === 'active') {
            $token = bin2hex(random_bytes(32));
            $this->accountModel->setResetToken($user->id, $token);
            sendResetPasswordEmail($email, $user->username, $token);
        }

        if (empty($errors)) {
            $_SESSION['flash_success'] = 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.';
            header('Location: /project1/User/login');
            exit;
        }
        include 'app/views/auth/forgot_password.php';
    }

    // ==============================
    // ĐẶT LẠI MẬT KHẨU
    // ==============================
    public function resetPassword($token = '') {
        if (empty($token)) {
            header('Location: /project1/User/login');
            exit;
        }
        $user = $this->accountModel->findByResetToken($token);
        if (!$user) {
            $_SESSION['flash_error'] = 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.';
            header('Location: /project1/User/forgotPassword');
            exit;
        }
        include 'app/views/auth/reset_password.php';
    }

    public function resetPasswordPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/login');
            exit;
        }
        $token    = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $errors   = [];

        $user = $this->accountModel->findByResetToken($token);
        if (!$user) {
            $_SESSION['flash_error'] = 'Link không hợp lệ hoặc đã hết hạn.';
            header('Location: /project1/User/forgotPassword');
            exit;
        }

        if (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if ($password !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';

        if (!empty($errors)) {
            include 'app/views/auth/reset_password.php';
            return;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $this->accountModel->resetPassword($user->id, $hashed);
        $this->accountModel->logActivity($user->id, 'reset_password', 'Đặt lại mật khẩu thành công');

        $_SESSION['flash_success'] = 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.';
        header('Location: /project1/User/login');
        exit;
    }

    // ==============================
    // HỒ SƠ CÁ NHÂN
    // ==============================
    public function profile() {
        requireLogin();
        $user = $this->accountModel->findById($_SESSION['user_id']);
        include 'app/views/user/profile.php';
    }

    // Gửi lại email xác thực từ trang hồ sơ
    public function resendVerification() {
        requireLogin();
        $user = $this->accountModel->findById($_SESSION['user_id']);

        if ($user->email_verified_at) {
            $_SESSION['flash_success'] = 'Email của bạn đã được xác thực rồi.';
            header('Location: /project1/User/profile');
            exit;
        }

        $token = bin2hex(random_bytes(32));
        $this->accountModel->setVerificationToken($_SESSION['user_id'], $token);
        sendVerificationEmail($user->email, $user->username, $token);

        $_SESSION['flash_success'] = 'Đã gửi lại email xác thực. Vui lòng kiểm tra hộp thư (file storage/email_log.txt trên localhost).';
        header('Location: /project1/User/profile');
        exit;
    }

    // Xác thực email qua link token
    public function verifyEmail($token = '') {
        if (empty($token)) {
            $_SESSION['flash_error'] = 'Token xác thực không hợp lệ.';
            header('Location: /project1/User/login');
            exit;
        }
        $verified = $this->accountModel->verifyEmail($token);
        if ($verified) {
            if (isset($_SESSION['user_id'])) {
                $_SESSION['flash_success'] = 'Xác thực email thành công!';
                header('Location: /project1/User/profile');
            } else {
                $_SESSION['flash_success'] = 'Xác thực email thành công! Bạn có thể đăng nhập ngay.';
                header('Location: /project1/User/login');
            }
        } else {
            $_SESSION['flash_error'] = 'Token không hợp lệ hoặc email đã được xác thực.';
            header('Location: /project1/User/login');
        }
        exit;
    }

    public function updateProfile() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/profile');
            exit;
        }

        $full_name = trim($_POST['full_name'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $errors    = [];

        if (empty($full_name)) $errors[] = 'Họ tên không được để trống.';

        if (!empty($errors)) {
            $user = $this->accountModel->findById($_SESSION['user_id']);
            include 'app/views/user/profile.php';
            return;
        }

        $this->accountModel->updateProfile($_SESSION['user_id'], $full_name, $phone);
        $this->accountModel->logActivity($_SESSION['user_id'], 'update_profile', 'Cập nhật thông tin cá nhân');

        // Cập nhật session
        $_SESSION['user_full_name'] = $full_name;
        $_SESSION['user_name']      = $full_name ?: $_SESSION['user_name'];

        $_SESSION['flash_success'] = 'Cập nhật hồ sơ thành công!';
        header('Location: /project1/User/profile');
        exit;
    }

    // Upload avatar
    public function uploadAvatar() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['avatar'])) {
            header('Location: /project1/User/profile');
            exit;
        }

        $file  = $_FILES['avatar'];
        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Lỗi khi tải file lên.';
        } else {
            $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) $errors[] = 'Chỉ cho phép JPG, PNG, GIF, WEBP.';
            if ($file['size'] > 5 * 1024 * 1024) $errors[] = 'Ảnh không được vượt quá 5MB.';
            if (!getimagesize($file['tmp_name'])) $errors[] = 'File không phải là ảnh hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            header('Location: /project1/User/profile');
            exit;
        }

        $dir      = 'uploads/avatars/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        // Xóa avatar cũ
        $currentUser = $this->accountModel->findById($_SESSION['user_id']);
        if ($currentUser->avatar && file_exists($currentUser->avatar)) {
            unlink($currentUser->avatar);
        }

        $newName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $target  = $dir . $newName;
        move_uploaded_file($file['tmp_name'], $target);

        $this->accountModel->updateAvatar($_SESSION['user_id'], $target);
        $_SESSION['user_avatar'] = $target;

        $_SESSION['flash_success'] = 'Cập nhật ảnh đại diện thành công!';
        header('Location: /project1/User/profile');
        exit;
    }

    // ==============================
    // ĐỔI MẬT KHẨU
    // ==============================
    public function changePassword() {
        requireLogin();
        $user = $this->accountModel->findById($_SESSION['user_id']);
        include 'app/views/user/change_password.php';
    }

    public function changePasswordPost() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/User/changePassword');
            exit;
        }

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $errors  = [];
        $user    = $this->accountModel->findById($_SESSION['user_id']);

        if (!password_verify($current, $user->password)) $errors[] = 'Mật khẩu hiện tại không đúng.';
        if (strlen($new) < 6) $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        if ($new !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';
        if ($current === $new) $errors[] = 'Mật khẩu mới phải khác mật khẩu cũ.';

        if (!empty($errors)) {
            include 'app/views/user/change_password.php';
            return;
        }

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $this->accountModel->changePassword($_SESSION['user_id'], $hashed);
        $this->accountModel->logActivity($_SESSION['user_id'], 'change_password', 'Đổi mật khẩu thành công');

        $_SESSION['flash_success'] = 'Đổi mật khẩu thành công!';
        header('Location: /project1/User/profile');
        exit;
    }

    // ==============================
    // PRIVATE HELPERS
    // ==============================
    private function setUserSession($user) {
        $_SESSION['user_id']        = $user->id;
        $_SESSION['user_name']      = $user->full_name ?: $user->username;
        $_SESSION['user_full_name'] = $user->full_name;
        $_SESSION['user_email']     = $user->email;
        $_SESSION['user_role']      = $user->role;
        $_SESSION['user_avatar']    = $user->avatar;
        $_SESSION['user_username']  = $user->username;
    }

    private function checkRememberMe() {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $user  = $this->accountModel->findByRememberToken($token);
            if ($user && $user->status === 'active') {
                $this->setUserSession($user);
                $this->accountModel->updateLastLogin($user->id);
            }
        }
    }
}
?>
