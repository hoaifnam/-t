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
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sản Phẩm</title>
    <style>
        /* Style bảng sản phẩm */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
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

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div>
    <h3>Danh Sách Sản Phẩm</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Loại</th>
                <th>Thương Hiệu</th>
                <th>Mô Tả</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Số Lượng</th>
                <th>Danh Mục</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['brand']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>
                            <td><img src='{$row['image_url']}' alt='product image'></td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['category']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Chưa có sản phẩm nào</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
