<?php
session_start();
include('db.php'); // Kết nối đến CSDL

// Lấy giỏ hàng
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$selectedProducts = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];

// Kiểm tra giỏ hàng
if (empty($selectedProducts)) {
    echo "<h2>Your cart is empty or no products selected!</h2>";
    exit;
}

// Lấy thông tin tài khoản ngân hàng của người dùng
$user_id = $_SESSION['user_id'];  // Giả sử bạn lưu id người dùng trong session
$sql_accounts = "SELECT id, account_details FROM user_bank_accounts WHERE user_id = ?";
$stmt_accounts = $conn->prepare($sql_accounts);
$stmt_accounts->bind_param('i', $user_id);
$stmt_accounts->execute();
$accounts_result = $stmt_accounts->get_result();
$bankAccounts = [];
while ($row = $accounts_result->fetch_assoc()) {
    $bankAccounts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
        padding: 20px;
    }

    h1, h2, h3 {
        font-family: 'Arial', sans-serif;
        color: #4CAF50;
    }

    h3 {
        font-size: 1.1em;
    }

    /* Checkout Container */
    .checkout-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        max-width: 1000px;
        margin: 30px auto;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .checkout-left, .checkout-right {
        flex: 1;
    }

    /* Cart Items Section */
    .checkout-item {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .checkout-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin-right: 20px;
        border-radius: 5px;
    }

    .product-details {
        flex: 1;
    }

    .product-details h3 {
        font-size: 1.2em;
        color: #333;
    }

    .product-details p {
        color: #666;
        margin: 5px 0;
    }

    /* Total Price Section */
    .total {
        font-size: 1.5em;
        font-weight: bold;
        padding-top: 20px;
        text-align: right;
    }

    /* Customer Form Section */
    .checkout-right h3 {
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        font-size: 1em;
        margin-bottom: 8px;
        color: #555;
    }

    input[type="text"], select {
        font-size: 1em;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 15px;
        width: 100%;
    }

    input[type="radio"] {
        margin-right: 8px;
    }

    .checkout-btn {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        font-size: 1.2em;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .checkout-btn:hover {
        background-color: #45a049;
    }

    /* Responsive Styling */
    @media (max-width: 768px) {
        .checkout-container {
            flex-direction: column;
            gap: 15px;
        }

        .checkout-left, .checkout-right {
            flex: none;
            width: 100%;
        }

        .checkout-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .checkout-item img {
            width: 100px;
            height: 100px;
        }

        .total {
            text-align: center;
        }
    }
</style>

</head>
<body>
<h1>Checkout</h1>
<a href="index.php" class="back-button">Quay lại</a>

<div class="checkout-container">
    <!-- Cart items section -->
    <div class="checkout-left">
        <?php 
            // Hiển thị các sản phẩm trong giỏ hàng và tính tổng tiền
            $totalAmount = 0;
            foreach ($selectedProducts as $productId => $quantity):
                // Lấy thông tin sản phẩm từ CSDL
                $sql_product = "SELECT id, name, price, image_url, type FROM products WHERE id = ?";
                $stmt = $conn->prepare($sql_product);
                $stmt->bind_param('i', $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $quantity = isset($selectedProducts[$productId]) ? (int) $selectedProducts[$productId] : 0;

                // Tính tổng
                $subtotal = $product['price'] * $quantity;
                $totalAmount += $subtotal;
        ?>
            <div class="checkout-item">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <p>Quantity: <?php echo $quantity; ?></p>
                    <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total">
            <h2>Total: $<?php echo number_format($totalAmount, 2); ?></h2>
        </div>
    </div>

    <!-- Customer details section -->
    <div class="checkout-right">
        <h3>Customer Information</h3>
        <form method="POST" action="submit_order.php">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="customer[name]" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="customer[address]" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="customer[phone]" required>

            <h3>Payment Method</h3>
            <label>
                <input type="radio" name="payment_method" value="credit_card" id="credit_card" required> Credit Card
            </label>
            <label>
                <input type="radio" name="payment_method" value="cash_on_delivery"> Cash on Delivery
            </label>

            <div id="credit_card_account" style="display:none;">
                <label for="credit_account">Select Credit Card</label>
                <select name="payment_account" id="credit_account">
                    <option value="">-- Select Your Credit Card --</option>
                    <?php foreach ($bankAccounts as $account): ?>
                        <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['account_details']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="checkout-btn">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
    // Hiển thị tài khoản ngân hàng khi chọn phương thức thanh toán là Credit Card
    document.querySelectorAll('input[name="payment_method"]').forEach((elem) => {
        elem.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                document.getElementById('credit_card_account').style.display = 'block';
            } else {
                document.getElementById('credit_card_account').style.display = 'none';
            }
        });
    });
</script>

</body>
</html>