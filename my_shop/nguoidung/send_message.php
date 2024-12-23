<?php
// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$successMessage = "";  // Biến lưu thông báo thành công
$errorMessage = "";    // Biến lưu thông báo lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Kiểm tra thông tin không để trống
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Lưu thông tin vào cơ sở dữ liệu
        $sql = "INSERT INTO contact_requests (name, email, subject, message) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            // Cài đặt email người nhận
            $to = "support@phonestore.com";  // Thay thế bằng email của bạn
            $headers = "From: $email";
            
            // Soạn nội dung email
            $emailSubject = "Yêu cầu liên hệ: $subject";
            $emailBody = "Tên: $name\nEmail: $email\nChủ đề: $subject\nNội dung: $message";

            // Gửi email
            if (mail($to, $emailSubject, $emailBody, $headers)) {
                $successMessage = "Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ trả lời bạn sớm nhất.";
            } else {
                $errorMessage = "Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ trả lời bạn sớm nhất.";
            }
        } else {
            $errorMessage = "Đã xảy ra lỗi khi gửi yêu cầu. Vui lòng thử lại sau.";
        }
    } else {
        $errorMessage = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        label {
            font-size: 16px;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .contact-info {
            margin-top: 30px;
            font-size: 16px;
        }

        .contact-info p {
            margin: 10px 0;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
        a.back-button{
            margin-left: 25px;
        }
    </style>
</head>
<body>

<header>
    <h1>Liên Hệ Chúng Tôi</h1>
</header>
<a href="index.php" class="back-button">Quay lại</a>

<div class="container">
    <?php
    // Hiển thị thông báo thành công hoặc lỗi
    if ($successMessage) {
        echo "<p class='message'>$successMessage</p>";
    }
    if ($errorMessage) {
        echo "<p class='error'>$errorMessage</p>";
    }
    ?>
    
    <h2>Chúng tôi luôn sẵn sàng hỗ trợ bạn!</h2>
    <p>Vui lòng điền thông tin và câu hỏi của bạn vào mẫu dưới đây. Chúng tôi sẽ phản hồi bạn sớm nhất có thể.</p>

    <form action="send_message.php" method="POST">
        <label for="name">Họ và tên:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">Chủ đề:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Nội dung:</label>
        <textarea id="message" name="message" rows="6" required></textarea>

        <button type="submit">Gửi yêu cầu</button>
    </form>

    <div class="contact-info">
        <h3>Thông tin liên hệ khác:</h3>
        <p><strong>Điện thoại:</strong> 0123-456-789</p>
        <p><strong>Email:</strong> support@phonestore.com</p>
        <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM, Việt Nam</p>
    </div>

    <br>
</div>

</body>
</html>
