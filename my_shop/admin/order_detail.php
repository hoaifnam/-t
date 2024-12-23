<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy order_id từ URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// Truy vấn thông tin đơn hàng
$sql_order = "SELECT id, user_id, total_amount, status, created_at FROM orders WHERE id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

// Kiểm tra nếu có kết quả
if ($result_order->num_rows > 0) {
    $order = $result_order->fetch_assoc();
    // Hiển thị thông tin đơn hàng
    echo "<h3>Thông tin đơn hàng</h3>";
    echo "Order ID: " . $order['id'] . "<br>";
    echo "User ID: " . $order['user_id'] . "<br>";
    echo "Total Amount: " . number_format($order['total_amount'], 0, ',', '.') . " VND<br>";
    echo "Status: " . $order['status'] . "<br>";
    echo "Created At: " . $order['created_at'] . "<br><br>";

    // Truy vấn thông tin thanh toán
    $sql_payment = "SELECT payment_method, payment_status FROM payments WHERE order_id = ?";
    $stmt_payment = $conn->prepare($sql_payment);
    $stmt_payment->bind_param("i", $order_id);
    $stmt_payment->execute();
    $result_payment = $stmt_payment->get_result();

    // Kiểm tra và hiển thị thông tin thanh toán
    if ($result_payment->num_rows > 0) {
        $payment = $result_payment->fetch_assoc();
        echo "<h3>Thông tin thanh toán</h3>";
        echo "Payment Method: " . ucfirst($payment['payment_method']) . "<br>";
        echo "Payment Status: " . ucfirst($payment['payment_status']) . "<br><br>";
    } else {
        echo "Không có thông tin thanh toán cho đơn hàng này.<br><br>";
    }

    // Truy vấn thông tin sản phẩm trong đơn hàng
    $sql_items = "SELECT oi.product_id, oi.quantity, oi.price, oi.subtotal, p.name AS product_name
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();

    echo "<h3>Chi Tiết Sản Phẩm</h3>";
    if ($result_items->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>";
        // Hiển thị các sản phẩm trong đơn hàng
        while ($item = $result_items->fetch_assoc()) {
            echo "<tr>
                    <td>" . $item['product_name'] . "</td>
                    <td>" . $item['quantity'] . "</td>
                    <td>" . number_format($item['price'], 0, ',', '.') . " VND</td>
                    <td>" . number_format($item['subtotal'], 0, ',', '.') . " VND</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Không có sản phẩm nào trong đơn hàng này.";
    }
} else {
    echo "Không tìm thấy thông tin đơn hàng.";
}

$conn->close();
?>
