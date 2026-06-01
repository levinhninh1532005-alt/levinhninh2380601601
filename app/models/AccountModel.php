<?php
class AccountModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tìm user theo username
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Tìm user theo email
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Tìm user theo ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Tìm user theo verification token
    public function findByVerificationToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE verification_token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Tìm user theo remember token
    public function findByRememberToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE remember_token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Tạo tài khoản mới
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO account (username, email, password, full_name, phone, role, status, created_at)
            VALUES (:username, :email, :password, :full_name, :phone, 'user', 'active', NOW())
        ");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':phone', $data['phone']);
        return $stmt->execute();
    }

    // Xác thực email
    public function verifyEmail($token) {
        $stmt = $this->conn->prepare("
            UPDATE account 
            SET email_verified_at = NOW(), verification_token = NULL 
            WHERE verification_token = :token AND email_verified_at IS NULL
        ");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Lưu token xác thực email mới (dùng khi gửi lại)
    public function setVerificationToken($id, $token) {
        $stmt = $this->conn->prepare("UPDATE account SET verification_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cập nhật lần đăng nhập cuối
    public function updateLastLogin($id) {
        $stmt = $this->conn->prepare("UPDATE account SET last_login = NOW(), failed_login_attempts = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Ghi nhận đăng nhập thất bại
    public function incrementFailedLogin($id) {
        $stmt = $this->conn->prepare("
            UPDATE account 
            SET failed_login_attempts = failed_login_attempts + 1,
                locked_until = IF(failed_login_attempts >= 4, DATE_ADD(NOW(), INTERVAL 30 MINUTE), locked_until)
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Đặt remember token
    public function setRememberToken($id, $token) {
        $stmt = $this->conn->prepare("UPDATE account SET remember_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Xóa remember token
    public function clearRememberToken($id) {
        $stmt = $this->conn->prepare("UPDATE account SET remember_token = NULL WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Lưu reset token
    public function setResetToken($id, $token) {
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $this->conn->prepare("
            UPDATE account SET reset_token = :token, reset_token_expires = :expires WHERE id = :id
        ");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires', $expires);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // Ghi vào bảng password_resets
        $stmt2 = $this->conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (:uid, :token)");
        $stmt2->bindParam(':uid', $id);
        $stmt2->bindParam(':token', $token);
        $stmt2->execute();
    }

    // Tìm user theo reset token còn hạn
    public function findByResetToken($token) {
        $stmt = $this->conn->prepare("
            SELECT * FROM account 
            WHERE reset_token = :token AND reset_token_expires > NOW() LIMIT 1
        ");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Đặt lại mật khẩu
    public function resetPassword($id, $hashedPassword) {
        $stmt = $this->conn->prepare("
            UPDATE account 
            SET password = :password, reset_token = NULL, reset_token_expires = NULL 
            WHERE id = :id
        ");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        // Đánh dấu token đã dùng trong bảng password_resets
        $stmt2 = $this->conn->prepare("UPDATE password_resets SET used_at = NOW() WHERE user_id = :uid AND used_at IS NULL");
        $stmt2->bindParam(':uid', $id);
        $stmt2->execute();
        return $stmt->execute();
    }

    // Đổi mật khẩu
    public function changePassword($id, $hashedPassword) {
        $stmt = $this->conn->prepare("UPDATE account SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cập nhật hồ sơ
    public function updateProfile($id, $full_name, $phone) {
        $stmt = $this->conn->prepare("
            UPDATE account SET full_name = :full_name, phone = :phone WHERE id = :id
        ");
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cập nhật avatar
    public function updateAvatar($id, $avatar) {
        $stmt = $this->conn->prepare("UPDATE account SET avatar = :avatar WHERE id = :id");
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy tất cả users (cho admin)
    public function getAllUsers() {
        $stmt = $this->conn->prepare("
            SELECT id, username, email, full_name, phone, avatar, role, status, 
                   email_verified_at, last_login, failed_login_attempts, locked_until, created_at
            FROM account ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Khóa/mở khóa tài khoản
    public function setStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE account SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Ghi activity log
    public function logActivity($user_id, $action, $description = '') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stmt = $this->conn->prepare("
            INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent)
            VALUES (:uid, :action, :desc, :ip, :ua)
        ");
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':ua', $ua);
        $stmt->execute();
    }

    // Xóa tài khoản
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM account WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Admin xác thực email thủ công
    public function adminVerifyUser($id) {
        $stmt = $this->conn->prepare("
            UPDATE account 
            SET email_verified_at = NOW(), verification_token = NULL 
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
