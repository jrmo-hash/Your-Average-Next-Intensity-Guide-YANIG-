-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql312.infinityfree.com
-- Generation Time: Feb 11, 2026 at 06:42 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41131473_user_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercise_recommendation`
--

CREATE TABLE `exercise_recommendation` (
  `recommendation_id` int(11) NOT NULL,
  `category` enum('Low','Normal','High') NOT NULL,
  `exercise_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercise_recommendation`
--

INSERT INTO `exercise_recommendation` (`recommendation_id`, `category`, `exercise_name`, `description`, `duration`) VALUES
(1, 'Low', 'Brisk Walking', 'Steady walk to stabilize heart rate.', '15 Mins'),
(2, 'Low', 'Yoga Stretch', 'Relaxing poses for recovery.', '10 Mins'),
(3, 'High', 'Sprinting', 'High intensity bursts for peak power.', '5 Mins'),
(4, 'High', 'Jumping Jacks', 'Fast-paced cardio for peak zone.', '8 Mins');

-- --------------------------------------------------------

--
-- Table structure for table `heart_data`
--

CREATE TABLE `heart_data` (
  `data_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `bpm` int(3) NOT NULL,
  `intensity` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `age` int(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_recommendation_history`
--

CREATE TABLE `user_recommendation_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recommendation_id` int(11) NOT NULL,
  `triggered_by_bpm` int(3) NOT NULL,
  `date_given` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercise_recommendation`
--
ALTER TABLE `exercise_recommendation`
  ADD PRIMARY KEY (`recommendation_id`);

--
-- Indexes for table `heart_data`
--
ALTER TABLE `heart_data`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `fk_heart_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_recommendation_history`
--
ALTER TABLE `user_recommendation_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_history_user` (`user_id`),
  ADD KEY `fk_history_rec` (`recommendation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercise_recommendation`
--
ALTER TABLE `exercise_recommendation`
  MODIFY `recommendation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `heart_data`
--
ALTER TABLE `heart_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_recommendation_history`
--
ALTER TABLE `user_recommendation_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `heart_data`
--
ALTER TABLE `heart_data`
  ADD CONSTRAINT `fk_heart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_recommendation_history`
--
ALTER TABLE `user_recommendation_history`
  ADD CONSTRAINT `fk_history_rec` FOREIGN KEY (`recommendation_id`) REFERENCES `exercise_recommendation` (`recommendation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
