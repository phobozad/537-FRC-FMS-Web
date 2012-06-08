-- phpMyAdmin SQL Dump
-- version 3.3.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2012 at 08:44 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fms`
--

-- --------------------------------------------------------

--
-- Table structure for table `matchresult`
--

CREATE TABLE IF NOT EXISTS `matchresult` (
  `redRawScore` int(11) NOT NULL,
  `blueRawScore` int(11) NOT NULL,
  `matchNum` int(11) NOT NULL,
  `matchType` enum('P','Q','E') COLLATE utf8_bin NOT NULL,
  `redBonus` int(11) NOT NULL,
  `blueBonus` int(11) NOT NULL,
  PRIMARY KEY (`matchNum`,`matchType`),
  KEY `matchType` (`matchType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `matchtime`
--

CREATE TABLE IF NOT EXISTS `matchtime` (
  `matchTime` datetime NOT NULL,
  `matchType` enum('P','Q','E') COLLATE utf8_bin NOT NULL,
  `matchNum` int(11) NOT NULL,
  `matchComplete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`matchType`,`matchNum`),
  KEY `matchNum` (`matchNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `teamNum` int(11) NOT NULL,
  `teamName` text COLLATE utf8_bin NOT NULL,
  `teamHometown` text COLLATE utf8_bin NOT NULL,
  `teamRookieYear` int(11) NOT NULL,
  `teamSponsers` longtext COLLATE utf8_bin NOT NULL,
  `teamSchool` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`teamNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `teamresult`
--

CREATE TABLE IF NOT EXISTS `teamresult` (
  `matchType` enum('P','Q','E') COLLATE utf8_bin NOT NULL,
  `matchNum` int(11) NOT NULL,
  `teamNum` int(11) NOT NULL,
  `teamColor` char(1) COLLATE utf8_bin NOT NULL,
  `teamPosition` int(11) NOT NULL,
  `teamPenalty` int(11) DEFAULT NULL,
  `teamRedCard` int(11) DEFAULT NULL,
  `teamYellowCard` int(11) DEFAULT NULL,
  PRIMARY KEY (`matchType`,`matchNum`,`teamNum`),
  KEY `teamNum` (`teamNum`),
  KEY `matchType` (`matchType`),
  KEY `matchNum` (`matchNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matchresult`
--
ALTER TABLE `matchresult`
  ADD CONSTRAINT `matchresult_ibfk_6` FOREIGN KEY (`matchType`) REFERENCES `matchtime` (`matchType`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matchresult_ibfk_5` FOREIGN KEY (`matchNum`) REFERENCES `matchtime` (`matchNum`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teamresult`
--
ALTER TABLE `teamresult`
  ADD CONSTRAINT `teamresult_ibfk_11` FOREIGN KEY (`matchType`) REFERENCES `matchtime` (`matchType`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teamresult_ibfk_10` FOREIGN KEY (`teamNum`) REFERENCES `team` (`teamNum`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `teamresult_ibfk_9` FOREIGN KEY (`matchNum`) REFERENCES `matchtime` (`matchNum`) ON DELETE CASCADE ON UPDATE CASCADE;
