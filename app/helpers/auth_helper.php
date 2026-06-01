<?php
/**
 * Kiểm tra quyền admin.
 * Nếu không phải admin thì redirect về trang đăng nhập admin.
 */
function requireAdmin() {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        $_SESSION['auth_error'] = 'Bạn không có quyền truy cập. Vui lòng đăng nhập với tài khoản quản trị viên.';
        header('Location: /project1/Auth/adminLogin');
        exit;
    }
}

/**
 * Kiểm tra có phải admin không (không redirect)
 */
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

/**
 * Yêu cầu đăng nhập user.
 * Nếu chưa đăng nhập, redirect về trang login.
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['auth_error'] = 'Vui lòng đăng nhập để tiếp tục.';
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        header('Location: /project1/User/login');
        exit;
    }
}

/**
 * Kiểm tra user đã đăng nhập chưa
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Lấy thông tin user hiện tại từ session
 */
function currentUser() {
    if (!isLoggedIn()) return null;
    return (object)[
        'id'        => $_SESSION['user_id'],
        'name'      => $_SESSION['user_name'] ?? '',
        'full_name' => $_SESSION['user_full_name'] ?? '',
        'email'     => $_SESSION['user_email'] ?? '',
        'role'      => $_SESSION['user_role'] ?? 'user',
        'avatar'    => $_SESSION['user_avatar'] ?? '',
        'username'  => $_SESSION['user_username'] ?? '',
    ];
}

/**
 * Lấy avatar URL, fallback về ảnh mặc định nếu không có
 */
function getAvatarUrl($avatar = '') {
    if ($avatar && file_exists($avatar)) {
        return '/project1/' . ltrim($avatar, '/');
    }
    return 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'User') . '&background=4e73df&color=fff&size=128';
}
?>
