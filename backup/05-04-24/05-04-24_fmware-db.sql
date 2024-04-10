-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2024 at 03:39 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fmware-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `house_no` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `brgy` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `house_no`, `street`, `brgy`, `municipality`, `description`, `user_id`) VALUES
(3, '99', 'T. Mendoza Street', 'Saog', 'Marilao', 'Tapat ng E. Garcia Street yung tindahan', 2),
(4, '9', 'Generator', 'Lias', 'Marilao', 'Tapat ng Our Lady of FATima', 6),
(7, '101', 'T. Mendoza Street', 'Saog', 'Marilao', '', 11),
(8, '10', 'malunggay', 'dump', 'balagtas', '', 12);

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`, `user_id`, `date`, `active`) VALUES
(5, 'Boysen', 1, '2024-03-19 01:07:17', 1),
(6, 'Duwell', 1, '2024-03-19 01:08:03', 1),
(7, 'Wynn\'s', 1, '2024-03-19 01:10:03', 1),
(8, 'WB', 1, '2024-03-19 01:10:09', 1),
(9, 'Stikwel', 1, '2024-03-19 11:01:25', 1),
(10, 'OMNI', 1, '2024-03-19 13:14:33', 1),
(11, 'Pioneer', 1, '2024-03-19 13:19:33', 1),
(12, 'Carlo', 1, '2024-03-19 13:22:38', 1),
(13, 'SUNAMES', 1, '2024-03-19 20:03:19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `qty`, `subtotal`) VALUES
(37, 10, 5, 1, 700),
(38, 10, 8, 1, 1700),
(43, 12, 5, 1, 700),
(44, 12, 8, 1, 1700);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `user_id`, `date`, `active`) VALUES
(5, 'Wood', 1, '2024-03-18 09:16:27', 1),
(6, 'Paint', 1, '2024-03-18 09:50:00', 1),
(7, 'Screws', 1, '2024-03-18 10:54:11', 1),
(8, 'Tools', 1, '2024-03-18 23:58:10', 1),
(9, 'Adhesive', 1, '2024-03-19 00:28:58', 1),
(10, 'Electrical', 1, '2024-03-19 13:16:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `user_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `category`, `value`, `user_group_id`) VALUES
(1, 'Name', 'FMWare', 1),
(2, 'Logo', 'logo.png', 1),
(3, 'Location', 'Poblacion II, Marilao, Bulacan', 1),
(4, 'Delivery Fee', '50', 1),
(5, 'VAT', '12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permission` text NOT NULL,
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permission`, `active`) VALUES
(1, 'admin', 'all', 0),
(2, 'user', '', 1),
(3, 'Store Clerk', 'viewGroup, viewUser, viewStaff, viewProducts, viewStocks, viewRestocks, viewReturns, viewReplacements, viewSuppliers, viewSales, viewOrders, viewAttributes, viewArchive', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_ref` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `gross` int(11) NOT NULL,
  `delivery_fee` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `net` int(11) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `paid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_ref`, `firstname`, `lastname`, `phone`, `date`, `gross`, `delivery_fee`, `vat`, `discount`, `net`, `transaction_type_id`, `payment_type_id`, `proof_of_payment`, `address_id`, `user_id`, `paid`, `status`) VALUES
