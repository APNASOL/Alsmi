-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 15, 2024 at 07:19 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rehmat_and_rayan_coal_company`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_book`
--

CREATE TABLE `account_book` (
  `id` int NOT NULL,
  `t_id` int NOT NULL,
  `mine_id` int NOT NULL,
  `mine_a` varchar(11) NOT NULL,
  `c_id` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `cash_in` double(20,2) NOT NULL,
  `cash_out` double(20,2) NOT NULL,
  `balance` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `current_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch`, `date`, `title`, `account`, `current_amount`, `created_at`, `updated_at`) VALUES
('9d8481d2-dadc-4df1-89be-e10f4e7912e2', 'UBL', 'DARA BAZAR', '2024-11-18', 'Mehmood Nawaz', '45845744', 6700.00, '2024-11-18 05:06:33', '2024-11-18 05:06:33'),
('9d87589f-ce38-4399-8faa-e2162ae5bcee', 'HBL', 'SamarBagh', '2024-11-20', 'Bilal Hussain', '1744790035003', 500000.00, '2024-11-19 14:58:50', '2024-11-19 14:58:50'),
('9d8758de-ba05-4208-8c24-792f01aa6478', 'Khyber Bank', 'Peshawar', '2024-11-20', 'Wasim Ahmad', '5544790035003', 500000.00, '2024-11-19 14:59:31', '2024-11-19 14:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `bank_account`
--

CREATE TABLE `bank_account` (
  `id` varchar(255) NOT NULL,
  `consumer_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `process_type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `bank_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ref_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cash_in` double(20,2) DEFAULT NULL,
  `cash_out` double(20,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_account`
--

INSERT INTO `bank_account` (`id`, `consumer_id`, `process_type`, `note`, `type`, `bank_id`, `ref_no`, `cash_in`, `cash_out`, `date`, `status`, `created_at`, `updated_at`) VALUES
('9d876216-588e-4a09-b039-0d39e7c51140', '9d8481d2-dadc-4df1-89be-e10f4e7912e2', 'Bank', 'Bank new entry', '', '9d8481d2-dadc-4df1-89be-e10f4e7912e2', NULL, 6700.00, 0.00, '2024-11-18', NULL, '2024-11-19 15:25:17', '2024-11-19 15:26:01'),
('9d87622e-df10-4d31-aaf4-35ac660fa19c', '9d87589f-ce38-4399-8faa-e2162ae5bcee', 'Bank', 'Bank new entry', '', '9d87589f-ce38-4399-8faa-e2162ae5bcee', NULL, 500000.00, 0.00, '2024-11-20', NULL, '2024-11-19 15:25:33', '2024-11-19 15:26:03'),
('9d876232-6f18-4804-8b52-121724252b08', '9d8758de-ba05-4208-8c24-792f01aa6478', 'Bank', 'Bank new entry', '', '9d8758de-ba05-4208-8c24-792f01aa6478', NULL, 500000.00, 0.00, '2024-11-20', NULL, '2024-11-19 15:25:36', '2024-11-19 15:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `cashbook`
--

CREATE TABLE `cashbook` (
  `id` char(36) NOT NULL,
  `notes` text,
  `type` varchar(255) DEFAULT NULL,
  `cash_in` decimal(15,2) DEFAULT NULL,
  `cash_out` decimal(15,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cashbook`
--

INSERT INTO `cashbook` (`id`, `notes`, `type`, `cash_in`, `cash_out`, `date`, `status`, `created_at`, `updated_at`) VALUES
('9d8487e6-dd2c-4142-8c9b-84e1ece21dbc', 'te', '1', 300.00, NULL, '2024-11-26', '1', '2024-11-18 05:23:32', '2024-11-18 05:23:32'),
('9d848a4c-79d2-4ba8-9327-a7fea7009623', 'Note of the entry', 'ABC', 0.00, 500.00, '2024-11-18', NULL, '2024-11-18 05:30:15', '2024-11-18 05:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES
('9dbb8e14-6ad1-4b2d-9bd2-7a56f8abd167', 'Bilal Hussain', '32512451222', 'XYZ.', 1, '2024-12-15 13:51:21', '2024-12-15 13:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE `customer_details` (
  `id` varchar(255) NOT NULL,
  `customer_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` int DEFAULT NULL,
  `credit` double(20,2) NOT NULL,
  `debit` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_details`
--

INSERT INTO `customer_details` (`id`, `customer_id`, `transaction_id`, `type`, `date`, `status`, `credit`, `debit`, `created_at`, `updated_at`) VALUES
('9dbb8e14-6f77-4146-83d2-d4065f29f8da', '9dbb8e14-6ad1-4b2d-9bd2-7a56f8abd167', '9dbb8e14-6ad1-4b2d-9bd2-7a56f8abd167', 'Old Amount', '2024-12-15', NULL, 7800.00, 0.00, '2024-12-15 13:51:21', '2024-12-15 13:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `expanse`
--

CREATE TABLE `expanse` (
  `id` int NOT NULL,
  `mine_id` int NOT NULL,
  `mine_a` varchar(255) NOT NULL,
  `total_expanse` double(16,4) NOT NULL,
  `year` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `expanse_account`
--

CREATE TABLE `expanse_account` (
  `id` int NOT NULL,
  `t_id` int NOT NULL,
  `mine_id` int NOT NULL,
  `mine_a` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `cash_in` double(16,4) NOT NULL,
  `cash_out` double(16,4) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expanse_details`
--

CREATE TABLE `expanse_details` (
  `id` int NOT NULL,
  `t_id` int NOT NULL,
  `expanse_name` varchar(255) NOT NULL,
  `amount` double(10,4) NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `expanse_type`
--

CREATE TABLE `expanse_type` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `urdu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mines`
--

CREATE TABLE `mines` (
  `id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mine_name` varchar(255) NOT NULL,
  `mine_no` varchar(255) NOT NULL,
  `mine_place` varchar(255) NOT NULL,
  `security` double(16,4) NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mines`
--

INSERT INTO `mines` (`id`, `mine_name`, `mine_no`, `mine_place`, `security`, `status`, `created_at`, `updated_at`) VALUES
('9d5dff0e-62d2-4a27-8217-6b172a6176b7', 'Mine One', 'M-1', 'DAKheil', 20000.0000, 0, '2024-10-30 01:39:26', '2024-10-30 03:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `id` int NOT NULL,
  `code` varchar(11) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
('9d7b6545-e817-4b1a-b27f-49c2164721a7', '9d5e0daa-0d74-426d-8cc8-e3e0f7b74a41', '2024-11-27', '2024-11-27', 1, '2024-11-13 16:24:16', '2024-11-13 16:24:16'),
('9d7b675a-5070-4a25-b46c-25f4f142160d', '9d7b6396-b518-4aab-b51c-a04e17e1ada6', '2024-11-27', '2024-11-29', 1, '2024-11-13 16:30:05', '2024-11-14 16:51:30'),
('9d829c0d-348b-4c7b-ba93-9d0e71c43024', '9d829bcf-7d43-4756-ad0c-c74fa45e6016', '2024-11-18', '2024-11-30', 1, '2024-11-17 06:28:14', '2024-11-17 06:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES
('9dbb8f03-e8af-4991-aaf9-373203d68bd3', 'Wasim Ahmad', '3625444111', 'xyz', 1, '2024-12-15 13:53:58', '2024-12-15 13:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `partner_details`
--

CREATE TABLE `partner_details` (
  `id` varchar(255) NOT NULL,
  `partner_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` int DEFAULT NULL,
  `credit` double(20,2) NOT NULL,
  `debit` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partner_details`
--

INSERT INTO `partner_details` (`id`, `partner_id`, `transaction_id`, `type`, `date`, `status`, `credit`, `debit`, `created_at`, `updated_at`) VALUES
('9dbb8f03-eb48-4fdb-981a-5bf3e3d2b71e', '9dbb8f03-e8af-4991-aaf9-373203d68bd3', '9dbb8f03-e8af-4991-aaf9-373203d68bd3', 'Old Amount', '2024-12-15', NULL, 0.00, 12500.00, '2024-12-15 13:53:58', '2024-12-15 13:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'webToken', '24e24d683535dd75fe4631914e929d6aa58322460073c7408b24447192cad549', '[\"*\"]', NULL, NULL, '2024-10-30 00:23:42', '2024-10-30 00:23:42'),
(2, 'App\\Models\\User', 1, 'webToken', '53daf3fedf4bb1ecd2828e2c58d71ea1b4a9799632b877875d5161d560c4c985', '[\"*\"]', NULL, NULL, '2024-11-13 02:30:59', '2024-11-13 02:30:59'),
(3, 'App\\Models\\User', 1, 'webToken', '37ae3b50dcd8dcf1b173e5b832a9e4eef48968cc735e6d5282e6b4dd2932b357', '[\"*\"]', NULL, NULL, '2024-11-13 02:41:00', '2024-11-13 02:41:00'),
(4, 'App\\Models\\User', 1, 'webToken', '856abf2ae8d45be9af2de7f57f5446c4dc777b5eb7a3930b83784facef741601', '[\"*\"]', NULL, NULL, '2024-11-13 02:43:02', '2024-11-13 02:43:02'),
(5, 'App\\Models\\User', 1, 'webToken', 'da072a9ce71060d7cb996dd4e51cd3d9b59ebca658f6ca6226704e47abbee930', '[\"*\"]', NULL, NULL, '2024-11-13 02:49:21', '2024-11-13 02:49:21'),
(6, 'App\\Models\\User', 1, 'webToken', 'cd58d02078c32114e1a2c008aa91d5207f650986cb0265e16bd9c40be8fffe1f', '[\"*\"]', NULL, NULL, '2024-11-13 02:52:25', '2024-11-13 02:52:25'),
(7, 'App\\Models\\User', 1, 'webToken', '63a761b010c28885802fc99471575b0ff3a271ae388f2499c0cc4151c441b691', '[\"*\"]', NULL, NULL, '2024-11-13 02:53:38', '2024-11-13 02:53:38'),
(8, 'App\\Models\\User', 1, 'webToken', '9467ed80de27ab945c93eab6cf297ff5c6913d3cfb95d6a712fd63b6d3dea0ca', '[\"*\"]', NULL, NULL, '2024-11-13 02:54:29', '2024-11-13 02:54:29'),
(9, 'App\\Models\\User', 1, 'webToken', '7f872b5aaaf8b4dce9115d4856d7933ed51f0a1b90e3a6890170cab76cce1d47', '[\"*\"]', NULL, NULL, '2024-11-13 02:54:55', '2024-11-13 02:54:55'),
(10, 'App\\Models\\User', 1, 'webToken', '193531c08e1e1bca18e609bdbb6415a229fb83f3d0fe638176269b23abff9fa3', '[\"*\"]', NULL, NULL, '2024-11-13 15:26:15', '2024-11-13 15:26:15'),
(11, 'App\\Models\\User', 1, 'webToken', 'f03c716d189d39f5a20e2bf75c2834a7e436a29eb909141dbc5f43febb2d533f', '[\"*\"]', NULL, NULL, '2024-11-14 14:15:48', '2024-11-14 14:15:48'),
(12, 'App\\Models\\User', 1, 'webToken', 'b5d22bf57e50dd8f65fc6796970ce31d660fe7de4b4c436c5a461354816e6a6e', '[\"*\"]', NULL, NULL, '2024-11-14 16:12:11', '2024-11-14 16:12:11'),
(13, 'App\\Models\\User', 1, 'webToken', '4bd60c58b47faab1d8071ffdefd8efe29dd17b3a982f6f9770a55502c58983ef', '[\"*\"]', NULL, NULL, '2024-11-14 16:14:19', '2024-11-14 16:14:19'),
(14, 'App\\Models\\User', 1, 'webToken', '262fdb38c642830c7fcd5327ae04ca324dcdd4f9e5862910e68bbc02f2574d44', '[\"*\"]', NULL, NULL, '2024-11-15 14:14:52', '2024-11-15 14:14:52'),
(15, 'App\\Models\\User', 1, 'webToken', '23cc6b9de016a732eb7cb480a1cb8d2a3a5e856a7d1f5279d4ddc11aff8c8f3b', '[\"*\"]', NULL, NULL, '2024-11-16 15:30:15', '2024-11-16 15:30:15'),
(16, 'App\\Models\\User', 1, 'webToken', 'aa740b606ad9f48ac368d5470bd59e643f6d3634e418ada706b8493fea9547ed', '[\"*\"]', NULL, NULL, '2024-11-17 06:26:45', '2024-11-17 06:26:45'),
(17, 'App\\Models\\User', 1, 'webToken', '0521f5b9b85d02de2679cc21f0a652dc3c6d4ebf025d68eee071ce71ae48eb9b', '[\"*\"]', NULL, NULL, '2024-11-17 15:34:51', '2024-11-17 15:34:51'),
(18, 'App\\Models\\User', 1, 'webToken', '5c646a39e46883a29d70c80f6c52a633792677d0677aebab327b03176e02770c', '[\"*\"]', NULL, NULL, '2024-11-18 02:50:41', '2024-11-18 02:50:41'),
(19, 'App\\Models\\User', 1, 'webToken', '4a171053d597d0dc8327a942250860b68da15c25bdaedb58db438a195ac02aeb', '[\"*\"]', NULL, NULL, '2024-11-20 05:09:52', '2024-11-20 05:09:52'),
(20, 'App\\Models\\User', 1, 'webToken', 'b51a6eda351e2b4bb608f908db2bd9568511f24ac90f55ce7b3ea438b8948910', '[\"*\"]', NULL, NULL, '2024-11-20 15:17:14', '2024-11-20 15:17:14'),
(21, 'App\\Models\\User', 1, 'webToken', 'ee8135c3eaec1be797c2fc0516114e6bb46834be357780e79d89cdc87324aacd', '[\"*\"]', NULL, NULL, '2024-12-15 05:03:38', '2024-12-15 05:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `footer` varchar(255) NOT NULL,
  `fevicon` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `login_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `address`, `date`, `status`, `created_at`, `updated_at`) VALUES
('9dbb8fe2-a974-4915-8eb8-8e13c73e96cd', 'Arbaz Ali Khan', '3124584554', 'xyz', NULL, 1, '2024-12-15 13:56:24', '2024-12-15 13:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_details`
--

CREATE TABLE `suppliers_details` (
  `id` varchar(255) NOT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `credit` double(20,4) NOT NULL,
  `debit` double(20,4) NOT NULL,
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers_details`
--

INSERT INTO `suppliers_details` (`id`, `supplier_id`, `transaction_id`, `type`, `date`, `credit`, `debit`, `status`, `created_at`, `updated_at`) VALUES
('9dbb8fe2-bc7d-485c-983b-3627298cbebd', '9dbb8fe2-a974-4915-8eb8-8e13c73e96cd', '9dbb8fe2-a974-4915-8eb8-8e13c73e96cd', 'Old Amount', '2024-12-15', 4500.0000, 0.0000, NULL, '2024-12-15 13:56:24', '2024-12-15 13:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `avator` varchar(255) NOT NULL,
  `group_id` int NOT NULL,
  `detete_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `mine_name` varchar(255) NOT NULL,
  `truck_weight` double(10,2) NOT NULL,
  `price_per_ton` double(15,2) NOT NULL,
  `truck_price` double(15,2) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `mine_expense` double(15,2) NOT NULL,
  `transport` double(20,2) NOT NULL,
  `tax` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL,
  `total_bill` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `order_id`, `supplier_id`, `mine_name`, `truck_weight`, `price_per_ton`, `truck_price`, `vehicle`, `mine_expense`, `transport`, `tax`, `date`, `status`, `total_bill`, `created_at`, `updated_at`) VALUES
('9d7f60c2-f91d-40c2-9ee6-cb0dbc269694', '9d7b6545-e817-4b1a-b27f-49c2164721a7', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', '20', 20.00, 20.00, 20.00, '20', 20.00, 20.00, 20.00, '2024-11-15', 1, 100.00, '2024-11-15 15:54:58', '2024-11-15 15:54:58'),
('9d815d40-362c-4245-ae93-b8293dbaf21c', '9d7b6545-e817-4b1a-b27f-49c2164721a7', '9d5e17b5-a61b-4d6b-b449-35414dcf06d0', 'Mine', 3.00, 2.00, 6.00, 'V#', 1.00, 4.00, 5.00, '2024-11-14', 1, 18.00, '2024-11-16 15:36:49', '2024-11-16 15:36:49'),
('9d815d41-0e9d-406f-99d2-f340b1997c31', '9d7b6545-e817-4b1a-b27f-49c2164721a7', '9d5e17b5-a61b-4d6b-b449-35414dcf06d0', 'Mine', 3.00, 2.00, 6.00, 'V#', 1.00, 4.00, 5.00, '2024-11-14', 1, 18.00, '2024-11-16 15:36:49', '2024-11-16 15:36:49'),
('9d8173dc-03f3-4d2d-a3da-e645d2d1f89c', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:40:01', '2024-11-16 16:40:01'),
('9d8173f3-b853-44ef-9a85-ce7db1be5259', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:40:16', '2024-11-16 16:40:16'),
('9d817438-99c8-4757-a87b-feffd2f8632d', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:41:02', '2024-11-16 16:41:02'),
('9d81746a-b911-4a28-8da0-95410d8e4f0c', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:41:34', '2024-11-16 16:41:34'),
('9d817476-ad72-40af-88e1-9c8dbb974941', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:41:42', '2024-11-16 16:41:42'),
('9d8174b4-590c-4a8a-a206-ac8e567f1dc7', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:42:23', '2024-11-16 16:42:23'),
('9d8174e6-5e0d-423f-bb89-04bfc73b4b4b', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:42:55', '2024-11-16 16:42:55'),
('9d817512-0212-4fc4-9919-bb73426b44c0', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:43:24', '2024-11-16 16:43:24'),
('9d817550-582d-4dbe-988c-2f7267318bf7', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:44:05', '2024-11-16 16:44:05'),
('9d8175df-8ecf-4ff7-afef-b24a496a69a3', '9d817364-3e0a-4e0c-9301-f2e21def2373', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine1', 23.00, 200.00, 4600.00, 'V-0234', 500.00, 500.00, 300.00, '2024-11-17', 1, 6100.00, '2024-11-16 16:45:39', '2024-11-16 16:45:39'),
('9d829c89-1e60-45ae-9533-b435916812f5', '9d829c0d-348b-4c7b-ba93-9d0e71c43024', '9d5e1511-b20d-4372-9818-0bc84bf1fdf5', 'Mine-12', 1.00, 100.00, 100.00, 'V-123', 200.00, 200.00, 200.00, '2024-11-17', 1, 800.00, '2024-11-17 06:29:35', '2024-11-17 06:29:35'),
('9d829cde-aa75-4b13-94ee-9438d33aa469', '9d829c0d-348b-4c7b-ba93-9d0e71c43024', '9d5e17b5-a61b-4d6b-b449-35414dcf06d0', 'M#12', 3.00, 100.00, 300.00, 'P3', 300.00, 200.00, 200.00, '2024-11-15', 1, 1100.00, '2024-11-17 06:30:31', '2024-11-17 06:30:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image` bigint DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verification_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `image`, `remember_token`, `token`, `verification_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', '2024-03-04 15:56:44', '$2y$12$NpE.iyEcisFLCwDQp3Mg7.OCIA/6K1vFQeKOsuh1pm3lPHOwvx0ga', 'admin', 262, NULL, 'snA6fFMSI6m3HlrUqI20UhQPa5n7jstB5foviX4JYrAIqTbmyYMpMU6scNF6mBBp', NULL, NULL, '2024-02-01 15:38:37', '2024-06-24 15:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `vihicles`
--

CREATE TABLE `vihicles` (
  `id` int NOT NULL,
  `vihicle_type` varchar(255) NOT NULL,
  `vihicle_no` varchar(255) NOT NULL,
  `vihicle_name` varchar(255) NOT NULL,
  `capacity` double(10,2) NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `driver_contact` varchar(255) NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `id` int NOT NULL,
  `year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_book`
--
ALTER TABLE `account_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_account`
--
ALTER TABLE `bank_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashbook`
--
ALTER TABLE `cashbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse`
--
ALTER TABLE `expanse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_account`
--
ALTER TABLE `expanse_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_details`
--
ALTER TABLE `expanse_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_type`
--
ALTER TABLE `expanse_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `mines`
--
ALTER TABLE `mines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner_details`
--
ALTER TABLE `partner_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers_details`
--
ALTER TABLE `suppliers_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vihicles`
--
ALTER TABLE `vihicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_book`
--
ALTER TABLE `account_book`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanse`
--
ALTER TABLE `expanse`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanse_account`
--
ALTER TABLE `expanse_account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanse_details`
--
ALTER TABLE `expanse_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expanse_type`
--
ALTER TABLE `expanse_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `vihicles`
--
ALTER TABLE `vihicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
