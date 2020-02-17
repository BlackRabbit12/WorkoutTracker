-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 17, 2020 at 04:04 PM
-- Server version: 10.1.44-MariaDB-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cdrennan_workout_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `day_plan`
--

CREATE TABLE `day_plan` (
  `day_plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `muscle_group`
--

CREATE TABLE `muscle_group` (
  `muscle_group_id` int(11) NOT NULL,
  `muscle_group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `handle` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `isPro` tinyint(1) NOT NULL DEFAULT '0',
  `membership_end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workout`
--

CREATE TABLE `workout` (
  `workout_id` int(11) NOT NULL,
  `workout_name` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workout_log`
--

CREATE TABLE `workout_log` (
  `workout_log_id` int(11) NOT NULL,
  `day_plan_id` int(11) NOT NULL,
  `workout_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `repetitions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workout_muscle_group`
--

CREATE TABLE `workout_muscle_group` (
  `workout_id` int(11) NOT NULL,
  `muscle_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `day_plan`
--
ALTER TABLE `day_plan`
  ADD PRIMARY KEY (`day_plan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `muscle_group`
--
ALTER TABLE `muscle_group`
  ADD PRIMARY KEY (`muscle_group_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `workout`
--
ALTER TABLE `workout`
  ADD PRIMARY KEY (`workout_id`);

--
-- Indexes for table `workout_log`
--
ALTER TABLE `workout_log`
  ADD PRIMARY KEY (`workout_log_id`),
  ADD KEY `day_plan_id` (`day_plan_id`),
  ADD KEY `workout_id` (`workout_id`);

--
-- Indexes for table `workout_muscle_group`
--
ALTER TABLE `workout_muscle_group`
  ADD PRIMARY KEY (`workout_id`,`muscle_group_id`),
  ADD KEY `workout_id` (`workout_id`),
  ADD KEY `muscle_group_id` (`muscle_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day_plan`
--
ALTER TABLE `day_plan`
  MODIFY `day_plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `muscle_group`
--
ALTER TABLE `muscle_group`
  MODIFY `muscle_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workout`
--
ALTER TABLE `workout`
  MODIFY `workout_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workout_log`
--
ALTER TABLE `workout_log`
  MODIFY `workout_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `day_plan`
--
ALTER TABLE `day_plan`
  ADD CONSTRAINT `day_plan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `workout_log`
--
ALTER TABLE `workout_log`
  ADD CONSTRAINT `workout_log_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workout` (`workout_id`),
  ADD CONSTRAINT `workout_log_ibfk_2` FOREIGN KEY (`day_plan_id`) REFERENCES `day_plan` (`day_plan_id`);

--
-- Constraints for table `workout_muscle_group`
--
ALTER TABLE `workout_muscle_group`
  ADD CONSTRAINT `workout_muscle_group_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workout` (`workout_id`),
  ADD CONSTRAINT `workout_muscle_group_ibfk_2` FOREIGN KEY (`muscle_group_id`) REFERENCES `muscle_group` (`muscle_group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
