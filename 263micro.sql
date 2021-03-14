-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2021 at 03:15 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `263micro`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_token`
--

CREATE TABLE `auth_token` (
  `id` int(11) NOT NULL,
  `user` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_token`
--

INSERT INTO `auth_token` (`id`, `user`, `token`, `date_created`) VALUES
(1, 'tttttttt44444444444111111111fffffaabbxbdgdddd1134sdf', 'rrrrrrrrrrrrrrrrrrrttttttttttttttttnnnnnnnnnnnnnnnnnnn1', '2021-03-13 00:13:18'),
(4, 'd56dc50b12912c6be76a8b7bc5d3c7d7', '7d7c3d5cb7b8a67eb6c21921b05cd65d', '2021-03-13 01:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL,
  `account_num` varchar(30) NOT NULL,
  `amount_owed` varchar(16) NOT NULL,
  `date_blacklisted` datetime NOT NULL,
  `isCleared` tinyint(1) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blacklist`
--

INSERT INTO `blacklist` (`id`, `account_num`, `amount_owed`, `date_blacklisted`, `isCleared`) VALUES
(1, '1575021111340', '1437284', '2021-03-13 10:27:09', 0),
(2, '1167439683935', '33190', '2021-03-14 10:21:30', 0),
(3, '1680322349833', '4389119', '2021-03-14 10:22:49', 0),
(4, '1789381160092', '2350000', '2021-03-14 10:24:04', 0),
(5, '[object HTMLDivElement]', '6340043', '2021-03-14 14:34:03', 0),
(6, '[object HTMLDivElement]', '3397342', '2021-03-14 14:35:10', 0),
(7, '[object HTMLDivElement]', '5421355', '2021-03-14 14:35:53', 0),
(8, '[object HTMLDivElement]', '3776252', '2021-03-14 14:37:05', 0),
(9, '[object HTMLDivElement]', '2194913', '2021-03-14 14:44:08', 0),
(10, '1384372825066', '3592391', '2021-03-14 14:45:54', 0),
(11, '1114597232351', '2828505', '2021-03-14 14:47:05', 0),
(12, '1790234387265', '6832331', '2021-03-14 14:48:07', 0),
(13, '1790234387265', '3360510', '2021-03-14 14:49:31', 0),
(14, '1586731792682', '3977690', '2021-03-14 15:04:56', 0),
(15, '1405180693614', '6401034', '2021-03-14 15:05:16', 0),
(16, '1616339450447', '4818487', '2021-03-14 15:05:30', 0),
(17, '1139110584735', '3347445', '2021-03-14 15:05:47', 0),
(18, '1353654957497', '1936132', '2021-03-14 15:06:05', 0),
(19, '1153215845944', '180994', '2021-03-14 15:08:04', 0),
(20, '1369995336938', '182506', '2021-03-14 15:09:16', 0),
(21, '1477582941062', '2976735', '2021-03-14 15:09:39', 0),
(22, '1231202249763', '1262604', '2021-03-14 15:10:26', 0),
(23, '1091100427453', '4220805', '2021-03-14 15:11:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `blacklist_transactions`
--

CREATE TABLE `blacklist_transactions` (
  `id` int(11) NOT NULL,
  `blacklist_id` int(11) NOT NULL,
  `account_num` varchar(20) NOT NULL,
  `paid_amount` varchar(30) NOT NULL,
  `date_paid` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blacklist_transactions`
--

INSERT INTO `blacklist_transactions` (`id`, `blacklist_id`, `account_num`, `paid_amount`, `date_paid`) VALUES
(1, 1, '1575021111340', '256', '2021-03-14 13:24:11'),
(2, 4, '1789381160092', '293', '2021-03-14 13:25:11');

-- --------------------------------------------------------

--
-- Table structure for table `client_account`
--

CREATE TABLE `client_account` (
  `id` int(11) NOT NULL,
  `account_num` varchar(30) NOT NULL,
  `account_name` varchar(50) NOT NULL,
  `isBusiness` tinyint(1) NOT NULL,
  `institution` int(11) NOT NULL,
  `manager` varchar(20) NOT NULL,
  `date_blacklisted` date NOT NULL,
  `blacklist_id` int(11) NOT NULL,
  `isBlacklisted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_account`
--

INSERT INTO `client_account` (`id`, `account_num`, `account_name`, `isBusiness`, `institution`, `manager`, `date_blacklisted`, `blacklist_id`, `isBlacklisted`) VALUES
(5, '1575021111340', 'hazel marksman', 0, 1, '263microfinance', '2021-03-13', 1, 1),
(8, '1384372825066', 'Lawrence Mikes', 0, 1, '263microfinance', '2021-03-14', 10, 1),
(9, '1359280200370', 'Thomas Makesi', 0, 2, '263microfinance', '0000-00-00', 0, 1),
(10, '1114597232351', 'Honest Thieves inc.', 1, 2, '263microfinance', '2021-03-14', 11, 1),
(11, '1575156152578', 'Columns wider.', 1, 3, '263microfinance', '0000-00-00', 0, 0),
(12, '1992828378473', 'Blue places', 1, 0, '263microfinance', '0000-00-00', 0, 1),
(13, '1746125235697', 'NOAH Arks', 0, 2, '263microfinance', '0000-00-00', 0, 0),
(15, '1065868235442', 'WIFI Places', 1, 2, '263microfinance', '0000-00-00', 0, 0),
(18, '1790234387265', 'WEB Queens.', 1, 2, '263microfinance', '2021-03-14', 13, 0),
(25, '1789381160092', 'HOLLOW NESTS', 1, 0, '263microfinance', '2021-03-14', 4, 1),
(26, '1586731792682', 'Oracle', 1, 3, '263microfinance', '2021-03-14', 14, 1),
(27, '1405180693614', 'Calendars.home', 1, 2, '263microfinance', '2021-03-14', 15, 1),
(28, '1616339450447', 'Samsons', 1, 1, '263microfinance', '2021-03-14', 16, 1),
(29, '1139110584735', 'Ronald', 1, 3, '263microfinance', '2021-03-14', 17, 1),
(30, '1353654957497', 'Costals', 1, 3, '263microfinance', '2021-03-14', 18, 1),
(31, '1153215845944', 'Developers', 1, 2, '263microfinance', '2021-03-14', 19, 1),
(32, '1369995336938', 'PHP_HOME', 1, 3, '263microfinance', '2021-03-14', 20, 1),
(33, '1477582941062', 'DOLLARS', 1, 3, '263microfinance', '2021-03-14', 21, 1),
(34, '1231202249763', 'DELL', 1, 2, '263microfinance', '2021-03-14', 22, 1),
(35, '1091100427453', 'TOWELS', 1, 2, '263microfinance', '2021-03-14', 23, 1);

-- --------------------------------------------------------

--
-- Table structure for table `institution`
--

CREATE TABLE `institution` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `institution`
--

INSERT INTO `institution` (`id`, `name`) VALUES
(1, 'HzM'),
(2, '263 Microfinance'),
(3, 'Lofters');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(9) NOT NULL,
  `uid` varchar(250) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `isManager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uid`, `first_name`, `last_name`, `email`, `username`, `password`, `isManager`) VALUES
(1, 'tttttttt44444444444111111111fffffaabbxbdgdddd1134sdf', 'Ronald', 'Ngwenya', '1999roniengwe@gmail.com', 'Ronnie', 'bef6db60202e35d43117b02ee7cbb120', 1),
(10, 'd56dc50b12912c6be76a8b7bc5d3c7d7', 'Koketso', 'Mandoza', 'Koketso@gmail.com', 'Ketso', '1b0fdf9518d53d5e0561f871bc43ae2e', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blacklist_transactions`
--
ALTER TABLE `blacklist_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_account`
--
ALTER TABLE `client_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_num` (`account_num`);

--
-- Indexes for table `institution`
--
ALTER TABLE `institution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_token`
--
ALTER TABLE `auth_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `blacklist_transactions`
--
ALTER TABLE `blacklist_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `client_account`
--
ALTER TABLE `client_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `institution`
--
ALTER TABLE `institution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
