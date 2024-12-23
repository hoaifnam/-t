<?php
session_start();
include('db.php');

// Lấy số lượng sản phẩm
$sql_products = "SELECT COUNT(*) as total_products FROM products";
$result_products = $conn->query($sql_products);
$row_products = $result_products->fetch_assoc();
$totalProducts = $row_products['total_products'];

// Lấy số lượng đơn hàng
$sql_orders = "SELECT COUNT(*) as total_orders FROM orders";
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
$totalOrders = $row_orders['total_orders'];

// Lấy số lượng người dùng
$sql_users = "SELECT COUNT(*) as total_users FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$totalUsers = $row_users['total_users'];

// Lấy tổng tiền kiếm được từ đơn hàng (tổng giá trị đơn hàng)
$sql_total_amount = "SELECT SUM(total_amount) as total_amount FROM orders WHERE payment_status = 'Paid'";
$result_total_amount = $conn->query($sql_total_amount);
$row_total_amount = $result_total_amount->fetch_assoc();
$totalAmount = $row_total_amount['total_amount'];

// Lấy doanh thu theo tháng trong năm
$sql_revenue = "SELECT MONTH(created_at) as month, SUM(total_amount) as monthly_revenue
                FROM orders
                WHERE payment_status = 'Paid'
                GROUP BY MONTH(created_at)
                ORDER BY MONTH(created_at)";
$result_revenue = $conn->query($sql_revenue);

$months = [];
$revenues = [];
$growthPercent = []; // Lưu trữ sự thay đổi tăng/giảm theo tháng
$previousRevenue = 0;

while ($row_revenue = $result_revenue->fetch_assoc()) {
    $months[] = $row_revenue['month'];
    $revenues[] = $row_revenue['monthly_revenue'];

    // Tính sự thay đổi (tăng/giảm) so với tháng trước
    if ($previousRevenue > 0) {
        $change = (($row_revenue['monthly_revenue'] - $previousRevenue) / $previousRevenue) * 100;
        $growthPercent[] = number_format($change, 2); // Lưu lại tỷ lệ tăng/giảm
    } else {
        $growthPercent[] = 0; // Tháng đầu tiên không có sự thay đổi
    }

    // Cập nhật doanh thu của tháng này làm doanh thu để tính tháng sau
    $previousRevenue = $row_revenue['monthly_revenue'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Global reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            padding: 20px;
            margin-left: 250px;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #007bff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 30px;
            color: white;
            font-size: 18px;
            text-align: left;
            padding-left: 20px;
        }

        .sidebar h2 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: normal;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 1.1rem;
            margin-bottom: 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        /* Dashboard styling */
        .dashboard {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        /* Stat-box Styles */
        .stat-box {
            background-color: #4CAF50;
            color: white;
            padding: 30px;
            margin: 15px 0;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-box h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-box p {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Media query for smaller screens */
        @media (max-width: 1024px) {
            body {
                margin-left: 0;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding-top: 20px;
            }

            .dashboard {
                flex-direction: column;
                align-items: center;
            }

            .stat-box {
                width: 80%;
                margin-bottom: 20px;
            }
        }

        /* Sửa lại CSS cho canvas */
        .stat-box canvas {
            width: 100% !important; /* Chiếm toàn bộ chiều rộng */
            height: 350px !important; /* Chiều cao biểu đồ */
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

<h1>Admin Dashboard</h1>

<div class="dashboard">
    <!-- Total Products -->
    <div class="stat-box">
        <h2>Total Products</h2>
        <p><?php echo $totalProducts; ?></p>
    </div>

    <!-- Total Orders -->
    <div class="stat-box">
        <h2>Total Orders</h2>
        <p><?php echo $totalOrders; ?></p>
    </div>

    <!-- Total Users -->
    <div class="stat-box">
        <h2>Total Users</h2>
        <p><?php echo $totalUsers; ?></p>
    </div>

    <!-- Total Earnings -->
    <div class="stat-box">
        <h2>Total Earnings</h2>
        <p><?php echo number_format($totalAmount, 2); ?> VND</p>
    </div>
</div>

<!-- Doanh Thu Theo Tháng -->
<div class="stat-box">
    <h2>Doanh Thu Theo Tháng</h2>
    <canvas id="revenueChart"></canvas>
</div>

<!-- Chart.js for Monthly Revenue -->
<script>
    var ctx = document.getElementById('revenueChart').getContext('2d');

    // Tạo giá trị bắt đầu từ 0
    var revenueData = new Array(12).fill(0); // Các tháng từ 0 - 11, ban đầu đều là 0
    var growthData = new Array(12).fill(0); // Tăng trưởng khởi tạo từ 0

    // Điền dữ liệu thực tế vào
    <?php
    foreach ($months as $index => $month) {
        echo "revenueData[$month - 1] = " . $revenues[$index] . ";\n";  // Điền doanh thu của từng tháng vào biểu đồ
        echo "growthData[$month - 1] = " . $growthPercent[$index] . ";\n";  // Điền tỉ lệ tăng trưởng
    }
    ?>

    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Các tháng trong năm
            datasets: [{
                label: 'Doanh Thu (VND)',
                data: revenueData,  // Doanh thu theo tháng
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(14, 15, 14, 0.2)',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'Tăng Trưởng (%)',
                data: growthData,  // Tăng trưởng theo tháng
                borderColor: '#FF9800',
                backgroundColor: 'rgba(255, 152, 0, 0.2)',
                fill: false,
                borderWidth: 2,
                tension: 0.3,
                yAxisID: 'y-axis-2'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toLocaleString();  // Định dạng VND
                        }
                    },
                    title: {
                        display: true,
                        text: 'Doanh Thu (VND)'
                    }
                },
                'y-axis-2': {
                    type: 'linear',
                    position: 'right',
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value + '%'; // Tỷ lệ tăng trưởng % ở trục phải
                        }
                    },
                    title: {
                        display: true,
                        text: 'Tăng Trưởng (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tháng'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            if (tooltipItem.datasetIndex === 0) {
                                return 'Doanh Thu: VND ' + tooltipItem.raw.toLocaleString();
                            } else {
                                return 'Tăng Trưởng: ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