(9, 'FMO_477DB7DFC4806945A09E', 'Dave', 'Rosero', '9064719681', '2024-04-02 04:00:58', 9630, 50, 12, NULL, 2860, 1, 1, '', 1, 2, 'paid', 'delivered'),
(10, 'FMO_2B2E5C36027ABFD6D4CA', 'Dave', 'Rosero', '9064719681', '2024-04-02 04:02:56', 15490, 50, 12, NULL, 4880, 1, 1, '', 1, 2, 'paid', 'delivered'),
(11, 'FMO_2539BC1509FABF25B4BB', 'Dave', 'Rosero', '9064719681', '2024-04-02 13:18:22', 7010, 50, 12, NULL, 2120, 1, 1, NULL, 3, 2, 'paid', 'delivered'),
(12, 'FMO_16B64EBC0D1013CED6C6', 'Israel', 'Gonzaleast', '9064719681', '2024-04-02 13:43:06', 3530, 50, 12, NULL, 1060, 1, 1, NULL, 4, 0, 'unpaid', 'delivering'),
(13, 'FMO_403B7BA3B30F23365756', 'Israel', 'Gonzaleast', '90647196818', '2024-04-02 13:54:43', 1750, 50, 12, NULL, 500, 1, 1, NULL, 4, 6, 'unpaid', 'delivering'),
(14, 'FMO_E02E4CB289C858DCF491', 'Shingie', 'Oresor', '9064719681', '2024-04-02 14:40:22', 3450, 50, 12, NULL, 1000, 1, 1, NULL, 7, 11, 'unpaid', 'pending'),
(15, 'FMO_14A3027A52920645DE53', 'Shingie', 'Oresor', '9064719681', '2024-04-03 00:57:16', 750, 50, 12, NULL, 200, 1, 1, NULL, 7, 11, 'unpaid', 'pending'),
(16, 'FMO_0EE07789D0B2D103E01F', 'ryogi', 'kuroyanagi', '9155692517', '2024-04-03 01:13:11', 2450, 50, 12, NULL, 700, 1, 1, NULL, 8, 12, 'paid', 'delivered'),
(17, 'FMO_641F34033FFA3899C749', 'Shingie', 'Oresor', '9064719681', '2024-04-04 09:26:12', 5610, 50, 12, NULL, 1720, 1, 1, NULL, 7, 11, 'unpaid', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_ref` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_ref`, `product_id`, `qty`) VALUES
(9, 'FMO_477DB7DFC4806945A09E', 4, 1),
(10, 'FMO_477DB7DFC4806945A09E', 8, 5),
(12, 'FMO_2B2E5C36027ABFD6D4CA', 4, 8),
(13, 'FMO_2B2E5C36027ABFD6D4CA', 8, 4),
(15, 'FMO_2539BC1509FABF25B4BB', 5, 2),
(16, 'FMO_2539BC1509FABF25B4BB', 8, 2),
(17, 'FMO_2539BC1509FABF25B4BB', 4, 2),
(18, 'FMO_16B64EBC0D1013CED6C6', 4, 1),
(19, 'FMO_16B64EBC0D1013CED6C6', 8, 1),
(20, 'FMO_16B64EBC0D1013CED6C6', 5, 1),
(21, 'FMO_403B7BA3B30F23365756', 8, 1),
(22, 'FMO_E02E4CB289C858DCF491', 8, 2),
(23, 'FMO_14A3027A52920645DE53', 5, 1),
(24, 'FMO_0EE07789D0B2D103E01F', 5, 1),
(25, 'FMO_0EE07789D0B2D103E01F', 8, 1),
(26, 'FMO_641F34033FFA3899C749', 8, 2),
(27, 'FMO_641F34033FFA3899C749', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`, `active`) VALUES
(1, 'COD', 1),
(2, 'GCash', 1);

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE `price_list` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `base_price` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_list`
--

INSERT INTO `price_list` (`id`, `product_id`, `base_price`, `unit_price`) VALUES
(1, 4, 720, 1080),
(2, 8, 1200, 1700),
(3, 5, 500, 700);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_code` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `unit_value` int(11) NOT NULL,
  `expiration_date` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `code`, `supplier_id`, `supplier_code`, `barcode`, `image`, `description`, `brand_id`, `category_id`, `unit_id`, `unit_value`, `expiration_date`, `user_id`, `date`, `active`) VALUES
(4, 'Stikwel Wood Glue', 'WG-001', 0, 'SWG-001', '', 'stickwell_wood_glue-removebg-preview.png', '', 9, 9, 10, 1, '', 1, '2024-03-19 11:12:48', 1),
(5, 'Boysen Premium Quick Dry Enamel', 'QDE-001', 0, 'B-673', '', 'Boysen_Premium_Quick_Drying_Enamel-removebg-preview - Copy.png', '', 5, 6, 5, 1, '2025-03-19', 1, '2024-03-19 13:07:37', 1),
(6, 'OMNI Extension Cord', 'EC-001', 0, 'WER-103-PK', '', 'omni_extension_hold_set-removebg-preview.png', '', 10, 10, 8, 2, '', 1, '2024-03-19 13:17:18', 1),
(7, 'Pioneer Epoxy', 'EP-001', 0, 'PEP-001', '', 'pioneer_all_purpose_epoxy-removebg-preview.png', '', 11, 9, 5, 1, '2025-02-19', 1, '2024-03-19 13:20:53', 1),
(8, 'CARLO Heavy Duty Riveter', 'HDR-001', 0, 'CHDR-001', '', 'heavy_duty_riveter-removebg-preview.png', '', 12, 8, 11, 1, '', 1, '2024-03-19 13:24:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `replacement`
--

CREATE TABLE `replacement` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restock`
--

CREATE TABLE `restock` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_order_no` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`id`, `user_id`, `supplier_id`, `supplier_order_no`, `product_id`, `qty`, `date`) VALUES
(1, 1, 0, 'SP-001', 4, 22, '2024-03-20'),
(2, 1, 0, 'SP-001', 5, 33, '2024-03-20'),
(3, 1, 0, 'SP-002', 8, 99, '2024-03-21'),
(4, 1, 0, 'SP-003', 4, 2, '2024-03-21'),
(5, 1, 0, 'SP-003', 7, 7, '2024-03-21'),
(6, 1, 0, 'SP-004', 7, 3, '2024-03-22'),
(7, 1, 0, 'SP-005', 4, 5, '2024-03-21'),
(8, 1, 0, 'SP-006', 5, 2, '2024-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `qty` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `critical_level` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `qty`, `critical_level`, `date`) VALUES
(1, 4, 15, 20, '2024-04-04 09:26:12'),
(2, 5, 30, 15, '2024-04-03 01:13:11'),
(3, 8, 81, 12, '2024-04-04 09:26:12'),
(4, 7, 10, 10, '2024-03-19 17:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`id`, `name`, `active`) VALUES
(1, 'Online Order', 1),
(2, 'POS', 1),
(3, 'Walk In', 1);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `name`, `user_id`, `date`, `active`) VALUES
(4, 'piece', 1, '2024-03-19 03:10:56', 1),
(5, 'liter', 1, '2024-03-19 03:28:20', 1),
(6, 'box', 1, '2024-03-19 03:28:26', 1),
(8, 'meter', 1, '2024-03-19 03:31:19', 1),
(9, 'centimeter', 1, '2024-03-19 03:33:35', 1),
(10, 'kilogram', 1, '2024-03-19 10:48:20', 1),
(11, 'set', 1, '2024-03-19 13:22:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '0=male, 1=female'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `sex`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '1234567890', '09064719681', 1),
(2, 'Dave', 'Rosero', 'roserodave06@gmail.com', 'rosero21', '09064719681', 0),
(3, 'Israel', 'Gonzales', 'israel1999@gmail.com', 'israelgonzales', '09666893585', 1),
(4, 'Marc Jhero', 'Marcelo', 'mjmarcelo@gmail.com', 'marcelo911', '09666893585', 0),
(6, 'Israel', 'Gonzaleast', 'israel1980@gmail.com', 'israel1980', '090647196818', 1),
(11, 'Shingie', 'Oresor', '21shingie@gmail.com', 'rosero21', '09064719681', 0),
(12, 'ryogi', 'kuroyanagi', 'ryogikuroyanagi@gmail.com', '12345', '9155692517', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4, 4, 3),
(8, 6, 2),
(11, 11, 2),
(12, 12, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`transaction_type_id`,`payment_type_id`,`user_id`),
  ADD KEY `net` (`net`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `price_list`
--
ALTER TABLE `price_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`brand_id`,`category_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `replacement`
--
ALTER TABLE `replacement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`order_id`,`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `restock`
--
ALTER TABLE `restock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`user_id`,`supplier_id`,`product_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`order_id`,`product_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`user_id`,`group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `replacement`
--
ALTER TABLE `replacement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
