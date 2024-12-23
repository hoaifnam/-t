<?php
session_start();

// Kiểm tra nếu có giỏ hàng trong session
if (isset($_SESSION['cart'])) {
    // Lấy ID sản phẩm và số lượng mới từ POST request
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Nếu số lượng là 0, xóa sản phẩm khỏi giỏ hàng
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        // Cập nhật số lượng sản phẩm
        $_SESSION['cart'][$productId] = $quantity;
    }

    // Trả về kết quả
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
