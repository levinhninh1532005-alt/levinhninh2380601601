-- ============================================================
-- Tạo tài khoản Admin mẫu
-- Mật khẩu: Admin@123 (đã băm bằng password_hash PHP)
-- Mã nhân viên hợp lệ: NV001, NV002, NV003, ADMIN2024
-- ============================================================

-- Tạo tài khoản admin (chạy lệnh này 1 lần)
INSERT INTO `account` (
    `username`,
    `email`,
    `password`,
    `full_name`,
    `role`,
    `status`,
    `email_verified_at`
) VALUES (
    'admin',
    'admin@mystore.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: password
    'Quản Trị Viên',
    'admin',
    'active',
    NOW()
);

-- Nếu muốn thay mật khẩu tùy chỉnh, dùng PHP để tạo hash:
-- echo password_hash('MatKhauCuaBan', PASSWORD_DEFAULT);
-- Rồi UPDATE account SET password = '<hash>' WHERE username = 'admin';
