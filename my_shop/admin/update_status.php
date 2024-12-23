<?php
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

// Lấy thông tin từ form
$order_id = $_POST['order_id'];
$status = $_POST['status'];
$payment_status = $_POST['payment_status'];

// Kiểm tra nếu trạng thái đơn hàng là "Delivered", cập nhật trạng thái thanh toán
if ($status == 'Delivered') {
    $payment_status = 'Paid'; // Cập nhật thành "Đã thanh toán" khi giao hàng
}

// Cập nhật trạng thái đơn hàng và thanh toán
$sql = "UPDATE orders SET status = ?, payment_status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssi', $status, $payment_status, $order_id);

if ($stmt->execute()) {
    echo "Cập nhật trạng thái thành công!";
} else {
    echo "Lỗi khi cập nhật trạng thái: " . $conn->error;
}

$stmt->close();
$conn->close();

// Chuyển hướng về trang quản lý đơn hàng
header("Location: order_management.php");
exit;
?>
