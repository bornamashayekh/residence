-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 07:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `residence`
--

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `weather_id` int(11) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `deleted_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `title`, `weather_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'اصفهان', 2, 1732651962, 1732652715, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `deleted_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'پارکینگ', 1733940439, 1733940439, NULL),
(2, 'حمام', 1733946873, 1733946873, NULL),
(3, 'باربیکیو', 1733946885, 1733946885, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) UNSIGNED NOT NULL,
  `host_id` int(11) UNSIGNED NOT NULL,
  `destination_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `room_detail` varchar(255) NOT NULL,
  `capacity` tinyint(3) UNSIGNED NOT NULL,
  `addition_capacity` tinyint(3) UNSIGNED DEFAULT NULL,
  `daily_price` int(11) UNSIGNED NOT NULL,
  `discount_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `host_id`, `destination_id`, `title`, `room_detail`, `capacity`, `addition_capacity`, `daily_price`, `discount_percent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 2, 'خانه برنا', '2اتاقی بزرگ و زیبا', 14, 1, 20000000, 0, 1733562896, 1733859915, NULL),
(4, 3, 2, 'خانه تست', 'اتاقی بزرگ و زیبا', 4, 1, 0, 0, 1733860158, 1733860158, 1733860197);

-- --------------------------------------------------------

--
-- Table structure for table `room_feature`
--

CREATE TABLE `room_feature` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `deleted_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `room_feature`
--

INSERT INTO `room_feature` (`id`, `room_id`, `feature_id`, `created_at`, `deleted_at`) VALUES
(1, 1, 1, 1733940696, NULL),
(2, 1, 2, 1733946890, NULL),
(3, 1, 3, 1733946895, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `role` enum('geust','host','admin','support') NOT NULL DEFAULT 'geust',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','reject','accept') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `username`, `mobile`, `profile`, `role`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'borna', 'borna', '09919472745', NULL, 'admin', 1731569196, 1731569196, NULL, 'accept'),
(3, '', 'borna2', '09945571447', '', '', 1731700263, 1731700263, NULL, 'pending'),
(4, '', 'born', '09131036745', '', 'admin', 1732477754, 1732477754, NULL, 'accept'),
(6, '', 'borrn', '09131036775', '', 'admin', 1732478552, 1732478552, NULL, 'accept');

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE `weather` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `deleted_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `weather`
--

INSERT INTO `weather` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'sharji', 1732197122, 1732197122, 1732480239),
(2, 'گرم و خشک', 1732480767, 1732480767, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`,`host_id`);

--
-- Indexes for table `room_feature`
--
ALTER TABLE `room_feature`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `weather`
--
ALTER TABLE `weather`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_feature`
--
ALTER TABLE `room_feature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `weather`
--
ALTER TABLE `weather`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
