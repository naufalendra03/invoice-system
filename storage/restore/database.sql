-- MariaDB dump 10.19  Distrib 10.4.25-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: invoice_system
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-admin@123|127.0.0.1','i:1;',1775996471),('laravel-cache-admin@123|127.0.0.1:timer','i:1775996471;',1775996471);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Naufalendra Mulyahartantya','BPL','Jl. Tlogo Timur III no.9','083137480495',NULL,NULL,'2026-04-12 05:35:33','2026-04-12 05:35:33'),(2,'Naufalendra M','BP2','Jl. Tlogo Timur III no.9','083137480495',NULL,NULL,'2026-04-25 23:55:50','2026-04-25 23:55:50');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Naufalendra Mulyahartantya','Jl. Tlogo Timur III no.9','083137480495','2026-04-12 05:35:41','2026-04-12 05:35:41'),(2,'rahmad','Jl. Tlogo Timur III no.9','083137480495','2026-04-19 02:23:05','2026-04-19 02:23:05'),(3,'doni','Jl. Tlogo Timur III no.9','083137480495','2026-04-25 23:56:11','2026-04-25 23:56:11');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_sale_id_foreign` (`sale_id`),
  CONSTRAINT `payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,6,'2026-04-19',30000.00,NULL,'belum lunas','2026-04-19 08:09:46','2026-04-19 08:09:46'),(2,8,'2026-05-14',50000.00,NULL,'belum lunas','2026-05-14 03:40:03','2026-05-14 03:40:03'),(3,13,'2026-05-28',130000.00,NULL,'lunas','2026-05-27 19:04:10','2026-05-27 19:04:10'),(4,13,'2026-05-28',30000.00,NULL,'lunas','2026-05-27 19:04:45','2026-05-27 19:04:45'),(5,16,'2026-05-28',240000.00,NULL,'belum lunas','2026-05-27 23:53:03','2026-05-27 23:53:03'),(6,8,'2026-05-31',20000.00,NULL,'lunas','2026-05-31 09:03:09','2026-05-31 09:03:09'),(7,16,'2026-05-31',635000.00,NULL,'lunas','2026-05-31 09:04:36','2026-05-31 09:04:36'),(8,10,'2026-05-31',100000.00,NULL,'belum lunas','2026-05-31 09:06:40','2026-05-31 09:06:40'),(9,10,'2026-06-06',30000.00,NULL,'lunas','2026-06-06 06:01:59','2026-06-06 06:01:59'),(10,11,'2026-06-06',70000.00,NULL,'belum lunas','2026-06-06 06:03:58','2026-06-06 06:03:58'),(11,11,'2026-06-06',20000.00,NULL,'belum lunas','2026-06-06 06:04:12','2026-06-06 06:04:12'),(12,17,'2026-06-07',100000.00,NULL,'belum lunas','2026-06-07 00:24:37','2026-06-07 00:24:37');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_company_id_foreign` (`company_id`),
  CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'bolpen','pack',60000.00,'2026-04-12 05:37:36','2026-04-12 05:37:36'),(2,2,'kertas','Dus',70000.00,'2026-04-25 23:57:28','2026-04-25 23:57:28'),(3,1,'sarung tangan@12pcs','ls',100000.00,'2026-05-27 23:08:52','2026-05-27 23:20:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surat_jalan_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total` decimal(15,2) NOT NULL,
  `ongkir` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_company_id_foreign` (`company_id`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  CONSTRAINT `sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,1,'INV-0001','1/BPL/IV/26',NULL,'2026-04-12','2026-04-26',120000.00,0.00,'unpaid','2026-04-12 05:38:04','2026-04-12 05:38:07','storage/invoices/invoice-INV-0001.pdf'),(2,1,1,'INV-0002','2/BPL/IV/26',NULL,'2026-04-12','2026-04-26',60000.00,0.00,'unpaid','2026-04-12 05:40:56','2026-04-12 05:40:56','storage/invoices/invoice-INV-0002.pdf'),(3,1,2,'INV-0003','3/BPL/IV/26',NULL,'2026-04-19','2026-05-03',60000.00,0.00,'unpaid','2026-04-19 02:24:30','2026-04-19 02:24:36','storage/invoices/invoice-INV-0003.pdf'),(4,1,1,'INV-0004','4/BPL/IV/26',NULL,'2026-04-15','2026-04-22',60000.00,0.00,'unpaid','2026-04-19 08:05:49','2026-04-19 08:05:54','storage/invoices/invoice-INV-0004.pdf'),(5,1,2,'INV-0005','5/BPL/IV/26',NULL,'2026-04-15','2026-04-29',60000.00,0.00,'unpaid','2026-04-19 08:06:15','2026-04-19 08:06:15','storage/invoices/invoice-INV-0005.pdf'),(6,1,2,'INV-0006','6/BPL/IV/26',NULL,'2026-04-14','2026-04-21',60000.00,0.00,'partial','2026-04-19 08:07:55','2026-04-19 08:09:46','storage/invoices/invoice-INV-0006.pdf'),(7,1,3,'INV-0007','7/BPL/IV/26',NULL,'2026-04-26','2026-05-10',70000.00,0.00,'unpaid','2026-04-25 23:58:46','2026-04-25 23:58:47','storage/invoices/invoice-INV-0007.pdf'),(8,1,3,'INV-0008','8/BPL/IV/26',NULL,'2026-04-26','2026-05-10',70000.00,0.00,'paid','2026-04-26 00:00:48','2026-05-31 09:03:09','storage/invoices/invoice-INV-0008.pdf'),(9,1,3,'INV-0009','9/BPL/IV/26',NULL,'2026-04-26','2026-05-10',430000.00,0.00,'unpaid','2026-04-26 00:02:27','2026-04-26 00:06:54','storage/invoices/invoice-INV-0009.pdf'),(10,1,2,'INV-0010','10/BPL/V/26',NULL,'2026-05-17','2026-05-31',130000.00,20000.00,'paid','2026-05-17 02:53:14','2026-06-06 06:01:59','storage/invoices/invoice-INV-0010.pdf'),(11,1,3,'INV-0011','11/BPL/V/26',NULL,'2026-05-17','2026-06-07',190000.00,0.00,'partial','2026-05-17 03:48:22','2026-06-06 06:03:58','storage/invoices/invoice-INV-0011.pdf'),(12,1,2,'INV-0012','12/BPL/V/26',NULL,'2026-05-24','2026-06-07',160000.00,40000.00,'unpaid','2026-05-24 06:16:37','2026-05-24 06:16:41','storage/invoices/invoice-INV-0012.pdf'),(13,1,2,'INV-0013','13/BPL/V/26',NULL,'2026-05-24','2026-06-07',160000.00,30000.00,'paid','2026-05-24 06:16:43','2026-05-27 19:04:45','storage/invoices/invoice-INV-0013.pdf'),(14,1,1,'INV-0014','14/BPL/V/26',NULL,'2026-05-28','2026-06-11',9020000.00,20000.00,'unpaid','2026-05-27 22:44:09','2026-05-27 22:44:09',NULL),(15,1,3,'INV-0015','15/BPL/V/26',NULL,'2026-05-28','2026-06-11',144000.00,39000.00,'unpaid','2026-05-27 23:02:35','2026-05-27 23:02:35',NULL),(16,1,3,'INV-0016','16/BPL/V/26',NULL,'2026-05-28','2026-06-11',875000.00,35000.00,'paid','2026-05-27 23:06:53','2026-05-31 09:04:36','storage/invoices/invoice-INV-0016.pdf'),(17,1,3,'INV-0017','17/BPL/V/26',NULL,'2026-05-28','2026-06-18',150000.00,30000.00,'partial','2026-05-27 23:22:26','2026-06-07 00:24:37','storage/invoices/invoice-INV-0017.pdf'),(18,2,3,'INV-0018','18/BP2/VI/26',NULL,'2026-06-01','2026-06-06',197000.00,30000.00,'unpaid','2026-06-06 23:18:53','2026-06-06 23:18:56','storage/invoices/invoice-INV-0018.pdf'),(19,2,2,'INV-0019','19/BP2/VI/26','08787878879','2026-06-03','2026-06-09',164000.00,80000.00,'unpaid','2026-06-06 23:20:03','2026-06-06 23:20:04','storage/invoices/invoice-INV-0019.pdf'),(20,1,2,'INV-0020','20/BPL/VI/26',NULL,'2026-06-07','2026-06-07',1271900.00,16000.00,'unpaid','2026-06-07 01:21:45','2026-06-07 01:23:47','storage/invoices/invoice-INV-0020.pdf'),(21,2,3,'INV-0021','21/BP2/VI/26',NULL,'2026-06-07','2026-06-14',1270000.00,70000.00,'unpaid','2026-06-07 01:35:56','2026-06-07 01:35:57','storage/invoices/invoice-INV-0021.pdf');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_items`
--

DROP TABLE IF EXISTS `sales_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `qty` decimal(15,3) NOT NULL DEFAULT '0.000',
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_items_sale_id_foreign` (`sale_id`),
  KEY `sales_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sales_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_items`
--

LOCK TABLES `sales_items` WRITE;
/*!40000 ALTER TABLE `sales_items` DISABLE KEYS */;
INSERT INTO `sales_items` VALUES (1,1,1,1.000,60000.00,60000.00,'2026-04-12 05:38:04','2026-04-12 05:38:04'),(2,1,1,1.000,60000.00,60000.00,'2026-04-12 05:38:04','2026-04-12 05:38:04'),(3,2,1,1.000,60000.00,60000.00,'2026-04-12 05:40:56','2026-04-12 05:40:56'),(4,3,1,1.000,60000.00,60000.00,'2026-04-19 02:24:30','2026-04-19 02:24:30'),(5,4,1,1.000,60000.00,60000.00,'2026-04-19 08:05:49','2026-04-19 08:05:49'),(6,5,1,1.000,60000.00,60000.00,'2026-04-19 08:06:15','2026-04-19 08:06:15'),(7,6,1,1.000,60000.00,60000.00,'2026-04-19 08:07:55','2026-04-19 08:07:55'),(8,7,2,1.000,70000.00,70000.00,'2026-04-25 23:58:46','2026-04-25 23:58:46'),(9,8,2,1.000,70000.00,70000.00,'2026-04-26 00:00:48','2026-04-26 00:00:48'),(11,9,2,1.000,70000.00,70000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(12,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(13,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(14,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(15,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(16,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(17,9,1,1.000,60000.00,60000.00,'2026-04-26 00:06:54','2026-04-26 00:06:54'),(18,10,2,1.000,70000.00,70000.00,'2026-05-17 02:53:14','2026-05-17 02:53:14'),(19,10,1,1.000,60000.00,60000.00,'2026-05-17 02:53:15','2026-05-17 02:53:15'),(22,11,2,1.000,70000.00,70000.00,'2026-05-17 03:49:08','2026-05-17 03:49:08'),(23,11,1,1.000,60000.00,60000.00,'2026-05-17 03:49:08','2026-05-17 03:49:08'),(24,11,1,1.000,60000.00,60000.00,'2026-05-17 03:49:08','2026-05-17 03:49:08'),(25,12,1,2.000,60000.00,120000.00,'2026-05-24 06:16:37','2026-05-24 06:16:37'),(35,13,1,1.000,60000.00,60000.00,'2026-05-24 09:04:37','2026-05-24 09:04:37'),(36,13,2,1.000,70000.00,70000.00,'2026-05-24 09:04:37','2026-05-24 09:04:37'),(37,15,2,1.500,70000.00,105000.00,'2026-05-27 23:02:35','2026-05-27 23:02:35'),(39,17,3,1.200,100000.00,120000.00,'2026-05-27 23:22:26','2026-05-27 23:22:26'),(42,16,3,1.500,100000.00,150000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(43,16,1,1.500,60000.00,90000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(44,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(45,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(46,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(47,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(48,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(49,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(50,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(51,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(52,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(53,16,1,1.000,60000.00,60000.00,'2026-05-28 00:10:25','2026-05-28 00:10:25'),(54,18,3,1.670,100000.00,167000.00,'2026-06-06 23:18:53','2026-06-06 23:18:53'),(55,19,2,1.200,70000.00,84000.00,'2026-06-06 23:20:04','2026-06-06 23:20:04'),(68,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(69,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(70,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(71,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(72,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(73,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(74,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(75,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(76,20,3,1.000,100000.00,100000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(77,20,3,1.670,100000.00,167000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(78,20,2,1.670,70000.00,116900.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(79,20,1,1.200,60000.00,72000.00,'2026-06-07 01:23:47','2026-06-07 01:23:47'),(80,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(81,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(82,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(83,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(84,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(85,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(86,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(87,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(88,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(89,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(90,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56'),(91,21,3,1.000,100000.00,100000.00,'2026-06-07 01:35:56','2026-06-07 01:35:56');
/*!40000 ALTER TABLE `sales_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('iYQLZLyblKt2XL7bKZ2gT3m4lZIPfBwdCxTJRvRY',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRFg1ZXZSeE00ak1Mb1RsczNSejd3OFpwR2xjdlhJRGlDVXNlcTdUbCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc3lzdGVtL2JhY2t1cC1wYWdlIjtzOjU6InJvdXRlIjtzOjE4OiJzeXN0ZW0uYmFja3VwLnBhZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1781423682);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'user','user@user',NULL,'$2y$12$UFIr/MLidKLIZaFi2bCENe/g4e79/mDhH/dgST6svT8Lb1RTDFyiu',NULL,'2026-04-12 05:34:47','2026-04-12 05:34:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-14 14:54:46
