-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 12:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_activation_attempts`
--

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'staff', 'Staff Exim'),
(3, 'accounting', 'Staff Accounting');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `email`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Admin', 1, '2025-09-15 09:00:32', 0),
(2, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-15 09:01:11', 1),
(3, '::1', 'Admin', NULL, '2025-09-18 02:44:35', 0),
(4, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-18 02:44:45', 1),
(5, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-18 04:21:42', 1),
(6, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-18 07:17:45', 1),
(7, '::1', 'Admin', NULL, '2025-09-18 12:02:04', 0),
(8, '::1', 'sefwef', NULL, '2025-09-18 12:12:37', 0),
(9, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 07:26:41', 1),
(10, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 07:42:57', 1),
(11, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 11:01:58', 1),
(12, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 11:54:22', 1),
(13, '::1', 'Admin', NULL, '2025-09-19 11:59:03', 0),
(14, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:00:48', 1),
(15, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:09:58', 1),
(16, '::1', 'Admin', NULL, '2025-09-19 12:12:23', 0),
(17, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:12:29', 1),
(18, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:15:22', 1),
(19, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:16:31', 1),
(20, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-19 12:20:26', 1),
(21, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 02:49:36', 1),
(22, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 02:49:47', 1),
(23, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 02:51:22', 1),
(24, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 03:07:57', 1),
(25, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 03:14:44', 1),
(26, '::1', 'ewffwe', NULL, '2025-09-20 03:15:02', 0),
(27, '::1', 'yogasaputra2896', NULL, '2025-09-20 03:15:53', 0),
(28, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 03:16:01', 1),
(29, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-20 03:17:00', 1),
(30, '::1', 'exim3@trustwaytransindo.com', 2, '2025-09-22 04:07:57', 1),
(31, '::1', 'accouting1', NULL, '2025-09-22 04:12:14', 0),
(32, '::1', 'accouting1', NULL, '2025-09-22 04:12:25', 0),
(33, '::1', 'accouting1', NULL, '2025-09-22 04:12:57', 0),
(34, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-09-22 04:14:10', 1),
(35, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 04:22:12', 1),
(36, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 04:58:55', 1),
(37, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:06:03', 1),
(38, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:34:56', 1),
(39, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:46:40', 1),
(40, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:52:15', 1),
(41, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:54:00', 1),
(42, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 07:59:15', 1),
(43, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 08:10:13', 1),
(44, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 09:46:02', 1),
(45, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 10:07:37', 1),
(46, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-22 10:41:42', 1),
(47, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 06:58:27', 1),
(48, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 06:59:06', 1),
(49, '::1', 'Exim3', NULL, '2025-09-23 07:00:22', 0),
(50, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 07:00:41', 1),
(51, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 07:13:09', 1),
(52, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 07:16:02', 1),
(53, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 07:36:20', 1),
(54, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 08:10:55', 1),
(55, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:18:42', 1),
(56, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:21:07', 1),
(57, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:33:17', 1),
(58, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:35:05', 1),
(59, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:36:43', 1),
(60, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 09:40:31', 1),
(61, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-23 10:38:29', 1),
(62, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 02:34:02', 1),
(63, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 02:36:00', 1),
(64, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 02:56:08', 1),
(65, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 03:00:27', 1),
(66, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 03:57:52', 1),
(67, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 05:01:18', 1),
(68, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 06:30:18', 1),
(69, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 07:10:59', 1),
(70, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-24 08:48:16', 1),
(71, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-27 06:24:01', 1),
(72, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-27 06:24:08', 1),
(73, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-27 06:24:17', 1),
(74, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-27 06:24:30', 1),
(75, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-30 01:57:36', 1),
(76, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-30 07:24:12', 1),
(77, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-30 07:51:53', 1),
(78, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-30 08:35:41', 1),
(79, '::1', 'yogasaputra2896@gmail.com', 1, '2025-09-30 10:36:25', 1),
(80, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-01 06:55:05', 1),
(81, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-01 09:42:41', 1),
(82, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-01 10:39:53', 1),
(83, '::1', 'Admin', NULL, '2025-10-01 15:52:16', 0),
(84, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-01 15:52:31', 1),
(85, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-04 08:31:05', 1),
(86, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-04 10:07:13', 1),
(87, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-04 11:08:06', 1),
(88, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-18 09:31:18', 1),
(89, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-18 10:37:44', 1),
(90, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-19 13:47:02', 1),
(91, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-19 15:22:30', 1),
(92, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:11:05', 1),
(93, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:11:59', 1),
(94, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:12:22', 1),
(95, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:12:29', 1),
(96, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:15:25', 1),
(97, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 03:20:25', 1),
(98, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 04:10:37', 1),
(99, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 06:30:48', 1),
(100, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 08:07:56', 1),
(101, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 09:21:54', 1),
(102, '::1', 'yogasaputra2896@gmail.com', 1, '2025-10-20 10:39:45', 1),
(103, '::1', 'exim3@trustwaytransindo.com', 2, '2025-10-20 10:42:20', 1),
(104, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-10-20 10:46:25', 1),
(105, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-05 02:40:34', 1),
(106, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-05 02:40:57', 1),
(107, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-05 11:37:57', 1),
(108, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-10 06:53:03', 1),
(109, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-10 09:18:39', 1),
(110, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-10 10:22:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_reset_attempts`
--

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_job`
--

CREATE TABLE `booking_job` (
  `id` int(10) UNSIGNED NOT NULL,
  `no_job` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `party` varchar(25) DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `pol` varchar(25) DEFAULT NULL,
  `no_pib_po` varchar(26) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `bl` varchar(50) DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'open job'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_job`
