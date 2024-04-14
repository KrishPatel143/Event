-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2024 at 07:09 PM
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
-- Database: `event_system_database`
--
CREATE DATABASE IF NOT EXISTS `event_system_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_system_database`;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `device_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `name`, `description`, `device_date`, `user_id`) VALUES
(2, 'Smartphone X', 'Latest model smartphone with high-resolution camera.', '2024-02-15', 1),
(3, 'Graphics Tablet', 'High-resolution graphics tablet for designers.', '2024-03-11', 2),
(4, 'E-Reader Classic', 'E-reader with a classic book feel.', '2024-04-05', 3),
(6, 'abc', 'abc', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT 'London',
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `location`, `event_date`, `event_time`, `is_published`) VALUES
(1, 'Hackathon 2024', 'Join us for an exciting 24-hour coding event!', 'London', '2024-04-20', '09:00:00', 1),
(2, 'Tech Talk', 'A series of tech talks on emerging technologies.', 'London', '2024-05-15', '14:00:00', 1),
(3, 'Webinar on Cybersecurity', 'Learn about the latest in cybersecurity practices.', 'London', '2024-06-05', '11:00:00', 1),
(4, 'Virtual Coding Camp', 'A week-long virtual event to hone your coding skills.', 'London', '2024-07-01', '10:00:00', 1),
(5, 'AI Workshop', 'Hands-on AI workshop with industry experts.', 'London', '2024-08-20', '15:00:00', 0),
(6, 'Data Science Seminar', 'Explore the world of data science with our experts.', 'London', '2024-09-10', '13:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_device`
--

DROP TABLE IF EXISTS `event_device`;
CREATE TABLE `event_device` (
  `event_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_device`
--

INSERT INTO `event_device` (`event_id`, `device_id`) VALUES
(2, 6),
(3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `reg_date`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'hashed_password1', 'admin', '2024-04-14 10:21:01'),
(2, 'Jane Smith', 'jane.smith@example.com', 'hashed_password2', 'editor', '2024-04-14 10:21:01'),
(3, 'Alice Johnson', 'alice.johnson@example.com', 'hashed_password3', 'subscriber', '2024-04-14 10:21:01'),
(9, 'abc', 'abc@gmail.com', 'abc', 'Participant', '2024-04-14 15:05:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_device`
--
ALTER TABLE `event_device`
  ADD PRIMARY KEY (`event_id`,`device_id`),
  ADD KEY `device_id` (`device_id`);

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
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `event_device`
--
ALTER TABLE `event_device`
  ADD CONSTRAINT `event_device_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `event_device_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
