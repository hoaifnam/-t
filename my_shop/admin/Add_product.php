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

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];  // Lấy danh mục từ dropdown
    $quantity = $_POST['quantity'];

    // Xử lý hình ảnh
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    if ($image_error === 0) {
        // Đặt tên và đường dẫn lưu ảnh
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid('', true) . '.' . $image_ext;
        $image_destination = 'uploads/' . $image_new_name;

        // Kiểm tra xem thư mục uploads có tồn tại không
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);  // Tạo thư mục uploads nếu chưa có
        }

        // Di chuyển ảnh vào thư mục "uploads"
        if (move_uploaded_file($image_tmp_name, $image_destination)) {
            // Câu lệnh SQL để thêm sản phẩm vào cơ sở dữ liệu
            $sql = "INSERT INTO products (name, type, brand, description, price, image_url, created_at, category, quantity) 
                    VALUES ('$name', '$type', '$brand', '$description', '$price', '$image_destination', NOW(), '$category', '$quantity')";

            if ($conn->query($sql) === TRUE) {
                // Thêm sản phẩm thành công, chuyển hướng về trang quản lý sản phẩm
                echo "<script>alert('Sản phẩm đã được thêm thành công!'); window.location.href = 'Admin Panel.php';</script>";
                exit();
            } else {
                echo "Lỗi: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Lỗi khi tải ảnh lên!";
        }
    } else {
        echo "Có lỗi xảy ra khi tải ảnh lên!";
    }
}

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
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
    <title>Quản Lý Sản Phẩm</title>
   
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .form-container {
        width: 900px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container h3 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
    }

    .form-container label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
        margin-top: 25px;
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container input[type="file"],
    .form-container select,
    .form-container textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-container textarea {
        height: 100px;
        resize: none;
    }

    .form-container .button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container .button:hover {
        background-color: #0056b3;
    }
    .back-link {
        
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

<div class="form-container">
    

    <h3>Thêm Sản Phẩm Mới</h3>
    <a href="Admin Panel.php" class="back-link"> Quay lại</a>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <!-- Tên sản phẩm -->
        <div>
            <label for="name">Tên Sản Phẩm:</label>
            <input type="text" id="name" name="name" required>
        </div>

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

        <!-- Thương hiệu -->
        <div>
            <label for="brand">Thương Hiệu:</label>
            <input type="text" id="brand" name="brand" required>
        </div>

        <!-- Mô tả -->
        <div>
            <label for="description">Mô Tả:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <!-- Hình ảnh -->
        <div>
            <label for="image">Hình Ảnh:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <!-- Giá -->
        <div>
            <label for="price">Giá:</label>
            <input type="number" id="price" name="price" required>
        </div>

        <!-- Danh mục (dropdown) -->
        <div>
            <label for="category">Danh Mục:</label>
            <select id="category" name="category" required>
                <option value="Phone">Phone</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>

        <!-- Số lượng -->
        <div>
            <label for="quantity">Số Lượng:</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>

        <div>
            <button type="submit" class="button">Thêm Sản Phẩm</button>
        </div>
    </form>
</div>
</body>
</html>
