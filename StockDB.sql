-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for stock
CREATE DATABASE IF NOT EXISTS `stock` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `stock`;

-- Dumping structure for table stock.outsourcer
CREATE TABLE IF NOT EXISTS `outsourcer` (
  `OutsourcerID` int NOT NULL AUTO_INCREMENT,
  `OutsourcerName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `ContactNumber` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`OutsourcerID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.product
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `main_cat` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `sub_cat` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `costPrice` double DEFAULT NULL,
  `creditPrice` double DEFAULT NULL,
  `checkPrice` double DEFAULT NULL,
  `cashPrice` double DEFAULT NULL,
  `productType` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `supplierId` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.product_grn
CREATE TABLE IF NOT EXISTS `product_grn` (
  `ProductGrnNo` int NOT NULL AUTO_INCREMENT,
  `productId` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `productName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Qty` int NOT NULL,
  PRIMARY KEY (`ProductGrnNo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.product_requirements
CREATE TABLE IF NOT EXISTS `product_requirements` (
  `product_id` int NOT NULL,
  `raw_material_id` int NOT NULL,
  `quantity_needed` int NOT NULL,
  PRIMARY KEY (`product_id`,`raw_material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.rawgrn
CREATE TABLE IF NOT EXISTS `rawgrn` (
  `RawMaterialsID` int DEFAULT NULL,
  `RawMaterialstName` varchar(50) DEFAULT NULL,
  `supplierId` int DEFAULT NULL,
  `supplierName` varchar(50) DEFAULT NULL,
  `rawQty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.rawgrnout
CREATE TABLE IF NOT EXISTS `rawgrnout` (
  `RawMaterialsID` int DEFAULT NULL,
  `RawMaterialstName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `OutsourcerId` int DEFAULT NULL,
  `OutsourcerName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rawQty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.rawmaterials
CREATE TABLE IF NOT EXISTS `rawmaterials` (
  `RawMaterialsID` int NOT NULL AUTO_INCREMENT,
  `RawMaterialstName` varchar(50) NOT NULL DEFAULT '0',
  `CostPrice` int NOT NULL DEFAULT (0),
  `SupplierId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `SupplierName` varchar(50) NOT NULL DEFAULT '0',
  `Unit` varchar(50) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`RawMaterialsID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplierId` int NOT NULL AUTO_INCREMENT,
  `supplierName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contactNumber` int NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(255) DEFAULT 'Unknown',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`supplierId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table stock.warehouse
CREATE TABLE IF NOT EXISTS `warehouse` (
  `WarehouseName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
