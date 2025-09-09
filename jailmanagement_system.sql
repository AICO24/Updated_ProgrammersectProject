-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 09, 2025 at 06:08 AM
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
-- Database: `jailmanagement_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_records`
--

CREATE TABLE `archive_records` (
  `archive_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_records`
--

INSERT INTO `archive_records` (`archive_id`, `module_name`, `record_id`, `data`, `deleted_by`, `deleted_at`) VALUES
(1, 'Inmate Management', 1, '{\"inmate_id\":\"1\",\"fullname\":\"MANOLO JR, GOCHAICO A.\",\"alias\":\"EMM\",\"age\":\"21\",\"address\":\"12c1asdc1\",\"birthdate\":\"1990-03-21\",\"height\":\"170.6\",\"weight\":\"50\",\"eye_color\":\"Brown\",\"crime\":\"Robbery\",\"cell_block\":\"2\",\"marital_status\":\"Single\",\"language\":\"Tagalog\",\"citizenship\":\"Philippines \",\"religion\":\"Roman Catholic\",\"photo\":\"\",\"medical\":\"\"}', 'AIC024', '2025-09-07 09:49:03'),
(2, 'Inmate Management', 2, '{\"inmate_id\":\"2\",\"fullname\":\"John Doe\",\"alias\":\"Dd\",\"age\":\"34\",\"address\":\"dawdc\",\"birthdate\":\"0000-00-00\",\"height\":\"\",\"weight\":\"\",\"eye_color\":\"\",\"crime\":\"\",\"cell_block\":\"\",\"marital_status\":\"\",\"language\":\"\",\"citizenship\":\"\",\"religion\":\"\",\"photo\":\"\",\"medical\":\"\"}', 'AIC024', '2025-09-07 11:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `inmate_number` varchar(50) NOT NULL,
  `fullname` varchar(120) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `case_type` varchar(120) NOT NULL,
  `status` enum('Active','Released') NOT NULL DEFAULT 'Active',
  `full_details` text DEFAULT NULL,
  `booked_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inmates`
--

CREATE TABLE `inmates` (
  `inmate_id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `eye_color` varchar(50) DEFAULT NULL,
  `crime` varchar(255) DEFAULT NULL,
  `cell_block` varchar(50) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `medical` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmates`
--

INSERT INTO `inmates` (`inmate_id`, `fullname`, `alias`, `age`, `address`, `birthdate`, `height`, `weight`, `eye_color`, `crime`, `cell_block`, `marital_status`, `language`, `citizenship`, `religion`, `photo`, `medical`) VALUES
(3, 'Dion, Areglo', 'DIonnnnn', 21, '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_education`
--

CREATE TABLE `inmate_education` (
  `id` int(11) NOT NULL,
  `inmate_id` int(11) DEFAULT NULL,
  `primary_ed` varchar(100) DEFAULT NULL,
  `secondary_ed` varchar(100) DEFAULT NULL,
  `tertiary_ed` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_education`
--

INSERT INTO `inmate_education` (`id`, `inmate_id`, `primary_ed`, `secondary_ed`, `tertiary_ed`) VALUES
(3, 3, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_emergency_contacts`
--

CREATE TABLE `inmate_emergency_contacts` (
  `id` int(11) NOT NULL,
  `inmate_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_emergency_contacts`
--

INSERT INTO `inmate_emergency_contacts` (`id`, `inmate_id`, `name`, `relationship`, `address`, `contact_number`) VALUES
(3, 3, 'dwadsd', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_family`
--

CREATE TABLE `inmate_family` (
  `id` int(11) NOT NULL,
  `inmate_id` int(11) DEFAULT NULL,
  `mother` varchar(100) DEFAULT NULL,
  `father` varchar(100) DEFAULT NULL,
  `siblings` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_family`
--

INSERT INTO `inmate_family` (`id`, `inmate_id`, `mother`, `father`, `siblings`) VALUES
(3, 3, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `created_at`, `reset_token`, `reset_token_expires`, `role`) VALUES
(1, 'ManoloJr', 'AIC024', 'mjgochaico24@gmail.com', '$2y$10$QgwUpAYQcWfSYt0TbXKDEOiM1VtwEo6nEVwKORC.wwPM7BmH8YD4G', '2025-08-20 08:14:41', '43048d232a5329bfa2570a5aa268896ef08bf732103f01dfdd49e4d06a19a594f7b03b6bb1c1aeaf69b1891e592da42c3368', NULL, 'user'),
(4, 'Dion Areglo', 'Lonewolf', 'dion.areglo1234@gmail.com', '$2y$10$Mq2SyOA/MIyxKLNQzn6Og.u1xyDt5upjG96bHtxPzQYXuFxo3rb1u', '2025-08-28 10:01:59', NULL, NULL, 'user'),
(5, 'luther', 'lut', 'ravelolutherl@gmail.com', '$2y$10$rXGeElDIx1TFlZG/FvyEvub0wLBJoh3uHtdRMETS95ZkLq2AF.MrK', '2025-08-28 12:10:35', NULL, NULL, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_records`
--
ALTER TABLE `archive_records`
  ADD PRIMARY KEY (`archive_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inmate_number` (`inmate_number`);

--
-- Indexes for table `inmates`
--
ALTER TABLE `inmates`
  ADD PRIMARY KEY (`inmate_id`);

--
-- Indexes for table `inmate_education`
--
ALTER TABLE `inmate_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`);

--
-- Indexes for table `inmate_emergency_contacts`
--
ALTER TABLE `inmate_emergency_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`);

--
-- Indexes for table `inmate_family`
--
ALTER TABLE `inmate_family`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive_records`
--
ALTER TABLE `archive_records`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inmates`
--
ALTER TABLE `inmates`
  MODIFY `inmate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inmate_education`
--
ALTER TABLE `inmate_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inmate_emergency_contacts`
--
ALTER TABLE `inmate_emergency_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inmate_family`
--
ALTER TABLE `inmate_family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inmate_education`
--
ALTER TABLE `inmate_education`
  ADD CONSTRAINT `inmate_education_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`inmate_id`) ON DELETE CASCADE;

--
-- Constraints for table `inmate_emergency_contacts`
--
ALTER TABLE `inmate_emergency_contacts`
  ADD CONSTRAINT `inmate_emergency_contacts_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`inmate_id`) ON DELETE CASCADE;

--
-- Constraints for table `inmate_family`
--
ALTER TABLE `inmate_family`
  ADD CONSTRAINT `inmate_family_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`inmate_id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
