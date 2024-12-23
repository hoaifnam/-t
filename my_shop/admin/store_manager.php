<?php
session_start();
include('db.php');

// Thêm cửa hàng mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_store'])) {
    $storeName = $_POST['store_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO store_info (store_name, address, phone, email) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $storeName, $address, $phone, $email);
    $stmt->execute();

    $_SESSION['message'] = "Store added successfully!";
    header("Location: store_manager.php");
    exit;
}

// Sửa cửa hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_store'])) {
    $storeId = $_POST['id'];
    $storeName = $_POST['store_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "UPDATE store_info SET store_name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $storeName, $address, $phone, $email, $storeId);
    $stmt->execute();

    $_SESSION['message'] = "Store updated successfully!";
    header("Location: store_manager.php");
    exit;
}

// Xóa cửa hàng
if (isset($_GET['delete'])) {
    $storeId = $_GET['delete'];

    $sql = "DELETE FROM store_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $storeId);
    $stmt->execute();

    $_SESSION['message'] = "Store deleted successfully!";
    header("Location: store_manager.php");
    exit;
}

// Lấy thông tin cửa hàng
$sql = "SELECT * FROM store_info";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Manager</title>
    <style>
       /* Tổng quát thiết lập */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
    margin-left: 250px; /* Đẩy nội dung tránh sidebar */
}

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #007bff;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 30px;
    padding-left: 20px;
    color: white;
    font-size: 18px;
}

.sidebar h2 {
    color: white;
    font-size: 24px;
    margin-bottom: 40px;
    text-transform: uppercase;
}

.sidebar a {
    display: block;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.sidebar a:hover {
    background-color: #0056b3;
}

/* Nội dung chính */
.content {
    padding: 30px;
    margin-left: 250px; /* Đảm bảo khoảng trống cho sidebar */
}

h1 {
    color: #333;
    font-size: 32px;
    margin-bottom: 20px;
}

/* Thông báo thành công hoặc lỗi */
p {
    color: #28a745;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 20px;
}

/* Form thêm cửa hàng */
.form-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.form-container h2 {
    color: #007bff;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

.form-container input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-container input:focus {
    border-color: #007bff;
    outline: none;
}

/* Nút thêm cửa hàng */
button {
    padding: 12px 18px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

button:active {
    transform: scale(0.98);
}

/* Bảng danh sách cửa hàng */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 15px;
    text-align: left;
    font-size: 16px;
}

th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

td {
    background-color: #fff;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

td button {
    width: 90px;
    margin-right: 10px;
}

/* Điều chỉnh thiết bị di động */
@media screen and (max-width: 768px) {
    /* Sidebar */
    .sidebar {
        width: 200px;
    }

    .content {
        margin-left: 200px;
        padding: 15px;
    }

    /* Form */
    .form-container input {
        padding: 10px;
    }

    button {
        font-size: 14px;
        padding: 10px 14px;
    }

    h1 {
        font-size: 24px;
    }

    /* Bảng danh sách cửa hàng */
    table th, table td {
        font-size: 14px;
    }
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
<h1>Store Management</h1>
<?php if (isset($_SESSION['message'])): ?>
    <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

<!-- Form Add Store -->
<div class="form-container">
    <h2>Add Store</h2>
    <form method="POST">
        <input type="text" name="store_name" placeholder="Store Name" required><br><br>
        <input type="text" name="address" placeholder="Address" required><br><br>
        <input type="text" name="phone" placeholder="Phone" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <button type="submit" name="add_store">Add Store</button>
    </form>
</div>

<!-- Store List -->
<h2>Stores List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Store Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['store_name']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td>
            <a href="edit_store.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
            <a href="store_manager.php?delete=<?php echo $row['id']; ?>"><button onclick="return confirm('Are you sure you want to delete?')">Delete</button></a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
