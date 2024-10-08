-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 05:01 PM
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
-- Database: `user_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_name`, `image_file`) VALUES
(1, '11', 'uploads/11.jpg'),
(2, '12', 'uploads/12.jpeg'),
(3, '13', 'uploads/13.jpeg'),
(4, '14', 'uploads/14.jpg'),
(5, '15', 'uploads/15.jpg'),
(6, '16', 'uploads/16.JPG'),
(7, '17', 'uploads/17.jpg'),
(8, '18', 'uploads/18.JPG'),
(9, '19', 'uploads/19.jpg'),
(10, '20', 'uploads/20.JPG'),
(11, '21', 'uploads/21.jpg'),
(12, '22', 'uploads/22.jpg'),
(13, '23', 'uploads/23.JPG'),
(14, '24', 'uploads/24.JPG'),
(15, '25', 'uploads/25.JPG'),
(16, '26', 'uploads/26.jpg'),
(17, '27', 'uploads/27.jpeg'),
(18, '28', 'uploads/28.jpeg'),
(19, '29', 'uploads/29.jpg'),
(20, '30', 'uploads/30.jpg'),
(21, '31', 'uploads/31.jpg'),
(22, '32', 'uploads/32.JPG'),
(23, '33', 'uploads/33.JPG'),
(24, '34', 'uploads/34.jpg'),
(25, '35', 'uploads/35.jpg'),
(26, '36', 'uploads/36.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(3, 'locaz', 'wutubus@mailinator.com', '$2y$10$/gPa5DKGv8XnRKbtAA/Z8e59Zbz4d1w8pDFFYtQJ7uBFyrxGO2d2K'),
(4, 'zokize', 'fyxi@mailinator.com', '$2y$10$NXIygC.uzlRk02v6cVneMOntGLbT5KfMeM9VUXqXtSJy5UWKmccIq'),
(5, 'hunym', 'ciza@mailinator.com', '$2y$10$JXA/clxbwhU.RZrfwDRjf.Psjn/u2F5AmkIMrjY7NRDNoX2ulh57S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
