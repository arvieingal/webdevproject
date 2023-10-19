-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2023 at 05:53 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reminder_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityId` int(11) NOT NULL,
  `activityName` varchar(63) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(127) NOT NULL,
  `ootd` varchar(255) NOT NULL,
  `status` varchar(15) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activityId`, `activityName`, `date`, `time`, `location`, `ootd`, `status`, `remarks`, `userId`) VALUES
(1, 'Deadline', '2023-10-19', '21:00:00', 'USC', 'uniform', '', '', 2),
(3, 'asdf', '2023-10-06', '03:36:00', 'asdf', 'asdf', 'Done', '', 2),
(4, 'Deadline for tomorrow', '2023-10-19', '18:00:00', 'USC', 'uniform', '', 'sana ma move', 2);

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcementId` int(11) NOT NULL,
  `title` varchar(63) NOT NULL,
  `content` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `editedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `deletedAt` timestamp NULL DEFAULT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcementId`, `title`, `content`, `createdAt`, `editedAt`, `deletedAt`, `userId`) VALUES
(1, 'For Users', 'This is for all', '2023-10-15 08:24:24', '2023-10-15 08:24:24', NULL, 1),
(7, 'test', 'test', '2023-10-18 12:48:14', '2023-10-18 12:48:14', NULL, 1),
(8, 'latest', 'latest', '2023-10-18 14:04:28', '2023-10-18 14:04:28', NULL, 1),
(9, 'test test', 'test', '2023-10-18 14:18:54', '2023-10-18 14:18:54', NULL, 1),
(10, 'latest announcement', 'our website is about to be finish', '2023-10-19 03:48:07', '2023-10-19 03:48:07', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `editedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `deletedAt` timestamp NULL DEFAULT NULL,
  `userId` int(11) NOT NULL,
  `announcementId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentId`, `content`, `createdAt`, `editedAt`, `deletedAt`, `userId`, `announcementId`) VALUES
(1, 'hello', '2023-10-15 09:51:48', '2023-10-15 09:51:48', NULL, 2, 1),
(7, 'hmm', '2023-10-18 12:55:03', '2023-10-18 12:55:03', NULL, 1, 7),
(21, 'new', '2023-10-18 14:18:30', '2023-10-18 14:18:30', NULL, 1, 8),
(22, 'for user', '2023-10-18 14:18:38', '2023-10-18 14:18:38', NULL, 1, 1),
(23, 'how about this?', '2023-10-18 18:10:37', '2023-10-18 18:10:37', NULL, 2, 9),
(24, 'another 1', '2023-10-18 18:10:42', '2023-10-18 18:10:42', NULL, 2, 9),
(25, 'hey', '2023-10-19 03:29:28', '2023-10-19 03:29:28', NULL, 2, 9),
(26, 'this is for the first announcement', '2023-10-19 03:29:43', '2023-10-19 03:29:43', NULL, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(31) NOT NULL,
  `lastName` varchar(31) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `email` varchar(63) NOT NULL,
  `password` varchar(31) NOT NULL,
  `role` varchar(11) NOT NULL DEFAULT 'user',
  `status` varchar(11) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `firstName`, `lastName`, `gender`, `email`, `password`, `role`, `status`) VALUES
(1, 'Arvie', 'Ingal', 'Male', 'arvie@gmail.com', 'gwapo', 'admin', 'active'),
(2, 'Nicole May', 'Abejar', 'Female', 'nicole@gmail.com', 'gwapa', 'user', 'active'),
(3, 'Bryan', 'Alcover', 'Others', 'bryan@gmail.com', 'bayot', 'user', 'deactivate'),
(4, 'Aviella', 'Cardines', 'Female', 'aviella@gmail.com', 'gwapa', 'user', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityId`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcementId`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
