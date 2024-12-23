<?php
// Thông tin cấu hình cơ sở dữ liệu
$servername = "localhost"; // Máy chủ (thường là localhost)
$username = "root";       // Tên người dùng MySQL (mặc định là root trên XAMPP)
$password = "";           // Mật khẩu (mặc định trống trên XAMPP)
$dbname = "phone_store";  // Tên cơ sở dữ liệu

// Tạo kết nối với cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đảm bảo UTF-8 cho kết nối
$conn->set_charset("utf8");
?>
