-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 13. Nov 2015 um 12:23
-- Server-Version: 5.5.44-0ubuntu0.14.04.1
-- PHP-Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ss2015`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Acl`
--

CREATE TABLE IF NOT EXISTS `Acl` (
  `acl_id` int(6) NOT NULL,
  `location_id` int(6) NOT NULL,
  `acl_username` varchar(255) NOT NULL,
  `acl_password` varchar(255) NOT NULL,
  `acl_isAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Acl`
--

INSERT INTO `Acl` (`acl_id`, `location_id`, `acl_username`, `acl_password`, `acl_isAdmin`) VALUES
(1, 3, 'TestUser', '$2y$10$DphkeiPg6ciyg2sQu1UawuM3F0Y7YiYjaBIAPbGgPAK/c77A2PAWK', 1),
(6, 3, 'Spitz', '$2y$10$xpspWhTc3vIfs33/QSRwfuiiYWrNPxuw2lsJxeO4zbtP3euUdy.Za', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Gps`
--

CREATE TABLE IF NOT EXISTS `Gps` (
  `gps_id` int(6) NOT NULL,
  `gps_long` double(22,20) NOT NULL,
  `gps_lat` double(22,20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Gps`
--

INSERT INTO `Gps` (`gps_id`, `gps_long`, `gps_lat`) VALUES
(27, 12.13124131400000000000, -12.12310000000000000000),
(28, -45.12124124000000000000, 45.12312120000000000000),
(29, 12.12234124100000000000, 0.00000000000000000000),
(30, 12.12234124100000000000, 0.00000000000000000000),
(31, 12.12234124100000000000, 41.12312354100000000000),
(32, 12.12234124100000000000, 0.00000000000000000000),
(33, 12.12234124100000000000, 41.12312354100000000000),
(34, 12.12234124100000000000, 0.00000000000000000000),
(35, 12.12234124100000000000, 41.12312354100000000000),
(36, 12.12234124100000000000, 41.12312354100000000000),
(37, 12.12234124100000000000, 41.12312354100000000000),
(38, 12.34567890000000000000, 9.87651560000000000000),
(39, 45.64645334567900000000, -76.56776567876500000000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Location`
--

CREATE TABLE IF NOT EXISTS `Location` (
  `location_id` int(6) NOT NULL,
  `location_name` varchar(255) NOT NULL COMMENT 'Name of location',
  `location_url` varchar(255) DEFAULT NULL,
  `location_banner_url` varchar(255) DEFAULT NULL,
  `location_map_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Location`
--

INSERT INTO `Location` (`location_id`, `location_name`, `location_url`, `location_banner_url`, `location_map_url`) VALUES
(3, 'Spitzkoppe', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Media`
--

CREATE TABLE IF NOT EXISTS `Media` (
  `media_id` int(6) NOT NULL,
  `poi_id` int(6) NOT NULL,
  `media_name` varchar(255) DEFAULT NULL,
  `media_content` varchar(10000) DEFAULT NULL,
  `media_type` varchar(50) NOT NULL,
  `media_pagenumber` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Media`
--

INSERT INTO `Media` (`media_id`, `poi_id`, `media_name`, `media_content`, `media_type`, `media_pagenumber`) VALUES
(13, 27, 'Database', 'http://localhost/ss2015/upload/770274bef2705960a30230ad941d0eae.png', 'image/png', 1),
(14, 27, 'Databse-SQL', 'http://localhost/ss2015/upload/d1380bacf8f215ca9c895ba64120f975.pdf', 'application/pdf', 0),
(15, 27, 'Text', 'Long text can be made herekadkkandsndnkandkjnasjdjadsjndkjnajdsnkjfnasndknkjfabjsdkjbfasjbdkjbfabskdbkjfajflka', 'text/plain', 0),
(16, 37, 'NKbk', 'test', 'text/plain', 1),
(17, 38, 'vgf', 'http://localhost/ss2015/upload/d1956fcfde8aee841b40b287c78d9959.pdf', 'application/pdf', 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Poi`
--

CREATE TABLE IF NOT EXISTS `Poi` (
  `poi_id` int(6) NOT NULL,
  `location_id` int(6) NOT NULL,
  `gps_id` int(6) NOT NULL,
  `poi_name` varchar(255) NOT NULL,
  `poi_description` varchar(1000) DEFAULT NULL,
  `poi_orientation` double(5,2) DEFAULT NULL,
  `poi_autoPlayMedia` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Poi`
--

INSERT INTO `Poi` (`poi_id`, `location_id`, `gps_id`, `poi_name`, `poi_description`, `poi_orientation`, `poi_autoPlayMedia`) VALUES
(27, 3, 28, 'Kassungs-POI', 'Whatever the description is', 270.00, 1),
(36, 3, 37, 'POI', 'POIPOIOIOI', 0.00, 1),
(37, 3, 38, 'POIOIO', 'OIOIOIO', 0.00, 1),
(38, 3, 39, 'ujn', 'zhhu', 77.26, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Acl`
--
ALTER TABLE `Acl`
  ADD PRIMARY KEY (`acl_id`),
  ADD UNIQUE KEY `acl_username` (`acl_username`),
  ADD KEY `location_id_foreignKey` (`acl_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indizes für die Tabelle `Gps`
--
ALTER TABLE `Gps`
  ADD PRIMARY KEY (`gps_id`);

--
-- Indizes für die Tabelle `Location`
--
ALTER TABLE `Location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indizes für die Tabelle `Media`
--
ALTER TABLE `Media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `poi_id` (`poi_id`);

--
-- Indizes für die Tabelle `Poi`
--
ALTER TABLE `Poi`
  ADD PRIMARY KEY (`poi_id`),
  ADD KEY `foreign_keys` (`location_id`,`gps_id`) USING BTREE,
  ADD KEY `poi_to_gps` (`gps_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Acl`
--
ALTER TABLE `Acl`
  MODIFY `acl_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `Gps`
--
ALTER TABLE `Gps`
  MODIFY `gps_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT für Tabelle `Location`
--
ALTER TABLE `Location`
  MODIFY `location_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `Media`
--
ALTER TABLE `Media`
  MODIFY `media_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT für Tabelle `Poi`
--
ALTER TABLE `Poi`
  MODIFY `poi_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Acl`
--
ALTER TABLE `Acl`
  ADD CONSTRAINT `acl_to_location` FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `Poi`
--
ALTER TABLE `Poi`
  ADD CONSTRAINT `poi_to_gps` FOREIGN KEY (`gps_id`) REFERENCES `Gps` (`gps_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `poi_to_location` FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
