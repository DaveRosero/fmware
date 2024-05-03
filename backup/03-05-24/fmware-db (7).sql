-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2024 at 10:49 AM
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
(14, '99', 'Mendoza Street', 'Saog', 'Marilao', '', 18),
(15, '99', 'T. Mendoza Street', 'Saog', 'Marilao', '', 31),
(16, '99', 'Mendoza Street', 'Saog', 'Marilao', 'Tapat ng E. Garcia Street', 40);

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
(14, 'Stikwel', 1, '2024-04-10 10:42:57', 1),
(15, 'Boysen', 1, '2024-04-10 11:11:15', 1),
(17, 'CARLO', 1, '2024-04-11 05:47:55', 1),
(18, 'Pioneer', 1, '2024-04-11 12:29:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `qty`, `subtotal`, `active`) VALUES
(81, 18, 19, 1, 450, 1);

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
(11, 'Adhesive', 1, '2024-04-10 10:48:30', 1),
(12, 'Paint', 1, '2024-04-10 11:11:15', 1),
(14, 'Tools', 1, '2024-04-11 05:47:55', 1);

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
-- Table structure for table `delivery_fee`
--

CREATE TABLE `delivery_fee` (
  `id` int(11) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `brgys` text NOT NULL,
  `delivery_fee` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL COMMENT '0 = inactive, 1 = active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_fee`
--

INSERT INTO `delivery_fee` (`id`, `municipality`, `brgys`, `delivery_fee`, `active`) VALUES
(1, 'Marilao', 'Abangan Norte,Abangan Sur,Ibayo,Lambakin,Lias,Loma de Gato,Nagbalon,Patubig,Poblacion 1st,Poblacion 2nd,Prenza 1st,Prenza 2nd,Santa Rosa 1st,Santa Rosa 2nd,Saog,Tabing-ilog', 50, 1),
(2, 'Meycauayan', 'Bagbaguin,Bahay Pare,Bancal,Banga,Bayugo,Caingin,Calvario,Camalig,Hulo,Iba,Langka,Lawa,Libtong,Liputan,Longos,Malhacan,Pajo,Pandayan,Pantoc,Perez,Poblacion,Saint Francis,Saluysoy,Tugatog,Ubihan,Zamora', 100, 1),
(3, 'Bocaue', 'Antipona,Bagumbayan,Bambang,Batia,Binang 1st,Binang 2nd,Bolacan,Bundukan,Bunlo,Caingin,Duhat,Igulot,Lolomboy,Poblacion,Sulucan,Taal,Tambobong,Turo,Wakas', 100, 1);

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
(1, 'admin', 'all', 1),
(2, 'user', '', 1),
(3, 'Store Clerk', 'viewGroup, viewUser, viewStaff, viewProducts, viewStocks, viewRestocks, viewReturns, viewReplacements, viewSuppliers, viewSales, viewOrders, viewAttributes, viewArchive', 1),
(4, 'Delivery Rider', '', 1);

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
  `address_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `paid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_ref`, `firstname`, `lastname`, `phone`, `date`, `gross`, `delivery_fee`, `vat`, `discount`, `net`, `transaction_type_id`, `payment_type_id`, `address_id`, `user_id`, `paid`, `status`, `code`) VALUES
(31, 'FMO_0995C8D130F457BC3395', 'Dave', 'Rosero', '09666893585', '2024-04-24 07:28:14', 500, 50, NULL, NULL, 100, 1, 2, 14, 18, 'paid', 'delivered', NULL),
(32, 'FMO_6091C6B4B56342A30B6F', 'Dave', 'Rosero', '09666893585', '2024-04-24 07:47:11', 2050, 50, NULL, NULL, 500, 1, 2, 14, 18, 'paid', 'delivered', NULL),
(33, 'FMO_B501342955FE2B940394', 'Dave', 'Rosero', '09064719681', '2024-04-25 02:40:03', 1600, 50, NULL, NULL, 250, 1, 2, 15, 31, 'paid', 'delivered', NULL),
(34, 'FMO_61C01EB074ECDE23910E', 'Dave', 'Rosero', '09064719681', '2024-04-25 02:47:33', 625, 50, NULL, NULL, 75, 1, 1, 15, 31, 'unpaid', 'to pay', '8299c155e08af827e13c1937da8006870f3b5a1e9ebdf00b8b96b7cc589c86a342ab9bdeb9d0d60307834e035691c61fe298'),
(35, 'FMO_BB89F9686308C2178D57', 'Dave', 'Rosero', '09064719681', '2024-04-26 12:42:36', 500, 50, NULL, NULL, 100, 1, 1, 15, 31, 'unpaid', 'delivering', '0cdc6f24a521f2e64446e80ef03e20908f91bebe39daa1ef04bf89e4870dbb27cc5930f524b220a7404c9baaa1f8138d5af4');

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
(51, 'FMO_0995C8D130F457BC3395', 19, 1),
(52, 'FMO_6091C6B4B56342A30B6F', 20, 5),
(53, 'FMO_B501342955FE2B940394', 14, 2),
(54, 'FMO_B501342955FE2B940394', 20, 1),
(56, 'FMO_61C01EB074ECDE23910E', 14, 1),
(57, 'FMO_BB89F9686308C2178D57', 19, 1);

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
-- Table structure for table `pos_cart`
--

CREATE TABLE `pos_cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_cart`
--

INSERT INTO `pos_cart` (`id`, `product_id`, `qty`, `price`, `discount`) VALUES
(10, 19, 1, 450, NULL),
(11, 14, 1, 575, NULL),
(12, 17, 1, 550, NULL);

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
(4, 17, 500, 550),
(5, 14, 500, 575),
(6, 19, 350, 450),
(7, 20, 300, 400),
(8, 22, 200, 236),
(9, 21, 500, 568);

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
  `variant_id` int(11) NOT NULL,
  `expiration_date` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `code`, `supplier_id`, `supplier_code`, `barcode`, `image`, `description`, `brand_id`, `category_id`, `unit_id`, `unit_value`, `variant_id`, `expiration_date`, `user_id`, `date`, `active`) VALUES
(14, 'Boysen Premium Quick Dry Enamel', 'PROD-001', 0, 'B-673', '1234567890', 'Boysen_Premium_Quick_Drying_Enamel-removebg-preview - Copy.png', '', 15, 12, 13, 1, 3, '', 1, '2024-04-11 01:47:17', 1),
(17, 'Boysen Premium Quick Dry Enamel', 'PROD-002', 0, 'B-673', '0987654321', 'Boysen_Premium_Quick_Drying_Enamel-removebg-preview - Copy.png', '', 15, 12, 13, 1, 2, '', 1, '2024-04-11 02:10:11', 1),
(18, 'Boysen Premium Quick Dry Enamel', 'PROD-003', 0, 'B-673', NULL, 'Boysen_Premium_Quick_Drying_Enamel-removebg-preview - Copy.png', '', 15, 12, 13, 1, 5, '', 1, '2024-04-11 02:11:02', 1),
(19, 'CARLO Heavy Duty Riveter', 'PROD-004', 0, 'HDR-001', '12345', 'heavy_duty_riveter-removebg-preview.png', '', 17, 14, 15, 1, 6, '', 1, '2024-04-11 05:47:55', 1),
(20, 'Boysen Premium Quick Dry Enamel', 'PROD-005', 0, 'B-673', '', 'Boysen_Premium_Quick_Drying_Enamel-removebg-preview - Copy.png', '', 15, 12, 13, 2, 7, '', 1, '2024-04-11 12:21:27', 1),
(21, 'Pioneer Epoxy', 'PROD-006', 0, 'SUPP-001', '', 'pioneer_all_purpose_epoxy-removebg-preview.png', '', 18, 11, 16, 500, 8, '', 1, '2024-04-11 12:29:35', 1),
(22, 'Stikwel Wood Glue', 'PROD-001', 0, 'SWG-001', '', 'stickwell_wood_glue-removebg-preview.png', '', 14, 11, 17, 500, 9, '', 1, '2024-04-16 02:06:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `proof_of_transaction`
--

CREATE TABLE `proof_of_transaction` (
  `id` int(11) NOT NULL,
  `order_ref` varchar(255) NOT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `proof_of_delivery` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proof_of_transaction`
--

INSERT INTO `proof_of_transaction` (`id`, `order_ref`, `proof_of_payment`, `proof_of_delivery`) VALUES
(11, 'FMO_0995C8D130F457BC3395', '6628b60f5a25f_1713944079_4875_gcash-receipt.jpg', NULL),
(13, 'FMO_6091C6B4B56342A30B6F', '6628b91b66aae_1713944859_1493_gcash-receipt.jpg', NULL),
(14, 'FMO_B501342955FE2B940394', '6629c2dfde7a4_1714012895_9536_gcash-receipt.jpg', NULL);

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
(9, 1, 0, 'SP-001', 17, 16, '2024-04-11'),
(10, 1, 0, 'SP-001', 14, 11, '2002-04-11'),
(11, 1, 0, 'SP-007', 20, 6, '2024-04-11'),
(12, 1, 0, 'SP009', 17, 14, '2024-04-29');

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
(5, 17, 15, 15, '2024-04-29 02:08:32'),
(6, 14, 12, 10, '2024-04-25 02:47:33'),
(7, 18, 50, 25, '2024-04-11 02:54:26'),
(8, 19, 65, 12, '2024-04-26 12:42:36'),
(9, 20, 9, 15, '2024-04-25 02:40:03'),
(10, 22, 6, 20, '2024-04-24 04:22:33'),
(11, 21, 0, 10, '2024-04-24 03:55:28');

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
(12, 'kilogram', 1, '2024-04-10 11:02:51', 1),
(13, 'liter', 1, '2024-04-10 11:11:15', 1),
(15, 'set', 1, '2024-04-11 05:47:55', 1),
(16, 'mililiter', 1, '2024-04-11 12:29:35', 1),
(17, 'grams', 1, '2024-04-16 02:06:47', 1);

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
  `code` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `code`, `active`) VALUES
(1, 'admin', 'admin', '21shingie@gmail.com', '$2y$10$oJ8xtBCeXr61rJEIZtrZjufk2rW7Igd.g9N0XyEK5edzGOOSxYyyO', '09064719681', NULL, 1),
(31, 'Dave', 'Rosero', 'daveshingierosero@gmail.com', '$2y$10$IUiLC1JdgtG0TwFNMQ9FcuwxEzNvOxoXjS9355SOx0eaOm6GU2LNK', '09064719681', NULL, 1),
(32, 'Israel', 'Gonzales', 'israel1990@gmail.com', '$2y$10$oJ8xtBCeXr61rJEIZtrZjufk2rW7Igd.g9N0XyEK5edzGOOSxYyyO', '09064719681', NULL, 1),
(33, 'Dave', 'Rosero', 'kurei7476@gmail.com', '$2y$10$t/Fg7HvG9FpyJ5wRImben.Pqiof2qPWqODLhugAA0yR7kx2IoqNtW', '09064719681', NULL, 1),
(40, 'Dave', 'Rosero', 'roserodave06@gmail.com', '$2y$10$6EH6/e4VYVocpr5KkAnd2uVPp7j6JaZGu2mrk8Kf/8xP9HBsqfit.', '09666893585', NULL, 1);

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
(17, 17, 2),
(18, 18, 2),
(19, 19, 2),
(20, 19, 2),
(21, 19, 2),
(22, 19, 2),
(23, 19, 2),
(24, 24, 2),
(25, 25, 2),
(26, 26, 2),
(27, 27, 2),
(28, 28, 2),
(29, 29, 2),
(31, 31, 2),
(32, 32, 4),
(33, 33, 2),
(36, 40, 2);

-- --------------------------------------------------------

--
-- Table structure for table `variant`
--

CREATE TABLE `variant` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `variant`
--

INSERT INTO `variant` (`id`, `name`, `user_id`, `date`, `active`) VALUES
(2, 'White', 1, '2024-04-10 11:12:14', 1),
(3, 'Red', 1, '2024-04-11 01:47:17', 1),
(5, 'Blue', 1, '2024-04-11 02:11:02', 1),
(6, '3/32\"', 1, '2024-04-11 05:47:55', 1),
(7, 'Pink', 1, '2024-04-11 12:21:27', 1),
(8, 'All Purpose', 1, '2024-04-11 12:29:35', 1),
(9, 'Wood Glue', 1, '2024-04-16 02:06:47', 1);

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
-- Indexes for table `delivery_fee`
--
ALTER TABLE `delivery_fee`
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
-- Indexes for table `pos_cart`
--
ALTER TABLE `pos_cart`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `proof_of_transaction`
--
ALTER TABLE `proof_of_transaction`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `variant`
--
ALTER TABLE `variant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery_fee`
--
ALTER TABLE `delivery_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pos_cart`
--
ALTER TABLE `pos_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `proof_of_transaction`
--
ALTER TABLE `proof_of_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `replacement`
--
ALTER TABLE `replacement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `variant`
--
ALTER TABLE `variant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
