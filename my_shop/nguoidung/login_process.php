<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ form đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

// Truy vấn kiểm tra đăng nhập bằng Prepared Statements (Bảo mật SQL Injection)
$sql = "SELECT * FROM users WHERE username = ? AND account_status = 0"; // Chỉ lấy người dùng có account_status = 0
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // "s" cho kiểu dữ liệu string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Kiểm tra mật khẩu sau khi đã lấy được người dùng
    $row = $result->fetch_assoc();
    
    if (password_verify($password, $row['password'])) {
        // Nếu đăng nhập thành công
        $_SESSION['user_id'] = $row['id']; // Lưu user_id vào session
        $_SESSION['username'] = $username; // Lưu tên đăng nhập vào session
        header("Location: index.php"); // Chuyển hướng về trang chính
        exit();
    } else {
        // Nếu mật khẩu sai
        echo "<script>alert('Mật khẩu không chính xác.'); window.location.href = 'login.html';</script>";
    }
} else {
    // Nếu không tìm thấy người dùng hoặc account_status không phải là 0
    echo "<script>alert('Tên đăng nhập không chính xác hoặc tài khoản chưa được kích hoạt.'); window.location.href = 'login.html';</script>";
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
