<?php
session_start();
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false]);
?>
