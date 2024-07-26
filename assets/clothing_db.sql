-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 01:34 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `added_prod`
--

CREATE TABLE `added_prod` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `added_prod`
--

INSERT INTO `added_prod` (`id`, `code`) VALUES
(2, 'B2 6B 1F 1D'),
(3, 'B2 6B 1F 1D'),
(4, 'C3 FA 5F 96'),
(5, '90 4E 89 26'),
(6, '90 4E 89 26'),
(7, 'B2 6B 1F 1D'),
(8, '90 4E 89 26'),
(9, 'C3 FA 5F 96'),
(10, 'C3 FA 5F 96'),
(11, 'C3 FA 5F 96'),
(12, 'C3 FA 5F 96'),
(13, 'B2 6B 1F 1D'),
(14, '90 4E 89 26'),
(15, '90 4E 89 26'),
(16, '29 F9 A1 99'),
(17, '29 F9 A1 99'),
(18, 'B2 6B 1F 1D'),
(19, 'B2 6B 1F 1D'),
(20, 'B2 6B 1F 1D'),
(21, 'B2 6B 1F 1D'),
(22, 'B2 6B 1F 1D'),
(23, '90 4E 89 26'),
(24, '90 4E 89 26'),
(25, '90 4E 89 26'),
(26, 'B2 6B 1F 1D'),
(27, '90 4E 89 26'),
(28, 'B2 6B 1F 1D'),
(29, '90 4E 89 26'),
(30, 'B2 6B 1F 1D'),
(31, '90 4E 89 26'),
(32, '90 4E 89 26'),
(33, '90 4E 89 26');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(30) NOT NULL,
  `item_code` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `size` varchar(10) NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `name`, `description`, `size`, `price`, `date_created`) VALUES
