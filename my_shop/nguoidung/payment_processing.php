<?php
// Kết nối cơ sở dữ liệu
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

// Lấy thông tin từ cổng thanh toán (ví dụ, thông tin đơn hàng sau khi thanh toán thành công)
$order_id = $_POST['order_id']; // Order ID từ cổng thanh toán hoặc từ thông báo callback
$payment_status = $_POST['payment_status']; // Thông tin trạng thái thanh toán từ hệ thống thanh toán

// Nếu thanh toán thành công qua credit_card, cập nhật trạng thái thanh toán
if ($payment_status == 'success') {
    $sql_payment = "UPDATE payments SET payment_status = 'completed' WHERE order_id = ?";
    $stmt_payment = $conn->prepare($sql_payment);
    $stmt_payment->bind_param("i", $order_id);
    
    // Kiểm tra nếu câu lệnh cập nhật thành công
    if ($stmt_payment->execute()) {
        echo "Thanh toán thành công, trạng thái đã được cập nhật.";
    } else {
        echo "Lỗi khi cập nhật trạng thái thanh toán.";
    }
} else {
    echo "Thanh toán không thành công.";
}

$conn->close();
?>
