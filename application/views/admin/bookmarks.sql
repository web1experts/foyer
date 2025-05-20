-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2021 at 08:45 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookmarks`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `cmp_id` int(11) NOT NULL,
  `lang` enum('english','french','spanish') NOT NULL DEFAULT 'english',
  `cmp_text` varchar(250) DEFAULT NULL,
  `cmp_desc` longtext DEFAULT NULL,
  `cmp_image` varchar(200) DEFAULT NULL,
  `cmp_thumb` varchar(200) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`cmp_id`, `lang`, `cmp_text`, `cmp_desc`, `cmp_image`, `cmp_thumb`, `created_date`, `updated_at`) VALUES
(1, 'english', 'First slide label', 'Hi this is test message', 'a46d0874eaca88d3efdc9c00f9f20cd9.jpg', 'a46d0874eaca88d3efdc9c00f9f20cd9_thumb.jpg', '2020-12-24 11:37:54', '2021-12-28 06:37:25'),
(2, 'english', 'Second slide label', 'Hi, This is the test message.', '40b126101c0555d98905d5443aa86519.jpg', '40b126101c0555d98905d5443aa86519_thumb.jpg', '2020-12-24 11:38:15', '2021-12-28 06:02:43'),
(3, 'english', 'Quis qui quidem ea u', 'Voluptas dolorum et ', 'f1b47cdc7625e21d92f567afee304f7b.png', 'f1b47cdc7625e21d92f567afee304f7b_thumb.png', '2021-12-28 06:10:16', NULL),
(5, 'english', 'C Company', 'Quis qui quidem ea u', '1678f5ae780405db55fb45e416a2992f.jpg', '1678f5ae780405db55fb45e416a2992f_thumb.jpg', '2021-12-28 06:11:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`cmp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `cmp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
