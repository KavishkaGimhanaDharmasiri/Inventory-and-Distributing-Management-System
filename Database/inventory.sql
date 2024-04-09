-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 04, 2024 at 03:29 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `outsourcer`
--

DROP TABLE IF EXISTS `outsourcer`;
CREATE TABLE IF NOT EXISTS `outsourcer` (
  `OutsourcerID` int DEFAULT NULL,
  `OutsourcerName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `ContactNumber` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `outsourcer`
--

INSERT INTO `outsourcer` (`OutsourcerID`, `OutsourcerName`, `Address`, `ContactNumber`) VALUES
(1, 'kamal', 'hanwella', 45678),
(NULL, 'amal', 'colombo', 45678),
(NULL, 'kavi', 'colombo', 14257);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `main_cat` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `sub_cat` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `stock` int DEFAULT NULL,
  `costPrice` double DEFAULT NULL,
  `creditPrice` double DEFAULT NULL,
  `checkPrice` double DEFAULT NULL,
  `cashPrice` double DEFAULT NULL,
  `productType` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `supplierId` int DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `main_cat`, `sub_cat`, `stock`, `costPrice`, `creditPrice`, `checkPrice`, `cashPrice`, `productType`, `supplierId`) VALUES
(1, 'Antenna and Accessories', 'Antenna', 200, NULL, 2200, 2100, 2000, NULL, NULL),
(2, 'Antenna and Accessories', 'Antenna Super', 200, NULL, 2400, 2300, 2200, NULL, NULL),
(3, 'Antenna and Accessories', 'Antenna Jack(Metal)', 200, NULL, 50, 40, 35, NULL, NULL),
(4, 'Antenna and Accessories', 'Antenna Jack (Plastic)', 200, NULL, 35, 30, 25, NULL, NULL),
(5, 'Antenna and Accessories', 'Power Supply', 200, NULL, 650, 600, 550, NULL, NULL),
(6, 'Antenna and Accessories', 'Antenna Head', 200, NULL, 950, 950, 900, NULL, NULL),
(7, 'Antenna and Accessories', 'Antenna wire( 5C2V) (100Y)', 200, NULL, 2800, 2650, 2550, NULL, NULL),
(8, 'Antenna and Accessories', 'Antenna Wire (5C2V) (15Y) (with socket)', 200, NULL, 550, 525, 500, NULL, NULL),
(9, 'Antenna and Accessories', 'TV Antenna (Arial)', 200, NULL, 290, 260, 240, NULL, NULL),
(10, 'Antenna and Accessories', 'Cup Antenna', 200, NULL, 390, 360, 340, NULL, NULL),
(11, 'Holder Accessories', 'Adapter', 200, NULL, 650, 650, 600, NULL, NULL),
(12, 'Holder Accessories', 'Ceiling Rose (Black)', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Holder Accessories', 'Ceiling Rose (super White)', 200, NULL, 990, 965, 940, NULL, NULL),
(14, 'Holder Accessories', 'Ceiling Rose (Jumbo)', 200, NULL, 1040, 1015, 990, NULL, NULL),
(15, 'Holder Accessories', 'A/B Holder (Black)', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'Holder Accessories', 'A/B Holder supe white', 200, NULL, 130, 120, 110, NULL, NULL),
(17, 'Holder Accessories', 'Holder Black', 200, NULL, 650, 650, 625, NULL, NULL),
(18, 'Holder Accessories', 'Holder White (Yashoda)', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Holder Accessories', 'Holder super white', 200, NULL, 115, 105, 95, NULL, NULL),
(20, 'Holder Accessories', 'Swan Holder (white)', 200, NULL, 150, 140, 125, NULL, NULL),
(21, 'Holder Accessories', 'Screw Holder (Super)', 200, NULL, 130, 120, 110, NULL, NULL),
(22, 'Holder Accessories', 'Multy Holder', 200, NULL, 140, 130, 120, NULL, NULL),
(23, 'Holder Accessories', 'Pin Screw socket', 200, NULL, 90, 80, 70, NULL, NULL),
(24, 'Holder Accessories', 'Screw pin socket', 200, NULL, 90, 80, 70, NULL, NULL),
(25, 'Holder Accessories', 'Double Holder', 200, NULL, 265, 245, 235, NULL, NULL),
(26, 'Wire Cord', '2 Pin', 200, NULL, 700, 680, 640, NULL, NULL),
(27, 'Wire Cord', '3 Pin 3m', 200, NULL, 1075, 1035, 1000, NULL, NULL),
(28, 'Wire Cord', '3 Pin 4.5m', 200, NULL, 1400, 1375, 1325, NULL, NULL),
(29, 'Wire Cord', '3 Pin 6m', 200, NULL, 1735, 1685, 1615, NULL, NULL),
(30, 'Wire Cord', 'Lotus (2m)', 200, NULL, 1005, 975, 930, NULL, NULL),
(31, 'Wire Cord', 'Lotus (3.5m)', 200, NULL, 1300, 1250, 1190, NULL, NULL),
(32, 'Wire Cord', 'Lotus (5m)', 200, NULL, 1640, 1590, 1520, NULL, NULL),
(33, 'Wire Cord', 'Lotus (10m)', 200, NULL, 3015, 2915, 2765, NULL, NULL),
(34, 'Wire Cord', 'Lotus Small (2m)', 200, NULL, NULL, 730, 680, NULL, NULL),
(35, 'Wire Cord', 'Lotus Small (4m)', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'Wire Cord', '13A 1m', 200, NULL, 1345, 1295, 1230, NULL, NULL),
(37, 'Wire Cord', '13A 2m', 200, NULL, 1750, 1700, 1625, NULL, NULL),
(38, 'Wire Cord', '13A 3m', 200, NULL, 2130, 2080, 2030, NULL, NULL),
(39, 'Wire Cord', '13A 5m', 200, NULL, 2965, 2890, 2815, NULL, NULL),
(40, 'Heater', 'Heater 1000w With out P/ Top', 200, NULL, 980, 940, 890, NULL, NULL),
(41, 'Heater', 'Heater 1500w with out p/Top', 200, NULL, 1005, 965, 915, NULL, NULL),
(42, 'Heater', 'Heater 1000W SA P/Top', 200, NULL, 1100, 1060, 1010, NULL, NULL),
(43, 'Heater', 'Heater 1500W 5A P/Top', 200, NULL, 1125, 1085, 1035, NULL, NULL),
(44, 'Heater', 'Heater 1000W(13A P/top)', 200, NULL, 1300, 1260, 1210, NULL, NULL),
(45, 'Heater', 'Heater 1500w(13A P/top)', 200, NULL, 1323, 1285, 1235, NULL, NULL),
(46, 'Heater', 'Hot plate 1000W', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'Heater', 'Jug Kettle', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'Decoration Bulb', '7 Colour Bulb', 200, NULL, 180, 170, 160, NULL, NULL),
(49, 'Decoration Bulb', '4 Colour Bulb', 200, NULL, 270, 250, 230, NULL, NULL),
(50, 'Decoration Bulb', 'Lotus Bulb', 200, NULL, 290, 270, 250, NULL, NULL),
(51, 'Decoration Bulb', 'Sthupa Bulb', 200, NULL, 270, 250, 230, NULL, NULL),
(52, 'Decoration Bulb', 'Darmachakra', 200, NULL, 270, 250, 230, NULL, NULL),
(53, 'Decoration Bulb', 'Cristel Bulb', 200, NULL, 330, 300, 290, NULL, NULL),
(54, 'Decoration Bulb', 'Stick Bulb', 200, NULL, 290, 270, 255, NULL, NULL),
(55, 'Decoration Bulb', 'Decorative Bulb', 200, NULL, 280, 260, 240, NULL, NULL),
(56, 'Plug Top', 'Plug top (SA) D', 200, NULL, 1550, 1500, 1450, NULL, NULL),
(57, 'Plug Top', 'Plug Top (13A) (China Pcs)', 200, NULL, 160, 150, 140, NULL, NULL),
(58, 'Plug Top', 'Plug Top (13A) (Super Pcs)', 200, NULL, 330, 320, 300, NULL, NULL),
(59, 'Sunk Box', 'Sunk Box (single)', 200, NULL, 40, 35, 30, NULL, NULL),
(60, 'Sunk Box', 'Sunk Box (Double)', 200, NULL, 80, 70, 60, NULL, NULL),
(61, 'Sunk Box', 'Sunk Box (3 Way)', 200, NULL, 130, 120, 110, NULL, NULL),
(62, 'Sunk Box', 'Sunk Box (4 Way)', 200, NULL, 160, 150, 140, NULL, NULL),
(63, 'Sunk Box', 'Sunk Box Lotus (Singale)', 200, NULL, 70, 65, 60, NULL, NULL),
(64, 'Junction Box', 'Junction Box (Square)', 200, NULL, 40, 38, 35, NULL, NULL),
(65, 'Junction Box', 'Junction Box (Round)', 200, NULL, 45, 40, 36, NULL, NULL),
(66, 'Junction Box', 'Round Blocks (white)', 200, NULL, 20, 15, 12.5, NULL, NULL),
(67, 'Distribution Boxes', 'ABC (500)', 200, NULL, 695, 645, 620, NULL, NULL),
(68, 'Distribution Boxes', 'ABC (700)', 200, NULL, 875, 840, 800, NULL, NULL),
(69, 'Distribution Boxes', 'ABC (900)', 200, NULL, 1000, 975, 950, NULL, NULL),
(70, 'Distribution Boxes', '4 Pole box', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'Distribution Boxes', 'ABC (Sunk) 700 (12way)', 200, NULL, 1200, 1150, 1100, NULL, NULL),
(72, 'Distribution Boxes', 'Brass Bar', 200, NULL, 900, 850, 800, NULL, NULL),
(73, 'Distribution Boxes', 'Main Switch', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'Distribution Boxes', 'Trip Switch', 200, NULL, 1800, 1700, 1650, NULL, NULL),
(75, 'Distribution Boxes', 'Wiring Clips', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'Distribution Boxes', 'Floter Swich', 200, NULL, 1350, 1250, 1200, NULL, NULL),
(77, 'simple', 'Samsung White HandsFree', NULL, 150, 250, 225, 200, 'Import Complete Product', 1),
(78, 'simple', 'Samsung White HandsFree', NULL, 150, 250, 225, 200, 'Import Complete Product', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rawgrn`
--

DROP TABLE IF EXISTS `rawgrn`;
CREATE TABLE IF NOT EXISTS `rawgrn` (
  `RawMaterialsID` int DEFAULT NULL,
  `RawMaterialstName` varchar(50) DEFAULT NULL,
  `supplierId` int DEFAULT NULL,
  `supplierName` varchar(50) DEFAULT NULL,
  `rawQty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rawgrn`
--

INSERT INTO `rawgrn` (`RawMaterialsID`, `RawMaterialstName`, `supplierId`, `supplierName`, `rawQty`) VALUES
(1, NULL, 2, NULL, 10),
(1, NULL, 2, NULL, 20),
(3, 'wire roll', 8, 'suraj', 100),
(3, 'wire roll', 8, 'suraj', 100),
(4, 'heatig element', 11, 'nimal', 40);

-- --------------------------------------------------------

--
-- Table structure for table `rawmaterials`
--

DROP TABLE IF EXISTS `rawmaterials`;
CREATE TABLE IF NOT EXISTS `rawmaterials` (
  `RawMaterialsID` int NOT NULL AUTO_INCREMENT,
  `RawMaterialstName` varchar(50) NOT NULL DEFAULT '0',
  `CostPrice` int NOT NULL DEFAULT (0),
  `SupplierId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `SupplierName` varchar(50) NOT NULL DEFAULT '0',
  `Unit` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`RawMaterialsID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rawmaterials`
--

INSERT INTO `rawmaterials` (`RawMaterialsID`, `RawMaterialstName`, `CostPrice`, `SupplierId`, `SupplierName`, `Unit`) VALUES
(1, '5A', 110, '2', '0', 'pcs'),
(2, '13A Plug Top', 120, '16', '0', 'pcs'),
(3, 'wire roll', 100, '6', 'ameer', 'M'),
(4, 'heatig element', 500, '11', 'nimal', '1pcs');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplierId` int NOT NULL AUTO_INCREMENT,
  `supplierName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contactNumber` int NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(255) DEFAULT 'Unknown',
  PRIMARY KEY (`supplierId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplierId`, `supplierName`, `contactNumber`, `address`, `city`) VALUES
(1, 'gen', 711111, 'Yakdehiwatta,nivithigala,rathnapura ', 'hanwella'),
(2, 'a', 711111, 'no:-19 ihala hanwella,hanwella ', 'Hanwella'),
(3, 'gen', 711111, 'No. 187/A/1, Jayanthi Road, Hapugoda, Kandana ', 'hanwella'),
(4, 'vsdihvpoasovasodjvapiosdjvpo[as', 44212123, 'hanwella', 'Hanwella'),
(5, 'gen222', 771168439, 'No. 187/A/1, Jayanthi Road, Hapugoda, Kandana ', 'Hanwella'),
(6, 'ameer', 7115154, 'kurunagala', 'kurunagala'),
(7, 'a', 771168439, 'No. 187/A/1, Jayanthi Road, Hapugoda, Kandana ', 'hha'),
(8, 'suraj', 716065995, 'thiss rd hambanthota', 'thissa'),
(9, 'nimal', 789, 'has', 'hanwella'),
(10, 'nimal', 789, 'has', 'hanwella'),
(11, 'nimal', 789, 'has', 'hanwella'),
(12, 'kamal', 7654, 'jhgfd', 'jhgf'),
(13, 'nimal', 789, 'jhgfd', 'Unknown'),
(14, 'aruna thilak ', 7654, 'matara', 'Unknown'),
(15, 'thisaru', 789, 'salawa', 'Unknown'),
(16, 'sahan lakruwan', 789, 'salawa', 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `WarehouseName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`WarehouseName`) VALUES
('Main'),
('A'),
('B');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
