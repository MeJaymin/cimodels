-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2019 at 07:42 PM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 5.6.33-3+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `giftcast_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `demo_store_details`
--

CREATE TABLE IF NOT EXISTS `demo_store_details` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `u_id` int(255) NOT NULL,
  `card_num` varchar(255) NOT NULL,
  `card_type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `demo_store_details`
--

INSERT INTO `demo_store_details` (`id`, `u_id`, `card_num`, `card_type`, `created_at`) VALUES
(1, 1, '123123123', 'Visa', '2017-12-29 15:54:46'),
(2, 1, '123123123', 'Visa', '2018-01-01 14:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE IF NOT EXISTS `gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `from_id` int(11) NOT NULL,
  `from_delete` tinyint(11) NOT NULL COMMENT '0- not delete, 1- delete',
  `recipient_id` int(11) NOT NULL,
  `recipient_gift_status` tinyint(11) NOT NULL COMMENT '0 - not opened, 1 - opened',
  `to_delete` tinyint(4) NOT NULL DEFAULT '0',
  `from_name` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `amount` float(10,2) NOT NULL,
  `final_amount` float(10,2) NOT NULL,
  `video` varchar(255) NOT NULL,
  `from_node_id` varchar(255) DEFAULT NULL,
  `recipient_node_id` varchar(255) DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `payment_type` enum('1','2','3','4') NOT NULL COMMENT '1- Bank Account 2 - Credit Card 3 - Debit Card 4- Prepaid Card',
  `gift_timestamp` varchar(255) NOT NULL,
  `gift_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - not sent, 1 - opened 2-sent, 3 - issue while running cron',
  `withdraw_status` tinyint(11) NOT NULL DEFAULT '0' COMMENT '0 - not withdraw, 1 - withdraw',
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `gifts`
--

INSERT INTO `gifts` (`id`, `title`, `from_id`, `from_delete`, `recipient_id`, `recipient_gift_status`, `to_delete`, `from_name`, `recipient_name`, `amount`, `final_amount`, `video`, `from_node_id`, `recipient_node_id`, `screenshot`, `payment_type`, `gift_timestamp`, `gift_status`, `withdraw_status`, `created_at`, `updated_at`) VALUES
(2, 'Postman', 23, 0, 40, 1, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-03 15:11:53', '2018-04-16 13:06:28'),
(23, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-16 18:45:04', '2018-04-16 13:15:11'),
(24, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-24 14:52:28', '2018-04-24 09:22:28'),
(25, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-24 14:53:30', '2018-04-24 09:23:30'),
(26, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-25 11:06:25', '2018-04-25 05:36:34'),
(27, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 09:42:12', '2018-04-26 04:12:20'),
(28, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 10:00:58', '2018-04-26 04:31:05'),
(29, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 10:02:38', '2018-04-26 04:32:50'),
(30, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 10:04:15', '2018-04-26 04:34:15'),
(31, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 101.50, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 10:04:54', '2018-04-26 04:34:59'),
(32, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 201.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-26 10:07:07', '2018-04-26 04:37:13'),
(33, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-27 10:58:55', '2018-04-27 05:28:55'),
(34, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-27 10:58:57', '2018-04-27 05:28:57'),
(35, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-27 10:59:45', '2018-04-27 05:29:52'),
(36, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-27 11:03:45', '2018-04-27 05:33:52'),
(39, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 112.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-04-27 11:16:46', '2018-04-27 05:48:43'),
(40, 'Postman', 23, 0, 40, 0, 0, NULL, NULL, 100.00, 112.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-05-31 15:21:48', '2018-05-31 09:53:11'),
(41, 'Postman', 23, 0, 40, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-11 18:29:48', '2018-09-11 12:59:48'),
(42, 'Postman', 13, 0, 41, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:15:43', '2018-09-17 12:45:49'),
(43, 'Postman', 13, 0, 42, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:16:29', '2018-09-17 12:46:35'),
(44, 'Postman', 13, 0, 43, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:21:37', '2018-09-17 12:51:43'),
(45, 'Postman', 13, 0, 44, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:22:14', '2018-09-17 12:52:18'),
(46, 'Postman', 13, 0, 45, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:29:26', '2018-09-17 12:59:26'),
(47, 'Postman', 13, 0, 46, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:44:22', '2018-09-17 13:14:28'),
(48, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:47:29', '2018-09-17 13:17:35'),
(49, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:48:54', '2018-09-17 13:19:01'),
(50, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:49:26', '2018-09-17 13:19:33'),
(51, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 18:58:05', '2018-09-17 13:28:10'),
(52, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 19:00:47', '2018-09-17 13:30:53'),
(53, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-17 19:07:26', '2018-09-17 13:37:32'),
(54, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:23:28', '2018-09-18 13:53:34'),
(55, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:29:03', '2018-09-18 13:59:09'),
(56, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:36:16', '2018-09-18 14:06:23'),
(57, 'Postman', 13, 0, 47, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:44:41', '2018-09-18 14:14:52'),
(58, 'Postman', 13, 0, 48, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:46:24', '2018-09-18 14:16:29'),
(59, 'Postman', 13, 0, 48, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-18 19:46:49', '2018-09-18 14:16:55'),
(60, 'Postman', 13, 0, 49, 0, 0, 'Postman', NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2018-09-19 13:03:46', '2018-09-19 07:33:53'),
(61, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:22:29', '2019-01-19 09:52:29'),
(62, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:23:33', '2019-01-19 09:53:33'),
(63, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:23:44', '2019-01-19 09:53:44'),
(64, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:24:08', '2019-01-19 09:54:08'),
(65, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:24:23', '2019-01-19 09:54:23'),
(66, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:25:07', '2019-01-19 09:55:07'),
(67, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:26:02', '2019-01-19 09:56:02'),
(68, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:26:41', '2019-01-19 09:56:41'),
(69, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:27:08', '2019-01-19 09:57:08'),
(70, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:27:25', '2019-01-19 09:57:25'),
(71, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:27:30', '2019-01-19 09:57:30'),
(72, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:27:37', '2019-01-19 09:57:37'),
(73, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:28:16', '2019-01-19 09:58:16'),
(74, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:28:31', '2019-01-19 09:58:31'),
(75, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:29:02', '2019-01-19 09:59:02'),
(76, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:32:06', '2019-01-19 10:02:06'),
(77, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:32:35', '2019-01-19 10:02:35'),
(78, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:33:51', '2019-01-19 10:03:51'),
(79, 'Postman', 50, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:35:54', '2019-01-19 10:05:54'),
(80, 'Postman', 50, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:37:25', '2019-01-19 10:07:25'),
(81, 'Postman', 50, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:37:59', '2019-01-19 10:07:59'),
(82, 'Postman', 50, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:38:47', '2019-01-19 10:08:47'),
(83, 'Postman', 50, 0, 47, 0, 0, NULL, NULL, 110.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-01-19 15:39:48', '2019-01-19 10:09:48'),
(84, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-02-26 19:10:40', '2019-02-26 13:40:40'),
(85, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-02-26 19:12:35', '2019-02-26 13:42:35'),
(86, 'Postman', 47, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-02-26 19:16:43', '2019-02-26 13:46:43'),
(87, 'Postman', 41, 0, 47, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 11:42:18', '2019-03-04 06:12:18'),
(88, 'Postman', 23, 0, 50, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:11:23', '2019-03-04 06:41:23'),
(89, 'Postman', 23, 0, 47, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:12:12', '2019-03-04 06:42:12'),
(90, 'Postman', 23, 0, 47, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:12:56', '2019-03-04 06:42:56'),
(91, 'Postman', 47, 0, 47, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:14:13', '2019-03-04 06:44:13'),
(92, 'Postman', 47, 0, 47, 0, 0, NULL, NULL, 200.00, 212.00, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:14:56', '2019-03-04 06:44:56'),
(93, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:16:19', '2019-03-04 06:46:19'),
(94, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:16:42', '2019-03-04 06:46:42'),
(95, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:18:09', '2019-03-04 06:48:09'),
(96, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:20:14', '2019-03-04 06:50:14'),
(97, 'Postman', 1, 0, 50, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:20:43', '2019-03-04 06:50:43'),
(98, 'Postman', 47, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 12:21:22', '2019-03-04 06:51:22'),
(99, 'Postman', 48, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 13:57:39', '2019-03-04 08:27:39'),
(100, 'Postman', 48, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 14:14:43', '2019-03-04 08:44:43'),
(101, 'Postman', 48, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-04 14:19:13', '2019-03-04 08:49:13'),
(102, 'Postman', 48, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', '5c8f398e4b7ba910295db8a6', '5c8f398e70fe0a13d65f4de5', NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 1, '2019-03-19 12:31:17', '2019-03-19 10:38:58'),
(103, 'Postman', 48, 0, 47, 0, 0, NULL, NULL, 100.00, 101.99, '123.mp4', NULL, NULL, NULL, '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-22 16:08:18', '2019-03-22 10:38:18'),
(104, 'Postman', 48, 0, 47, 0, 0, 'Postman', NULL, 101.99, 100.00, '123.mp4', NULL, NULL, 'abcd', '', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-27 11:20:52', '2019-03-27 05:50:52'),
(107, 'Postman', 48, 0, 47, 0, 0, 'Postman', NULL, 101.99, 100.00, '123.mp4', NULL, NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-27 15:14:43', '2019-03-27 09:44:43'),
(108, 'Postman', 48, 0, 47, 0, 0, 'Postman', NULL, 10.00, 11.99, '123.mp4', '5c7e5eac70fe0a00695f37b4', NULL, 'abcd', '1', '20190319T083503', 2, 0, '2019-03-27 15:15:56', '2019-03-27 10:50:37'),
(109, 'Postman', 48, 0, 47, 0, 0, 'Postman', NULL, 10.00, 11.99, '123.mp4', '5c7e5eac70fe0a00695f37b4', NULL, 'abcd', '1', '20190319T083503', 0, 0, '2019-03-27 15:58:19', '2019-03-27 10:50:03'),
(110, 'Postman', 48, 0, 47, 0, 0, 'Postman', NULL, 101.99, 100.00, '123.mp4', '5c8f398e4b7ba910295db8a6', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-28 14:25:23', '2019-03-28 08:55:23'),
(111, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 101.99, 100.00, '123.mp4', '5c8f398e4b7ba910295db8a6', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-03-28 14:26:40', '2019-03-28 08:56:40'),
(112, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 101.99, 100.00, '123.mp4', '5c8f398e4b7ba910295db8a6', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-03 19:11:05', '2019-04-03 13:41:05'),
(113, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 10.00, 10.00, '123.mp4', '5c8f398e70fe0a13d65f4de5', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-16 13:06:47', '2019-04-16 07:36:50'),
(114, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 10.00, 10.00, '123.mp4', '5c8f398e70fe0a13d65f4de5', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-16 13:08:54', '2019-04-16 07:38:57'),
(115, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 10.00, 10.00, '123.mp4', '5c8f398e70fe0a13d65f4de5', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-16 13:10:44', '2019-04-16 07:40:48'),
(116, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 10.00, 10.00, '123.mp4', '5ca5edc6d23e5f371c19b79b', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-16 13:11:19', '2019-04-17 08:06:56'),
(117, 'Postman', 50, 0, 47, 0, 0, 'Postman', NULL, 10.00, 10.00, '123.mp4', '5ca5edc6d23e5f371c19b79b', NULL, 'abcd', '1', 'Fri Aug 25 2017 12:34:18 GMT+0530 (IST)', 2, 0, '2019-04-16 13:11:59', '2019-04-17 08:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `recieve_gift` tinyint(11) NOT NULL COMMENT 'When user recieve a gift',
  `send_gift` tinyint(11) NOT NULL COMMENT 'When user send a gift',
  `contacts_joined` tinyint(11) NOT NULL COMMENT 'When a new user joins contact',
  `admin_giftcast_settings` tinyint(11) NOT NULL COMMENT 'Offers from Giftcast partners by Admin',
  `gift_opened` tinyint(11) NOT NULL COMMENT 'when a user opens a gift',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `user_id`, `recieve_gift`, `send_gift`, `contacts_joined`, `admin_giftcast_settings`, `gift_opened`, `created_at`, `updated_at`) VALUES
(1, 16, 0, 0, 1, 1, 0, '2018-01-08 15:12:44', '2018-01-16 13:15:08'),
(2, 1, 1, 1, 1, 0, 0, '2018-01-08 15:13:25', '2018-01-25 13:37:19'),
(17, 23, 1, 1, 1, 0, 0, '2018-01-08 15:13:25', '2018-01-25 13:37:19'),
(18, 27, 1, 1, 1, 1, 1, '2018-03-27 14:38:22', '0000-00-00 00:00:00'),
(19, 27, 1, 1, 1, 1, 1, '2018-03-27 14:38:39', '0000-00-00 00:00:00'),
(20, 48, 1, 1, 1, 1, 1, '2019-03-27 18:27:33', '0000-00-00 00:00:00'),
(21, 40, 1, 1, 1, 1, 1, '2018-09-25 19:09:13', '0000-00-00 00:00:00'),
(22, 50, 1, 1, 1, 1, 1, '2018-09-25 19:14:51', '0000-00-00 00:00:00'),
(23, 51, 1, 1, 1, 1, 1, '2019-02-28 12:17:43', '0000-00-00 00:00:00'),
(24, 52, 1, 1, 1, 1, 1, '2019-02-28 12:22:50', '0000-00-00 00:00:00'),
(25, 52, 1, 1, 1, 1, 1, '2019-02-28 12:28:36', '0000-00-00 00:00:00'),
(26, 53, 1, 1, 1, 1, 1, '2019-02-28 12:29:31', '0000-00-00 00:00:00'),
(27, 47, 1, 1, 1, 1, 1, '2019-02-28 12:55:05', '0000-00-00 00:00:00'),
(28, 55, 1, 1, 1, 1, 1, '2019-03-13 18:37:35', '0000-00-00 00:00:00'),
(29, 48, 1, 1, 1, 1, 1, '2019-03-27 18:27:33', '0000-00-00 00:00:00'),
(31, 58, 1, 1, 1, 1, 1, '2019-03-27 17:59:26', '0000-00-00 00:00:00'),
(32, 59, 1, 1, 1, 1, 1, '2019-03-27 18:01:11', '0000-00-00 00:00:00'),
(33, 58, 1, 1, 1, 1, 1, '2019-04-03 19:15:16', '0000-00-00 00:00:00'),
(34, 59, 1, 1, 1, 1, 1, '2019-04-03 19:19:20', '0000-00-00 00:00:00'),
(35, 60, 1, 1, 1, 1, 1, '2019-04-03 19:21:58', '0000-00-00 00:00:00'),
(36, 62, 1, 1, 1, 1, 1, '2019-05-23 12:39:07', '0000-00-00 00:00:00'),
(37, 63, 1, 1, 1, 1, 1, '2019-05-23 12:40:12', '0000-00-00 00:00:00'),
(38, 64, 1, 1, 1, 1, 1, '2019-05-23 12:41:57', '0000-00-00 00:00:00'),
(39, 65, 1, 1, 1, 1, 1, '2019-05-23 12:43:14', '0000-00-00 00:00:00'),
(40, 66, 1, 1, 1, 1, 1, '2019-05-23 12:45:15', '0000-00-00 00:00:00'),
(41, 67, 1, 1, 1, 1, 1, '2019-05-23 12:47:00', '0000-00-00 00:00:00'),
(42, 68, 1, 1, 1, 1, 1, '2019-05-31 13:10:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_data`
--

CREATE TABLE IF NOT EXISTS `payment_data` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `client_synapsefy_userid` varchar(255) NOT NULL COMMENT 'synapsefy returns userid',
  `client_custody_account` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `payment_data`
--

INSERT INTO `payment_data` (`id`, `client_synapsefy_userid`, `client_custody_account`) VALUES
(1, '5c7df3ed7d093e00671e228b', '5c89f5c34b7ba9102c659ea0');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `complaint` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `user_id`, `topic`, `title`, `complaint`, `created_at`) VALUES
(1, 18, 'AAA', 'AAA', 'AAA', '0000-00-00 00:00:00'),
(2, 18, 'AAA', 'AAA', 'AAA', '0000-00-00 00:00:00'),
(3, 18, 'AAA', 'AAA', 'AAA', '0000-00-00 00:00:00'),
(4, 16, 'AA', 'asdasd', 'Asasdasd', '0000-00-00 00:00:00'),
(5, 16, 'AA', 'asdasd', 'Asasdasd', '0000-00-00 00:00:00'),
(6, 16, 'AA', 'asdasd', 'Asasdasd', '0000-00-00 00:00:00'),
(7, 16, 'AA', 'asdasd', 'Asasdasd', '2018-01-29 18:01:34');

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

