<?php
session_start();

// Kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra giỏ hàng
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Truy vấn thông tin sản phẩm
$productData = [];
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $sql = "SELECT id, name, price, image_url, type FROM products WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($cart)), ...array_keys($cart));
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart[$row['id']]; // Số lượng từ session
        $productData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Đoạn CSS cập nhật theo yêu cầu */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            background: linear-gradient(to right, #ece9e6, #ffffff);
        }
        h1 {
            text-align: center;
            color: #444;
            margin-top: 20px;
        }
        .cart-container {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Khoảng cách giữa các phần tử */
            border-bottom: 1px solid #ddd;
            padding: 15px 10px;
            transition: box-shadow 0.3s ease;
        }
        .cart-item:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            margin-right: 20px;
            border-radius: 8px;
        }
        .product-details {
            flex: 1; /* Chiếm phần còn lại của hàng */
        }
        h3 {
            margin: 5px 0;
            color: #333;
        }
        p {
            margin: 5px 0;
            color: #666;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 45%; /* Khoảng cách từ Quantity đến nút Remove */
        }
        .quantity-controls button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .quantity-controls button:hover {
            background-color: #0056b3;
        }
        .remove-btn {
            background: #dc3545;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-left: auto; /* Đẩy nút Remove sang bên phải */
        }
        .remove-btn:hover {
            background: #bd2130;
        }
        .checkout-btn {
            background: #28a745;
            color: white;
            text-transform: uppercase;
            font-size: 16px;
            padding: 12px;
            margin: 20px auto 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 200px;
            text-align: center;
            transition: background 0.3s ease;
        }
        .checkout-btn:hover {
            background: #218838;
        }
        input[type="checkbox"] {
            margin-bottom: 15%;
        }
    </style>
</head>
<body>

<h1>Your Shopping Cart</h1>
<a href="index.php" class="back-button">Quay lại</a>

<?php if (empty($productData)): ?>
    <p style="text-align:center; font-size:18px;">Your cart is empty.</p>
<?php else: ?>
    <form action="checkout.php" method="POST">
        <div class="cart-container">
            <?php foreach ($productData as $product): ?>
                <div class="cart-item">

                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p>Price: <strong>$<?php echo $product['price']; ?></strong></p>
                        <p>Quantity: <strong><?php echo $product['quantity']; ?></strong></p>
                        <p>Subtotal: <strong>$<?php echo $product['price'] * $product['quantity']; ?></strong></p>

                        <!-- Hiển thị lựa chọn màu và GB chỉ khi sản phẩm là điện thoại -->
                        <?php if ($product['type'] == 'phone'): ?>
                            <label for="color-<?php echo $product['id']; ?>">Color:</label>
                            <select name="product_details[<?php echo $product['id']; ?>][color]" id="color-<?php echo $product['id']; ?>">
                                <option value="black">Black</option>
                                <option value="white">White</option>
                                <option value="blue">Blue</option>
                            </select>

                            <label for="gb-<?php echo $product['id']; ?>">GB:</label>
                            <select name="product_details[<?php echo $product['id']; ?>][gb]" id="gb-<?php echo $product['id']; ?>">
                                <option value="64">64GB</option>
                                <option value="128">128GB</option>
                                <option value="256">256GB</option>
                            </select>
                        <?php endif; ?>
                        <!-- Hiển thị lựa chọn màu và GB chỉ khi sản phẩm là cường lực -->
                        <?php if ($product['type'] == 'strength'): ?>
                            <label for="color-<?php echo $product['id']; ?>">Color:</label>
                            <select name="product_details[<?php echo $product['id']; ?>][color]" id="color-<?php echo $product['id']; ?>">
                                <option value="Iphone 14promax">Iphone 14promax</option>
                                <option value="white">Iphone 14</option>
                                <option value="blue">Iphone 15</option>
                            </select>
                        <?php endif; ?>

                        <input type="hidden" name="product_details[<?php echo $product['id']; ?>][name]" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_details[<?php echo $product['id']; ?>][price]" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_details[<?php echo $product['id']; ?>][quantity]" value="<?php echo $product['quantity']; ?>">
                        <input type="hidden" name="product_details[<?php echo $product['id']; ?>][image_url]" value="<?php echo $product['image_url']; ?>">
                         <!-- Truyền số lượng -->
                <input type="hidden" name="selected_products[<?php echo $product['id']; ?>][quantity]" value="<?php echo $quantity; ?>">
                    </div>
                    <div class="quantity-controls">
                        <button type="button" onclick="updateQuantity(<?php echo $product['id']; ?>, -1)">-</button>
                        <span id="quantity-<?php echo $product['id']; ?>"><?php echo $product['quantity']; ?></span>
                        <button type="button" onclick="updateQuantity(<?php echo $product['id']; ?>, 1)">+</button>
                    </div>

                    <button type="button" class="remove-btn" onclick="removeProduct(<?php echo $product['id']; ?>)">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
    </form>
<?php endif; ?>

<script>
    // Hàm cập nhật số lượng sản phẩm
    function updateQuantity(productId, change) {
        const quantityElement = document.getElementById('quantity-' + productId);
        let currentQuantity = parseInt(quantityElement.innerText);
        const newQuantity = currentQuantity + change;

        if (newQuantity < 1) return; // Không cho số lượng nhỏ hơn 1

        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&quantity=${newQuantity}`
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  quantityElement.innerText = newQuantity;
                  location.reload();
              }
          });
    }

    // Hàm xóa sản phẩm khỏi giỏ hàng
    function removeProduct(productId) {
        fetch('remove_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Bạn có chắc muốn xóa sản phẩm khỏi rỏ hàng ?');
                  location.reload();
              }
          });
    }
</script>

</body>
</html>