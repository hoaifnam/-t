<?php
session_start();
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_code'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($entered_code == $_SESSION['reset_code']) {
        if ($new_password === $confirm_password) {
            // Kết nối cơ sở dữ liệu
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "phone_store";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }

            $email = $_SESSION['reset_email'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới
            $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
            if ($conn->query($sql) === TRUE) {
                echo "Mật khẩu đã được đổi thành công!";
                session_unset();
                session_destroy();
                header("Location: login.html");
                exit();
            } else {
                echo "Có lỗi xảy ra khi cập nhật mật khẩu: " . $conn->error;
            }
            $conn->close();
        } else {
            echo "Mật khẩu mới và xác nhận không khớp.";
        }
    } else {
        echo "Mã xác thực không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập mã xác thực</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="verify-code-container">
        <h2>Nhập mã xác thực</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="reset_code">Mã xác thực:</label>
                <input type="text" id="reset_code" name="reset_code" required>
            </div>
            <div class="input-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Đổi mật khẩu</button>
        </form>
    </div>
</body>
</html>
