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

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM products ORDER BY id ASC";
$result = $conn->query($sql);


// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị Cửa Hàng</title>
    <style>
        /* Cấu trúc chung */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

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
        }

        .sidebar h2 {
            color: white;
            font-size: 22px;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 0;
            text-decoration: none;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content h1 {
            color: #333;
        }

        .section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .section h3 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #007bff;
        }

        .section p {
            color: #555;
        }

        .button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button:hover {
            background-color: #218838;
        }

        /* Bảng danh sách sản phẩm */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td img {
            width: 50px; /* Kích thước hình ảnh */
            height: auto;
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

<!-- Main Content -->
<div class="content">
    
    <!-- Quản lý sản phẩm -->
    <div class="section">
        <h3>Quản Lý Sản Phẩm</h3>
        <p>Danh sách sản phẩm hiện tại của cửa hàng:</p>
        <a href="add_product.php" class="button">Thêm Sản Phẩm Mới</a>
        
        <!-- Bảng sản phẩm -->
        <table id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Loại</th>
                    <th>Thương Hiệu</th>
                    <th>Giá</th>
                    <th>Mô Tả</th>
                    <th>Hình Ảnh</th>
                    <th>Ngày Tạo</th>
                    <th>Danh Mục</th>
                    <th>Số Lượng</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hiển thị các sản phẩm từ cơ sở dữ liệu
                if ($result->num_rows > 0) {
                    // Duyệt qua từng sản phẩm
                    while($row = $result->fetch_assoc()) {
                        echo "<tr id='product" . $row["id"] . "'>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["type"] . "</td>
                                <td>" . $row["brand"] . "</td>
                                <td>" . number_format($row["price"], 0, ',', '.') . " VND</td>
                                <td>" . $row["description"] . "</td>
                                <td><img src='" . $row["image_url"] . "' alt='Product Image'></td>
                                <td>" . $row["created_at"] . "</td>
                                <td>" . $row["category"] . "</td>
                                <td>" . $row["quantity"] . "</td>
                                <td>
                                    <a href='edit_product.php?id=" . $row["id"] . "' class='button'>Sửa</a>
                                    <button class='button' onclick='deleteProduct(" . $row["id"] . ")'>Xóa</button>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Không có sản phẩm nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    // Hàm xóa sản phẩm và cập nhật UI
    function deleteProduct(productId) {
        var confirmation = confirm("Bạn có chắc chắn muốn xóa sản phẩm này?");
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "delete_product.php?id=" + productId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response === "success") {
                        // Xóa sản phẩm khỏi bảng mà không tải lại trang
                        var productRow = document.getElementById("product" + productId);
                        productRow.parentNode.removeChild(productRow);
                    } else {
                        alert("Có lỗi xảy ra khi xóa sản phẩm.");
                    }
                }
            };
            xhr.send();
        }
    }
</script>

</body>
</html>
