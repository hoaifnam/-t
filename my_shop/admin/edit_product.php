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

// Kiểm tra nếu có ID sản phẩm trong URL
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productName = $row['name'];
        $productType = $row['type'];
        $productBrand = $row['brand'];
        $productPrice = $row['price'];
        $productDescription = $row['description'];
        $productImage = $row['image_url'];
        $productCategory = $row['category'];
        $productQuantity = $row['quantity'];
    } else {
        echo "Sản phẩm không tồn tại.";
        exit;
    }
} else {
    echo "Không có ID sản phẩm.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-top: 20px;
    }

    form {
        max-width: 600px;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select,
    input[type="file"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
    }

    textarea {
        height: 100px;
        resize: vertical;
    }

    button {
        width: 100%;
        padding: 10px 15px;
        background-color: #28a745;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #218838;
    }

    /* Add styles for dropdown menus */
    select {
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        background: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="black" d="M2 0L0 2h4z"/></svg>') no-repeat right 10px center;
        background-color: #fff;
        background-size: 12px 12px;
        cursor: pointer;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        form {
            width: 90%;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 10px);
        }
    }
    .back-link {
        display: inline-block;
        margin: 20px 0;
        text-decoration: none;
        font-size: 18px;
        color: #007bff;
        font-weight: bold;
        padding: 10px 15px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #0056b3;
        background-color: #e8e8e8;
        text-decoration: none;
        border-color: #bbb;
    }

    .back-link:before {
        content: "⬅ "; /* Thêm biểu tượng mũi tên trước chữ */
    }
</style>

<body>
    
    <h1>Sửa Sản Phẩm</h1>
    <a href="Admin Panel.php" class="back-link"> Quay lại</a>

    <form action="update_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $productId; ?>">
        <label for="name">Tên Sản Phẩm:</label>
        <input type="text" name="name" value="<?php echo $productName; ?>"><br>

        <!-- Loại sản phẩm (dropdown) -->
        <div>
            <label for="type">Loại Sản Phẩm:</label>
            <select id="type" name="type" required>
                <option value="Phone">Phone</option>
                <option value="Strength">Strength</option>
                <option value="Screen">Screen</option>
                <option value="Cover">Cover</option>
                <option value="Battery">Battery</option>
                <option value="Charging">Charging</option>
                <option value="Power Bank">Power Bank</option>
            </select>
        </div>
        <label for="brand">Thương Hiệu:</label>
        <input type="text" name="brand" value="<?php echo $productBrand; ?>"><br>

        <label for="price">Giá:</label>
        <input type="number" name="price" value="<?php echo $productPrice; ?>"><br>

        <label for="description">Mô Tả:</label>
        <textarea name="description"><?php echo $productDescription; ?></textarea><br>

        <label for="image">Hình Ảnh:</label>
        <input type="file" name="image"><br>

        <!-- Danh mục (dropdown) -->
        <div>
            <label for="category">Danh Mục:</label>
            <select id="category" name="category" required>
                <option value="Phone">Phone</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>
        <label for="quantity">Số Lượng:</label>
        <input type="number" name="quantity" value="<?php echo $productQuantity; ?>"><br>

        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
