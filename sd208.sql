-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2023 at 01:13 PM
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
  `Time` varchar(5) NOT NULL,
  `Ampm` varchar(50) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Ootd` varchar(255) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`Id`, `Name`, `Date`, `Time`, `Ampm`, `Location`, `Ootd`, `Status`, `Remarks`) VALUES
(105, 'Bday ni ambot', '2024-12-03', '10:20', 'am', 'sa bahay nila', 'brief short', 'Done', 'it was fun'),
(107, 'kahit ano', '2023-09-22', '12:12', 'am', 'kahit saan', 'kahit ano', '', 'kahit ano'),
(108, '', '2023-12-15', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `title` varchar(31) NOT NULL,
  `content` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `title`, `content`, `createdAt`) VALUES
(2, 'New Announcement', 'This is a new announcement', '2023-09-26 10:06:22');

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
(9, 'Jay Clark', 'Anore', 19, 'Lapu Lapu City', 'Male', 'clark@gmail.com', 'batigtupi', 'user', 'Active'),
(10, 'Jhon Kenneth', 'Rosales', 21, 'Ambot niya', 'Others', 'jhon@gmail.com', 'bayoot', 'user', 'Active'),
(11, 'Nicole May', 'Abejar', 19, 'Tutug an Barugo, Leyte', 'Female', 'nicole@gmail.com', 'gwapa', 'user', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
