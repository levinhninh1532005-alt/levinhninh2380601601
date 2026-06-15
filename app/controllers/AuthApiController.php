<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

/**
 * API XÁC THỰC (Auth)
 * ------------------------------------------------------------
 *  POST   /api/auth          -> Đăng nhập (trả về token + user)
 *  GET    /api/auth          -> Lấy thông tin user đang đăng nhập
 *  GET    /api/auth/{id}      -> (alias của trên, id bị bỏ qua)
 *  DELETE /api/auth/{anything}-> Đăng xuất
 *
 * Hỗ trợ 2 cách xác thực cho các request sau khi login:
 *  1) Cookie session (PHPSESSID) - tự động nếu Postman dùng chung Cookie Jar
 *  2) Header  Authorization: Bearer <token>  (token trả về khi login)
 * ------------------------------------------------------------
 */
class AuthApiController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db           = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // Đọc body JSON, ưu tiên $GLOBALS nếu router đã đọc trước
    private function getBody()
    {
        if (!empty($GLOBALS['_API_BODY'])) {
            return $GLOBALS['_API_BODY'];
        }
        $raw = file_get_contents('php://input');
        return $raw ? (json_decode($raw, true) ?? []) : [];
    }

    // Lấy Bearer token từ header Authorization (nếu có)
    private function getBearerToken()
    {
        $header = '';

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    $header = $value;
                    break;
                }
            }
        }

        if (!$header && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if (!$header && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if ($header && preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    // Lấy user hiện tại: ưu tiên session, sau đó Bearer token
    private function getAuthUser()
    {
        if (!empty($_SESSION['user_id'])) {
            $user = $this->accountModel->findById($_SESSION['user_id']);
            if ($user) return $user;
        }

        $token = $this->getBearerToken();
        if ($token) {
            $user = $this->accountModel->findByRememberToken($token);
            if ($user && $user->status === 'active') return $user;
        }

        return null;
    }

    // Định dạng thông tin user trả ra JSON (ẩn các trường nhạy cảm)
    private function formatUser($user)
    {
        return [
            'id'         => (int)$user->id,
            'username'   => $user->username,
            'email'      => $user->email,
            'full_name'  => $user->full_name,
            'phone'      => $user->phone,
            'role'       => $user->role,
            'status'     => $user->status,
            'avatar'     => $user->avatar,
            'verified'   => !empty($user->email_verified_at),
            'last_login' => $user->last_login,
        ];
    }

    // ============================================================
    // POST /api/auth -> Đăng nhập
    // body JSON: { "username": "...", "password": "..." }
    // (có thể dùng email thay cho username)
    // ============================================================
    public function store()
    {
        header('Content-Type: application/json');

        $data     = $this->getBody();
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['message' => 'Vui lòng nhập username (hoặc email) và password']);
            return;
        }

        // Cho phép đăng nhập bằng username hoặc email
        $user = $this->accountModel->findByUsername($username);
        if (!$user) {
            $user = $this->accountModel->findByEmail($username);
        }

        if (!$user || !password_verify($password, $user->password)) {
            http_response_code(401);
            echo json_encode(['message' => 'Tên đăng nhập hoặc mật khẩu không đúng']);
            return;
        }

        if ($user->status !== 'active') {
            http_response_code(403);
            echo json_encode(['message' => 'Tài khoản đang bị khoá hoặc không hoạt động']);
            return;
        }

        // Sinh token đăng nhập (dùng cho Authorization: Bearer <token>)
        $token = bin2hex(random_bytes(32));
        $this->accountModel->setRememberToken($user->id, $token);
        $this->accountModel->updateLastLogin($user->id);
        $this->accountModel->logActivity($user->id, 'login_api', 'Đăng nhập qua API');

        // Đồng thời lưu session, hữu ích nếu Postman dùng chung Cookie Jar
        $_SESSION['user_id']        = $user->id;
        $_SESSION['user_name']      = $user->full_name ?: $user->username;
        $_SESSION['user_full_name'] = $user->full_name;
        $_SESSION['user_email']     = $user->email;
        $_SESSION['user_role']      = $user->role;
        $_SESSION['user_avatar']    = $user->avatar;
        $_SESSION['user_username']  = $user->username;

        http_response_code(200);
        echo json_encode([
            'message' => 'Đăng nhập thành công',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ============================================================
    // GET /api/auth        -> Thông tin user đang đăng nhập
    // GET /api/auth/{id}   -> alias (id bị bỏ qua)
    // Yêu cầu: session đăng nhập HOẶC header Authorization: Bearer <token>
    // ============================================================
    public function index()
    {
        header('Content-Type: application/json');

        $user = $this->getAuthUser();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['message' => 'Chưa đăng nhập hoặc token không hợp lệ/đã hết hạn']);
            return;
        }

        echo json_encode($this->formatUser($user));
    }

    public function show($id)
    {
        // id không được dùng - chỉ trả thông tin user hiện tại
        $this->index();
    }

    // ============================================================
    // DELETE /api/auth/{anything} -> Đăng xuất
    // Xoá token (Bearer) và xoá session hiện tại
    // ============================================================
    public function destroy($id)
    {
        header('Content-Type: application/json');

        $user = $this->getAuthUser();
        if ($user) {
            $this->accountModel->clearRememberToken($user->id);
            $this->accountModel->logActivity($user->id, 'logout_api', 'Đăng xuất qua API');
        }

        unset(
            $_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'],
            $_SESSION['user_role'], $_SESSION['user_avatar'], $_SESSION['user_full_name'],
            $_SESSION['user_username']
        );

        echo json_encode(['message' => 'Đăng xuất thành công']);
    }

    // PUT /api/auth/{id} -> không hỗ trợ cho resource này
    public function update($id)
    {
        header('Content-Type: application/json');
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed trên /api/auth. Dùng POST để đăng nhập.']);
    }
}
?>
