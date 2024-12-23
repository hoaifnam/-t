<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Kiểm tra email có tồn tại không bằng câu lệnh chuẩn bị (prepared statement) để tránh SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" là kiểu dữ liệu cho email (string)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Tạo mã xác thực ngẫu nhiên
        $reset_code = rand(100000, 999999);

        // Lưu mã xác thực vào session và email người dùng
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $reset_code;

        // Gửi mã xác thực đến email người dùng
        $to = $email;
        $subject = "Mã xác thực đặt lại mật khẩu";
        $message = "Mã xác thực của bạn là: $reset_code";
        $headers = "From: no-reply@phonestore.com";

        // Gửi email
        if (mail($to, $subject, $message, $headers)) {
            // Chuyển đến trang nhập mã xác thực
            header("Location: verify_reset_code.php");
            exit();
        } else {
            echo "Không thể gửi email. Vui lòng thử lại.";
        }
    } else {
        echo "Email không tồn tại. Vui lòng kiểm tra lại email của bạn.";
    }

    // Đóng kết nối chuẩn bị
    $stmt->close();
}

$conn->close();
?>
