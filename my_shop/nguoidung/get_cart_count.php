<?php
session_start();

// Kiểm tra nếu có giỏ hàng trong session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Đếm tổng số lượng sản phẩm trong giỏ hàng
    $cartCount = array_sum($_SESSION['cart']);
} else {
    // Nếu giỏ hàng trống
    $cartCount = 0;
}

// Trả về số lượng sản phẩm trong giỏ hàng
echo json_encode(['cartCount' => $cartCount]);
?>
