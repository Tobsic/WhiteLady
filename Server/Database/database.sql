-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 22, 2015 at 11:21 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

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
  `acl_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Gps`
--

CREATE TABLE IF NOT EXISTS `Gps` (
  `gps_id` int(6) NOT NULL,
  `gps_long` double(21,20) NOT NULL,
  `gps_lat` double(21,20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Location`
--

CREATE TABLE IF NOT EXISTS `Location` (
  `location_id` int(6) NOT NULL,
  `location_name` varchar(255) NOT NULL COMMENT 'Name of location'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Media`
--

CREATE TABLE IF NOT EXISTS `Media` (
  `media_id` int(6) NOT NULL,
  `media_name` varchar(255) DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `media_text` varchar(10000) DEFAULT NULL,
  `media_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Poi`
--

CREATE TABLE IF NOT EXISTS `Poi` (
  `poi_id` int(6) NOT NULL,
  `location_id` int(6) NOT NULL,
  `media_id` int(6) NOT NULL,
  `gps_id` int(6) NOT NULL,
  `poi_type` varchar(255) NOT NULL,
  `poi_name` varchar(255) NOT NULL,
  `poi_description` varchar(1000) DEFAULT NULL,
  `orientation` double(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `Poi`
--
ALTER TABLE `Poi`
  ADD PRIMARY KEY (`poi_id`),
  ADD KEY `foreign_keys` (`location_id`,`media_id`,`gps_id`) USING BTREE,
  ADD KEY `poi_to_media` (`media_id`),
  ADD KEY `poi_to_gps` (`gps_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Acl`
--
ALTER TABLE `Acl`
  MODIFY `acl_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Gps`
--
ALTER TABLE `Gps`
  MODIFY `gps_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Location`
--
ALTER TABLE `Location`
  MODIFY `location_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Media`
--
ALTER TABLE `Media`
  MODIFY `media_id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Poi`
--
ALTER TABLE `Poi`
  MODIFY `poi_id` int(6) NOT NULL AUTO_INCREMENT;
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
  ADD CONSTRAINT `poi_to_location` FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `poi_to_media` FOREIGN KEY (`media_id`) REFERENCES `Media` (`media_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
