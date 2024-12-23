<?php
session_start();
if (isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
        $price = 20; // Tạm thời. Lấy giá thực tế từ DB.
        echo json_encode(['success' => true, 'newSubtotal' => $price * $quantity]);
        exit;
    }
}
echo json_encode(['success' => false]);
?>
