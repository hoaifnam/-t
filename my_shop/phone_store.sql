-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 23, 2024 lúc 12:10 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phone_store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_requests`
--

INSERT INTO `contact_requests` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(9, 'gà', 'atm15092003@gmail.com', 'e', '3', '2024-12-18 23:13:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `payment_method` enum('credit_card','cash_on_delivery') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_account` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered') DEFAULT 'Pending',
  `payment_status` enum('Paid','Pending') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `address`, `phone`, `payment_method`, `total_amount`, `created_at`, `payment_account`, `status`, `payment_status`) VALUES
(1, 'ORD-1', 'sq', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 18:19:47', NULL, 'Delivered', 'Paid'),
(2, 'ORD-2', 'sq', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 18:24:34', NULL, 'Delivered', 'Paid'),
(3, '', 'swđ', 'cầu diễn', '0393650486', 'cash_on_delivery', 3000.00, '2024-12-18 18:29:22', NULL, 'Delivered', 'Paid'),
(4, '', 'a', 'cầu diễn', '0393650486', 'cash_on_delivery', 4000.00, '2024-12-18 19:22:56', '', 'Pending', 'Pending'),
(6, '', 'swdd', 'cầu diễn', '0393650486', 'credit_card', 4000.00, '2024-12-18 19:32:44', '1', 'Delivered', 'Paid'),
(7, '', 'swdd', 'cầu diễn', '0393650486', 'credit_card', 4000.00, '2024-12-18 19:40:25', '1', 'Delivered', 'Paid'),
(8, '', 's', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:01:05', '', 'Pending', 'Pending'),
(9, '', 'swdd', 'cầu diễn', '0393650486', 'cash_on_delivery', 5000.00, '2024-12-18 20:05:59', '', 'Pending', 'Pending'),
(10, '', 's', 'cầu diễn', '0393650486', 'cash_on_delivery', 5000.00, '2024-12-18 20:08:38', '', 'Pending', 'Pending'),
(11, '', 'sq', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:09:57', '', 'Delivered', 'Paid'),
(12, '', 'a3', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:44:49', '', 'Pending', 'Pending'),
(13, '', 's', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:51:26', '', 'Pending', 'Pending'),
(14, '', 's', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:56:40', '', 'Delivered', 'Paid'),
(15, '', 'swdd', 'cầu diễn', '0393650486', 'cash_on_delivery', 1000.00, '2024-12-18 20:59:55', '', 'Delivered', 'Paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1, 16, 1),
(2, 2, 16, 1),
(3, 3, 16, 3),
(4, 4, 16, 4),
(7, 6, 16, 2),
(8, 6, 14, 2),
(9, 7, 16, 2),
(10, 7, 14, 2),
(11, 8, 14, 1),
(12, 9, 14, 5),
(13, 10, 14, 5),
(14, 11, 14, 1),
(15, 12, 14, 1),
(16, 13, 16, 1),
(17, 14, 14, 1),
(18, 15, 14, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `type` enum('phone','earphone','charging','battery','strength','cover','screen') NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `rating` decimal(2,1) DEFAULT 0.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `discount_price`, `image_url`, `type`, `brand`, `stock`, `status`, `rating`, `created_at`, `updated_at`, `category`, `quantity`) VALUES
(14, 'iphone 11', 'Apple chính thức giới thiệu bộ 3 siêu phẩm iPhone 2019 mạnh mẽ nhất của mình vào tháng 9/2019. Có mức giá rẻ nhất nhưng vẫn được nâng cấp mạnh mẽ như chiếc iPhone Xr trước đó, đó chính là phiên bản iPhone 11. Trên iPhone 2019 mới Apple nâng cấp con chip của mình lên thế hệ Apple A13 Bionic rất mạnh mẽ và dung lượng RAM 4 GB thay vì 3 GB như thế hệ trước đó.\r\n', 20000.00, NULL, 'uploads/11.jpg', 'phone', 'apple', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:07:42', 'Phone', 731),
(16, 'iphone 12', 'iPhone 12 đã được giới thiệu trong sự kiện Hi, Speed của Apple đêm 14/10 (theo giờ Việt Nam).\r\n\r\nDưới đây là những thông tin mới nhất về iPhone 12 mà Quantrimang.com vừa cập nhật được. Bạn sẽ biết iPhone 12 có những model nào, cấu hình iPhone 12 và giá iPhone 12 (từ Apple). Ngoài ra, còn có những thông tin bên lề khác như hộp iPhone 12 sẽ có gì bên trong đó? So sánh iPhone 12 (dựa trên những gì chúng ta đã biết) với những chiếc máy khác của Apple để xem nó có đáng để bạn chờ đợi hay không.', 1000.00, NULL, 'uploads/12.jpg', 'phone', 'apple', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:08:59', 'Phone', 733),
(17, 'iphone 13', 'iPhone 13 sở hữu hệ thống camera kép xuất sắc nhất từ trước đến nay, bộ vi xử lý Apple A15 nhanh nhất thế giới smartphone cùng thời lượng pin cực khủng, sẵn sàng đồng hành cùng bạn suốt cả ngày\r\n\r\nTrích dẫn nguồn: https://thongsokythuat.vn/sp/apple-iphone-13-2021/', 1000.00, NULL, 'uploads/13.jpg', 'phone', 'apple', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:09:40', 'Phone', 734),
(18, 'iphone 14', 'Kích thước mới lớn hơn 6,7 inch cùng với thiết kế 6,1 inch phổ biến, cùng hệ thống camera kép mới, tính năng Phát hiện Va chạm, dịch vụ an toàn lần đầu tiên có trên điện thoại thông minh với SOS Khẩn cấp qua vệ tinh và thời lượng pin lâu nhất trên iPhone', 1000.00, NULL, 'uploads/14.jpg', 'phone', 'apple', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:10:29', 'Phone', 734),
(19, 'samsung galaxy', 'Máy được trang bị 4GB RAM và 128GB bộ nhớ trong, mang đến không gian lưu trữ rộng rãi cho người dùng.\r\nBộ ba camera bao gồm camera chính 50 MP, camera góc siêu rộng 5 MP và camera macro 2 MP chất lượng cao giúp chụp ảnh sắc nét.\r\nChip MediaTek Dimensity 6300(6nm) đảm bảo xử lý tác vụ mượt mà, cung cấp trải nghiệm mượt mà và nhiều tính năng hữu ích.\r\nPin dung lượng 5000 mAh hỗ trợ sạc nhanh 25W, Galaxy A16 đảm bảo thời gian sử dụng lâu dài cho các hoạt động hàng ngày.', 1000.00, NULL, 'uploads/15.jpg', 'phone', 'samsung', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:12:05', 'Phone', 734),
(20, 'Samsung Galaxy S23 Ultra', 'Galaxy AI tiện ích - Khoanh vùng search đa năng, là trợ lý chỉnh ảnh, note thông minh, phiên dịch trực tiếp\r\nThoả sức chụp ảnh, quay video chuyên nghiệp - Camera đến 200MP, chế độ chụp đêm cải tiến, bộ xử lí ảnh thông minh\r\nChiến game bùng nổ - chip Snapdragon 8 Gen 2 8 nhân tăng tốc độ xử lí, màn hình 120Hz, pin 5.000mAh\r\nNâng cao hiệu suất làm việc với Siêu bút S Pen tích hợp, dễ dàng đánh dấu sự kiện từ hình ảnh hoặc video\r\nThiết kế bền bỉ, thân thiện - Màu sắc lấy cảm hứng từ thiên nhiên, chất liệu kính và lớp phim phủ PET tái chế', 1000.00, NULL, 'uploads/16.jpg', 'phone', 'samsung', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:13:31', 'Phone', 734),
(21, 'tai nghe', 'Giữ cho nhạc chill suốt ngày dài với thời gian phát lên đến 40H, không còn lo lắng về việc hết pin!\r\n03 chế độ EQ tối ưu sẵn: Balance, Vocal, Bass Boost dành cho trải nghiệm âm nhạc theo cách hoàn toàn riêng biệt.\r\nĐắm mình trong âm thanh chi tiết và sống động với màng driver 40mm mạnh mẽ cho chất bass sâu, chân thực mang công nghệ từ Alpha Works Signature Sound.', 1000.00, NULL, 'uploads/17.jpg', 'strength', '1', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:14:59', 'Accessories', 734),
(22, 'cường lực', 'Miếng dán chống va đập iPhone 12/12 Pro với chất liệu kính cao cấp, cùng độ bền vượt trội là người bạn đồng hành lý tưởng cho chiếc iPhone 12/12 Pro của bạn. Giúp bạn tư tin sử dụng máy hằng ngày mà không lo lắng đến các tác động bên ngoài có thể ảnh hưởng đến màn hình trên iPhone của mình.\r\n\r\nBảo vệ máy khỏi các vết trầy xước, va đập với độ cứng 9H\r\nDán màn hình cường lực iPhone 12/12 Pro sử dụng chất liệu kính cường lực được kiểm tra nghiêm ngặt về độ bền đem đến độ cứng 9H giúp hạn chế các vết trầy xước thông thường.\r\n\r\nBảo vệ máy khỏi các vết trầy xước, va đập với độ cứng 9H\r\n\r\nKhi màn hình chịu lực tác động, bề mặt kính hấp thụ hoàn toàn mọ va chấn từ đó tránh gây hư hỏng cho bề mặt kính và tấm nền màn hình trên iPhone 12/12 Pro.\r\n\r\nLớp vật liệu phủ hạn chế bám bẩn, sử dụng chung với mọi loại ốp lưng\r\nMiếng dán chống va đập iPhone 12/12 Pro KingKong được thiết kế sao cho vừa khít với màn hình của máy nhưng vẫn đảm bảo có thể dùng chung với mọi loại ốp lưng mà không gặp hiện tượng hở viền, bung kính.\r\n\r\nLớp vật liệu phủ hạn chế bám bẩn\r\n\r\nLớp vật liệu mỏng được nhà sản xuất phủ lên bề mặt kính giúp hạn chế bám bẩn, dấu vân tay. Người dùng chỉ cần sử dụng khăn lau và vệ sinh nhanh chóng màn hình qua một vài thao tác.\r\n\r\nsử dụng chung với mọi loại ốp lưng\r\n\r\nThao tán dán dễ dàng, không để lại bong bóng hay hở viền\r\nChỉ cần với khăn lau và vệ sinh bề mặt màn hình iPhone 12/12 Pro thật sạch sẽ, người dùng có thể tự thực hiện việc dán kính cường lực iPhone 12/12 Pro King một cách dễ dàng không để lại khuyết điểm nào như bong bóng hay hở viền.\r\n\r\nThao tán dán dễ dàng, không để lại bong bóng hay hở viền\r\n\r\nMua miếng dán iPhone 12/12 Pro KingKong chất lượng, giá tốt tại CellphoneS\r\nĐể đặt mua miếng dán chống va đập iPhone 12/12 Pro KingKong chất lượng và hỗ trợ dán miễn phí nhanh chóng, hãy đến ngay với hệ thống bán lẻ CellphoneS. Cung cấp sản phẩm với mức giá tốt, hỗ trợ dán miễn phí lại 1 lần trong 30 ngày đầu cùng độ ngũ tư vấn viên chuyên nghiệp hỗ trợ chu đáo cho khách hàng.\r\n\r\n', 1000.00, NULL, 'uploads/18.jpg', 'cover', 'apple', 0, 'active', 0.0, '2024-12-18 16:46:59', '2024-12-19 00:16:27', 'Accessories', 734),
(23, 'dây sạc type c', 'Cáp Type-C to Lightning Baseus Superior 0.25m là dòng cáp sạc thế hệ mới với độ bền cao cùng khả năng hỗ trợ sạc nhanh với công suất lên tới 20W. Mẫu cáp USB-C này còn vô cùng nhỏ gọn để bạn có thể mang theo bất kỳ đâu.\r\n\r\nHoàn thiện với chất liệu cao cấp\r\nCáp Type-C to Lightning Baseus Superior 0.25m được hoàn thiện với chất liệu cao cấp. Phần vỏ ngoài của cáp được kết hợp từ nhựa ABS và nhựa TPE. Nhờ đó mà dây cáp Baseus vừa có được sự dẻo dai, linh hoạt nhưng vẫn vô cùng cứng cáp và bền bỉ.', 1000.00, NULL, 'uploads/19.jpg', 'charging', 'samsung', 0, 'active', 0.0, '2024-12-18 20:45:31', '2024-12-19 00:17:43', 'Accessories', 45);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `store_info`
--

CREATE TABLE `store_info` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `store_info`
--

INSERT INTO `store_info` (`id`, `store_name`, `address`, `phone`, `email`) VALUES
(2, 'sd', 'cầu diễn', '0393650486', 'atm15092003@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `account_status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `account_status`) VALUES
(1, 'admin1', '$2y$10$AtRmYz9g28W1CtuONGOIMuEpCCkSKNL1wZjLb5zHqimlJIg6yTDCO', 'atm15092003@gmail.com', 1, 1),
(2, 'admin', '$2y$10$AtRmYz9g28W1CtuONGOIMuEpCCkSKNL1wZjLb5zHqimlJIg6yTDCO', 'atm15092003@gmail.com', 0, 0),
(3, 'admin12', '$2y$10$MDhyq8eEAeWlUmKj8smDrOMeSt9EtrEsCEcoeayko/zJoxi8oGnCW', 'atm1509200@gmail.com', 0, 0),
(6, '20211827', '$2y$10$0Az1TaDOzNcyZJoiDoDq7.WK9WkNzsskBA0xzBIWpmbI6ZEk8j9hm', 'atm15092003@gmail.com', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_bank_accounts`
--

CREATE TABLE `user_bank_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `account_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_bank_accounts`
--

INSERT INTO `user_bank_accounts` (`id`, `user_id`, `bank_account`, `bank_name`, `card_number`, `account_details`) VALUES
(1, 18, '98000021129999', 'mb', '222222222222000000000', '2222222222222'),
(2, 18, '98000021129999', 's', 's', NULL),
(3, 18, '98000021129999', 'Vietcombank', '222222222222000000000', NULL),
(4, 18, '98000021129999', 'Vietcombank', '222222222222000000000', NULL),
(5, 18, '98000021129999', 'BIDV', '222222222222000000000', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `store_info`
--
ALTER TABLE `store_info`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `store_info`
--
ALTER TABLE `store_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `user_bank_accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
