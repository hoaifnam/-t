<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy `id` từ URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra xem `id` có hợp lệ không
if ($productId <= 0) {
    echo "ID sản phẩm không hợp lệ!";
    exit;
}

// Truy vấn sản phẩm dựa vào `id`
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem sản phẩm có tồn tại không
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Không tìm thấy sản phẩm!";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .product-details {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 20px;
            margin-top: -150px;
            margin-left: -50%;
        }

        .product-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-info h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        .product-info p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-info .price {
            font-size: 24px;
            color: #007bff;
            font-weight: bold;
        }

        .product-info .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .actions a, .actions button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .actions .back-button {
            background-color: #f4f4f4;
            color: #333;
        }

        .actions .add-to-cart {
            background-color: #007bff;
            color: white;
        }

        .actions .back-button:hover {
            background-color: #ddd;
        }

        .actions .add-to-cart:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="product-details">
        <!-- Hình ảnh sản phẩm bên trái -->
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <!-- Thông tin sản phẩm bên phải -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
            <div class="actions">
                <a href="index.php" class="back-button">Quay lại</a>
                <button class="add-to-cart" onclick="addToCart(<?php echo $productId; ?>)">Thêm vào giỏ hàng</button>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId, quantity = 1) {
            // Gửi yêu cầu AJAX để thêm sản phẩm vào giỏ hàng
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng!');
                } else {
                    alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Có lỗi xảy ra:', error);
                alert('Không thể kết nối với server.');
            });
        }
    </script>
</body>
</html>
