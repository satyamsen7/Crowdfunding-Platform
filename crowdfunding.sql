-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 12:30 PM
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
-- Database: `crowdfunding`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullname`, `username`, `email`, `password`) VALUES
(1, 'Admin Name', 'admin', 'admin@example.com', '$2y$10$HUJw1q6Mpb6kp2zQBGoEwutaLVi5V6DbwlXJHoHEJTWlX2ZVwH0zK');

-- --------------------------------------------------------

--
-- Table structure for table `assistance_requests`
--

CREATE TABLE `assistance_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `request_type` varchar(20) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assistance_requests`
--

INSERT INTO `assistance_requests` (`id`, `name`, `phone`, `purpose`, `request_type`, `submitted_at`) VALUES
(8, 'raj', '+91 7544575457', 'education', 'whatsapp', '2025-01-21 06:47:27'),
(9, 'faraz', '+91 7544342542', 'others', 'call', '2025-01-21 06:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form`
--

INSERT INTO `contact_form` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(5, 'Satyam Sen', 'satyamsen267@gmail.com', 'hello ', '2025-01-20 11:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `campaign_link` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `campaign_link`, `amount`, `payment_id`, `created_at`) VALUES
(51, 'campaign_678d48a52c2b3', 2000.00, 'pay_PlPFk9miky1c0e', '2025-01-19 18:48:15'),
(52, 'campaign_678e33f3b8533', 2000.00, 'pay_Plh7oaInEMVSdO', '2025-01-20 12:17:13'),
(53, 'campaign_678eb5b40a0dd', 200.00, 'pay_Plpmd2ZVcCNh0P', '2025-01-20 20:45:25'),
(54, 'campaign_678e365fb84d5', 20000.00, 'pay_PlzyOERjgpZidv', '2025-01-21 06:43:27'),
(55, 'campaign_678f44ee10ae5', 10000.00, 'pay_Pm0EgTvaLPs3j5', '2025-01-21 06:58:54'),
(56, 'campaign_678f60b3232cc', 100.00, 'pay_Pm2DEXpUjSzwKh', '2025-01-21 08:54:54');

-- --------------------------------------------------------

--
-- Table structure for table `fundraisers`
--

CREATE TABLE `fundraisers` (
  `campaign_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `person_name` varchar(100) NOT NULL,
  `purpose` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `campaign_link` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `category` enum('Education','Medical','Women & Girls','Animals','Creative','Food & Hunger','Environment','Children','Memorial','Community Development','Others') NOT NULL DEFAULT 'Others',
  `address` varchar(255) NOT NULL,
  `raised` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `phone`, `password`, `balance`) VALUES
(12, 'SATYAM', 'satyamsen269', 'satyamsen27@gmail.com', '123456', '$2y$10$LpGBqMDODGkCMapJNdE5h.oV6fwMQL5/y5hYd.qbtOE3brPk9NI1S', 100.00),
(13, 'sonu', 'sonu', 'sonu@sonu.cpom', '84135468123153', '$2y$10$Izh3M3MJJJs1FR56u2W7HepUzvF4sLNGjZblnyDPZybT32.FJvUhi', 0.00),
(14, 'Yogitha', 'Yogitha', 'Yogitha@example.com', '1234567890', '$2y$10$Z0jcGEqcMyPuXMUFSvPIxuYKyRUQqEOi.6A2WhWv9Tx3o6NnqmLfa', 0.00),
(15, 'Vasantha Madhava', 'Vasantha', 'VasanthaMadhava@example.com', '', '$2y$10$VZ3YNSbdA8q91g3wMUBFE.RWEK0h1WNSaz5WbComQyzsdQs7Z3k8y', 1600.00),
(16, 'Aditi Shah', 'Aditi', 'AditiShah@example.com', '', '$2y$10$AX7SbqstUuPy46Jm37HuvOQn31Ha5zvEl1rYD5F0206C7DI7slf8W', 20000.00),
(17, 'raj vardhan ', 'raj', 'raj@gmail.com', '351512151581', '$2y$10$huBvIZKCm2eDqWmPIWAGo.so0IOZknBOW6mSQSn.EB9qfT1snAKLu', 0.00),
(18, 'raj', 'raj', 'raj@gmail.com', '65352523', '$2y$10$vFLXoQ7.YNxf32b/qMTOdO71Cx7m27fCRIPA4hqrN1.s8xQ.QOqo6', 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upi_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Processing','Success','Failed') NOT NULL DEFAULT 'Processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `upi_id`, `amount`, `requested_at`, `status`) VALUES
(9, 15, 'satyamsen268@ybl', 400.00, '2025-01-20 19:16:22', 'Success'),
(10, 12, 'satyamsen268@ybl', 200.00, '2025-01-21 08:55:08', 'Failed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `assistance_requests`
--
ALTER TABLE `assistance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fundraisers`
--
ALTER TABLE `fundraisers`
  ADD PRIMARY KEY (`campaign_id`),
  ADD UNIQUE KEY `campaign_link` (`campaign_link`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assistance_requests`
--
ALTER TABLE `assistance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `fundraisers`
--
ALTER TABLE `fundraisers`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
