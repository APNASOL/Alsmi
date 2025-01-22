-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2025 at 10:56 PM
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
-- Database: `goritmi_alsmi`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `expense_type_id` varchar(255) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `transaction_id`, `expense_type_id`, `transaction_date`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
('9e061f17-1d72-452a-9e6c-3079832647e3', '9e061f17-1c0b-4913-ade0-a82e2f67a4f6', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-01-22 00:00:00', 5000.00, '2025-01-21 20:28:06', '2025-01-21 20:28:06', NULL),
('9e061f17-6871-48f5-a4ba-1f5508c43146', '9e061f17-6718-4cd2-a117-ce8a4e8b730c', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-01-22 00:00:00', 5000.00, '2025-01-21 20:28:06', '2025-01-21 20:28:06', NULL),
('9e062622-ff8b-488c-aa5e-3f5abf48ecfc', '9e062622-fe36-41e7-aee4-5badd77e2834', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-01-23 00:00:00', 34400.00, '2025-01-21 20:47:48', '2025-01-21 20:47:48', NULL),
('9e06263d-1597-4a59-9ff6-ef7d2167ec0c', '9e06263d-14a6-4f86-b0ef-f34fff67b434', '9e0418f6-755b-4fcc-b964-313d8370820f', '2025-01-23 00:00:00', 5600.00, '2025-01-21 20:48:05', '2025-01-21 20:48:05', NULL),
('9e06263d-8e2e-4343-93d3-7c1d7b30d80f', '9e06263d-8c69-4610-a336-dfcd4b1ce2f9', '9e0418f6-755b-4fcc-b964-313d8370820f', '2025-01-23 00:00:00', 5600.00, '2025-01-21 20:48:05', '2025-01-21 20:48:05', NULL),
('9e072c9e-d6d2-4fb2-96dd-f73dde70c610', '9e072c9e-d4d4-4364-8d6f-46245e48ad59', '9e0418f6-755b-4fcc-b964-313d8370820f', '2025-02-08 00:00:00', 50000.00, '2025-01-22 09:01:45', '2025-01-22 09:01:45', NULL),
('9e072cd9-a49c-467c-92d7-377928ef13fb', '9e072cd9-a35c-4690-944c-a254c374949b', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-02-06 00:00:00', 32600.00, '2025-01-22 09:02:24', '2025-01-22 09:02:24', NULL),
('9e074da4-9672-4a71-b9c1-64d9246f5dcd', '9e074da4-9489-4091-8dbd-4a1116001650', '9e0418f6-755b-4fcc-b964-313d8370820f', '2025-03-06 00:00:00', 45000.00, '2025-01-22 10:34:05', '2025-01-22 10:34:05', NULL),
('9e074dba-0228-4dbe-9abd-6143e38320c1', '9e074dba-0153-4078-9551-858c3ac78e6a', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-03-08 00:00:00', 3400.00, '2025-01-22 10:34:19', '2025-01-22 10:34:19', NULL),
('9e074e14-7b38-4113-861a-f808f6b0c86c', '9e074e14-79f5-4673-abd6-ffa33f0f96e0', '9e0146e4-b4d9-4b77-8f86-4ebe12f91042', '2025-04-10 00:00:00', 3490.00, '2025-01-22 10:35:19', '2025-01-22 10:35:19', NULL),
('9e07e3df-79e2-4b29-8d4e-517b65474c9a', '9e07e3de-3562-49ef-858d-fb9e3ff9b7a0', '9e041902-3d1c-4d26-8fc7-a3dedc0e482a', '2025-01-22 00:00:00', 50000.00, '2025-01-22 17:34:10', '2025-01-22 17:34:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('9e0146e4-b4d9-4b77-8f86-4ebe12f91042', 'Utility Bills', '2025-01-19 10:40:15', '2025-01-20 20:19:13', NULL),
('9e0418f6-755b-4fcc-b964-313d8370820f', 'Salaries', '2025-01-20 20:19:18', '2025-01-20 20:19:18', NULL),
('9e041902-3d1c-4d26-8fc7-a3dedc0e482a', 'Transport', '2025-01-20 20:19:26', '2025-01-20 20:19:26', NULL),
('9e07e483-ff39-49b4-a041-a074a8da062e', 'Food Expanse', '2025-01-22 17:35:58', '2025-01-22 17:35:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `income_type_id` varchar(255) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `transaction_id`, `income_type_id`, `transaction_date`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
('9e061ec1-2c3a-40a5-b7cd-489d055b6ee7', '9e061ec1-2afa-40de-aea2-3d858efc9165', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-22 00:00:00', 3000.00, '2025-01-21 20:27:09', '2025-01-21 20:27:09', NULL),
('9e061ee4-a335-4556-8d4c-11f90e127daf', '9e061ee4-a24f-4ab7-bf74-2a62dbb4b107', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-22 00:00:00', 5000.00, '2025-01-21 20:27:32', '2025-01-21 20:27:32', NULL),
('9e061efd-e6db-4c22-be3e-fc27496d4bf4', '9e061efd-e5ef-4669-9fb1-4a53cf224d6d', '9e021a91-4814-4906-a31f-bf6a05e4a14b', '2025-01-22 00:00:00', 3000.00, '2025-01-21 20:27:49', '2025-01-21 20:27:49', NULL),
('9e062656-063e-458e-b339-9f3e30da25a1', '9e062656-053d-4666-a6c9-5f7c3a5c2e8c', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-23 00:00:00', 12000.00, '2025-01-21 20:48:21', '2025-01-21 20:48:21', NULL),
('9e06267f-c87e-4ef3-9263-bd0a13e9c934', '9e06267f-c70c-4442-a32b-052a1f096999', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-23 00:00:00', 1000.00, '2025-01-21 20:48:49', '2025-01-21 20:48:49', NULL),
('9e072be0-80b5-4a6e-a75a-5113139d907f', '9e072bdf-d3e0-4901-bacd-41f4b9738cdd', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-30 00:00:00', 500000.00, '2025-01-22 08:59:40', '2025-01-22 08:59:40', NULL),
('9e072cfe-81e7-457b-aad6-734dceda533d', '9e072cfe-80fa-4303-bab1-ff7f2dc991bd', '9e021a91-4814-4906-a31f-bf6a05e4a14b', '2025-02-25 00:00:00', 21000.00, '2025-01-22 09:02:48', '2025-01-22 09:02:48', NULL),
('9e074dd7-0544-49d0-a0cf-b8a9a5ef30ab', '9e074dd7-045e-4225-b5d3-ccd74e769c32', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-04-16 00:00:00', 34000.00, '2025-01-22 10:34:39', '2025-01-22 10:34:39', NULL),
('9e074dee-3e6b-4ab5-89f3-6199cc339ac2', '9e074dee-3d85-428d-83ac-aab8f65a8811', '9e021a91-4814-4906-a31f-bf6a05e4a14b', '2025-04-24 00:00:00', 45000.00, '2025-01-22 10:34:54', '2025-01-22 10:34:54', NULL),
('9e07e43e-216c-4ad3-8337-62a61bcd6ef2', '9e07e43e-1f73-469e-bf5b-9e3f3b2bfb66', '9e0211c1-a452-4ad7-a99e-9312ca88ba4f', '2025-01-22 00:00:00', 500000.00, '2025-01-22 17:35:12', '2025-01-22 17:35:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `income_types`
--

CREATE TABLE `income_types` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `income_types`
--

INSERT INTO `income_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('9e0211c1-a452-4ad7-a99e-9312ca88ba4f', 'Hospital Counter', '2025-01-19 20:07:30', '2025-01-20 20:18:59', NULL),
('9e021a91-4814-4906-a31f-bf6a05e4a14b', 'Pharmacy', '2025-01-19 20:32:08', '2025-01-20 20:18:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'webToken', '32b3f4d3092339318f606106b989b065ab3e9abc90c73cb6e7df7ef8a776d6fc', '[\"*\"]', NULL, NULL, '2025-01-18 04:47:40', '2025-01-18 04:47:40'),
(2, 'App\\Models\\User', 1, 'webToken', 'fa5090e0287bde87b8978feeb0709ba55289bece4e6c9d343acf55a78d33f7c8', '[\"*\"]', NULL, NULL, '2025-01-18 09:30:59', '2025-01-18 09:30:59'),
(3, 'App\\Models\\User', 1, 'webToken', '1e1c2a91927596b7e1856064c12174d92250e916f07336e754a4ed8842350240', '[\"*\"]', NULL, NULL, '2025-01-18 09:31:37', '2025-01-18 09:31:37'),
(4, 'App\\Models\\User', 1, 'webToken', '843753b9fca5eee7df13923148eba45cf58a945cf0c31c61c0e121efe3d0b0ca', '[\"*\"]', NULL, NULL, '2025-01-18 10:40:42', '2025-01-18 10:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` varchar(255) NOT NULL,
  `cash_in` decimal(15,2) DEFAULT NULL,
  `cash_out` decimal(15,2) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `method` enum('Bank','Cash') NOT NULL,
  `remarks` text,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_type_id` varchar(255) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `cash_in`, `cash_out`, `transaction_date`, `ref_no`, `method`, `remarks`, `user_id`, `transaction_type_id`, `receipt_image`, `created_at`, `updated_at`, `deleted_at`) VALUES
('9e061ec1-2afa-40de-aea2-3d858efc9165', 3000.00, NULL, '2024-11-11 00:00:00', 'R1', 'Bank', 'Remarks 1', 1, NULL, NULL, '2025-01-21 20:27:09', '2025-01-22 15:40:04', NULL),
('9e061ee4-a24f-4ab7-bf74-2a62dbb4b107', 500.00, NULL, '2024-11-04 00:00:00', 'R2', 'Cash', 'Remarks 2', 1, NULL, NULL, '2025-01-21 20:27:32', '2025-01-22 15:43:16', NULL),
('9e061efd-e5ef-4669-9fb1-4a53cf224d6d', 3000.00, NULL, '2025-01-22 00:00:00', 'R4', 'Cash', 'Remarks 3', 1, NULL, NULL, '2025-01-21 20:27:49', '2025-01-21 20:27:49', NULL),
('9e061f17-1c0b-4913-ade0-a82e2f67a4f6', NULL, 5000.00, '2024-09-10 00:00:00', 'R5', 'Bank', 'Remarks 5', 1, NULL, NULL, '2025-01-21 20:28:06', '2025-01-22 15:40:11', NULL),
('9e061f17-6718-4cd2-a117-ce8a4e8b730c', NULL, 5000.00, '2025-01-22 00:00:00', 'R5', 'Bank', 'Remarks 5', 1, NULL, NULL, '2025-01-21 20:28:06', '2025-01-21 20:28:06', NULL),
('9e062622-fe36-41e7-aee4-5badd77e2834', NULL, 3440.00, '2024-04-17 00:00:00', 'R1', 'Bank', 'Remarks 1', 1, NULL, NULL, '2025-01-21 20:47:48', '2025-01-22 15:43:43', NULL),
('9e06263d-14a6-4f86-b0ef-f34fff67b434', NULL, 5600.00, '2025-01-23 00:00:00', 'R4', 'Bank', 'Remarks 2', 1, NULL, NULL, '2025-01-21 20:48:05', '2025-01-21 20:48:05', NULL),
('9e06263d-8c69-4610-a336-dfcd4b1ce2f9', NULL, 5600.00, '2024-10-15 00:00:00', 'R4', 'Bank', 'Remarks 2', 1, NULL, NULL, '2025-01-21 20:48:05', '2025-01-22 15:41:27', NULL),
('9e062656-053d-4666-a6c9-5f7c3a5c2e8c', 1200.00, NULL, '2024-09-17 00:00:00', 'R2', 'Bank', 'Remarks 4', 1, NULL, NULL, '2025-01-21 20:48:21', '2025-01-22 15:42:58', NULL),
('9e06267f-c70c-4442-a32b-052a1f096999', 1000.00, NULL, '2024-10-15 00:00:00', 'R4', 'Cash', 'Remarks 5', 1, NULL, NULL, '2025-01-21 20:48:49', '2025-01-22 15:40:20', NULL),
('9e072bdf-d3e0-4901-bacd-41f4b9738cdd', 500.00, NULL, '2025-01-30 00:00:00', 'R2', 'Bank', 'Remarks 1', 1, NULL, NULL, '2025-01-22 08:59:40', '2025-01-22 15:43:11', NULL),
('9e072c9e-d4d4-4364-8d6f-46245e48ad59', NULL, 5000.00, '2024-09-17 00:00:00', 'R2', 'Bank', 'Payed Salaries to Employees', 1, NULL, NULL, '2025-01-22 09:01:45', '2025-01-22 15:43:32', NULL),
('9e072cd9-a35c-4690-944c-a254c374949b', NULL, 3200.00, '2025-02-06 00:00:00', 'R2', 'Bank', 'Electricity Bill Paid', 1, NULL, NULL, '2025-01-22 09:02:24', '2025-01-22 15:43:36', NULL),
('9e072cfe-80fa-4303-bab1-ff7f2dc991bd', 1000.00, NULL, '2025-02-25 00:00:00', 'R2', 'Cash', 'Remarks 45', 1, NULL, NULL, '2025-01-22 09:02:48', '2025-01-22 15:42:32', NULL),
('9e074da4-9489-4091-8dbd-4a1116001650', NULL, 4500.00, '2024-08-21 00:00:00', 'R45', 'Bank', 'Remarks', 1, NULL, NULL, '2025-01-22 10:34:05', '2025-01-22 15:43:28', NULL),
('9e074dba-0153-4078-9551-858c3ac78e6a', NULL, 3400.00, '2025-03-08 00:00:00', 'R453', 'Bank', 'Remarks', 1, NULL, NULL, '2025-01-22 10:34:19', '2025-01-22 10:34:19', NULL),
('9e074dd7-045e-4225-b5d3-ccd74e769c32', 3400.00, NULL, '2024-09-17 00:00:00', 'R453', 'Cash', 'Remarks', 1, NULL, NULL, '2025-01-22 10:34:38', '2025-01-22 15:42:46', NULL),
('9e074dee-3d85-428d-83ac-aab8f65a8811', 4500.00, NULL, '2025-04-24 00:00:00', 'R4533', 'Cash', 'Remarks', 1, NULL, NULL, '2025-01-22 10:34:54', '2025-01-22 15:42:49', NULL),
('9e074e14-79f5-4673-abd6-ffa33f0f96e0', NULL, 3490.00, '2024-05-07 00:00:00', 'R453', 'Cash', 'Remarks', 1, NULL, NULL, '2025-01-22 10:35:19', '2025-01-22 15:40:36', NULL),
('9e07e3de-3562-49ef-858d-fb9e3ff9b7a0', NULL, 50000.00, '2025-01-22 00:00:00', 'Ref123', 'Cash', 'Transport expanses', 1, NULL, NULL, '2025-01-22 17:34:10', '2025-01-22 17:34:10', NULL),
('9e07e43e-1f73-469e-bf5b-9e3f3b2bfb66', 500000.00, NULL, '2025-01-22 00:00:00', 'Ref230', 'Cash', 'Income form Hospital', 1, NULL, NULL, '2025-01-22 17:35:12', '2025-01-22 17:35:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int NOT NULL,
  `file_original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `extension` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Goritmi', 'alsmi@admin.com', '2024-03-04 15:56:44', '$2y$10$xEp1S5ohkdg6iaq7IntpJObUss0lWxspZ.1uGMFnVOMBqsqZbFknW', 'admin', 1033, NULL, 'snA6fFMSI6m3HlrUqI20UhQPa5n7jstB5foviX4JYrAIqTbmyYMpMU6scNF6mBBp', NULL, NULL, '2024-02-01 15:38:37', '2025-01-18 15:15:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `expense_type_id` (`expense_type_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `income_type_id` (`income_type_id`);

--
-- Indexes for table `income_types`
--
ALTER TABLE `income_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `transaction_type_id` (`transaction_type_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`income_type_id`) REFERENCES `income_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`transaction_type_id`) REFERENCES `expense_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
