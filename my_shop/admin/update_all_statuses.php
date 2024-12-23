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

// Kiểm tra dữ liệu từ AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updates'])) {
    $updates = $_POST['updates'];

    // Cập nhật trạng thái cho mỗi đơn hàng
    foreach ($updates as $update) {
        $order_id = $update['order_id'];
        $status = $update['status'];

        $sql = "UPDATE orders SET order_status = '$status' WHERE order_id = '$order_id'";
        if (!$conn->query($sql)) {
            echo "Lỗi khi cập nhật trạng thái: " . $conn->error;
            exit;
        }
    }

    echo "Cập nhật thành công";
}

// Đóng kết nối
$conn->close();
?>
