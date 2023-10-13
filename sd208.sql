-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2023 at 02:46 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sd208`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Time` time(5) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Ootd` varchar(255) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Remarks` varchar(255) NOT NULL,
  `UserId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`Id`, `Name`, `Date`, `Time`, `Location`, `Ootd`, `Status`, `Remarks`, `UserId`) VALUES
(128, 'Bday', '2023-10-05', '08:43:00.00000', 'Church', 'Dress', 'Cancelled', '', 9),
(129, 'asfa', '2023-10-10', '20:46:00.00000', 'asdf', 'asdf', 'Done', '', 9),
(130, 'asdf', '2023-10-13', '08:32:00.00000', 'asdf', 'asdf', 'Done', 'yeah', 11);

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `title` varchar(31) NOT NULL,
  `content` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `title`, `content`, `createdAt`, `UserId`) VALUES
(2, 'New Announcement', 'This is a new announcement', '2023-09-26 10:06:22', 1),
(8, 'Latest Announcement', 'This is for all', '2023-10-13 12:41:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `editedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `deletedAt` timestamp NULL DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `announcementId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentId`, `content`, `createdAt`, `editedAt`, `deletedAt`, `UserId`, `announcementId`) VALUES
(28, 'hey', '2023-10-13 12:42:40', '2023-10-13 12:42:40', NULL, 2, 0),
(29, 'hello', '2023-10-13 12:43:00', '2023-10-13 12:43:00', NULL, 11, 0),
(30, 'last comment', '2023-10-13 12:44:24', '2023-10-13 12:44:24', NULL, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Age` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Role` varchar(50) NOT NULL DEFAULT 'user',
  `Status` varchar(50) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Firstname`, `Lastname`, `Age`, `Address`, `Gender`, `Email`, `Password`, `Role`, `Status`) VALUES
(1, 'Arvie', 'Handsome', 19, 'Tondo, Manila', 'Male', 'arvie@gmail.com', 'gwapo', 'admin', 'Active'),
(2, 'Aviella', 'Conejar', 2, 'Barugo, Leyte', 'Female', 'aviella@gmail.com', 'gwapa', 'user', 'Active'),
(8, 'Paul', 'Henry', 19, 'Bantayan, Island', 'Male', 'paul@gmail.com', 'batigtupipud', 'user', 'Active'),
(9, 'Jay Clark', 'Anore', 19, 'Lapu Lapu City', 'Male', 'clark@gmail.com', 'gwapo', 'admin', 'Active'),
(11, 'Nicole May', 'Abejar', 19, 'Tutug an Barugo, Leyte', 'Female', 'nicole@gmail.com', 'gwapa', 'user', 'Active'),
(12, 'Jaysa', 'Lendio', 20, 'Alegria bandang kanan at kaliwa', 'Female', 'jaysa@gmail.com', 'lendio', 'user', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_user_id` (`UserId`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
