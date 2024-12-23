<?php
// Kết nối tới cơ sở dữ liệu
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

// Lấy thông tin từ bảng contact_requests
$sql = "SELECT * FROM contact_requests";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin Liên Hệ</title>
    <style>
       /* General Styling */
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
    margin-left: 250px; /* Push content aside to account for sidebar */
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
    color: white;
    font-size: 18px;
}

.sidebar h2 {
    color: white;
    font-size: 24px;
    margin-bottom: 40px;
    text-transform: uppercase;
    text-align: center;
}

.sidebar a {
    display: block;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: 15px;
    border-radius: 4px;
    text-align: left;
}

.sidebar a:hover {
    background-color: #0056b3;
}

/* Main Content */
.content {
    padding: 30px;
    margin-left: 250px; /* Align content next to sidebar */
}

h1 {
    color: #333;
    font-size: 32px;
    margin-bottom: 20px;
}

/* Success and Error Messages */
p.success {
    color: #28a745;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 20px;
}

p.error {
    color: #dc3545;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 20px;
}

/* Form Styling */
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

.form-container input, 
.form-container textarea, 
.form-container select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-container input:focus, 
.form-container textarea:focus, 
.form-container select:focus {
    border-color: #007bff;
    outline: none;
}

/* Buttons */
button {
    padding: 12px 18px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

button:active {
    transform: scale(0.98);
}

/* Email List Table */
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

.action-buttons a button {
    display: inline-block;
    width: 90px;
    margin-right: 10px;
    text-align: center;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .sidebar {
        width: 200px;
    }

    .content {
        margin-left: 200px;
        padding: 15px;
    }

    .form-container input, 
    .form-container textarea, 
    .form-container select {
        font-size: 14px;
    }

    button {
        font-size: 14px;
        padding: 10px 14px;
    }

    h1 {
        font-size: 24px;
    }

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

<header>
    <h1>Danh Sách Liên Hệ</h1>
</header>

<div class="container">
    <h2>Thông Tin Liên Hệ Từ Khách Hàng</h2>
    
    <?php
    // Kiểm tra nếu có dữ liệu từ bảng contact_requests
    if ($result->num_rows > 0) {
        // Hiển thị dữ liệu trong bảng
        echo "<table>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Chủ Đề</th>
                    <th>Nội Dung</th>
                    <th>Ngày Yêu Cầu</th>
                </tr>";

        // Xuất dữ liệu cho mỗi hàng
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["email"]) . "</td>
                    <td>" . htmlspecialchars($row["subject"]) . "</td>
                    <td>" . nl2br(htmlspecialchars($row["message"])) . "</td>
                    <td>" . $row["created_at"] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-requests'>Không có yêu cầu liên hệ nào trong hệ thống.</p>";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>


</body>
</html>
