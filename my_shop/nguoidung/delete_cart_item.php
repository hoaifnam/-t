<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Kiểm tra nếu có product_id gửi đến
    if (isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        
        // Xóa sản phẩm khỏi giỏ hàng trong cơ sở dữ liệu
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        
        if ($stmt->execute()) {
            // Trả về kết quả thành công
            echo json_encode(['success' => true]);
        } else {
            // Trả về kết quả thất bại
            echo json_encode(['success' => false]);
        }
    }
} else {
    // Nếu chưa đăng nhập, xử lý giỏ hàng trong session
    if (isset($_SESSION['cart']) && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        
        // Xóa sản phẩm khỏi giỏ hàng trong session
        unset($_SESSION['cart'][$productId]);
        
        // Trả về kết quả thành công
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}

$conn->close();
?>