--

INSERT INTO `booking_job` (`id`, `no_job`, `type`, `consignee`, `party`, `eta`, `pol`, `no_pib_po`, `shipping_line`, `bl`, `master_bl`, `created_at`, `updated_at`, `status`) VALUES
(11, 'IM/25/600001', 'import_fcl_nonjaminan', 'PT TESTER DATA', '2 x 20', '2025-09-23', 'SHEKOU', 'C25-000014', 'SITC', 'SITC123456', 'MSITC123456', '2025-09-23 08:58:39', '2025-10-04 11:36:33', 'worksheet'),
(12, 'IM/25/400001', 'import_fcl_jaminan', 'PT TESTER DATA', '1 X 20', '2025-09-23', 'PORT KELANG', 'C25-001234', 'MCC LINE', 'MCC123456', 'MMCC123456', '2025-09-23 08:55:00', '2025-10-04 11:36:19', 'worksheet'),
(21, 'EX/25/300001', 'export', 'PT TESTER DATA', '1 X 20', '2025-09-01', 'TANJUNG PERAK', 'CE25-00001', 'ONE LINE', 'ONE123444', 'MONE123444', '2025-09-24 08:48:41', '2025-10-04 11:33:19', 'worksheet'),
(22, 'EX/25/300002', 'export', 'PT TESTER DATA', '2 X 20', '2025-09-30', 'TANJUNG PRIOK', 'CE25-00002', 'KMTC LINE', 'KMTC000001', 'MKMTC000001', '2025-09-30 02:20:11', '2025-10-04 11:35:58', 'worksheet'),
(23, 'EX/25/300003', 'export', 'PT TESTER DATA', '3 X 20', '2025-09-29', 'TANJUNG PERAK', 'CE25-00003', 'MCC LINE', 'MCC000001', 'MMCC000001', '2025-09-30 02:21:35', '2025-10-01 10:54:52', 'open job'),
(24, 'EX/25/300004', 'export', 'PT TESTER DATA', '4 X 20', '2025-09-28', 'TANJUNG PRIOK', 'CE25-00004', 'SITC LINE', 'SITC000001', 'MSITC000001', '2025-09-30 02:23:07', '2025-10-01 10:54:32', 'open job'),
(28, 'IM/25/100001', 'import_lcl', 'PT TESTER DATA', '1 PK', '2025-09-30', 'SHANGHAI', 'C25-000001', 'WAN HAI LINE', 'WAN123456', 'MWAN123456', '2025-09-30 08:32:17', '2025-10-04 10:20:52', 'worksheet'),
(29, 'IM/25/100002', 'import_lcl', 'PT TESTER DATA', '2 PK', '2025-09-29', 'PORT KELANG', 'C25-000002', 'KMTC LINE', 'KMTC000003', 'MKMTC000003', '2025-09-30 08:40:26', '2025-10-04 11:36:13', 'worksheet'),
(30, 'IM/25/100003', 'import_lcl', 'PT TESTER DATA', '3 PK', '2025-09-28', 'SHEKOU', 'C25-000003', 'ONE LINE', 'ONE000003', 'MONE000003', '2025-09-30 08:41:36', '2025-09-30 08:41:36', 'open job'),
(31, 'IM/25/100004', 'import_lcl', 'PT TESTER DATA', '4 PK', '2025-09-27', 'INCHEON', 'C25-000004', 'WAN HAI LINE', 'WAN000001', 'MWAN000001', '2025-09-30 08:42:36', '2025-09-30 08:42:36', 'open job'),
(33, 'IM/25/400002', 'import_fcl_jaminan', 'PT TESTER DATA', '1 X 20', '2025-09-10', 'PORT KELANG', 'C25-000010', 'ONE LINE', 'ONE000010', 'MONE000010', '2025-09-30 10:37:57', '2025-10-04 11:36:25', 'worksheet'),
(34, 'IM/25/400003', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-19', 'INCHEON', 'C25-000011', 'SITC LINE', 'SITC000010', 'MSITC000010', '2025-09-30 10:38:52', '2025-09-30 10:38:52', 'open job'),
(35, 'IM/25/400004', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-16', 'SHANGHAI', 'C25-000012', 'MCC LINE', 'MCC000010', 'MMCC000010', '2025-09-30 10:39:58', '2025-09-30 10:39:58', 'open job'),
(37, 'IM/25/600002', 'import_fcl_nonjaminan', 'PT TESTER DATA', '1 X 20', '2025-09-18', 'SHEKOU', 'C25-000015', 'ONE LINE', 'ONE000011', 'MONE000011', '2025-09-30 10:42:36', '2025-10-04 11:36:38', 'worksheet'),
(38, 'IM/25/600003', 'import_fcl_nonjaminan', 'PT TESTER DATA', '4 X 20', '2025-09-17', 'PORT KELANG', 'C25-000016', 'WAN HAI LINE', 'WAN000002', 'MWAN000002', '2025-09-30 10:46:08', '2025-10-01 10:55:47', 'open job'),
(39, 'IM/25/600004', 'import_fcl_nonjaminan', 'PT TESTER DATA', '2 x 20', '2025-09-25', 'SHEKOU', 'C25-000017', 'SITC LINE', 'SITC000011', 'MSITC000011', '2025-09-30 10:46:57', '2025-09-30 10:46:57', 'open job'),
(43, 'IM/25/400005', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-27', 'PORT KELANG', 'C25-000013', 'KMTC LINE', 'KMTC000010', 'MKMTC000010', '2025-10-01 10:41:54', '2025-10-01 10:55:26', 'open job'),
(44, 'IM/25/600005', 'import_fcl_nonjaminan', 'PT TESTER DATA', '4 X 20', '2025-09-15', 'SHANGHAI', 'C25-000017', 'ONE LINE', 'ONE000012', 'MONE000012', '2025-10-01 10:41:58', '2025-10-01 10:41:58', 'open job'),
(45, 'EX/25/300005', 'export', 'PT TESTER DATA', '5 x 20', '2025-09-28', 'TANJUNG PRIOK', 'CE25-00005', 'ONE LINE', 'ONE000002', 'MONE000002', '2025-10-01 10:48:17', '2025-10-01 18:39:23', 'open job'),
(46, 'IM/25/500001', 'lain', 'PT TESTER DATA', '2 BAG', '2025-10-04', 'INCHEON', 'BUAT ASURANSI', 'OOCL LINE', 'OOLU000001', '-', '2025-10-04 12:04:13', '2025-10-04 12:13:57', 'worksheet'),
(47, 'IM/25/500002', 'lain', 'PT TESTER DATA', '2 PK', '2025-10-04', 'SHANGHAI', 'TRUCKING ONLY', ' KMTC LINE', 'KMTCT000001', '-', '2025-10-04 12:06:47', '2025-10-04 12:14:01', 'worksheet'),
(48, 'IM/25/500003', 'lain', 'PT TESTER DATA', '2 PK', '2025-10-04', '-', 'BUAT LARTAS LS', '-', 'INV123456', '-', '2025-10-04 12:11:23', '2025-10-04 12:11:23', 'open job'),
(49, 'IM/25/500004', 'lain', 'PT TESTER DATA', '5 PK', '2025-10-04', 'BANGKOK', 'BUAT ASURANSI', 'KMTC LINE', 'INV654321', '-', '2025-10-04 12:12:34', '2025-10-04 12:12:34', 'open job'),
(50, 'IM/25/500005', 'lain', 'PT TESTER DATA', '1 X 20', '2025-10-04', 'KUALA LUMPUR', 'TRUCKING ONLY', 'ONE LINE', 'ONE0101010', '-', '2025-10-04 12:13:52', '2025-10-04 12:13:52', 'open job');

-- --------------------------------------------------------

--
-- Table structure for table `booking_job_trash`
--

CREATE TABLE `booking_job_trash` (
  `id` int(10) UNSIGNED NOT NULL,
  `no_job` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `party` varchar(25) DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `pol` varchar(25) DEFAULT NULL,
  `no_pib_po` varchar(26) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `bl` varchar(50) DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'open job'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_job_trash`
--

INSERT INTO `booking_job_trash` (`id`, `no_job`, `type`, `consignee`, `party`, `eta`, `pol`, `no_pib_po`, `shipping_line`, `bl`, `master_bl`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(26, 'IM/25/100005', 'import_lcl', 'PT TESTER DATA', '5 PK', '2025-09-20', 'SHANGHAI', 'C25-000005', 'SITC LINE', 'SITC000002', 'MSITC000002', '2025-10-19 13:50:56', '2025-10-19 13:50:56', 'Admin', '2025-10-19 13:50:56', 'open job');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'App\\Database\\Migrations\\CreateAuthTables', 'default', 'App', 1757912074, 1),
(2, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1757912074, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'yogasaputra2896@gmail.com', 'Admin', '$2y$10$Qxxbcq/MJ1lDOWB6P179qOzk5GZgL9nAUQSFUUsG3.n8S6uH3A5.G', NULL, NULL, NULL, 'f39d1e936f604c944bcc80301836ab1c', NULL, NULL, 1, 0, '2025-09-15 08:58:13', '2025-09-15 08:58:13', NULL),
(2, 'exim3@trustwaytransindo.com', 'Exim3', '$2y$10$bw2vbtf1QGMNspj9gtOpd.UPjtDxEgY04K67eFGxbUKwafJ5s/A6G', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2025-09-22 04:05:53', '2025-09-22 04:05:53', NULL),
(3, 'accounting1@trustwaytransindo.com', 'Accounting1', '$2y$10$QbcZm0AEA2kBbA/rmdOxn./ZCtCGvayMyXZKEXBDCo1IFAvokZHfa', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2025-09-22 04:11:54', '2025-09-22 04:11:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_trash`
--

CREATE TABLE `user_trash` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_container`
--

CREATE TABLE `worksheet_container` (
  `id` int(10) NOT NULL,
  `id_ws` int(10) NOT NULL,
  `no_container` varchar(20) NOT NULL,
  `tipe` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_container`
--

INSERT INTO `worksheet_container` (`id`, `id_ws`, `no_container`, `tipe`, `created_at`) VALUES
(5, 11, 'CSNU1234567', '20', '2025-11-10 04:01:30'),
(6, 11, 'CSNU7654321', '40', '2025-11-10 04:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_export`
--

CREATE TABLE `worksheet_export` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `tgl_aju` date DEFAULT NULL,
  `peb_nopen` varchar(6) DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `notify_party` varchar(255) DEFAULT NULL,
  `vessel` varchar(50) DEFAULT NULL,
  `no_voyage` varchar(25) DEFAULT NULL,
  `pod` varchar(25) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `commodity` varchar(255) DEFAULT NULL,
  `party` varchar(10) DEFAULT NULL,
  `jenis_con` varchar(10) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL,
  `net` int(50) DEFAULT NULL,
  `gross` int(50) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` varchar(10) DEFAULT NULL,
  `etd` date DEFAULT NULL,
  `closing` date DEFAULT NULL,
  `stuffing` date DEFAULT NULL,
  `depo` varchar(255) DEFAULT NULL,
  `terminal` varchar(255) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(25) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_export`
--

INSERT INTO `worksheet_export` (`id`, `no_ws`, `no_aju`, `tgl_aju`, `peb_nopen`, `tgl_nopen`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pod`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `etd`, `closing`, `stuffing`, `depo`, `terminal`, `top`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(1, 'EX/25/300001', 'CE25-00001', NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, 'TANJUNG PERAK', 'ONE LINE', NULL, '1 X 20', NULL, NULL, NULL, NULL, 'ONE123444', NULL, 'MONE123444', NULL, NULL, NULL, '2025-09-01', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:33:19', '2025-10-04 20:25:52', NULL, '0000-00-00 00:00:00', 'not completed'),
(2, 'EX/25/300002', 'CE25-00002', NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, 'TANJUNG PRIOK', 'KMTC LINE', NULL, '2 X 20', NULL, NULL, NULL, NULL, 'KMTC000001', NULL, 'MKMTC000001', NULL, NULL, NULL, '2025-09-30', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:35:58', '2025-10-04 20:25:41', NULL, '0000-00-00 00:00:00', 'not completed');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_import`
--

CREATE TABLE `worksheet_import` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `tgl_aju` date DEFAULT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `io_number` varchar(10) DEFAULT NULL,
  `pib_nopen` varchar(6) DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `tgl_sppb` date DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `notify_party` varchar(255) DEFAULT NULL,
  `vessel` varchar(50) DEFAULT NULL,
  `no_voyage` varchar(25) DEFAULT NULL,
  `pol` varchar(25) DEFAULT NULL,
  `terminal` varchar(255) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `commodity` varchar(255) DEFAULT NULL,
  `party` varchar(10) DEFAULT NULL,
  `jenis_con` varchar(10) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `net` int(50) DEFAULT NULL,
  `gross` int(50) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` date DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `do` varchar(50) DEFAULT NULL,
  `tgl_mati_do` date DEFAULT NULL,
  `asuransi` varchar(50) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(25) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_import`
--

INSERT INTO `worksheet_import` (`id`, `no_ws`, `no_aju`, `tgl_aju`, `no_po`, `io_number`, `pib_nopen`, `tgl_nopen`, `tgl_sppb`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pol`, `terminal`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `kemasan`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `eta`, `do`, `tgl_mati_do`, `asuransi`, `top`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(11, 'IM/25/100001', '00000000686820251018000001', '2025-10-18', 'POIF-25-0001', '-', '123456', '2025-10-18', '2025-10-18', 'SHELL OIL CO LTD', 'PT TESTER DATA', 'PT TESTER DATA GRUB', 'ONE MADRID', '0001W', 'KUALA LUMPUR', 'NPCT', 'WAN HAI LINE', 'BAHAN BAKAR SHELL', '1 PK', 'FCL', '1', 'Package', 1000, 1000, 'WAN123456', '2025-10-18', 'MWAN123456', '2025-10-18', 'INV123456', '2025-10-18', '2025-09-30', 'Sudah Ada DO', '2025-10-18', 'CIF / CIP', 'PREPAID', '-', '2025-10-04 10:20:51', '2025-11-10 11:01:30', NULL, '0000-00-00 00:00:00', 'completed'),
(12, 'IM/25/100002', 'C25-000002', '0000-00-00', '', '', '', '0000-00-00', '0000-00-00', '', 'PT TESTER DATA', '', '', '', 'PORT KELANG', '', 'KMTC LINE', '', '2 PK', NULL, '', '', 0, 0, 'KMTC000003', '0000-00-00', 'MKMTC000003', '0000-00-00', '', '0000-00-00', '2025-09-29', '', '0000-00-00', '', '', '', '2025-10-04 11:36:13', '2025-10-20 03:59:42', NULL, '0000-00-00 00:00:00', 'not completed'),
(13, 'IM/25/400001', 'C25-001234', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'PORT KELANG', NULL, 'MCC LINE', NULL, '1 X 20', NULL, NULL, '', NULL, NULL, 'MCC123456', NULL, 'MMCC123456', NULL, NULL, NULL, '2025-09-23', NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:36:19', '2025-10-04 20:28:49', NULL, '0000-00-00 00:00:00', 'not completed'),
(14, 'IM/25/400002', 'C25-000010', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'PORT KELANG', NULL, '', NULL, '1 X 20', NULL, NULL, '', NULL, NULL, 'ONE000010', NULL, 'MONE000010', NULL, NULL, NULL, '2025-09-10', NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:36:25', '2025-10-04 20:33:09', NULL, '0000-00-00 00:00:00', 'not completed'),
(15, 'IM/25/600001', 'C25-000014', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'SHEKOU', NULL, 'SITC', NULL, '2 x 20', NULL, NULL, '', NULL, NULL, 'SITC123456', NULL, 'MSITC123456', NULL, NULL, NULL, '2025-09-23', NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:36:33', '2025-10-04 20:33:43', NULL, '0000-00-00 00:00:00', 'not completed'),
(16, 'IM/25/600002', 'C25-000015', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'SHEKOU', NULL, 'ONE LINE', NULL, '1 X 20', NULL, NULL, '', NULL, NULL, 'ONE000011', NULL, 'MONE000011', NULL, NULL, NULL, '2025-09-18', NULL, NULL, NULL, NULL, NULL, '2025-10-04 11:36:38', '2025-10-04 20:33:43', NULL, '0000-00-00 00:00:00', 'not completed'),
(17, 'IM/25/500001', 'BUAT ASURANSI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'INCHEON', NULL, 'OOCL LINE', NULL, '2 BAG', NULL, NULL, '', NULL, NULL, 'OOLU000001', NULL, '-', NULL, NULL, NULL, '2025-10-04', NULL, NULL, NULL, NULL, NULL, '2025-10-04 12:13:57', '2025-10-04 20:33:43', NULL, '0000-00-00 00:00:00', 'not completed'),
(18, 'IM/25/500002', 'TRUCKING ONLY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'SHANGHAI', NULL, '', NULL, '2 PK', NULL, NULL, '', NULL, NULL, 'KMTCT000001', NULL, '-', NULL, NULL, NULL, '2025-10-04', NULL, NULL, NULL, NULL, NULL, '2025-10-04 12:14:01', '2025-10-04 20:33:55', NULL, '0000-00-00 00:00:00', 'not completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indexes for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indexes for table `booking_job`
--
ALTER TABLE `booking_job`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_job` (`no_job`),
  ADD UNIQUE KEY `bl` (`bl`);

--
-- Indexes for table `booking_job_trash`
--
ALTER TABLE `booking_job_trash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_trash`
--
ALTER TABLE `user_trash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worksheet_container`
--
ALTER TABLE `worksheet_container`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`) USING BTREE;

--
-- Indexes for table `worksheet_export`
--
ALTER TABLE `worksheet_export`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_ws` (`no_ws`),
  ADD UNIQUE KEY `no_aju` (`no_aju`),
  ADD UNIQUE KEY `bl` (`bl`);

--
-- Indexes for table `worksheet_import`
--
ALTER TABLE `worksheet_import`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_ws` (`no_ws`),
  ADD UNIQUE KEY `no_aju` (`no_aju`),
  ADD UNIQUE KEY `bl` (`bl`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_job`
--
ALTER TABLE `booking_job`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `booking_job_trash`
--
ALTER TABLE `booking_job_trash`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_trash`
--
ALTER TABLE `user_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_container`
--
ALTER TABLE `worksheet_container`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `worksheet_export`
--
ALTER TABLE `worksheet_export`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `worksheet_import`
--
ALTER TABLE `worksheet_import`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_container`
--
ALTER TABLE `worksheet_container`
  ADD CONSTRAINT `worksheet_container_ibfk_1` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
