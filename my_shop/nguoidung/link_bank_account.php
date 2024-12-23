<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to link a bank account.");
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Xử lý lưu thông tin tài khoản ngân hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $bank_account = $_POST['bank_account'];
    $bank_name = $_POST['bank_name'];
    $card_number = $_POST['card_number'];

    // Kiểm tra xem thông tin đã nhập có hợp lệ không
    if (empty($bank_account) || empty($bank_name) || empty($card_number)) {
        echo "Vui lòng nhập đầy đủ thông tin tài khoản ngân hàng.";
        exit;
    }

    // Lưu tài khoản ngân hàng vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO user_bank_accounts (user_id, bank_account, bank_name, card_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $bank_account, $bank_name, $card_number);

    if ($stmt->execute()) {
        // Lưu tài khoản ngân hàng vào session để ghi nhớ
        $_SESSION['bank_account'] = $bank_account;
        $_SESSION['bank_name'] = $bank_name;
        $_SESSION['card_number'] = $card_number;

        // Chuyển hướng đến trang thanh toán
        header("Location: checkout.php");  // Hoặc trang thanh toán bạn muốn chuyển đến
        exit();
    } else {
        echo "Có lỗi xảy ra khi liên kết tài khoản ngân hàng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Bank Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label, input, select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="text"] {
            padding: 8px;
            margin-top: 5px;
        }
        select {
            padding: 8px;
            margin-top: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Link Your Bank Account</h2>
    <form method="POST">
        <label for="bank_account">Bank Account Number</label>
        <input type="text" id="bank_account" name="bank_account" required placeholder="Enter your bank account number">

        <label for="bank_name">Bank Name</label>
        <select id="bank_name" name="bank_name" required>
            <option value="">Select Bank</option>
            <option value="Vietcombank">Vietcombank</option>
            <option value="ACB">ACB</option>
            <option value="BIDV">BIDV</option>
            <option value="Sacombank">Sacombank</option>
            <option value="Techcombank">Techcombank</option>
        </select>

        <label for="card_number">Card Number</label>
        <input type="text" id="card_number" name="card_number" required placeholder="Enter your card number">

        <button type="submit">Link Account</button>
    </form>
</div>

</body>
</html>
