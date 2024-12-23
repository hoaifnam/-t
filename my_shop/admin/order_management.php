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

// Truy vấn danh sách đơn hàng và chi tiết sản phẩm với tên sản phẩm
$sql = "SELECT 
            o.id AS order_id, 
            o.customer_name, 
            o.address, 
            o.phone, 
            o.payment_method, 
            o.total_amount, 
            o.payment_status, 
            o.status,
            DATE_FORMAT(o.created_at, '%d-%m-%Y %H:%i:%s') AS created_at,
            oi.product_id, 
            oi.quantity,
            p.name AS product_name
        FROM 
            orders AS o
        LEFT JOIN 
            order_details AS oi
        ON 
            o.id = oi.order_id
        LEFT JOIN
            products AS p
        ON 
            oi.product_id = p.id
        ORDER BY o.created_at DESC";  // Orders will be sorted by most recent first

$result = $conn->query($sql);

if (!$result) {
    die("Lỗi khi thực hiện truy vấn: " . $conn->error);
}

// Lưu kết quả trong một mảng
$orderData = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orderData[$order_id])) {
        $orderData[$order_id] = [
            'order_id' => $row['order_id'],
            'customer_name' => $row['customer_name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'payment_method' => $row['payment_method'],
            'payment_status' => $row['payment_status'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'items' => []
        ];
    }
    $orderData[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <style>
    /* Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #007bff;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 30px 20px;
        box-shadow: 2px 0px 6px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 40px;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 12px 15px;
        text-decoration: none;
        font-size: 18px;
        border-radius: 5px;
        margin-bottom: 18px;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #0056b3;
    }

    /* Content */
    .content {
        margin-left: 270px; 
        padding: 40px;
        background-color: #fff;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    h1 {
        font-size: 2.4rem;
        font-weight: bold;
        color: #007bff;
        text-align: center;
        margin-bottom: 50px;
    }

    .section {
        margin-bottom: 40px;
        padding: 30px;
        background-color: white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .section h3 {
        font-size: 1.8rem;
        color: #007bff;
        margin-bottom: 20px;
    }

    .section p {
        font-size: 1rem;
        color: #777;
    }

    .button {
        background-color: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        display: inline-block;
        margin-bottom: 25px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: #218838;
    }

    /* Product Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    td {
        background-color: #f9f9f9;
    }

    td img {
        width: 60px;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Action Buttons inside the Table */
    .button-action {
        display: inline-block;
        padding: 8px 15px;
        font-size: 16px;
        color: white;
        background-color: #007bff;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .button-action:hover {
        background-color: #0056b3;
    }

    .button-danger {
        background-color: #dc3545;
    }

    .button-danger:hover {
        background-color: #c82333;
    }

    .no-data {
        text-align: center;
        font-size: 16px;
        color: #888;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }
    
</style>


</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h2>Quản Trị Cửa Hàng</h2>
    <a href="dashboard.php">Trang Chủ</a>
    <a href="Admin Panel.php">Quản Lý Sản Phẩm</a>
    <a href="order_management.php">Quản Lý Đơn Hàng</a>
    <a href="account.php">Quản Lý Khách Hàng</a>
    <a href="store_manager.php">Thông Tin Cửa Hàng</a>
    <a href="mail management.php">Email</a>
    <a href="login.php">Đăng Xuất</a>
</div>
<div class="content">
    <h1>Quản Lý Đơn Hàng</h1>
    <?php foreach ($orderData as $order): ?>
        <h2>Đơn Hàng: <?= $order['order_id']; ?></h2>
        <p>Họ Tên: <?= htmlspecialchars($order['customer_name']); ?></p>
        <p>Địa Chỉ: <?= htmlspecialchars($order['address']); ?></p>
        <p>Số Điện Thoại: <?= htmlspecialchars($order['phone']); ?></p>
        <p>Phương Thức Thanh Toán: <?= htmlspecialchars($order['payment_method']); ?></p>
        <p>Trạng Thái Thanh Toán: 
            <?= ($order['payment_status'] == 'Paid') ? 'Đã Thanh Toán' : 'Đang Xử Lý'; ?>
        </p>
        <p>Tổng Giá: <?= number_format($order['total_amount'], 0, ',', '.'); ?> VND</p>
        <p>Ngày Đặt: <?= $order['created_at']; ?></p>

        <form action="update_status.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
            <label for="status">Trạng Thái Đơn Hàng:</label>
            <select name="status" id="status">
                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
            </select>

            <p>Trạng Thái Thanh Toán: 
    <?php 
        if ($order['payment_method'] == 'credit_card') {
            echo 'Đã Thanh Toán';
        } elseif ($order['payment_method'] == 'cash_on_delivery' && $order['payment_status'] == 'Pending') {
            echo 'Chưa Thanh Toán';
        } else {
            echo 'Đã Thanh Toán';
        }
    ?>
</p>


            <button type="submit">Cập Nhật</button>
        </form>

        <table class="order-list">
            <thead>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Số Lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']); ?></td>
                        <td><?= htmlspecialchars($item['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
    <?php endforeach; ?>
</div>
</body>
</html>
