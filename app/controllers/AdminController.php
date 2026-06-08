<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/auth_helper.php');

class AdminController {

    private $db;
    private $accountModel;

    public function __construct() {
        $this->db           = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // Trang API Playground cho admin
    public function apiPlayground() {
        requireAdmin();
        include 'app/views/admin/api_playground.php';
    }

    // Trang quản lý sản phẩm bằng jQuery + API
    public function products() {
        requireAdmin();
        include 'app/views/admin/products.php';
    }

        // Trang quản lý người dùng
    public function users() {
        requireAdmin();
        $users   = $this->accountModel->getAllUsers();
        $current = $_SESSION['admin_id'] ?? null;
        include 'app/views/admin/users.php';
    }

    // Khóa tài khoản
    public function lockUser($id) {
        requireAdmin();
        $id = (int)$id;
        // Không cho khóa chính mình
        if ($id === (int)($_SESSION['admin_id'] ?? 0)) {
            $_SESSION['flash_error'] = 'Không thể khóa tài khoản của chính mình.';
            header('Location: /project1/Admin/users');
            exit;
        }
        $user = $this->accountModel->findById($id);
        if ($user && $user->role !== 'admin') {
            $this->accountModel->setStatus($id, 'locked');
            $this->accountModel->logActivity($_SESSION['admin_id'], 'lock_user', "Khóa tài khoản ID=$id ({$user->username})");
            $_SESSION['flash_success'] = "Đã khóa tài khoản '{$user->username}'.";
        } else {
            $_SESSION['flash_error'] = 'Không thể khóa tài khoản này.';
        }
        header('Location: /project1/Admin/users');
        exit;
    }

    // Mở khóa tài khoản
    public function unlockUser($id) {
        requireAdmin();
        $id   = (int)$id;
        $user = $this->accountModel->findById($id);
        if ($user) {
            $this->accountModel->setStatus($id, 'active');
            $this->accountModel->logActivity($_SESSION['admin_id'], 'unlock_user', "Mở khóa tài khoản ID=$id ({$user->username})");
            $_SESSION['flash_success'] = "Đã mở khóa tài khoản '{$user->username}'.";
        }
        header('Location: /project1/Admin/users');
        exit;
    }

    // Xác thực email thủ công cho user
    public function verifyUser($id) {
        requireAdmin();
        $id   = (int)$id;
        $user = $this->accountModel->findById($id);
        if ($user && !$user->email_verified_at) {
            $this->accountModel->adminVerifyUser($id);
            $this->accountModel->logActivity($_SESSION['admin_id'], 'verify_user', "Xác thực tài khoản ID=$id ({$user->username})");
            $_SESSION['flash_success'] = "Đã xác thực tài khoản '{$user->username}'.";
        } else {
            $_SESSION['flash_error'] = 'Tài khoản đã được xác thực rồi.';
        }
        header('Location: /project1/Admin/users');
        exit;
    }

    // Xem chi tiết user
    public function userDetail($id) {
        requireAdmin();
        $id   = (int)$id;
        $user = $this->accountModel->findById($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'Không tìm thấy người dùng.';
            header('Location: /project1/Admin/users');
            exit;
        }
        // Lấy activity logs
        $stmt = $this->db->prepare("SELECT * FROM activity_logs WHERE user_id = :uid ORDER BY created_at DESC LIMIT 20");
        $stmt->bindParam(':uid', $id);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
        include 'app/views/admin/user_detail.php';
    }

    // Xóa tài khoản người dùng
    public function deleteUser($id) {
        requireAdmin();
        $id = (int)$id;

        // Không cho xóa chính mình
        if ($id === (int)($_SESSION['admin_id'] ?? 0)) {
            $_SESSION['flash_error'] = 'Không thể xóa tài khoản của chính mình.';
            header('Location: /project1/Admin/users');
            exit;
        }

        $user = $this->accountModel->findById($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'Không tìm thấy người dùng.';
            header('Location: /project1/Admin/users');
            exit;
        }

        if ($user->role === 'admin') {
            $_SESSION['flash_error'] = 'Không thể xóa tài khoản admin.';
            header('Location: /project1/Admin/users');
            exit;
        }

        $this->accountModel->deleteUser($id);
        $this->accountModel->logActivity($_SESSION['admin_id'], 'delete_user', "Xóa tài khoản ID=$id ({$user->username})");
        $_SESSION['flash_success'] = "Đã xóa tài khoản '{$user->username}' thành công.";
        header('Location: /project1/Admin/users');
        exit;
    }
}
?>
