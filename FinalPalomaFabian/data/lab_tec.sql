-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 24, 2017 at 07:33 AM
-- Server version: 5.6.34-log
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_tec`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendant_wannabe`
--

CREATE TABLE IF NOT EXISTS `attendant_wannabe` (
  `attendant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendant_wannabe`
--

INSERT INTO `attendant_wannabe` (`attendant_id`) VALUES
(12);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed`
--

CREATE TABLE IF NOT EXISTS `borrowed` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `professor` varchar(100) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `borrowed`
--

INSERT INTO `borrowed` (`request_id`, `user_id`, `professor`, `start_date`, `end_date`, `status_id`) VALUES
(43, 14, '', '2017-04-23 00:00:00', '2017-05-05 00:00:00', 3),
(44, 11, '', '2017-04-24 00:00:00', '2017-04-24 00:00:00', 2),
(45, 11, '', '2017-04-24 00:00:00', '2017-04-24 00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_status`
--

CREATE TABLE IF NOT EXISTS `borrowed_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `borrowed_status`
--

INSERT INTO `borrowed_status` (`status_id`, `status`) VALUES
(1, 'ON TIME'),
(2, 'DELAYED'),
(3, 'PENDING'),
(4, 'CANCELLED'),
(5, 'RETURNED');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `laboratory_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `material_id`, `quantity`, `request_id`, `laboratory_id`) VALUES
(39, 6, 1, 44, 2),
(40, 6, 1, 45, 2);

-- --------------------------------------------------------

--
-- Table structure for table `catalog`
--

