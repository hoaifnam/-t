<?php
session_start(); // Bắt đầu session
// Lấy thông tin danh mục từ URL nếu có
$category = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
    <div class="header-container">
        <h1>Phone Store</h1>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Tìm kiếm">
            <button onclick="searchProduct()">Tìm kiếm</button>
        </div>
    </div>
    
    <!-- Header Navigation Section -->
    <header>
        <nav>
            <ul class="nav-left">
                <li><a href="index.php">Trang chủ</a></li>
                <li class="dropdown">
                    <a href="#">Sản phẩm</a>
                    <ul class="dropdown-content">
                        <li><a href="index.php?category=phone">Điện thoại</a></li>
                        <li><a href="index.php?category=accessories">Phụ kiện</a></li>
                        <li><a href="index.php?category=promotion">Khuyến mãi</a></li>
                    </ul>
                </li>
                <li><a href="send_message.php">Liên hệ</a></li>
                <li><a href="about_us.php">Giới thiệu</a></li>
            </ul>
            <ul class="nav-right">
                <li><a href="cart.php">&#128722; Giỏ hàng <span id="cart-count">0</span></a></li>
                <li class="dropdown">
                    <?php
                    if (isset($_SESSION['username'])) { // Nếu người dùng đã đăng nhập
                        echo '<a href="javascript:void(0)"> ' . $_SESSION['username'] . '</a>';
                        echo '<div class="dropdown-content">';
                        echo '<a href="order_history.php">Đơn hàng</a>';
                        echo '<a href="change_password.php">Đổi mật khẩu</a>';
                        echo '<a href="logout.php">Đăng xuất</a>';
                        echo '</div>';
                    } else {
                        echo '<div class="auth-links">';
                        echo '<a href="login.html">Đăng nhập</a>';
                        echo '<a href="register.html">Đăng ký</a>';
                        echo '</div>';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main>
    <div class="slider">
    <div class="slides">
        <div class="slide">
            <img src="th (2).jpg" alt="Banner 1">
        </div>
        <div class="slide">
            <img src="th (1).jpg" alt="Banner 2">
        </div>
        <div class="slide">
            <img src="th.jpg" alt="Banner 3">
        </div>
    </div>
    <div class="navigation">
        <span class="prev" onclick="changeSlide(-1)">&#10094;</span>
        <span class="next" onclick="changeSlide(1)">&#10095;</span>
    </div>
</div>

    </div>
        <h2>Danh Sách Sản Phẩm</h2>
        <div class="product-list">
            <?php
            // Kết nối cơ sở dữ liệu
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "phone_store"; // Thay bằng tên cơ sở dữ liệu của bạn

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Kiểm tra kết nối
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }

            // Xây dựng câu truy vấn SQL để lọc theo category nếu có
            $sql = "SELECT * FROM products";
            if ($category) {
                $sql .= " WHERE category = '$category'"; // Lọc theo danh mục
            }

            // Truy vấn lấy danh sách sản phẩm
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<div class="product-image">';
                    echo '<img src="' . $row["image_url"] . '" alt="' . $row["name"] . '">';
                    echo '</div>';
                    echo '<h3>' . $row["name"] . '</h3>';
                    echo '<p class="price">$' . $row["price"] . '</p>';
                    echo '<button class="add-to-cart" onclick="addToCart(' . $row['id'] . ')">Thêm vào giỏ hàng</button>';
                    echo '<button class="view-details" onclick="viewDetails(' . $row['id'] . ')">Xem chi tiết</button>';
                    echo '</div>';
                }
            } else {
                echo "Không có sản phẩm nào trong danh mục này!";
            }

            $conn->close();
            ?>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Phone Store. Mọi quyền được bảo lưu.</p>
    </footer>

    <script>
        function searchProduct() {
            let input = document.getElementById('search-input').value.toLowerCase();
            let products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                let productName = product.querySelector('h3').innerText.toLowerCase();
                let productDescription = product.querySelector('p').innerText.toLowerCase();
                
                if (productName.includes(input) || productDescription.includes(input)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        function addToCart(productId, quantity = 1) {
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
                    updateCartCount(); // Cập nhật số lượng giỏ hàng
                } else {
                    alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Có lỗi xảy ra:', error);
                alert('Không thể kết nối với server.');
            });
        }

        function updateCartCount() {
            fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.cartCount;
            })
            .catch(error => {
                console.error('Có lỗi xảy ra:', error);
                alert('Không thể cập nhật số lượng giỏ hàng.');
            });
        }

        window.onload = updateCartCount;

        function viewDetails(productId) {
            window.location.href = `product_details.php?id=${productId}`;
        }

    let slideIndex = 0;

    // Hàm chuyển slide theo hướng
    function changeSlide(direction) {
        showSlide(slideIndex += direction);
    }

    // Hàm để hiển thị slide hiện tại
    function showSlide(index) {
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        
        // Nếu index vượt quá số slide, quay lại đầu
        if (index >= totalSlides) {
            slideIndex = 0;
        }

        // Nếu index nhỏ hơn 0, chuyển đến slide cuối
        if (index < 0) {
            slideIndex = totalSlides - 1;
        }

        // Ẩn tất cả các slide
        slides.forEach(slide => {
            slide.style.display = 'none';
        });

        // Hiển thị slide hiện tại
        slides[slideIndex].style.display = 'block';
    }

    // Gọi hàm để hiển thị slide đầu tiên khi trang web tải
    window.onload = function () {
        showSlide(slideIndex);
        // Tự động chuyển slide sau mỗi 5 giây (5000ms)
        setInterval(function () {
            changeSlide(1);
        }, 1000); // 5000ms = 5 giây
    };


    </script>
</body>
</html>
