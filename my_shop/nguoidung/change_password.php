<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Chuyển hướng nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu thay đổi mật khẩu
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu cũ
    $sql = "SELECT password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Giả sử mật khẩu được lưu dưới dạng mã hóa hash
        if (password_verify($old_password, $row['password'])) {
            if ($new_password === $confirm_password) {
                // Cập nhật mật khẩu mới
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
                if ($conn->query($update_sql) === TRUE) {
                    // Hiển thị thông báo và chuyển hướng
                    echo "<script>
                            alert('Mật khẩu đã được đổi thành công.');
                            window.location.href = 'index.php'; // Thay 'index.php' bằng trang chính của bạn
                          </script>";
                    exit();
                } else {
                    $message = "Có lỗi xảy ra khi cập nhật mật khẩu: " . $conn->error;
                }
            } else {
                $message = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
            }
        } else {
            $message = "Mật khẩu cũ không đúng.";
        }
    } else {
        $message = "Người dùng không tồn tại.";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    width: 300px;
}

form label {
    font-weight: bold;
}

form input {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form button {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
}

form button:hover {
    background-color: #0056b3;
}

</style>
<body>
<a href="index.php" class="back-button">Quay lại</a>

    <header>
        <h1>Đổi Mật Khẩu</h1>
    </header>
    <main>
        <form method="POST">
            <label for="old_password">Mật khẩu cũ:</label><br>
            <input type="password" id="old_password" name="old_password" required><br><br>

            <label for="new_password">Mật khẩu mới:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>

            <label for="confirm_password">Xác nhận mật khẩu mới:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <button type="submit">Đổi mật khẩu</button>
        </form>
        <p><?php echo $message; ?></p>
    </main>
</body>
</html>