CREATE TABLE IF NOT EXISTS `catalog` (
  `material_id` int(11) NOT NULL,
  `material` varchar(100) NOT NULL,
  `material_type_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `additional_info` text,
  `laboratory_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catalog`
--

INSERT INTO `catalog` (`material_id`, `material`, `material_type_id`, `total`, `available`, `additional_info`, `laboratory_id`) VALUES
(5, 'Multimeter', 1, 25, 35, '', 2),
(6, 'Resistor 1.4 kOhm', 2, 100, 98, '', 2),
(7, 'Resistor 100k', 2, 100, 100, '', 2),
(8, 'Small Locker', 0, 10, 10, 'We provide you with a padlock.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `degree_programs`
--

CREATE TABLE IF NOT EXISTS `degree_programs` (
  `degree_program_id` int(11) NOT NULL,
  `degree_program` varchar(100) NOT NULL,
  `degree_program_acronym` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `degree_programs`
--

INSERT INTO `degree_programs` (`degree_program_id`, `degree_program`, `degree_program_acronym`) VALUES
(0, 'Other', 'Other'),
(1, 'B.S. Biomedical Engineering', 'IMD'),
(2, 'B.S. Business Informatics', 'INT'),
(3, 'B.S. Computer Science and Technology', 'ITC'),
(4, 'B.S. Digital Systems and Robotics Engineering', 'ISD'),
(5, 'B.S. Electronic and Computer Engineering', 'ITE');

-- --------------------------------------------------------

--
-- Table structure for table `laboratories`
--

CREATE TABLE IF NOT EXISTS `laboratories` (
  `laboratory_id` int(11) NOT NULL,
  `laboratory_location` varchar(25) NOT NULL,
  `attendant_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `laboratories`
--

INSERT INTO `laboratories` (`laboratory_id`, `laboratory_location`, `attendant_id`) VALUES
(2, 'A4-438', 12),
(3, 'A4-331', 14);

-- --------------------------------------------------------

--
-- Table structure for table `material_types`
--

CREATE TABLE IF NOT EXISTS `material_types` (
  `material_type_id` int(11) NOT NULL,
  `material_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `material_types`
--

INSERT INTO `material_types` (`material_type_id`, `material_type`) VALUES
(0, 'Locker'),
(1, 'Equipment'),
(2, 'Electronic components');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_status_id` int(11) NOT NULL,
  `notification_status` int(11) NOT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `request_status_id`, `notification_status`, `request_id`) VALUES
(11, 11, 1, 1, 44);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `request_id` int(11) NOT NULL,
  `material` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `laboratory_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status_id` int(11) NOT NULL,
  `additional_information` text
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `material`, `quantity`, `user_id`, `laboratory_id`, `date`, `status_id`, `additional_information`) VALUES
(3, 'OSCILOSCOPE', 1, 11, 2, '2017-04-24 01:53:29', 1, ''),
(4, 'RESISTOR', 1, 11, 2, '2017-04-24 01:56:08', 1, 'A'),
(5, 'guiso', 1, 11, 2, '2017-04-24 02:20:15', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `request_status`
--

CREATE TABLE IF NOT EXISTS `request_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request_status`
--

INSERT INTO `request_status` (`status_id`, `status`) VALUES
(1, 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `degree_program_id` int(11) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `creation` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`, `degree_program_id`, `user_type_id`, `creation`) VALUES
(11, 'Jane', 'Doe', 'A00000000', 'A00000000@itesm.mx', 'Rv3eUolUuyygm187U7xTKN7a6V0m/A9WIDr/k4jbd3A=', 2, 1, '2017-04-23 23:25:26'),
(12, 'John ', 'Doe', 'L00000000', 'john.doe@itesm.mx', 'PVl1GGMfFib2mAfbII0ywQakHi5lxGF8iX/USHJsdvs=', 0, 3, '2017-04-23 23:26:50'),
(13, 'Peter', 'Doe', 'L00000001', 'peter.doe@itesm.mx', 'qx7QG+IsH2M5KKB8tzkdTwhP+8mRwUnosxKHwR0D5UY=', 0, 2, '2017-04-23 23:35:08'),
(14, 'Joe', 'Doe', 'L00000002', 'joe.doe@itesm.mx', 'fgQkfEXC2RL0If1b9Se2ILh/ofB8kxSWQNcSySJXfqs=', 0, 3, '2017-04-23 23:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `user_type_id` int(11) NOT NULL,
  `user_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`user_type_id`, `user_type`) VALUES
(0, 'Administrator'),
(1, 'Student'),
(2, 'Professor'),
(3, 'Attendant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendant_wannabe`
--
ALTER TABLE `attendant_wannabe`
  ADD PRIMARY KEY (`attendant_id`);

--
-- Indexes for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `professor_id` (`professor`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `borrowed_status`
--
ALTER TABLE `borrowed_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `laboratory_id` (`laboratory_id`);

--
-- Indexes for table `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `material_type_id` (`material_type_id`),
  ADD KEY `location` (`laboratory_id`);

--
-- Indexes for table `degree_programs`
--
ALTER TABLE `degree_programs`
  ADD PRIMARY KEY (`degree_program_id`);

--
-- Indexes for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD PRIMARY KEY (`laboratory_id`),
  ADD KEY `attendant_id` (`attendant_id`);

--
-- Indexes for table `material_types`
--
ALTER TABLE `material_types`
  ADD PRIMARY KEY (`material_type_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `request_status_id` (`request_status_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `laboratory_id` (`laboratory_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `request_status`
--
ALTER TABLE `request_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `degree_program_id` (`degree_program_id`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed`
--
ALTER TABLE `borrowed`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `borrowed_status`
--
ALTER TABLE `borrowed_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `catalog`
--
ALTER TABLE `catalog`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `laboratories`
--
ALTER TABLE `laboratories`
  MODIFY `laboratory_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `request_status`
--
ALTER TABLE `request_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendant_wannabe`
--
ALTER TABLE `attendant_wannabe`
  ADD CONSTRAINT `attendant_wannabe_ibfk_1` FOREIGN KEY (`attendant_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD CONSTRAINT `borrowed_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `borrowed_ibfk_5` FOREIGN KEY (`status_id`) REFERENCES `borrowed_status` (`status_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `catalog` (`material_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `borrowed` (`request_id`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`laboratory_id`);

--
-- Constraints for table `catalog`
--
ALTER TABLE `catalog`
  ADD CONSTRAINT `catalog_ibfk_1` FOREIGN KEY (`material_type_id`) REFERENCES `material_types` (`material_type_id`),
  ADD CONSTRAINT `catalog_ibfk_2` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`laboratory_id`);

--
-- Constraints for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD CONSTRAINT `laboratories_ibfk_1` FOREIGN KEY (`attendant_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`request_status_id`) REFERENCES `borrowed_status` (`status_id`),
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`request_id`) REFERENCES `borrowed` (`request_id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`laboratory_id`),
  ADD CONSTRAINT `requests_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `request_status` (`status_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`degree_program_id`) REFERENCES `degree_programs` (`degree_program_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_type_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
