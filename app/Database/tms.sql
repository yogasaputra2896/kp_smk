-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 05:01 PM
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
(110, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-10 10:22:24', 1),
(111, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 00:37:02', 1),
(112, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 01:35:27', 1),
(113, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 03:47:00', 1),
(114, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 04:03:12', 1),
(115, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 04:40:28', 1),
(116, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 07:00:22', 1),
(117, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 08:42:25', 1),
(118, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-11 11:10:24', 1),
(119, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 01:44:01', 1),
(120, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 02:47:35', 1),
(121, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 04:07:20', 1),
(122, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 04:29:26', 1),
(123, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 07:42:48', 1),
(124, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 08:55:06', 1),
(125, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 09:48:49', 1),
(126, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-12 10:44:20', 1),
(127, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 02:42:01', 1),
(128, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 03:05:23', 1),
(129, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 05:02:55', 1),
(130, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 07:17:49', 1),
(131, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 09:25:06', 1),
(132, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-13 11:10:15', 1),
(133, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 01:42:20', 1),
(134, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 01:45:58', 1),
(135, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 01:53:16', 1),
(136, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 01:53:46', 1),
(137, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 01:59:06', 1),
(138, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:04:30', 1),
(139, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:07:43', 1),
(140, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:13:05', 1),
(141, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:20:30', 1),
(142, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:34:54', 1),
(143, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-14 02:35:10', 1),
(144, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-11-14 02:35:27', 1),
(145, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:35:53', 1),
(146, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:42:18', 1),
(147, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:44:11', 1),
(148, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:53:21', 1),
(149, '::1', 'asfefsef', NULL, '2025-11-14 02:54:38', 0),
(150, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-11-14 02:54:53', 1),
(151, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-11-14 02:55:08', 1),
(152, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 02:55:22', 1),
(153, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 04:16:22', 1),
(154, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 06:57:10', 1),
(155, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 07:29:09', 1),
(156, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-14 11:56:10', 1),
(157, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-11-14 12:00:16', 1),
(158, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-14 12:02:31', 1),
(159, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-14 12:05:16', 1),
(160, '::1', 'accounting1@trustwaytransindo.com', 3, '2025-11-14 12:05:55', 1),
(161, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 12:09:22', 1),
(162, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-14 12:09:49', 1),
(163, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-14 12:12:25', 1),
(164, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-18 03:06:08', 1),
(165, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-18 09:50:41', 1),
(166, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-18 10:02:42', 1),
(167, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-19 01:50:11', 1),
(168, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-19 09:50:21', 1),
(169, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-19 10:53:05', 1),
(170, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-19 11:18:26', 1),
(171, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-20 03:44:10', 1),
(172, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-20 05:07:29', 1),
(173, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-20 06:41:55', 1),
(174, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-20 08:06:58', 1),
(175, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-20 10:46:09', 1),
(176, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 01:37:42', 1),
(177, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 02:46:26', 1),
(178, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 04:08:39', 1),
(179, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 06:46:38', 1),
(180, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 07:05:29', 1),
(181, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 08:10:24', 1),
(182, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 11:19:16', 1),
(183, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-24 11:19:25', 1),
(184, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 03:34:37', 1),
(185, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 04:08:31', 1),
(186, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 04:49:34', 1),
(187, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 07:14:36', 1),
(188, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 07:21:59', 1),
(189, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 08:45:08', 1),
(190, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-26 10:55:15', 1),
(191, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 03:11:50', 1),
(192, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 04:56:13', 1),
(193, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 07:20:42', 1),
(194, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 08:09:02', 1),
(195, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 10:08:14', 1),
(196, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 11:36:38', 1),
(197, '::1', 'yogasaputra2896@gmail.com', 1, '2025-11-27 14:59:17', 1),
(198, '::1', 'Exim1', NULL, '2025-11-27 16:00:55', 0),
(199, '::1', 'exim3@trustwaytransindo.com', 2, '2025-11-27 16:00:58', 1);

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
(11, 'IM/25/600001', 'import_fcl_nonjaminan', 'PT TESTER DATA', '2 x 20', '2025-09-23', 'SHEKOU', 'C25-000014', 'SITC', 'SITC123456', 'MSITC123456', '2025-09-23 08:58:39', '2025-11-11 02:11:50', 'worksheet'),
(12, 'IM/25/400001', 'import_fcl_jaminan', 'PT TESTER DATA', '1 X 20', '2025-09-23', 'PORT KELANG', 'C25-001234', 'MCC LINE', 'MCC123456', 'MMCC123456', '2025-09-23 08:55:00', '2025-11-11 02:11:37', 'worksheet'),
(21, 'EX/25/300001', 'export', 'PT TESTER DATA', '1 X 20', '2025-09-01', 'TANJUNG PERAK', 'CE25-00001', 'ONE LINE', 'ONE123444', 'MONE123444', '2025-09-24 08:48:41', '2025-11-14 08:05:55', 'worksheet'),
(22, 'EX/25/300002', 'export', 'PT TESTER DATA', '2 X 20', '2025-09-30', 'TANJUNG PRIOK', 'CE25-00002', 'KMTC LINE', 'KMTC000001', 'MKMTC000001', '2025-09-30 02:20:11', '2025-11-14 08:06:00', 'worksheet'),
(23, 'EX/25/300003', 'export', 'PT TESTER DATA', '3 X 20', '2025-09-29', 'TANJUNG PERAK', 'CE25-00003', 'MCC LINE', 'MCC000001', 'MMCC000001', '2025-09-30 02:21:35', '2025-11-14 08:06:06', 'worksheet'),
(24, 'EX/25/300004', 'export', 'PT TESTER DATA', '4 X 20', '2025-09-28', 'TANJUNG PRIOK', 'CE25-00004', 'SITC LINE', 'SITC000001', 'MSITC000001', '2025-09-30 02:23:07', '2025-11-14 08:06:15', 'worksheet'),
(28, 'IM/25/100001', 'import_lcl', 'PT TESTER DATA', '1 PK', '2025-09-30', 'SHANGHAI', 'C25-000001', 'WAN HAI LINE', 'WAN123456', 'MWAN123456', '2025-09-30 08:32:17', '2025-10-04 10:20:52', 'worksheet'),
(29, 'IM/25/100002', 'import_lcl', 'PT TESTER DATA', '2 PK', '2025-09-29', 'PORT KELANG', 'C25-000002', 'KMTC LINE', 'KMTC000003', 'MKMTC000003', '2025-09-30 08:40:26', '2025-11-11 02:11:20', 'worksheet'),
(30, 'IM/25/100003', 'import_lcl', 'PT TESTER DATA', '3 PK', '2025-09-28', 'SHEKOU', 'C25-000003', 'ONE LINE', 'ONE000003', 'MONE000003', '2025-09-30 08:41:36', '2025-09-30 08:41:36', 'open job'),
(31, 'IM/25/100004', 'import_lcl', 'PT TESTER DATA', '4 PK', '2025-09-27', 'INCHEON', 'C25-000004', 'WAN HAI LINE', 'WAN000001', 'MWAN000001', '2025-09-30 08:42:36', '2025-09-30 08:42:36', 'open job'),
(33, 'IM/25/400002', 'import_fcl_jaminan', 'PT TESTER DATA', '1 X 20', '2025-09-10', 'PORT KELANG', 'C25-000010', 'ONE LINE', 'ONE000010', 'MONE000010', '2025-09-30 10:37:57', '2025-11-14 06:58:51', 'worksheet'),
(34, 'IM/25/400003', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-19', 'INCHEON', 'C25-000011', 'SITC LINE', 'SITC000010', 'MSITC000010', '2025-09-30 10:38:52', '2025-11-11 02:09:52', 'open job'),
(35, 'IM/25/400004', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-16', 'SHANGHAI', 'C25-000012', 'MCC LINE', 'MCC000010', 'MMCC000010', '2025-09-30 10:39:58', '2025-09-30 10:39:58', 'open job'),
(37, 'IM/25/600002', 'import_fcl_nonjaminan', 'PT TESTER DATA', '1 X 20', '2025-09-18', 'SHEKOU', 'C25-000015', 'ONE LINE', 'ONE000011', 'MONE000011', '2025-09-30 10:42:36', '2025-11-14 07:14:45', 'worksheet'),
(38, 'IM/25/600003', 'import_fcl_nonjaminan', 'PT TESTER DATA', '4 X 20', '2025-09-17', 'PORT KELANG', 'C25-000016', 'WAN HAI LINE', 'WAN000002', 'MWAN000002', '2025-09-30 10:46:08', '2025-10-01 10:55:47', 'open job'),
(39, 'IM/25/600004', 'import_fcl_nonjaminan', 'PT TESTER DATA', '2 x 20', '2025-09-25', 'SHEKOU', 'C25-000017', 'SITC LINE', 'SITC000011', 'MSITC000011', '2025-09-30 10:46:57', '2025-09-30 10:46:57', 'open job'),
(43, 'IM/25/400005', 'import_fcl_jaminan', 'PT TESTER DATA', '2 x 20', '2025-09-27', 'PORT KELANG', 'C25-000013', 'KMTC LINE', 'KMTC000010', 'MKMTC000010', '2025-10-01 10:41:54', '2025-10-01 10:55:26', 'open job'),
(44, 'IM/25/600005', 'import_fcl_nonjaminan', 'PT TESTER DATA', '4 X 20', '2025-09-15', 'SHANGHAI', 'C25-000017', 'ONE LINE', 'ONE000012', 'MONE000012', '2025-10-01 10:41:58', '2025-10-01 10:41:58', 'open job'),
(45, 'EX/25/300005', 'export', 'PT TESTER DATA', '5 x 20', '2025-09-28', 'TANJUNG PRIOK', 'CE25-00005', 'ONE LINE', 'ONE000002', 'MONE000002', '2025-10-01 10:48:17', '2025-11-14 08:06:21', 'worksheet'),
(46, 'IM/25/500001', 'lain', 'PT TESTER DATA', '2 BAG', '2025-10-04', 'INCHEON', 'BUAT ASURANSI', 'OOCL LINE', 'OOLU000001', '-', '2025-10-04 12:04:13', '2025-11-11 02:12:16', 'worksheet'),
(47, 'IM/25/500002', 'lain', 'PT TESTER DATA', '2 PK', '2025-10-04', 'SHANGHAI', 'TRUCKING ONLY', ' KMTC LINE', 'KMTCT000001', '-', '2025-10-04 12:06:47', '2025-11-14 07:15:02', 'worksheet'),
(48, 'IM/25/500003', 'lain', 'PT TESTER DATA', '2 PK', '2025-10-04', '-', 'BUAT LARTAS LS', '-', 'INV123456', '-', '2025-10-04 12:11:23', '2025-10-04 12:11:23', 'open job'),
(49, 'IM/25/500004', 'lain', 'PT TESTER DATA', '5 PK', '2025-10-04', 'BANGKOK', 'BUAT ASURANSI', 'KMTC LINE', 'INV654321', '-', '2025-10-04 12:12:34', '2025-10-04 12:12:34', 'open job'),
(50, 'IM/25/500005', 'lain', 'PT TESTER DATA', '1 X 20', '2025-10-04', 'KUALA LUMPUR', 'TRUCKING ONLY', 'ONE LINE', 'ONE0101010', '-', '2025-10-04 12:13:52', '2025-10-04 12:13:52', 'open job'),
(52, 'IM/25/100005', 'import_lcl', 'PT TESTER DATA', '5 PK', '2025-11-14', 'PORT KELANG', 'C25-001528', 'WAN HAI LINE', 'WAN000028', 'WAN987654321', '2025-11-14 07:09:22', '2025-11-14 07:09:22', 'open job');

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
-- Table structure for table `master_consignee`
--

CREATE TABLE `master_consignee` (
  `id` int(10) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama_consignee` varchar(50) NOT NULL,
  `npwp_consignee` varchar(16) DEFAULT NULL,
  `alamat_consignee` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_consignee`
--

INSERT INTO `master_consignee` (`id`, `kode`, `nama_consignee`, `npwp_consignee`, `alamat_consignee`, `created_at`, `updated_at`) VALUES
(1, 'PDTR', 'PT DATA TESTER', '1234567890123456', 'Jalan Test Dulu Bang', '2025-11-24 04:47:01', '2025-11-26 21:16:39'),
(2, 'PYMS', 'PT YOGA MAKMUR SEJAHTERA', '1111222233334444', 'Jalan Semoga Beneran Punya', '2025-11-26 02:39:35', '2025-11-26 21:16:58'),
(3, 'PBAS', 'PT BUDI ADUNG SENTOSA', '6543211234566541', 'Jalan Budi Agung Nih Bos', '2025-11-26 04:06:33', '2025-11-26 21:17:13');

-- --------------------------------------------------------

--
-- Table structure for table `master_fasilitas`
--

CREATE TABLE `master_fasilitas` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `tipe_fasilitas` varchar(100) NOT NULL,
  `nama_fasilitas` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_informasi_tambahan`
--

CREATE TABLE `master_informasi_tambahan` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama_pengurusan` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_kemasan`
--

CREATE TABLE `master_kemasan` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `jenis_kemasan` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_lartas`
--

CREATE TABLE `master_lartas` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama_lartas` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_lokasi_sandar`
--

CREATE TABLE `master_lokasi_sandar` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama_sandar` varchar(200) NOT NULL,
  `alamat_sandar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_notify_party`
--

CREATE TABLE `master_notify_party` (
  `id` int(10) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama_notify` varchar(50) NOT NULL,
  `npwp_notify` varchar(16) DEFAULT NULL,
  `alamat_notify` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_notify_party`
--

INSERT INTO `master_notify_party` (`id`, `kode`, `nama_notify`, `npwp_notify`, `alamat_notify`, `created_at`, `updated_at`) VALUES
(1, 'PDTF', 'PT DATA TESTER FORWARDER', '1212131314141515', 'Komplek Rukan Mitra Bahari Jl.Pakin Raya no.1 Blok C3A-6, RT.2/RW.4, Penjaringan, Kecamatan Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14440', '2025-11-27 08:43:28', '2025-11-27 08:43:28'),
(2, 'PYMF', 'PT YOGA MAKMUR FORWARDING', '2121313141415151', 'Jl. Enggano, Tj. Priok, Kec. Tj. Priok, Jkt Utara, Daerah Khusus Ibukota Jakarta 14310', '2025-11-27 08:50:02', '2025-11-27 08:50:02'),
(3, 'PBDF', 'PT BUDI ADUNG FORWARDING', '9090808070706060', 'Komplek, SEDAYU SQUARE, Jl. Outer Ringroad Lkr. Luar No.L-16 1, RT.1/RW.12, Cengkareng Bar., Kecamatan Cengkareng, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11730', '2025-11-27 08:52:27', '2025-11-27 08:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `master_pelayaran`
--

CREATE TABLE `master_pelayaran` (
  `id` int(10) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama_pelayaran` varchar(50) NOT NULL,
  `npwp_pelayaran` varchar(16) DEFAULT NULL,
  `alamat_pelayaran` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_pelayaran`
--

INSERT INTO `master_pelayaran` (`id`, `kode`, `nama_pelayaran`, `npwp_pelayaran`, `alamat_pelayaran`, `created_at`, `updated_at`) VALUES
(1, 'ONEI', 'PT OCEAN NETWORK EXPRESS INDONESIA', '9999888833332222', 'AIA Central Building, Jl. Jend. Sudirman No.Kav. 48A, RT.5/RW.4, Karet Semanggi, Setiabudi, South Jakarta City, Jakarta 12930', '2025-11-27 01:23:54', '2025-11-27 01:55:51'),
(2, 'KMTC', 'PT SAMUDERA AGENCIES INDONESIA', '7777222211113333', 'Lippo Kuningan, 21st Floor, Jl. HR Rasuna Said No.Kav. B-12, RT.6/RW.7, Kuningan, Karet Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12940', '2025-11-27 01:50:06', '2025-11-27 01:50:06'),
(3, 'MSCL', 'PT. PERUSAHAAN PELAYARAN NUSANTARA PANURJWAN', '2222444477775555', 'Capital Place Building, 39th Floor Jl. Jend. Gatot Subroto Kav. 18 ID - 12710 JAKARTA, JAVA', '2025-11-27 01:55:16', '2025-11-27 01:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `master_port`
--

CREATE TABLE `master_port` (
  `id` int(10) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama_port` varchar(50) NOT NULL,
  `negara_port` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_port`
--

INSERT INTO `master_port` (`id`, `kode`, `nama_port`, `negara_port`, `created_at`, `updated_at`) VALUES
(1, 'IDJKT', 'TANJUNG PRIOK', 'INDONESIA', '2025-11-27 05:00:22', '2025-11-27 05:00:22'),
(2, 'IDCGK', 'CENGKARENG / SOEKARNO', 'INDONESIA', '2025-11-27 05:07:01', '2025-11-27 05:07:01'),
(3, 'IDSUB', 'TANJUNG PERAK', 'INDONESIA', '2025-11-27 05:10:43', '2025-11-27 05:10:43'),
(4, 'CNXGG', 'XINGANG', 'CHINA', '2025-11-27 05:18:45', '2025-11-27 05:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `master_vessel`
--

CREATE TABLE `master_vessel` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama_vessel` varchar(200) NOT NULL,
  `negara_vessel` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `worksheet_container_export`
--

CREATE TABLE `worksheet_container_export` (
  `id` int(10) NOT NULL,
  `no_container` varchar(20) NOT NULL,
  `ukuran` varchar(5) DEFAULT NULL,
  `tipe` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_container_export`
--

INSERT INTO `worksheet_container_export` (`id`, `no_container`, `ukuran`, `tipe`, `created_at`, `id_ws`) VALUES
(49, 'ASSU123456', '20', 'DRY', '2025-11-20 03:57:05', 12),
(50, 'SUSU123456', '20', 'DRY', '2025-11-20 03:57:05', 12);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_container_export_trash`
--

CREATE TABLE `worksheet_container_export_trash` (
  `id` int(10) NOT NULL,
  `no_container` varchar(20) NOT NULL,
  `ukuran` varchar(5) DEFAULT NULL,
  `tipe` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_container_import`
--

CREATE TABLE `worksheet_container_import` (
  `id` int(10) NOT NULL,
  `no_container` varchar(20) NOT NULL,
  `ukuran` varchar(5) DEFAULT NULL,
  `tipe` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_container_import`
--

INSERT INTO `worksheet_container_import` (`id`, `no_container`, `ukuran`, `tipe`, `created_at`, `id_ws`) VALUES
(77, 'CSNU1181201', '20', 'DRY', '2025-11-13 00:19:43', 19),
(78, 'ASNU7654321', '40', 'REEFER', '2025-11-13 00:19:43', 19),
(82, 'OOLU123456', '20', 'DRY', '2025-11-13 00:20:19', 20),
(83, 'ULLU123456', '20', 'DRY', '2025-11-13 00:20:19', 20),
(84, 'OLLU123456', '20', 'DRY', '2025-11-13 00:20:20', 20),
(88, 'TENU2132654', '40', 'DRY', '2025-11-14 04:42:02', 28);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_container_import_trash`
--

CREATE TABLE `worksheet_container_import_trash` (
  `id` int(10) NOT NULL,
  `no_container` varchar(20) NOT NULL,
  `ukuran` varchar(5) DEFAULT NULL,
  `tipe` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_do_export`
--

CREATE TABLE `worksheet_do_export` (
  `id` int(11) NOT NULL,
  `tipe_do` varchar(50) NOT NULL,
  `pengambil_do` varchar(100) NOT NULL,
  `tgl_mati_do` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_do_export`
--

INSERT INTO `worksheet_do_export` (`id`, `tipe_do`, `pengambil_do`, `tgl_mati_do`, `created_at`, `id_ws`) VALUES
(20, 'Delivery Order', 'Aan', '2025-11-14', '2025-11-20 10:57:05', 12);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_do_export_trash`
--

CREATE TABLE `worksheet_do_export_trash` (
  `id` int(11) NOT NULL,
  `tipe_do` varchar(50) NOT NULL,
  `pengambil_do` varchar(100) NOT NULL,
  `tgl_mati_do` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_do_import`
--

CREATE TABLE `worksheet_do_import` (
  `id` int(11) NOT NULL,
  `tipe_do` varchar(50) NOT NULL,
  `pengambil_do` varchar(100) NOT NULL,
  `tgl_mati_do` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_do_import`
--

INSERT INTO `worksheet_do_import` (`id`, `tipe_do`, `pengambil_do`, `tgl_mati_do`, `created_at`, `id_ws`) VALUES
(105, 'Delivery Order', 'Aan', '2025-11-12', '2025-11-13 07:19:43', 19),
(138, 'Delivery Order', 'Aan', '2025-11-14', '2025-11-20 10:56:36', 27);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_do_import_trash`
--

CREATE TABLE `worksheet_do_import_trash` (
  `id` int(11) NOT NULL,
  `tipe_do` varchar(50) NOT NULL,
  `pengambil_do` varchar(100) NOT NULL,
  `tgl_mati_do` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_export`
--

CREATE TABLE `worksheet_export` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `pengurusan_peb` varchar(50) DEFAULT NULL,
  `peb_nopen` varchar(6) DEFAULT NULL,
  `tgl_aju` date DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `io_number` varchar(50) DEFAULT NULL,
  `penjaluran` varchar(50) DEFAULT NULL,
  `tgl_npe` date DEFAULT NULL,
  `tgl_spjm` date DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `notify_party` varchar(255) DEFAULT NULL,
  `vessel` varchar(50) DEFAULT NULL,
  `no_voyage` varchar(25) DEFAULT NULL,
  `pol` varchar(25) DEFAULT NULL,
  `pod` varchar(25) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `commodity` varchar(255) DEFAULT NULL,
  `party` varchar(10) DEFAULT NULL,
  `jenis_con` varchar(10) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `net` decimal(12,2) DEFAULT NULL,
  `gross` decimal(12,2) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` date DEFAULT NULL,
  `etd` date DEFAULT NULL,
  `closing` date DEFAULT NULL,
  `stuffing` date DEFAULT NULL,
  `depo` varchar(255) DEFAULT NULL,
  `terminal` varchar(255) DEFAULT NULL,
  `dok_ori` varchar(50) DEFAULT NULL,
  `tgl_ori` date NOT NULL,
  `pengurusan_do` varchar(50) DEFAULT NULL,
  `asuransi` varchar(50) DEFAULT NULL,
  `jenis_trucking` varchar(50) DEFAULT NULL,
  `jenis_fasilitas` varchar(50) DEFAULT NULL,
  `jenis_tambahan` varchar(50) DEFAULT NULL,
  `pengurusan_lartas` varchar(50) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(25) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_export`
--

INSERT INTO `worksheet_export` (`id`, `no_ws`, `no_aju`, `pengurusan_peb`, `peb_nopen`, `tgl_aju`, `tgl_nopen`, `no_po`, `io_number`, `penjaluran`, `tgl_npe`, `tgl_spjm`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pol`, `pod`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `kemasan`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `etd`, `closing`, `stuffing`, `depo`, `terminal`, `dok_ori`, `tgl_ori`, `pengurusan_do`, `asuransi`, `jenis_trucking`, `jenis_fasilitas`, `jenis_tambahan`, `pengurusan_lartas`, `top`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(12, 'EX/25/300001', '00003003048420251105000001', 'Pembuatan Draft PEB', '111111', '2025-11-14', '2025-11-14', '', '', 'NPE', '2025-11-14', '0000-00-00', 'PT TESTER DATA', 'SHIPPER TEST DATA CO LTD', '', 'WAN WAN', '120E', 'TANJUNG PRIOK', 'PORT KELANG', 'ONE LINE', 'SAMPLE BARANG', '1 X 20', 'FCL', '2', 'Package', 100.00, 100.00, 'ONE123444', '2025-11-10', 'MONE123444', '2025-11-13', 'INVE00001', '2025-11-14', '2025-11-14', '2025-11-21', '2025-11-14', 'PT DEPO DEPOAN', 'JICT', 'Sudah Ada', '2025-11-14', 'Pengambilan Delivery Order', 'Asuransi Sendiri', 'Pengurusan Trucking', 'Pengurusan Fasilitas', 'Pengurusan Tambahan', 'Pembuatan Lartas', 'PREPAID', '', '2025-11-14 08:05:55', '2025-11-20 10:57:05', NULL, NULL, 'completed'),
(13, 'EX/25/300002', 'CE25-00002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, NULL, 'TANJUNG PRIOK', 'KMTC LINE', NULL, '2 X 20', 'FCL', NULL, NULL, NULL, NULL, 'KMTC000001', NULL, 'MKMTC000001', NULL, NULL, NULL, '2025-09-30', NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 08:06:00', '2025-11-14 08:06:00', NULL, NULL, 'not completed'),
(14, 'EX/25/300003', 'CE25-00003', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, NULL, 'TANJUNG PERAK', 'MCC LINE', NULL, '3 X 20', 'FCL', NULL, NULL, NULL, NULL, 'MCC000001', NULL, 'MMCC000001', NULL, NULL, NULL, '2025-09-29', NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 08:06:06', '2025-11-14 08:06:06', NULL, NULL, 'not completed'),
(15, 'EX/25/300004', 'CE25-00004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, NULL, 'TANJUNG PRIOK', 'SITC LINE', NULL, '4 X 20', 'FCL', NULL, NULL, NULL, NULL, 'SITC000001', NULL, 'MSITC000001', NULL, NULL, NULL, '2025-09-28', NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 08:06:15', '2025-11-14 08:06:15', NULL, NULL, 'not completed');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_export_trash`
--

CREATE TABLE `worksheet_export_trash` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `pengurusan_peb` varchar(50) DEFAULT NULL,
  `peb_nopen` varchar(6) DEFAULT NULL,
  `tgl_aju` date DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `io_number` varchar(50) DEFAULT NULL,
  `penjaluran` varchar(50) DEFAULT NULL,
  `tgl_npe` date DEFAULT NULL,
  `tgl_spjm` date DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `notify_party` varchar(255) DEFAULT NULL,
  `vessel` varchar(50) DEFAULT NULL,
  `no_voyage` varchar(25) DEFAULT NULL,
  `pol` varchar(25) DEFAULT NULL,
  `pod` varchar(25) DEFAULT NULL,
  `shipping_line` varchar(100) DEFAULT NULL,
  `commodity` varchar(255) DEFAULT NULL,
  `party` varchar(10) DEFAULT NULL,
  `jenis_con` varchar(10) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL,
  `kemasan` varchar(255) DEFAULT NULL,
  `net` decimal(12,2) DEFAULT NULL,
  `gross` decimal(12,2) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` date DEFAULT NULL,
  `etd` date DEFAULT NULL,
  `closing` date DEFAULT NULL,
  `stuffing` date DEFAULT NULL,
  `depo` varchar(255) DEFAULT NULL,
  `terminal` varchar(255) DEFAULT NULL,
  `dok_ori` varchar(50) DEFAULT NULL,
  `tgl_ori` date NOT NULL,
  `pengurusan_do` varchar(50) DEFAULT NULL,
  `asuransi` varchar(50) DEFAULT NULL,
  `jenis_trucking` varchar(50) DEFAULT NULL,
  `jenis_fasilitas` varchar(50) DEFAULT NULL,
  `jenis_tambahan` varchar(50) DEFAULT NULL,
  `pengurusan_lartas` varchar(50) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(25) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_export_trash`
--

INSERT INTO `worksheet_export_trash` (`id`, `no_ws`, `no_aju`, `pengurusan_peb`, `peb_nopen`, `tgl_aju`, `tgl_nopen`, `no_po`, `io_number`, `penjaluran`, `tgl_npe`, `tgl_spjm`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pol`, `pod`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `kemasan`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `etd`, `closing`, `stuffing`, `depo`, `terminal`, `dok_ori`, `tgl_ori`, `pengurusan_do`, `asuransi`, `jenis_trucking`, `jenis_fasilitas`, `jenis_tambahan`, `pengurusan_lartas`, `top`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(2, 'EX/25/300005', 'CE25-00005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, NULL, NULL, 'TANJUNG PRIOK', 'ONE LINE', NULL, '5 x 20', 'FCL', NULL, NULL, NULL, NULL, 'ONE000002', NULL, 'MONE000002', NULL, NULL, NULL, '2025-09-28', NULL, NULL, NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 12:10:16', '2025-11-20 12:10:16', 'Admin', '2025-11-20 12:10:35', 'not completed');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_fasilitas_export`
--

CREATE TABLE `worksheet_fasilitas_export` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `tipe_fasilitas` varchar(50) DEFAULT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `tgl_fasilitas` date DEFAULT NULL,
  `no_fasilitas` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_fasilitas_export`
--

INSERT INTO `worksheet_fasilitas_export` (`id`, `id_ws`, `tipe_fasilitas`, `nama_fasilitas`, `tgl_fasilitas`, `no_fasilitas`, `created_at`) VALUES
(24, 12, 'ECOO', 'FORM ATIGA', '2025-11-14', 'ATIGA123456', '2025-11-20 10:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_fasilitas_export_trash`
--

CREATE TABLE `worksheet_fasilitas_export_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `tipe_fasilitas` varchar(50) DEFAULT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `tgl_fasilitas` date DEFAULT NULL,
  `no_fasilitas` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_fasilitas_import`
--

CREATE TABLE `worksheet_fasilitas_import` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `tipe_fasilitas` varchar(50) DEFAULT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `tgl_fasilitas` date DEFAULT NULL,
  `no_fasilitas` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_fasilitas_import`
--

INSERT INTO `worksheet_fasilitas_import` (`id`, `id_ws`, `tipe_fasilitas`, `nama_fasilitas`, `tgl_fasilitas`, `no_fasilitas`, `created_at`) VALUES
(41, 27, 'ECOO', 'FORM E', '2025-11-13', 'E123456789', '2025-11-20 10:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_fasilitas_import_trash`
--

CREATE TABLE `worksheet_fasilitas_import_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `tipe_fasilitas` varchar(50) DEFAULT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `tgl_fasilitas` date DEFAULT NULL,
  `no_fasilitas` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_import`
--

CREATE TABLE `worksheet_import` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `pengurusan_pib` varchar(25) NOT NULL,
  `tgl_aju` date DEFAULT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `io_number` varchar(10) DEFAULT NULL,
  `pib_nopen` varchar(6) DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `tgl_sppb` date DEFAULT NULL,
  `penjaluran` varchar(50) DEFAULT NULL,
  `tgl_spjm` date DEFAULT NULL,
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
  `net` decimal(12,2) DEFAULT NULL,
  `gross` decimal(12,2) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` date DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `dok_ori` varchar(50) DEFAULT NULL,
  `tgl_ori` date DEFAULT NULL,
  `pengurusan_do` varchar(50) DEFAULT NULL,
  `asuransi` varchar(50) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `jenis_trucking` varchar(50) DEFAULT NULL,
  `jenis_tambahan` varchar(50) DEFAULT NULL,
  `pengurusan_lartas` varchar(50) DEFAULT NULL,
  `jenis_fasilitas` varchar(50) DEFAULT NULL,
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

INSERT INTO `worksheet_import` (`id`, `no_ws`, `no_aju`, `pengurusan_pib`, `tgl_aju`, `no_po`, `io_number`, `pib_nopen`, `tgl_nopen`, `tgl_sppb`, `penjaluran`, `tgl_spjm`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pol`, `terminal`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `kemasan`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `eta`, `dok_ori`, `tgl_ori`, `pengurusan_do`, `asuransi`, `top`, `jenis_trucking`, `jenis_tambahan`, `pengurusan_lartas`, `jenis_fasilitas`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(19, 'IM/25/100002', '00000000686820251018000002', 'Pembuatan Draft PIB', '2025-11-11', '-', '-', '123457', '2025-11-11', '2025-11-11', 'SPJM', '2025-11-08', 'SHIPPER TEST DATA CO LTD', 'PT TESTER DATA', '-', 'WAN WAN', '120E', 'PORT KELANG', 'JICT', 'KMTC LINE', 'ALUMINIUM ALLOY', '2 PK', 'FCL', '2', 'Package', 100.00, 100.00, 'KMTC000003', '2025-11-11', 'MKMTC000003', '2025-11-11', 'INV000003', '2025-11-11', '2025-09-29', 'Belum Ada', NULL, 'Pengambilan Delivery Order', 'CIF', 'PREPAID', 'Pengurusan Trucking', 'Tidak Ada Tambahan', 'Pembuatan Lartas', 'Tidak Ada Fasilitas', '', '2025-11-11 02:11:19', '2025-11-13 07:19:43', NULL, '0000-00-00 00:00:00', 'completed'),
(20, 'IM/25/400001', '00000000686820251018000003', 'Pembuatan Draft PIB', '2025-11-13', '-', '-', '123458', '2025-11-13', '2025-11-13', 'SPPB', NULL, 'SHIPPER TEST DATA CO LTD', 'PT TESTER DATA', '-', 'WAN WAN', '150E', 'PORT KELANG', 'KOJA', 'MCC LINE', 'TEST NAMA BARANG', '3 X 20', 'FCL', '3000', 'Carton', 10000.00, 15000.00, 'MCC123456', '2025-11-13', 'MMCC123456', '2025-11-13', 'INV0000456', '2025-11-13', '2025-09-23', 'Belum Ada', NULL, 'Delivery Order Sendiri', 'CIF', 'PREPAID', 'Pengurusan Trucking', 'Tidak Ada Tambahan', 'Pembuatan Lartas', 'Tidak Ada Fasilitas', '', '2025-11-11 02:11:37', '2025-11-13 07:20:19', NULL, '0000-00-00 00:00:00', 'completed'),
(21, 'IM/25/600001', 'C25-000014', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'SHEKOU', NULL, 'SITC', NULL, '2 x 20', 'FCL', NULL, NULL, NULL, NULL, 'SITC123456', NULL, 'MSITC123456', NULL, NULL, NULL, '2025-09-23', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2025-11-11 02:11:50', '2025-11-11 09:47:20', NULL, '0000-00-00 00:00:00', 'not completed'),
(22, 'IM/25/500001', 'BUAT ASURANSI ONLY', 'Draft PIB Sendiri', '2025-11-13', '', '', '', '2025-11-13', '2025-11-13', 'SPPB', NULL, 'SHIPPER TEST DATA CO LTD', 'PT TESTER DATA', '', 'WAN WAN', '150E', 'INCHEON', 'JICT', 'OOCL LINE', 'TEST NAMA BARANG', '2 BG', 'LCL', '2', 'Bag', 10.00, 10.00, 'OOLU000001', '2025-11-13', '', '0000-00-00', 'INV000001', '2025-11-13', '2025-10-04', 'Belum Ada', NULL, 'Delivery Order Sendiri', 'Pembuatan Asuransi', 'PREPAID', 'Trucking Sendiri', 'Tidak Ada Tambahan', 'Lartas Sendiri', 'Tidak Ada Fasilitas', 'BUAT ASURANSI ONLY', '2025-11-11 02:12:16', '2025-11-24 01:41:11', NULL, '0000-00-00 00:00:00', 'completed'),
(27, 'IM/25/100001', '00000000686820251018000001', 'Pembuatan Draft PIB', '2025-10-18', 'POIF-25-0001', '-', '123456', '2025-10-18', '2025-11-11', 'SPPB', NULL, 'SHELL OIL CO LTD', 'PT TESTER DATA', 'PT TESTER DATA GRUB', 'ONE MADRID', '0001W', 'KUALA LUMPUR', 'NPCT', 'WAN HAI LINE', 'BAHAN BAKAR SHELL', '1 PK', 'LCL', '1', 'Package', 1000.00, 1000.00, 'WAN123456', '2025-11-11', 'MWAN123456', '2025-10-18', 'INV123456', '2025-10-18', '2025-09-30', 'Sudah Ada', '2025-11-13', 'Pengambilan Delivery Order', 'Pembuatan Asuransi', 'PREPAID', 'Pengurusan Trucking', 'Pengurusan Tambahan', 'Pembuatan Lartas', 'Fasilitas Sendiri', '', '2025-11-20 07:15:29', '2025-11-20 10:56:35', NULL, '0000-00-00 00:00:00', 'completed'),
(28, 'IM/25/400002', '00000000686820251018000004', 'Pembuatan Draft PIB', '2025-11-14', '', '', '216598', '2025-11-14', '2025-11-14', 'SPJM', '2025-11-14', 'SHIPPER TEST DATA CO LTD', 'PT TESTER DATA', '', 'MSC YOGA', '315E', 'PORT KELANG', 'NPCT', 'ONE LINE', 'TEST NAMA BARANG', '1 X 20', 'FCL', '50', 'Pallet', 4000.00, 5000.00, 'ONE000010', '2025-11-14', 'MONE000010', '2025-11-14', 'INV653297898', '2025-11-14', '2025-09-10', 'Belum Ada', NULL, 'Delivery Order Sendiri', 'CIF', 'PREPAID', 'Trucking Sendiri', 'Tidak Ada Tambahan', 'Lartas Sendiri', 'Tidak Ada Fasilitas', '', '2025-11-20 07:15:37', '2025-11-20 07:15:37', NULL, '0000-00-00 00:00:00', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_import_trash`
--

CREATE TABLE `worksheet_import_trash` (
  `id` int(10) NOT NULL,
  `no_ws` varchar(50) NOT NULL,
  `no_aju` varchar(26) NOT NULL,
  `pengurusan_pib` varchar(25) NOT NULL,
  `tgl_aju` date DEFAULT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `io_number` varchar(10) DEFAULT NULL,
  `pib_nopen` varchar(6) DEFAULT NULL,
  `tgl_nopen` date DEFAULT NULL,
  `tgl_sppb` date DEFAULT NULL,
  `penjaluran` varchar(50) DEFAULT NULL,
  `tgl_spjm` date DEFAULT NULL,
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
  `net` decimal(12,2) DEFAULT NULL,
  `gross` decimal(12,2) DEFAULT NULL,
  `bl` varchar(50) NOT NULL,
  `tgl_bl` date DEFAULT NULL,
  `master_bl` varchar(50) DEFAULT NULL,
  `tgl_master` date DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `tgl_invoice` date DEFAULT NULL,
  `eta` date DEFAULT NULL,
  `dok_ori` varchar(50) DEFAULT NULL,
  `tgl_ori` date DEFAULT NULL,
  `pengurusan_do` varchar(50) DEFAULT NULL,
  `asuransi` varchar(50) DEFAULT NULL,
  `top` varchar(10) DEFAULT NULL,
  `jenis_trucking` varchar(50) DEFAULT NULL,
  `jenis_tambahan` varchar(50) DEFAULT NULL,
  `pengurusan_lartas` varchar(50) DEFAULT NULL,
  `jenis_fasilitas` varchar(50) DEFAULT NULL,
  `berita_acara` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_by` varchar(25) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_import_trash`
--

INSERT INTO `worksheet_import_trash` (`id`, `no_ws`, `no_aju`, `pengurusan_pib`, `tgl_aju`, `no_po`, `io_number`, `pib_nopen`, `tgl_nopen`, `tgl_sppb`, `penjaluran`, `tgl_spjm`, `shipper`, `consignee`, `notify_party`, `vessel`, `no_voyage`, `pol`, `terminal`, `shipping_line`, `commodity`, `party`, `jenis_con`, `qty`, `kemasan`, `net`, `gross`, `bl`, `tgl_bl`, `master_bl`, `tgl_master`, `no_invoice`, `tgl_invoice`, `eta`, `dok_ori`, `tgl_ori`, `pengurusan_do`, `asuransi`, `top`, `jenis_trucking`, `jenis_tambahan`, `pengurusan_lartas`, `jenis_fasilitas`, `berita_acara`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`, `status`) VALUES
(10, 'IM/25/500002', 'TRUCKING ONLY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PT TESTER DATA', NULL, NULL, NULL, 'SHANGHAI', NULL, ' KMTC LINE', NULL, '2 PK', 'FCL', NULL, NULL, NULL, NULL, 'KMTCT000001', NULL, '-', NULL, NULL, NULL, '2025-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 07:15:02', '2025-11-14 07:15:02', 'Admin', '2025-11-20 12:03:24', 'not completed');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_informasi_tambahan_export`
--

CREATE TABLE `worksheet_informasi_tambahan_export` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_pengurusan` varchar(255) DEFAULT NULL,
  `tgl_pengurusan` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_informasi_tambahan_export`
--

INSERT INTO `worksheet_informasi_tambahan_export` (`id`, `id_ws`, `nama_pengurusan`, `tgl_pengurusan`, `created_at`) VALUES
(11, 12, 'Redress Manifest', '2025-11-14', '2025-11-20 10:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_informasi_tambahan_export_trash`
--

CREATE TABLE `worksheet_informasi_tambahan_export_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_pengurusan` varchar(255) DEFAULT NULL,
  `tgl_pengurusan` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_informasi_tambahan_import`
--

CREATE TABLE `worksheet_informasi_tambahan_import` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_pengurusan` varchar(255) DEFAULT NULL,
  `tgl_pengurusan` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_informasi_tambahan_import`
--

INSERT INTO `worksheet_informasi_tambahan_import` (`id`, `id_ws`, `nama_pengurusan`, `tgl_pengurusan`, `created_at`) VALUES
(57, 27, 'Redress Manifest', '2025-11-12', '2025-11-20 10:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_informasi_tambahan_import_trash`
--

CREATE TABLE `worksheet_informasi_tambahan_import_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_pengurusan` varchar(255) DEFAULT NULL,
  `tgl_pengurusan` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_lartas_export`
--

CREATE TABLE `worksheet_lartas_export` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_lartas` varchar(100) NOT NULL,
  `no_lartas` varchar(100) NOT NULL,
  `tgl_lartas` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_lartas_export`
--

INSERT INTO `worksheet_lartas_export` (`id`, `id_ws`, `nama_lartas`, `no_lartas`, `tgl_lartas`, `created_at`) VALUES
(11, 12, 'Laporan Surveyor', 'LS165468468468', '2025-11-14', '2025-11-20 10:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_lartas_export_trash`
--

CREATE TABLE `worksheet_lartas_export_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_lartas` varchar(100) NOT NULL,
  `no_lartas` varchar(100) NOT NULL,
  `tgl_lartas` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_lartas_import`
--

CREATE TABLE `worksheet_lartas_import` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_lartas` varchar(100) NOT NULL,
  `no_lartas` varchar(100) NOT NULL,
  `tgl_lartas` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_lartas_import`
--

INSERT INTO `worksheet_lartas_import` (`id`, `id_ws`, `nama_lartas`, `no_lartas`, `tgl_lartas`, `created_at`) VALUES
(122, 19, 'Laporan Surveyor', 'LS123456789', '2025-11-12', '2025-11-13 07:19:43'),
(124, 20, 'Laporan Surveyor', 'LS987654321', '2025-11-13', '2025-11-13 07:20:20'),
(197, 27, 'Laporan Surveyor', 'LS123456789', '2025-11-12', '2025-11-20 10:56:36'),
(198, 27, 'SURAT PERSETUJUAN MUAT BPOM', 'BPOM123456789', '2025-11-12', '2025-11-20 10:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_lartas_import_trash`
--

CREATE TABLE `worksheet_lartas_import_trash` (
  `id` int(11) NOT NULL,
  `id_ws` int(11) NOT NULL,
  `nama_lartas` varchar(100) NOT NULL,
  `no_lartas` varchar(100) NOT NULL,
  `tgl_lartas` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_trucking_export`
--

CREATE TABLE `worksheet_trucking_export` (
  `id` int(11) NOT NULL,
  `no_mobil` varchar(20) NOT NULL,
  `tipe_mobil` varchar(20) DEFAULT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp_supir` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_trucking_export`
--

INSERT INTO `worksheet_trucking_export` (`id`, `no_mobil`, `tipe_mobil`, `nama_supir`, `alamat`, `telp_supir`, `created_at`, `id_ws`) VALUES
(47, 'B 6844 DAS', 'TRAILER', 'Samuel', 'Gudang Cikarang', '088291954971', '2025-11-20 10:57:05', 12),
(48, 'B 8585 ASU', 'TRAILER', 'Satya', 'Gudang Cikarang', '088291954971', '2025-11-20 10:57:05', 12);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_trucking_export_trash`
--

CREATE TABLE `worksheet_trucking_export_trash` (
  `id` int(11) NOT NULL,
  `no_mobil` varchar(20) NOT NULL,
  `tipe_mobil` varchar(20) DEFAULT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp_supir` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_trucking_import`
--

CREATE TABLE `worksheet_trucking_import` (
  `id` int(11) NOT NULL,
  `no_mobil` varchar(20) NOT NULL,
  `tipe_mobil` varchar(20) DEFAULT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp_supir` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worksheet_trucking_import`
--

INSERT INTO `worksheet_trucking_import` (`id`, `no_mobil`, `tipe_mobil`, `nama_supir`, `alamat`, `telp_supir`, `created_at`, `id_ws`) VALUES
(147, 'B 12345 UUF', 'TRAILER', 'Budi', 'Gudang Marunda', '088291954971', '2025-11-13 07:19:43', 19),
(151, 'B 12345 UUF', 'TRAILER', 'Budi', 'Gudang Daanmogot', '088291954971', '2025-11-13 07:20:20', 20),
(152, 'B 54321 UUI', 'TRAILER', 'Bagas', 'Gudang Daanmogot', '0812345678', '2025-11-13 07:20:20', 20),
(153, 'B 11111 UUU', 'TRAILER', 'Jono', 'Gudang Daanmogot', '0812345678', '2025-11-13 07:20:20', 20),
(191, 'B 12345 UUF', 'WING BOX', 'Bagas', 'Gudang Tigaraksa', '088291954971', '2025-11-20 10:56:35', 27);

-- --------------------------------------------------------

--
-- Table structure for table `worksheet_trucking_import_trash`
--

CREATE TABLE `worksheet_trucking_import_trash` (
  `id` int(11) NOT NULL,
  `no_mobil` varchar(20) NOT NULL,
  `tipe_mobil` varchar(20) DEFAULT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp_supir` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_ws` int(11) NOT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `master_consignee`
--
ALTER TABLE `master_consignee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_fasilitas`
--
ALTER TABLE `master_fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_informasi_tambahan`
--
ALTER TABLE `master_informasi_tambahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_kemasan`
--
ALTER TABLE `master_kemasan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_lartas`
--
ALTER TABLE `master_lartas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_lokasi_sandar`
--
ALTER TABLE `master_lokasi_sandar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_notify_party`
--
ALTER TABLE `master_notify_party`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_pelayaran`
--
ALTER TABLE `master_pelayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_port`
--
ALTER TABLE `master_port`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_vessel`
--
ALTER TABLE `master_vessel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

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
-- Indexes for table `worksheet_container_export`
--
ALTER TABLE `worksheet_container_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_container_export_trash`
--
ALTER TABLE `worksheet_container_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_container_import`
--
ALTER TABLE `worksheet_container_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_container_ws_id` (`id_ws`);

--
-- Indexes for table `worksheet_container_import_trash`
--
ALTER TABLE `worksheet_container_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_container_ws_id` (`id_ws`);

--
-- Indexes for table `worksheet_do_export`
--
ALTER TABLE `worksheet_do_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_do_export_trash`
--
ALTER TABLE `worksheet_do_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_do_import`
--
ALTER TABLE `worksheet_do_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_do_ws_id` (`id_ws`);

--
-- Indexes for table `worksheet_do_import_trash`
--
ALTER TABLE `worksheet_do_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_do_ws_id` (`id_ws`);

--
-- Indexes for table `worksheet_export`
--
ALTER TABLE `worksheet_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_no_ws` (`no_ws`),
  ADD KEY `idx_no_aju` (`no_aju`),
  ADD KEY `idx_bl` (`bl`);

--
-- Indexes for table `worksheet_export_trash`
--
ALTER TABLE `worksheet_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_no_ws` (`no_ws`),
  ADD KEY `idx_no_aju` (`no_aju`),
  ADD KEY `idx_bl` (`bl`);

--
-- Indexes for table `worksheet_fasilitas_export`
--
ALTER TABLE `worksheet_fasilitas_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_fasilitas_export_trash`
--
ALTER TABLE `worksheet_fasilitas_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_fasilitas_import`
--
ALTER TABLE `worksheet_fasilitas_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_fasilitas_import_trash`
--
ALTER TABLE `worksheet_fasilitas_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_import`
--
ALTER TABLE `worksheet_import`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_ws` (`no_ws`),
  ADD UNIQUE KEY `no_aju` (`no_aju`),
  ADD UNIQUE KEY `bl` (`bl`);

--
-- Indexes for table `worksheet_import_trash`
--
ALTER TABLE `worksheet_import_trash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worksheet_informasi_tambahan_export`
--
ALTER TABLE `worksheet_informasi_tambahan_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_informasi_tambahan_export_trash`
--
ALTER TABLE `worksheet_informasi_tambahan_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_informasi_tambahan_import`
--
ALTER TABLE `worksheet_informasi_tambahan_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_informasi_tambahan_import_trash`
--
ALTER TABLE `worksheet_informasi_tambahan_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_lartas_export`
--
ALTER TABLE `worksheet_lartas_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_lartas_export_trash`
--
ALTER TABLE `worksheet_lartas_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_lartas_import`
--
ALTER TABLE `worksheet_lartas_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lartas_ws_import` (`id_ws`);

--
-- Indexes for table `worksheet_lartas_import_trash`
--
ALTER TABLE `worksheet_lartas_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lartas_ws_import` (`id_ws`);

--
-- Indexes for table `worksheet_trucking_export`
--
ALTER TABLE `worksheet_trucking_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_trucking_export_trash`
--
ALTER TABLE `worksheet_trucking_export_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ws` (`id_ws`);

--
-- Indexes for table `worksheet_trucking_import`
--
ALTER TABLE `worksheet_trucking_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trucking_ws_id` (`id_ws`);

--
-- Indexes for table `worksheet_trucking_import_trash`
--
ALTER TABLE `worksheet_trucking_import_trash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trucking_ws_id` (`id_ws`);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `booking_job_trash`
--
ALTER TABLE `booking_job_trash`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `master_consignee`
--
ALTER TABLE `master_consignee`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_fasilitas`
--
ALTER TABLE `master_fasilitas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_informasi_tambahan`
--
ALTER TABLE `master_informasi_tambahan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_kemasan`
--
ALTER TABLE `master_kemasan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_lartas`
--
ALTER TABLE `master_lartas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_lokasi_sandar`
--
ALTER TABLE `master_lokasi_sandar`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_notify_party`
--
ALTER TABLE `master_notify_party`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_pelayaran`
--
ALTER TABLE `master_pelayaran`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_port`
--
ALTER TABLE `master_port`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_vessel`
--
ALTER TABLE `master_vessel`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `worksheet_container_export`
--
ALTER TABLE `worksheet_container_export`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `worksheet_container_export_trash`
--
ALTER TABLE `worksheet_container_export_trash`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_container_import`
--
ALTER TABLE `worksheet_container_import`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `worksheet_container_import_trash`
--
ALTER TABLE `worksheet_container_import_trash`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `worksheet_do_export`
--
ALTER TABLE `worksheet_do_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `worksheet_do_export_trash`
--
ALTER TABLE `worksheet_do_export_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_do_import`
--
ALTER TABLE `worksheet_do_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `worksheet_do_import_trash`
--
ALTER TABLE `worksheet_do_import_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `worksheet_export`
--
ALTER TABLE `worksheet_export`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `worksheet_export_trash`
--
ALTER TABLE `worksheet_export_trash`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `worksheet_fasilitas_export`
--
ALTER TABLE `worksheet_fasilitas_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `worksheet_fasilitas_export_trash`
--
ALTER TABLE `worksheet_fasilitas_export_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_fasilitas_import`
--
ALTER TABLE `worksheet_fasilitas_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `worksheet_fasilitas_import_trash`
--
ALTER TABLE `worksheet_fasilitas_import_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `worksheet_import`
--
ALTER TABLE `worksheet_import`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `worksheet_import_trash`
--
ALTER TABLE `worksheet_import_trash`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `worksheet_informasi_tambahan_export`
--
ALTER TABLE `worksheet_informasi_tambahan_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `worksheet_informasi_tambahan_export_trash`
--
ALTER TABLE `worksheet_informasi_tambahan_export_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_informasi_tambahan_import`
--
ALTER TABLE `worksheet_informasi_tambahan_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `worksheet_informasi_tambahan_import_trash`
--
ALTER TABLE `worksheet_informasi_tambahan_import_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `worksheet_lartas_export`
--
ALTER TABLE `worksheet_lartas_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `worksheet_lartas_export_trash`
--
ALTER TABLE `worksheet_lartas_export_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_lartas_import`
--
ALTER TABLE `worksheet_lartas_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `worksheet_lartas_import_trash`
--
ALTER TABLE `worksheet_lartas_import_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `worksheet_trucking_export`
--
ALTER TABLE `worksheet_trucking_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `worksheet_trucking_export_trash`
--
ALTER TABLE `worksheet_trucking_export_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worksheet_trucking_import`
--
ALTER TABLE `worksheet_trucking_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `worksheet_trucking_import_trash`
--
ALTER TABLE `worksheet_trucking_import_trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `worksheet_container_export`
--
ALTER TABLE `worksheet_container_export`
  ADD CONSTRAINT `fk_container_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_container_import`
--
ALTER TABLE `worksheet_container_import`
  ADD CONSTRAINT `fk_container_ws_id` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`);

--
-- Constraints for table `worksheet_do_export`
--
ALTER TABLE `worksheet_do_export`
  ADD CONSTRAINT `fk_do_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_do_import`
--
ALTER TABLE `worksheet_do_import`
  ADD CONSTRAINT `fk_do_ws_id` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`);

--
-- Constraints for table `worksheet_fasilitas_export`
--
ALTER TABLE `worksheet_fasilitas_export`
  ADD CONSTRAINT `fk_fasilitas_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_fasilitas_import`
--
ALTER TABLE `worksheet_fasilitas_import`
  ADD CONSTRAINT `worksheet_fasilitas_import_ibfk_1` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_informasi_tambahan_export`
--
ALTER TABLE `worksheet_informasi_tambahan_export`
  ADD CONSTRAINT `fk_info_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_informasi_tambahan_import`
--
ALTER TABLE `worksheet_informasi_tambahan_import`
  ADD CONSTRAINT `worksheet_informasi_tambahan_import_ibfk_1` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_lartas_export`
--
ALTER TABLE `worksheet_lartas_export`
  ADD CONSTRAINT `fk_lartas_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_lartas_import`
--
ALTER TABLE `worksheet_lartas_import`
  ADD CONSTRAINT `fk_lartas_ws_import` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `worksheet_trucking_export`
--
ALTER TABLE `worksheet_trucking_export`
  ADD CONSTRAINT `fk_trucking_export` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `worksheet_trucking_import`
--
ALTER TABLE `worksheet_trucking_import`
  ADD CONSTRAINT `fk_trucking_ws_id` FOREIGN KEY (`id_ws`) REFERENCES `worksheet_import` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
