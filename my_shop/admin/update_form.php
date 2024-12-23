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

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy sản phẩm!";
    }
} else {
    echo "Không có ID sản phẩm.";
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Sản Phẩm</title>
</head>
<body>
    <h2>Cập Nhật Thông Tin Sản Phẩm</h2>
    
    <form action="update_product.php?id=<?php echo $productId; ?>" method="POST">
        <label for="name">Tên Sản Phẩm:</label>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br>

        <label for="type">Loại:</label>
        <input type="text" id="type" name="type" value="<?php echo $product['type']; ?>" required><br>

        <label for="brand">Thương Hiệu:</label>
        <input type="text" id="brand" name="brand" value="<?php echo $product['brand']; ?>" required><br>

        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required><br>

        <label for="description">Mô Tả:</label>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br>

        <label for="category">Danh Mục:</label>
        <input type="text" id="category" name="category" value="<?php echo $product['category']; ?>" required><br>

        <label for="quantity">Số Lượng:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required><br>

        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
