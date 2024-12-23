<?php
session_start();
include('db.php');

// Kiểm tra dữ liệu khách hàng và đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin khách hàng
    $customerName = $_POST['customer']['name'];
    $customerAddress = $_POST['customer']['address'];
    $customerPhone = $_POST['customer']['phone'];
    $paymentMethod = $_POST['payment_method'];
    $paymentAccount = isset($_POST['payment_account']) ? $_POST['payment_account'] : null;

    // Lấy giỏ hàng từ session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // Tính tổng tiền đơn hàng
    $totalAmount = 0;
    foreach ($cart as $productId => $quantity) {
        // Lấy giá sản phẩm từ cơ sở dữ liệu
        $sql_price = "SELECT price FROM products WHERE id = ?";
        $stmt_price = $conn->prepare($sql_price);
        $stmt_price->bind_param('i', $productId);
        $stmt_price->execute();
        $result_price = $stmt_price->get_result();
        $product = $result_price->fetch_assoc();

        // Cộng dồn giá tiền
        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $totalAmount += $subtotal;
        }
    }

    // Kiểm tra tổng tiền
    if ($totalAmount <= 0) {
        $_SESSION['message'] = "Cart is empty or invalid total.";
        header("Location: checkout.php");
        exit;
    }

    // Xác định trạng thái thanh toán
    $paymentStatus = ($paymentMethod == 'credit_card') ? 'Paid' : 'Pending';  // "Paid" cho thanh toán trực tuyến, "Pending" cho thanh toán khi nhận hàng

    // Lưu đơn hàng vào bảng orders
    $sql = "INSERT INTO orders (customer_name, address, phone, payment_method, payment_account, payment_status, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssd', $customerName, $customerAddress, $customerPhone, $paymentMethod, $paymentAccount, $paymentStatus, $totalAmount);
    // Giảm số lượng sản phẩm trong kho
    $updateProductSql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($updateProductSql);
    $stmtUpdate->bind_param('ii', $quantity, $productId);
    $stmtUpdate->execute();

    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id; // ID của đơn hàng mới tạo

        // Lưu chi tiết sản phẩm trong đơn hàng
        foreach ($cart as $productId => $quantity) {
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt_details = $conn->prepare($sql_details);
            $stmt_details->bind_param('iii', $orderId, $productId, $quantity);
            $stmt_details->execute();
        }
// Xóa giỏ hàng trong session
unset($_SESSION['cart']);
        // Thông báo thành công
        $_SESSION['message'] = "Order placed successfully! Order ID: $orderId";
        header("Location: thank_you.html");
        exit;
    } else {
        $_SESSION['message'] = "Error processing order.";
        header("Location: checkout.php");
        exit;
    }
}
?>
