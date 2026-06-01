<?php
/**
 * Gửi email xác thực tài khoản
 * Trong môi trường dev, ghi ra file log thay vì gửi thật.
 * Trong production, dùng PHPMailer hoặc smtp thực.
 */
function sendVerificationEmail($email, $username, $token) {
    $baseUrl    = 'http://localhost/project1';
    $verifyLink = $baseUrl . '/User/verifyEmail/' . $token;
    $subject    = '[MyStore] Xác thực tài khoản của bạn';
    $body       = "Xin chào $username,\n\nVui lòng nhấn vào link bên dưới để xác thực tài khoản:\n$verifyLink\n\nLink hết hạn sau 24 giờ.\n\nTrân trọng,\nMyStore";

    // Ghi log (dev mode)
    _logEmail($email, $subject, $body, $verifyLink);

    // Uncomment dòng dưới để gửi email thực:
    // mail($email, $subject, $body, "From: noreply@mystore.vn\r\nContent-Type: text/plain; charset=UTF-8");
}

/**
 * Gửi email đặt lại mật khẩu
 */
function sendResetPasswordEmail($email, $username, $token) {
    $baseUrl    = 'http://localhost/project1';
    $resetLink  = $baseUrl . '/User/resetPassword/' . $token;
    $subject    = '[MyStore] Đặt lại mật khẩu';
    $body       = "Xin chào $username,\n\nBạn (hoặc ai đó) đã yêu cầu đặt lại mật khẩu.\nNhấn vào link bên dưới để tiếp tục:\n$resetLink\n\nLink hết hạn sau 1 giờ.\nNếu bạn không yêu cầu, hãy bỏ qua email này.\n\nTrân trọng,\nMyStore";

    _logEmail($email, $subject, $body, $resetLink);

    // mail($email, $subject, $body, "From: noreply@mystore.vn\r\nContent-Type: text/plain; charset=UTF-8");
}

/**
 * Ghi email ra file log (dùng cho môi trường dev)
 * File: storage/email_log.txt
 */
function _logEmail($to, $subject, $body, $link = '') {
    $logDir  = 'storage/';
    $logFile = $logDir . 'email_log.txt';

    if (!is_dir($logDir)) mkdir($logDir, 0777, true);

    $entry = "========================================\n";
    $entry .= "Time   : " . date('Y-m-d H:i:s') . "\n";
    $entry .= "To     : $to\n";
    $entry .= "Subject: $subject\n";
    if ($link) $entry .= "Link   : $link\n";
    $entry .= "Body   :\n$body\n\n";

    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}
?>
