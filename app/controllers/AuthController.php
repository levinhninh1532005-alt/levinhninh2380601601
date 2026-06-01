<?php
require_once('app/config/database.php');
require_once('app/helpers/auth_helper.php');

class AuthController {

    private $db;
    private $validEmployeeCodes = ['NV001', 'NV002', 'NV003', 'ADMIN2024'];

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function adminLogin() {
        if ($this->isAdmin()) {
            header('Location: /project1/Product/list');
            exit;
        }
        include 'app/views/auth/admin_login.php';
    }

    public function adminLoginPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /project1/Auth/adminLogin');
            exit;
        }

        $username     = trim($_POST['username'] ?? '');
        $password     = $_POST['password'] ?? '';
        $employeeCode = trim($_POST['employee_code'] ?? '');
        $error        = '';

        if (!in_array($employeeCode, $this->validEmployeeCodes)) {
            $error = 'Mã nhân viên không hợp lệ!';
            include 'app/views/auth/admin_login.php';
            return;
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM account WHERE username = :username AND role = 'admin' AND status = 'active' LIMIT 1"
        );
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user || !password_verify($password, $user->password)) {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            include 'app/views/auth/admin_login.php';
            return;
        }

        // Set admin session
        $_SESSION['admin']         = true;
        $_SESSION['admin_id']      = $user->id;
        $_SESSION['admin_name']    = $user->full_name ?: $user->username;
        $_SESSION['employee_code'] = $employeeCode;

        // Cập nhật last_login
        $this->db->prepare("UPDATE account SET last_login = NOW() WHERE id = :id")
                 ->execute([':id' => $user->id]);

        header('Location: /project1/Product/list');
        exit;
    }

    public function adminLogout() {
        unset($_SESSION['admin'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['employee_code']);
        header('Location: /project1/Product/list');
        exit;
    }

    public static function isAdminStatic() {
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }

    public function isAdmin() {
        return self::isAdminStatic();
    }
}
?>
