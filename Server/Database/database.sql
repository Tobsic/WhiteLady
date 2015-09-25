-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2015 at 10:38 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ss2015`
--

-- --------------------------------------------------------

--
-- Table structure for table `Acl`
--

CREATE TABLE IF NOT EXISTS `Acl` (
  `acl_id` int(6) NOT NULL,
  `location_id` int(6) NOT NULL,
  `acl_username` varchar(255) NOT NULL,
  `acl_password` varchar(255) NOT NULL,
  `acl_isAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Acl`
--

INSERT INTO `Acl` (`acl_id`, `location_id`, `acl_username`, `acl_password`, `acl_isAdmin`) VALUES
(1, 3, 'TestUser', '$2y$10$DphkeiPg6ciyg2sQu1UawuM3F0Y7YiYjaBIAPbGgPAK/c77A2PAWK', 1),
(6, 3, 'Spitz', '$2y$10$xpspWhTc3vIfs33/QSRwfuiiYWrNPxuw2lsJxeO4zbtP3euUdy.Za', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Gps`
--

CREATE TABLE IF NOT EXISTS `Gps` (
  `gps_id` int(6) NOT NULL,
  `gps_long` double(22,20) NOT NULL,
  `gps_lat` double(22,20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Gps`
--

INSERT INTO `Gps` (`gps_id`, `gps_long`, `gps_lat`) VALUES
(2, 22.44000000000000000000, 55.33000000000000000000),
(3, 12.57368767191263500000, 32.76876187687168000000),
(4, 12.34000000000000000000, 56.78000000000000000000),
(5, 12.34000000000000000000, 56.78000000000000000000);

-- --------------------------------------------------------

--
-- Table structure for table `Location`
--

CREATE TABLE IF NOT EXISTS `Location` (
  `location_id` int(6) NOT NULL,
  `location_name` varchar(255) NOT NULL COMMENT 'Name of location',
  `location_url` varchar(255) DEFAULT NULL,
  `location_banner_url` varchar(255) DEFAULT NULL,
  `location_map_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Location`
--

INSERT INTO `Location` (`location_id`, `location_name`, `location_url`, `location_banner_url`, `location_map_url`) VALUES
(3, 'Spitzkoppe', NULL, NULL, NULL),
(4, 'Brandberg', NULL, NULL, NULL),
(5, 'Brandberg', NULL, NULL, NULL),
(6, 'TestLocation', NULL, NULL, NULL),
(7, 'Brandberg', NULL, NULL, NULL),
(8, 'Brandberg', NULL, NULL, NULL),
(9, 'Brandberg', NULL, NULL, NULL),
(10, 'Brandberg', NULL, NULL, NULL),
(11, 'Brandberg', NULL, NULL, NULL),
(12, 'Spitzkoppe', NULL, NULL, NULL),
(13, 'Spitzkoppe', NULL, NULL, NULL),
(14, 'Spitzkoppe', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Media`
--

CREATE TABLE IF NOT EXISTS `Media` (
  `media_id` int(6) NOT NULL,
  `poi_id` int(6) NOT NULL,
  `media_name` varchar(255) DEFAULT NULL,
  `media_content` varchar(10000) DEFAULT NULL,
  `media_type` varchar(50) NOT NULL,
  `media_pagenumber` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Media`
--

INSERT INTO `Media` (`media_id`, `poi_id`, `media_name`, `media_content`, `media_type`, `media_pagenumber`) VALUES
(1, 0, 'TestMedia', 'Some sample Text!', 'text/plain', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Poi`
--

CREATE TABLE IF NOT EXISTS `Poi` (
  `poi_id` int(6) NOT NULL,
  `location_id` int(6) NOT NULL,
  `gps_id` int(6) NOT NULL,
  `poi_name` varchar(255) NOT NULL,
  `poi_description` varchar(1000) DEFAULT NULL,
  `orientation` double(3,2) DEFAULT NULL,
  `autoPlayMedia` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Poi`
--

INSERT INTO `Poi` (`poi_id`, `location_id`, `gps_id`, `poi_name`, `poi_description`, `orientation`, `autoPlayMedia`) VALUES
(1, 3, 2, 'TestPoi', 'Here is a test poi', NULL, 0),
(6, 3, 2, 'TestPoi2', 'Description', NULL, 0),
(7, 3, 2, 'TestPoi2', 'Description', NULL, 0),
(8, 3, 2, 'TestPoi2', 'Description', NULL, 0),
(9, 3, 2, 'TestPoi2', 'Description', NULL, 0),
(10, 3, 2, 'TestPoi2', 'Description', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Acl`
--
ALTER TABLE `Acl`
  ADD PRIMARY KEY (`acl_id`),
  ADD UNIQUE KEY `acl_username` (`acl_username`),
  ADD KEY `location_id_foreignKey` (`acl_id`,`location_id`),
  ADD KEY `acl_to_location` (`location_id`);

--
-- Indexes for table `Gps`
--
ALTER TABLE `Gps`
  ADD PRIMARY KEY (`gps_id`);

--
-- Indexes for table `Location`
--
ALTER TABLE `Location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `Media`
--
ALTER TABLE `Media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `poi_id` (`poi_id`);

--
-- Indexes for table `Poi`
--
ALTER TABLE `Poi`
  ADD PRIMARY KEY (`poi_id`),
  ADD KEY `foreign_keys` (`location_id`,`gps_id`) USING BTREE,
  ADD KEY `poi_to_gps` (`gps_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Acl`
--
ALTER TABLE `Acl`
  MODIFY `acl_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Gps`
--
ALTER TABLE `Gps`
  MODIFY `gps_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Location`
--
ALTER TABLE `Location`
  MODIFY `location_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `Media`
--
ALTER TABLE `Media`
  MODIFY `media_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Poi`
--
ALTER TABLE `Poi`
  MODIFY `poi_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Acl`
--
ALTER TABLE `Acl`
  ADD CONSTRAINT `acl_to_location` FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Poi`
--
ALTER TABLE `Poi`
  ADD CONSTRAINT `poi_to_gps` FOREIGN KEY (`gps_id`) REFERENCES `Gps` (`gps_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `poi_to_location` FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
