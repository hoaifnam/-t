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

// Lấy id sản phẩm từ URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Nếu id sản phẩm hợp lệ, thực hiện xóa
if ($product_id > 0) {
    $sql = "DELETE FROM products WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "success";  // Trả về "success" nếu xóa thành công
    } else {
        echo "error";    // Trả về "error" nếu có lỗi
    }
} else {
    echo "error";        // Nếu không có id hợp lệ
}

// Đóng kết nối
$conn->close();
?>
