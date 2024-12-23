<?php
include('db.php'); // Kết nối với cơ sở dữ liệu

// Xử lý xóa đơn hàng
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];

    // Xóa chi tiết đơn hàng trong bảng order_details
    $sqlDetails = "DELETE FROM order_details WHERE order_id = ?";
    $stmtDetails = $conn->prepare($sqlDetails);
    $stmtDetails->bind_param('i', $orderId);
    $stmtDetails->execute();

    // Sau đó xóa đơn hàng trong bảng orders
    $sqlOrder = "DELETE FROM orders WHERE id = ?";
    $stmtOrder = $conn->prepare($sqlOrder);
    $stmtOrder->bind_param('i', $orderId);
    $stmtOrder->execute();

    // Kiểm tra xem việc xóa có thành công không
    if ($stmtOrder->affected_rows > 0) {
        echo '<script>alert("Order deleted successfully!"); window.location="order_history.php";</script>';
    } else {
        echo '<script>alert("Failed to delete order!"); window.location="order_history.php";</script>';
    }

    exit; // Dừng việc thực thi để tránh các mã khác tiếp tục chạy
}

// Truy vấn danh sách đơn hàng
$sql = "SELECT 
            orders.id AS order_id,
            products.name AS product_name,
            order_details.quantity AS quantity,
            orders.payment_method,
            orders.status,
            orders.payment_status
        FROM orders
        JOIN order_details ON orders.id = order_details.order_id
        JOIN products ON order_details.product_id = products.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 12px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #d32f2f;
        }

        .message {
            margin-bottom: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Order History</h1>
<a href="index.php" class="back-button">Quay lại</a>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message">
        <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        ?>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Payment Method</th>
            <th>Order Status</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['payment_method']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['payment_status']; ?></td>
            <td>
                <!-- Button xóa đơn hàng -->
                <a href="order_history.php?delete_order=<?php echo $row['order_id']; ?>" class="btn" 
                   onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