(17, '90 4E 89 26', 'T-SHIRT', 'RED', 'S', 100, '2023-07-10 19:32:38'),
(18, 'C3 FA 5F 96', 'SLEEVE', 'WHITE', 'S', 100, '2023-07-11 04:36:37'),
(24, 'B2 6B 1F 1D', 'CAP', 'GRAY', '', 50, '2024-04-19 15:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `paypal_sales`
--

CREATE TABLE `paypal_sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `amount_tendered` int(11) NOT NULL,
  `inventory_ids` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `receiving`
--

CREATE TABLE `receiving` (
  `id` int(30) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `description` text NOT NULL,
  `total_cost` float NOT NULL,
  `inventory_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receiving`
--

INSERT INTO `receiving` (`id`, `supplier_id`, `description`, `total_cost`, `inventory_ids`, `date_created`) VALUES
(1, 1, '', 215000, '24', '2020-11-04 14:56:18');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `total_amount` float NOT NULL,
  `amount_tendered` int(30) NOT NULL,
  `inventory_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL DEFAULT current_timestamp(),
  `month` int(11) NOT NULL,
  `t_id` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `item_ids` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total_amount`, `amount_tendered`, `inventory_ids`, `date_created`, `date`, `month`, `t_id`, `payment_method`, `item_ids`) VALUES
(88, 1, 50, 50, '61', '2024-04-21 13:29:08', '2024-04-21', 0, '4KC7720959011520E', 'Paypal', '24');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1= in,2=out',
  `qty` int(30) NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `item_id`, `type`, `qty`, `price`, `date_created`) VALUES
(1, 18, 2, 1, 120, '2023-07-11 06:26:26'),
(2, 17, 2, 1, 500, '2023-07-11 07:36:45'),
(3, 17, 2, 1, 500, '2023-07-11 07:36:56'),
(4, 17, 2, 1, 500, '2023-07-11 08:21:07'),
(6, 17, 2, 1, 500, '2023-07-11 08:29:54'),
(7, 17, 2, 1, 500, '2023-07-11 08:31:08'),
(8, 17, 2, 1, 500, '2023-07-11 08:34:31'),
(9, 17, 2, 1, 500, '2023-07-11 08:35:38'),
(10, 17, 2, 1, 500, '2023-07-11 08:37:06'),
(11, 17, 2, 1, 500, '2023-07-12 00:27:59'),
(12, 18, 2, 1, 120, '2023-07-12 00:28:53'),
(13, 18, 2, 1, 120, '2023-07-22 03:59:51'),
(14, 18, 2, 1, 120, '2023-07-22 04:01:16'),
(15, 18, 2, 1, 120, '2023-07-22 04:06:11'),
(16, 17, 2, 1, 500, '2023-07-22 04:06:11'),
(23, 18, 2, 1, 120, '2023-07-22 04:51:02'),
(24, 17, 2, 1, 500, '2023-07-22 04:51:02'),
(25, 18, 2, 1, 120, '2023-07-22 06:08:58'),
(26, 17, 2, 1, 500, '2023-07-22 06:08:58'),
(27, 18, 2, 1, 120, '2023-07-22 15:54:28'),
(28, 17, 2, 1, 500, '2023-07-22 15:54:28'),
(29, 18, 2, 1, 120, '2023-07-22 16:29:20'),
(30, 17, 2, 1, 500, '2023-07-22 16:29:20'),
(31, 17, 2, 1, 500, '2023-08-18 16:25:25'),
(32, 17, 2, 1, 500, '2023-08-18 16:30:14'),
(33, 18, 2, 1, 120, '2023-08-18 16:30:14'),
(34, 17, 2, 1, 500, '2023-09-15 16:03:36'),
(35, 18, 2, 1, 120, '2023-09-15 16:03:37'),
(36, 18, 2, 1, 120, '2023-09-15 16:19:09'),
(37, 17, 2, 1, 500, '2023-09-15 16:19:59'),
(38, 18, 2, 1, 120, '2023-09-15 16:19:59'),
(39, 17, 2, 1, 150, '2023-10-20 14:49:24'),
(40, 18, 2, 1, 100, '2023-10-20 14:49:25'),
(41, 17, 2, 1, 150, '2023-10-20 14:52:56'),
(42, 17, 2, 1, 150, '2023-10-20 15:01:23'),
(43, 18, 2, 1, 100, '2023-10-20 15:01:23'),
(44, 17, 2, 1, 150, '2023-10-20 15:25:34'),
(45, 18, 2, 1, 100, '2023-10-20 15:25:34'),
(46, 17, 2, 1, 150, '2023-10-20 15:29:37'),
(47, 18, 2, 1, 100, '2023-10-20 15:29:41'),
(48, 17, 2, 1, 150, '2023-10-20 15:31:09'),
(49, 18, 2, 1, 100, '2023-10-20 15:31:10'),
(50, 17, 2, 1, 150, '2023-10-20 15:34:54'),
(51, 18, 2, 1, 100, '2023-10-20 15:34:54'),
(52, 17, 2, 1, 150, '2023-10-20 16:17:15'),
(53, 18, 2, 1, 100, '2023-10-20 16:17:16'),
(54, 17, 2, 1, 150, '2023-10-20 16:20:01'),
(55, 18, 2, 1, 100, '2023-10-20 16:20:02'),
(56, 17, 2, 1, 150, '2023-10-20 16:33:11'),
(57, 18, 2, 1, 100, '2023-10-20 16:40:01'),
(58, 17, 2, 1, 150, '2023-10-20 16:40:02'),
(59, 18, 2, 10, 100, '2023-10-20 16:46:46'),
(60, 18, 2, 1, 100, '2024-04-18 21:51:20'),
(61, 24, 2, 1, 50, '2024-04-21 13:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `contact`, `date_created`) VALUES
(1, 'ABC Apparel', 'CBD St., EFG City', '+6948 8542 623', '2020-11-04 09:33:26'),
(2, 'Men Apparel', 'Manila, Taytay Rizal', '65524556', '2020-11-04 09:33:48'),
(3, 'Ladies Apparel', 'Cebu, Minglanilla', '65524556', '2020-11-04 09:34:15'),
(4, 'Trends Apparel', 'USA, Nevada', '8747808787', '2020-11-04 09:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'Slech Pick Cartculator System', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=Admin,2=Staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'admin', 'admin', '0192023a7bbd73250516f069df18b500', 1),
(2, 'Cashier', 'cashier', '6ac2470ed8ccf204fd5ff89b32a355cf', 2),
(6, 'mira', 'mira', 'cf5bdfb40421ac1f30cc4d45b66b5a81', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `added_prod`
--
ALTER TABLE `added_prod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_sales`
--
ALTER TABLE `paypal_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receiving`
--
ALTER TABLE `receiving`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `added_prod`
--
ALTER TABLE `added_prod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `paypal_sales`
--
ALTER TABLE `paypal_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receiving`
--
ALTER TABLE `receiving`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
