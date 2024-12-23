<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem form đăng ký đã được gửi hay chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Mã hóa mật khẩu (sử dụng bcrypt, tốt hơn md5)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra xem tên đăng nhập đã tồn tại trong cơ sở dữ liệu chưa
    $sqlCheckUsername = "SELECT * FROM users WHERE username = ?";
    $stmtCheckUsername = $conn->prepare($sqlCheckUsername);
    $stmtCheckUsername->bind_param("s", $username);
    $stmtCheckUsername->execute();
    $resultCheckUsername = $stmtCheckUsername->get_result();

    if ($resultCheckUsername->num_rows > 0) {
        echo "<script>alert('Tên đăng nhập đã tồn tại!'); window.location.href = 'register.html';</script>";
    } else {
        // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
        $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
        $stmtCheckEmail = $conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $resultCheckEmail = $stmtCheckEmail->get_result();

        if ($resultCheckEmail->num_rows > 0) {
            echo "<script>alert('Email đã tồn tại!'); window.location.href = 'register.html';</script>";
        } else {
            // Thêm thông tin người dùng vào cơ sở dữ liệu
            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            
            if ($stmt->execute()) {
                // Đăng ký thành công, hiển thị thông báo và chuyển hướng sau 5 giây
                echo "<h2>Đăng ký thành công!</h2>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.html'; // Chuyển hướng tới trang đăng nhập
                        }, 1000);
                      </script>";
                exit(); // Dừng script để không xảy ra lỗi
            } else {
                echo "Lỗi: " . $stmt->error;
            }
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>
