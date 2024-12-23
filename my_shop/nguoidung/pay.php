<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy giỏ hàng từ session hoặc cơ sở dữ liệu
$cartItems = [];
$totalPrice = 0;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT p.name, p.price, p.image_url, c.product_id, c.quantity 
            FROM cart c 
            INNER JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['price'] * $row['quantity'];
    }
} else {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $sql = "SELECT name, price, image_url FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'quantity' => $quantity
                ];
                $totalPrice += $product['price'] * $quantity;
            }
        }
    }
}

// Xử lý thanh toán khi người dùng nhấn nút "Xác nhận thanh toán"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = uniqid('ORDER_');
    $userId = $_SESSION['user_id'] ?? null; // Lấy ID người dùng từ session nếu đăng nhập

    // Lấy thông tin khách hàng từ form
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];
    $note = $_POST['note']; // Lấy ghi chú từ form

    // Thêm đơn hàng vào bảng `orders` (thêm ghi chú vào câu lệnh SQL)
    $sqlOrder = "INSERT INTO orders (order_id, total_price, full_name, email, phone, address, payment_method, note, created_at, order_status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
    $stmtOrder = $conn->prepare($sqlOrder);
    $orderStatus = 'pending'; // Trạng thái mặc định cho đơn hàng mới
    $stmtOrder->bind_param("sdsssssss", $orderId, $totalPrice, $fullName, $email, $phone, $address, $paymentMethod, $note, $orderStatus);
    $stmtOrder->execute();

    // Thêm từng sản phẩm vào bảng `order_items`
    foreach ($cartItems as $item) {
        $sqlOrderItem = "INSERT INTO order_items (order_id, product_id, quantity, price, product_name) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmtOrderItem = $conn->prepare($sqlOrderItem);
        $stmtOrderItem->bind_param("siiss", $orderId, $item['product_id'], $item['quantity'], $item['price'], $item['name']);
        $stmtOrderItem->execute();
    }

    // Xóa giỏ hàng
    if ($userId) {
        $sqlDeleteCart = "DELETE FROM cart WHERE user_id = ?";
        $stmtDeleteCart = $conn->prepare($sqlDeleteCart);
        $stmtDeleteCart->bind_param("i", $userId);
        $stmtDeleteCart->execute();
    } else {
        unset($_SESSION['cart']); // Xóa giỏ hàng trong session nếu không đăng nhập
    }

    // Chuyển hướng sang trang cảm ơn
    header("Location: thank_you.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <style>
        /* Style các phần tử trang thanh toán */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .cart-container {
            width: 60%;
            margin-right: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .cart-item img {
            width: 100px;
            margin-right: 20px;
        }

        .cart-item h3 {
            margin: 0;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .customer-form {
            width: 40%;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .customer-form h2 {
            margin-bottom: 15px;
        }

        .customer-form label {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
        }

        .customer-form input,
        .customer-form select,
        .customer-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .customer-form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>Thanh Toán</h1>
        <?php if (empty($cartItems)): ?>
            <p>Không có sản phẩm nào để thanh toán.</p>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                    <div>
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Số lượng: <?php echo $item['quantity']; ?></p>
                        <p>Giá: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <p class="total-price">Tổng cộng: $<?php echo number_format($totalPrice, 2); ?></p>
    </div>

    <div class="customer-form">
        <h2>Thông Tin Khách Hàng</h2>
        <form method="POST" action="">
            <label for="fullName">Họ và tên</label>
            <input type="text" id="fullName" name="full_name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Số điện thoại</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Địa chỉ</label>
            <input type="text" id="address" name="address" required>

            <label for="note">Ghi chú</label>
            <textarea id="note" name="note" rows="4" placeholder="Nhập ghi chú của bạn cho sản phẩm (nếu có)"></textarea>

            <label for="paymentMethod">Phương thức thanh toán</label>
            <select id="paymentMethod" name="payment_method">
                <option value="credit_card">Thẻ tín dụng</option>
                <option value="paypal">Tài khoản ngân hàng</option>
                <option value="cash_on_delivery">Thanh toán khi nhận hàng</option>
            </select>

            <button type="submit" name="submit">Xác nhận thanh toán</button>
        </form>
    </div>
</body>
</html>
