<?php
require '../vendor/autoload.php'; // Đảm bảo Composer đã được cài đặt

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Tạo đối tượng PHPMailer
$mail = new PHPMailer(true);

try {
    // Cấu hình server SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '20211827@eaut.edu.vn';  // Địa chỉ email của bạn
    $mail->Password   = '132479780';  // Mật khẩu ứng dụng nếu sử dụng Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Hoặc PHPMailer::ENCRYPTION_SMTPS nếu dùng SSL
    $mail->Port       = 587;  // Cổng 587 cho STARTTLS

    // Người gửi và người nhận
    $mail->setFrom('20211827@eaut.edu.vn', 'Your Name');
    $mail->addAddress($email, 'User Name');

    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = 'Mã xác thực đặt lại mật khẩu';
    $mail->Body    = "Mã xác thực của bạn là: $reset_code";

    // Gửi email
    $mail->send();
    header("Location: verify_reset_code.php");
    exit();
} catch (Exception $e) {
    echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
}

?>
