-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2022 at 03:21 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `banking_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(50) NOT NULL,
  `username` text NOT NULL,
  `pwd` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `pwd`, `date_added`) VALUES
(8, 'admin', '4Vcq', '2022-03-29 13:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `account_number` int(11) NOT NULL,
  `contact_number` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`account_number`, `contact_number`, `contact_name`, `id`) VALUES
(5526236, 23453454, 'Froggy', 1),
(5526236, 23453454, 'Froggy', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(30) NOT NULL,
  `account_number` bigint(50) NOT NULL,
  `account_traded_with` bigint(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `transaction_informations` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `account_number`, `account_traded_with`, `type`, `amount`, `transaction_informations`, `date_created`) VALUES
(1, 5526236, 543245, '3', 300, 'Transfered 300 dollars to account 543245.', '2022-03-30 11:15:14'),
(17, 0, 0, 'Transfer to account ', 0, '', '2022-03-30 15:39:43'),
(18, 0, 404434, 'Transfer to account 404434', 400, 'Test Transaction', '2022-03-30 15:47:06'),
(19, 0, 234234, 'Transfer to account 234234', 500, 'Test2', '2022-03-30 15:51:04'),
(20, 0, 234234, 'Transfer to account 234234', 5000, 'Test Transaction', '2022-03-30 16:32:15'),
(21, 0, 234234, 'Transfer to account 234234', 500, 'Test Transaction', '2022-03-30 16:35:19'),
(22, 5526236, 234234, 'Transfer to account 234234', 444, '123213', '2022-03-30 16:42:35'),
(23, 5526236, 0, 'Deposit', 5000, 'User deposited 5000 in his own account', '2022-03-30 17:09:43'),
(24, 5526236, 123, 'Transfer to account 123', 123, '123', '2022-03-30 18:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL,
  `account_number` bigint(20) NOT NULL,
  `pin` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `balance` float NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `account_number`, `pin`, `first_name`, `last_name`, `mobile`, `address`, `email`, `username`, `pwd`, `balance`, `reg_date`) VALUES
(9, 381050, 5646, 'TestFname1', 'TestLname1', '777-777-7777', '12345 Test Street1', 'test1@test.com', 'TestUserName1', '9DpJB69dQ5Iwm8k=', 0, '2022-03-29 13:04:19'),
(10, 4870452, 4609, 'TestName2', 'TestLname2', '777-777-7777', '12345 Test Street2', 'test2@test.com', 'TestUserName2', '9DpJB69dQ5Iwm8k=', 0, '2022-03-29 13:04:44'),
(12, 9582095, 5901, 'TestFname3', 'TestLname3', '777-777-7777', '12345 Test Street3', 'test3@test.com', 'TestUserName3', '9DpJB69dQ5Iwm8k=', 0, '2022-03-29 13:23:36'),
(14, 245031, 4559, 'test', 'test', '777-777-7777', '12345 Test Street', 'test@test.com', 'test', '9DpJB69dQ5Iwm8k=', 0, '2022-03-29 13:25:03'),
(15, 5265256, 2405, 'TestName2', 'TestLastName2', '777-777-7777', '1234 Test', 'test@test.com7', 'test2', '4Vcq', 0, '2022-03-29 13:27:22'),
(16, 5526236, 334, '123', '123', '123', '123', '123@123', '123', '4Vcq', 0, '2022-03-30 09:06:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`,`email`,`username`),
  ADD UNIQUE KEY `account_number_2` (`account_number`,`email`,`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `account_number_3` (`account_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
