<?php
session_start();
include('db.php'); // Kết nối với CSDL

// Truy vấn lấy tất cả người dùng, bao gồm cả cột account_status
$sql = "SELECT id, username, email, account_status FROM users";
$result = $conn->query($sql);

// Kiểm tra nếu có dữ liệu trong bảng
if ($result->num_rows > 0):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
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
        padding: 20px;
        color: #333;
    }

    h1 {
        color: #007bff;
        text-align: center;
        font-size: 2.4rem;
        margin-bottom: 30px;
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
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
        font-size: 18px;
    }

    .sidebar h2 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 40px;
        text-align: center;
    }

    .sidebar a {
        display: block;
        padding: 12px 15px;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 5px;
        margin-bottom: 20px;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #0056b3;
    }

    /* Container */
    .container {
        max-width: 900px;
        margin-left: 270px; /* To leave space for the sidebar */
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* Back Button */
    .back-button {
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 10px 18px;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        margin-bottom: 20px;
        transition: background-color 0.3s ease;
    }

    .back-button:hover {
        background-color: #218838;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        font-size: 1rem;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: left;
        background-color: #f9f9f9;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s ease;
    }

    td {
        font-size: 16px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .sidebar {
            width: 220px;
        }
        
        .container {
            margin-left: 0;
            padding: 20px;
        }

        h1 {
            font-size: 2rem;
        }

        table th, table td {
            font-size: 14px;
        }

        .back-button {
            font-size: 14px;
            padding: 8px 15px;
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

    <div class="container">
        <h1>Thông Tin Tài Khoản</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Account Status</th> <!-- Thêm cột account_status -->
                </tr>
            </thead>
            <tbody>
                <?php
                    // Lặp qua tất cả kết quả và hiển thị thông tin
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <?php
                                // Kiểm tra trạng thái tài khoản và hiển thị tương ứng
                                echo $row['account_status'] == 1 ? "Quản trị" : "Khách";
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
else:
    echo "<p>No users found.</p>";
endif;

// Đóng kết nối
$conn->close();
?>