CREATE TABLE IF NOT EXISTS `tax` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `credit_card_fee` float(10,2) NOT NULL,
  `debit_card_fee` float(10,2) NOT NULL,
  `bank_account_fee` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tax`
--

INSERT INTO `tax` (`id`, `credit_card_fee`, `debit_card_fee`, `bank_account_fee`) VALUES
(1, 1.50, 1.50, 1.50);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_id` int(11) NOT NULL,
  `payment_type` enum('1','2','3','4') NOT NULL COMMENT '1- Bank Account 2 - Credit Card 3 - Debit Card 4- Prepaid Card',
  `transaction_id` varchar(255) NOT NULL,
  `transaction_details` text NOT NULL,
  `payment_status` varchar(255) NOT NULL COMMENT '1 - success 0 - fail',
  `gift_sent_type` enum('1','2','','') NOT NULL COMMENT '1-gift sent 2- withdraw',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `gift_id`, `payment_type`, `transaction_id`, `transaction_details`, `payment_status`, `gift_sent_type`, `created_at`) VALUES
(25, 117, '3', '5cb6cfa861f78900c82a53e5', 'a:11:{s:3:"_id";s:24:"5cb6cfa861f78900c82a53e5";s:6:"_links";a:1:{s:4:"self";a:1:{s:4:"href";s:127:"https://uat-api.synapsefi.com/v3.1/users/5c49abcf0049e60069022f15/nodes/5ca5edc6d23e5f371c19b79b/trans/5cb6cfa861f78900c82a53e5";}}s:2:"_v";i:2;s:6:"amount";a:2:{s:6:"amount";d:27;s:8:"currency";s:3:"USD";}s:6:"client";a:2:{s:2:"id";s:24:"5c061102f183c9002a6b2107";s:4:"name";s:12:"* Jaymin Zap";}s:5:"extra";a:13:{s:5:"asset";N;s:10:"created_on";i:1555484584604;s:8:"group_id";N;s:2:"ip";s:13:"216.55.169.45";s:6:"latlon";s:15:"unknown,unknown";s:8:"location";a:6:{s:12:"address_city";N;s:20:"address_country_code";N;s:19:"address_postal_code";N;s:19:"address_subdivision";N;s:3:"lat";i:0;s:3:"lon";i:0;}s:4:"note";s:23:"Deposit to bank account";s:5:"other";a:3:{s:11:"attachments";a:0:{}s:12:"dispute_form";N;s:8:"disputed";b:0;}s:10:"process_on";i:1555570984604;s:8:"same_day";b:0;s:16:"settlement_delay";i:0;s:7:"supp_id";s:22:"1283764wqwsdd34wd13212";s:15:"tracking_number";N;}s:4:"fees";a:1:{i:0;a:3:{s:3:"fee";d:0;s:4:"note";s:15:"Facilitator Fee";s:2:"to";a:1:{s:2:"id";s:4:"None";}}}s:4:"from";a:4:{s:2:"id";s:24:"5ca5edc6d23e5f371c19b79b";s:8:"nickname";s:10:"Test Debit";s:4:"type";s:14:"INTERCHANGE-US";s:4:"user";a:2:{s:3:"_id";s:24:"5c49abcf0049e60069022f15";s:11:"legal_names";a:1:{i:0;s:7:"Anuj T1";}}}s:13:"recent_status";a:4:{s:4:"date";i:1555484584604;s:4:"note";s:20:"Transaction Created.";s:6:"status";s:7:"CREATED";s:9:"status_id";s:1:"1";}s:8:"timeline";a:1:{i:0;a:4:{s:4:"date";i:1555484584604;s:4:"note";s:20:"Transaction Created.";s:6:"status";s:7:"CREATED";s:9:"status_id";s:1:"1";}}s:2:"to";a:4:{s:2:"id";s:24:"5c89f5c34b7ba9102c659ea0";s:8:"nickname";s:15:"Custody Account";s:4:"type";s:10:"CUSTODY-US";s:4:"user";a:2:{s:3:"_id";s:24:"5c7df3ed7d093e00671e228b";s:11:"legal_names";a:1:{i:0;s:21:"Tim Campbell GiftCast";}}}}', 'SETTLED', '1', '0000-00-00 00:00:00'),
(26, 116, '2', '5cb6cfa861f78900c82a53e5', 'a:11:{s:3:"_id";s:24:"5cb6cfa861f78900c82a53e5";s:6:"_links";a:1:{s:4:"self";a:1:{s:4:"href";s:127:"https://uat-api.synapsefi.com/v3.1/users/5c49abcf0049e60069022f15/nodes/5ca5edc6d23e5f371c19b79b/trans/5cb6cfa861f78900c82a53e5";}}s:2:"_v";i:2;s:6:"amount";a:2:{s:6:"amount";d:27;s:8:"currency";s:3:"USD";}s:6:"client";a:2:{s:2:"id";s:24:"5c061102f183c9002a6b2107";s:4:"name";s:12:"* Jaymin Zap";}s:5:"extra";a:13:{s:5:"asset";N;s:10:"created_on";i:1555484584604;s:8:"group_id";N;s:2:"ip";s:13:"216.55.169.45";s:6:"latlon";s:15:"unknown,unknown";s:8:"location";a:6:{s:12:"address_city";N;s:20:"address_country_code";N;s:19:"address_postal_code";N;s:19:"address_subdivision";N;s:3:"lat";i:0;s:3:"lon";i:0;}s:4:"note";s:23:"Deposit to bank account";s:5:"other";a:3:{s:11:"attachments";a:0:{}s:12:"dispute_form";N;s:8:"disputed";b:0;}s:10:"process_on";i:1555570984604;s:8:"same_day";b:0;s:16:"settlement_delay";i:0;s:7:"supp_id";s:22:"1283764wqwsdd34wd13212";s:15:"tracking_number";N;}s:4:"fees";a:1:{i:0;a:3:{s:3:"fee";d:0;s:4:"note";s:15:"Facilitator Fee";s:2:"to";a:1:{s:2:"id";s:4:"None";}}}s:4:"from";a:4:{s:2:"id";s:24:"5ca5edc6d23e5f371c19b79b";s:8:"nickname";s:10:"Test Debit";s:4:"type";s:14:"INTERCHANGE-US";s:4:"user";a:2:{s:3:"_id";s:24:"5c49abcf0049e60069022f15";s:11:"legal_names";a:1:{i:0;s:7:"Anuj T1";}}}s:13:"recent_status";a:4:{s:4:"date";i:1555484584604;s:4:"note";s:20:"Transaction Created.";s:6:"status";s:7:"CREATED";s:9:"status_id";s:1:"1";}s:8:"timeline";a:1:{i:0;a:4:{s:4:"date";i:1555484584604;s:4:"note";s:20:"Transaction Created.";s:6:"status";s:7:"CREATED";s:9:"status_id";s:1:"1";}}s:2:"to";a:4:{s:2:"id";s:24:"5c89f5c34b7ba9102c659ea0";s:8:"nickname";s:15:"Custody Account";s:4:"type";s:10:"CUSTODY-US";s:4:"user";a:2:{s:3:"_id";s:24:"5c7df3ed7d093e00671e228b";s:11:"legal_names";a:1:{i:0;s:21:"Tim Campbell GiftCast";}}}}', 'SETTLED', '1', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1- active 0 inactive',
  `referal_status` tinyint(11) NOT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `device_type` enum('ios','android') DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `synapsefy_user_id` varchar(255) DEFAULT NULL,
  `kyc` tinyint(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email_id`, `password`, `facebook_id`, `phone_number`, `profile_picture`, `status`, `referal_status`, `device_token`, `device_type`, `ip_address`, `synapsefy_user_id`, `kyc`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, 'admin@super.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '', '', '', 1, 1, 'a529adbf0d89171b97e2dd067049eb7546975e5b2fd2a171a32ae15bda306ed6', 'android', '::1', NULL, NULL, '2017-12-15 19:15:39', NULL),
(41, 'Phonebook Name', NULL, 'anurag.star3@gmail.com', '', '', '', NULL, 1, 0, NULL, NULL, '172.16.0.60', '5c9b269d774ea60067965183', NULL, '2018-09-17 18:15:43', NULL),
(47, 'Phonebook Name', NULL, 'jaymin.s@zaptechsolutions.com', 'e10adc3949ba59abbe56e057f20f883e', '', '918460629514', NULL, 1, 0, 'ed3b3be07e4956c68f1261b9fdaccae0f2bd7abeb79e1246c8f26341fa1aa3d6', 'ios', '172.16.0.60', '5c8f396231d1774ac5efa579', NULL, '2018-09-17 18:47:29', '2019-04-16 16:06:48'),
(48, 'Jaymin12', 'Sejpal', 'jaymin.s1@zaptechsolutions.com', 'e10adc3949ba59abbe56e057f20f883e', '', '123456', '', 1, 1, 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '172.16.0.60', '5c7e4e427d093e03a21acd44', NULL, '2019-03-27 18:27:33', '2019-03-27 18:27:33'),
(50, 'jaymin', 'Zap', 'jayminzap@gmail.com', '25f9e794323b453885f5181f1b624d0b', '', '12312312379', '1548247986.png', 1, 0, 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '172.16.0.60', '', NULL, '2018-09-25 19:14:51', '2019-04-03 18:19:40'),
(53, 'Priyank', 'Zap', 'priyank@zaptechsolutions.com', 'e10adc3949ba59abbe56e057f20f883e', '', '9712654712', '', 1, 0, 'a6e2694e004d59b6806b782be0b0d461085f64e138f1984e992ecebd382ec21c', 'ios', '172.16.0.60', '', NULL, '2019-02-28 12:29:31', NULL),
(54, 'Nikhil', '', 'hiten@zaptechsolutions.com', '27b3c9c5299dfcacd7a24cf80e3ee638', '', '9712654712', '1551338968.png', 1, 0, 'abcdefghijklmnopqrstuvwxyz0123456789', '', '172.16.0.60', '5c77bb2ceaf3f3861f3cd576', NULL, '2019-02-28 12:55:05', '2019-03-15 18:28:28'),
(55, 'Nikhil Gandhi', '', 'nikhil.g@zaptechsolutions.com', 'b4c478d25d178b25b9bc69596ec4b826', '', '1234567980', '', 1, 0, 'abcdefghijklmnopqrstuvwxyz0123456789', '', '192.168.15.159', '5c8900e55ac648006672551c', NULL, '2019-03-13 18:37:35', NULL),
(56, 'Nikhil Gandhi', '', 'nikhil.g1@zaptechsolutions.com', '25d55ad283aa400af464c76d713c07ad', '', '1234567980', '', 1, 0, 'abcdefghijklmnopqrstuvwxyz0123456789', '', '192.168.15.159', '5c88cc7d7d093e0066623e40', NULL, '2019-03-13 18:37:35', NULL),
(57, 'Phonebook Name', NULL, '', '', '', '912345678901', NULL, 1, 0, NULL, NULL, '172.16.0.60', NULL, NULL, '2019-03-27 10:25:21', NULL),
(60, 'Jaymin12', 'Sejpal', 'priyank@zaptechsolutions.com', 'e10adc3949ba59abbe56e057f20f883e', '', '123456787', '', 1, 0, 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '172.16.0.60', '', NULL, '2019-04-03 19:21:58', NULL),
(61, 'Megha', 'Sejwani', 'zaptest100@gmail.com', '', '1234567890asdfghjklq', '', 'abcd.jpg', 1, 0, 'abcdefghijklmnopqrstuvwxyz0123456789', 'android', '', NULL, NULL, '2019-04-04 18:23:42', NULL),
(67, 'Jaymin', 'Sejpal', 'jayminsejpal@gmail.com', '3e156f866f28d69b21110b06dbdc8f70', '', '2018628841', '', 1, 0, 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '172.16.0.60', '5cee641d5e00f9bffbdc6803', NULL, '2019-05-23 12:47:00', NULL),
(68, 'Prakash', 'Tank', 'prakash@zaptechsolutions.com', '70152de3bba9720940558fc346e45e38', '', '12562813199', '', 1, 0, 'a6e2694e004d59b6806b782be0b0d461085f64e138f1984e992ecebd382ec21c', 'ios', '101.201.301.401', '5cf0da470080d400f069391d', 0, '2019-05-31 13:10:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `user_id` int(100) NOT NULL,
  `ipaddress` varchar(100) NOT NULL,
  `device_token` varchar(100) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `loggedin_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `ipaddress`, `device_token`, `device_type`, `loggedin_time`) VALUES
(1, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:39:20'),
(2, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:39:22'),
(3, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:40:17'),
(4, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:40:18'),
(5, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:51:06'),
(6, 1, '127.0.0.1', NULL, NULL, '2018-01-10 18:52:57'),
(7, 1, '172.16.0.60', NULL, NULL, '2018-01-10 18:53:27'),
(8, 1, '192.168.15.139', NULL, NULL, '2018-01-10 18:56:21'),
(9, 1, '192.168.15.122', NULL, NULL, '2018-01-16 19:09:56'),
(10, 1, '127.0.0.1', NULL, NULL, '2018-01-17 10:09:34'),
(11, 1, '127.0.0.1', NULL, NULL, '2018-01-17 12:47:54'),
(12, 1, '192.168.15.122', NULL, NULL, '2018-01-17 13:27:45'),
(13, 1, '172.16.0.60', NULL, NULL, '2018-01-17 13:31:21'),
(14, 1, '192.168.15.219', NULL, NULL, '2018-01-17 13:31:57'),
(15, 19, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-19 18:32:44'),
(16, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-22 10:38:35'),
(17, 1, '127.0.0.1', NULL, NULL, '2018-01-22 15:07:08'),
(18, 1, '127.0.0.1', NULL, NULL, '2018-01-24 11:55:51'),
(19, 1, '172.16.0.60', NULL, NULL, '2018-01-24 12:31:55'),
(20, 1, '192.168.15.122', NULL, NULL, '2018-01-24 12:36:24'),
(21, 1, '172.16.0.60', NULL, NULL, '2018-01-24 15:32:25'),
(22, 1, '192.168.15.122', NULL, NULL, '2018-01-24 15:47:29'),
(23, 1, '172.16.0.60', NULL, NULL, '2018-01-24 16:21:44'),
(24, 1, '172.16.0.85', NULL, NULL, '2018-01-24 18:50:49'),
(25, 1, '172.16.0.85', NULL, NULL, '2018-01-24 19:03:55'),
(26, 1, '172.16.0.60', NULL, NULL, '2018-01-25 10:07:26'),
(27, 1, '172.16.0.85', NULL, NULL, '2018-01-25 10:54:12'),
(28, 1, '127.0.0.1', NULL, NULL, '2018-01-29 17:40:03'),
(29, 16, '172.16.0.187', 'dowqfU2HYcE:APA91bHryOcKGzXMFVDYSYpmHN9EsvgxJO19wrJvqHcDDClT6SrIVOYzYpROOZ_v73IgcRpHePzzMiPkTaoSYZQH', 'Android', '2018-03-07 18:07:15'),
(30, 1, '127.0.0.1', NULL, NULL, '2018-04-03 14:25:34'),
(31, 1, '127.0.0.1', NULL, NULL, '2018-04-06 13:44:43'),
(32, 1, '127.0.0.1', NULL, NULL, '2018-04-06 14:28:24'),
(33, 1, '127.0.0.1', NULL, NULL, '2018-04-26 12:34:53'),
(34, 1, '127.0.0.1', NULL, NULL, '2018-04-26 13:44:20'),
(35, 1, '127.0.0.1', NULL, NULL, '2018-04-27 09:26:49'),
(36, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-27 10:50:11'),
(37, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-27 10:52:30'),
(38, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-27 10:53:00'),
(39, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-27 10:53:44'),
(40, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-27 10:53:55'),
(41, 23, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-04-30 13:21:08'),
(42, 1, '127.0.0.1', NULL, NULL, '2018-05-29 15:50:53'),
(43, 1, '127.0.0.1', NULL, NULL, '2018-05-29 15:57:11'),
(44, 1, '127.0.0.1', NULL, NULL, '2018-07-11 15:36:05'),
(45, 1, '127.0.0.1', NULL, NULL, '2018-09-20 14:21:43'),
(46, 54, '192.168.15.159', 'abcdefghijklmnopqrstuvwxyz0123456789', 'WEB', '2019-03-13 14:54:25'),
(47, 54, '192.168.15.159', 'abcdefghijklmnopqrstuvwxyz0123456789', 'WEB', '2019-03-13 15:04:18'),
(48, 54, '192.168.15.159', 'abcdefghijklmnopqrstuvwxyz0123456789', 'WEB', '2019-03-13 15:18:08'),
(49, 54, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2019-03-15 18:22:30'),
(50, 54, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2019-03-15 18:23:27'),
(51, 54, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2019-03-15 18:24:08'),
(52, 54, '192.168.15.159', 'abcdefghijklmnopqrstuvwxyz0123456789', 'WEB', '2019-03-15 18:28:28'),
(53, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2018-01-10 15:39:20'),
(54, 16, '172.16.0.60', 'b9931ce31158decc13db830qwc9f0b766baced84a29f7a881b937304d80abdcbac', 'ios', '2019-04-15 16:51:12');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
