<?php
session_start();
include('db.php'); // Kết nối với CSDL


// Truy vấn lấy thông tin cửa hàng từ bảng `store_info`
$sql = "SELECT * FROM store_info LIMIT 1";
$result = $conn->query($sql);
$store_info = $result->fetch_assoc();

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .content {
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        .section-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #333;
        }

        .contact-info {
            margin-top: 30px;
            font-size: 1.1rem;
            color: #333;
        }

        .contact-info p {
            margin: 5px 0;
        }

        .back-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <h1>About Our Store</h1>
</header>

<!-- Main Content -->
<div class="content">
    <h2 class="section-title">Chúng tôi là ai</h2>
    <p class="section-text">
    Chúng tôi là cửa hàng trực tuyến hàng đầu cung cấp nhiều loại điện thoại di động, phụ kiện và đồ điện tử chất lượng cao. Mục tiêu của chúng tôi là cung cấp các sản phẩm đáp ứng mọi nhu cầu của khách hàng, từ điện thoại thông minh cơ bản đến các mẫu cao cấp mới nhất với các tính năng tiên tiến.
    </p>

    <h2 class="section-title">Sứ mệnh của chúng tôi</h2>
    <p class="section-text">
    Sứ mệnh của chúng tôi là cung cấp dịch vụ khách hàng đặc biệt và cung cấp các sản phẩm chất lượng cao để đảm bảo sự hài lòng của khách hàng. Chúng tôi nỗ lực để giữ cho khách hàng của mình được thông báo và giúp họ đưa ra quyết định mua hàng đúng đắn.
    </p>

    <h2 class="section-title">Tại sao chọn chúng tôi</h2>
    <p class="section-text">
    - Sản phẩm đa dạng<br>
    - Hỗ trợ khách hàng hàng đầu<br>
    - Giao hàng nhanh chóng và đáng tin cậy<br>
    - Phương thức thanh toán an toàn và đáng tin cậy<br>
    - Giá cả phải chăng và chiết khấu
    </p>

    <h2 class="section-title">Liên hệ với chúng tôi</h2>
    <div class="contact-info">
        <p><strong>Name:</strong>
        <?php
        // Hiển thị tên cửa hàng
        if (isset($store_info['store_name'])) {
            echo htmlspecialchars($store_info['store_name']);
        } else {
            echo "Tên cửa hàng chưa được cập nhật.";
        }
        ?>
        </p>
        <p><strong>Email:</strong>
        <?php
        // Hiển thị email cửa hàng
        if (isset($store_info['email'])) {
            echo htmlspecialchars($store_info['email']);
        } else {
            echo "Email chưa được cập nhật.";
        }
        ?>
        </p>
        <p><strong>Phone:</strong>
        <?php
        // Hiển thị số điện thoại cửa hàng
        if (isset($store_info['phone'])) {
            echo htmlspecialchars($store_info['phone']);
        } else {
            echo "Số điện thoại chưa được cập nhật.";
        }
        ?>
        </p>
        <p><strong>Address:</strong>
        <?php
        // Hiển thị địa chỉ cửa hàng
        if (isset($store_info['address'])) {
            echo htmlspecialchars($store_info['address']);
        } else {
            echo "Địa chỉ chưa được cập nhật.";
        }
        ?>
        </p>
    </div>

    <a href="index.php" class="back-button">Back to Home</a>
</div>

</body>
</html>
