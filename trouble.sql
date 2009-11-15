-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 15, 2009 at 08:57 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trouble`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `course` varchar(255) NOT NULL,
  `societies` text NOT NULL,
  `clubs` text NOT NULL,
  `timetable` text NOT NULL,
  `kill_count` int(11) NOT NULL,
  `death_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `name`, `alias`, `email`, `phone`, `address`, `course`, `societies`, `clubs`, `timetable`, `kill_count`, `death_count`) VALUES
(1, 'James Cleveland', 'twat', 'jamescleveland@gmail.com', '07865998857', '30 Wokingham Road\r\nReading\r\nRG6 1JH', 'Computer Science & Cybernetics (BSc)', 'Indie Society\r\nVideo Game Society', 'Iguana\r\nUpin Arms', 'Blahh', 0, 0),
(2, 'Amy Garrett', 'Amycus', 'amycus@hotmail.com', '0223209382', 'Adasdasd', 'Classical Studies (BSc)', 'None', 'Upin Arms\r\nDogma', 'Latin and stuff', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `signup_date` datetime NOT NULL,
  `location` text NOT NULL,
  `victor` int(11) NOT NULL,
  `description` text NOT NULL,
  `finalised` tinyint(4) NOT NULL,
  `entry_fee` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `start_date`, `end_date`, `signup_date`, `location`, `victor`, `description`, `finalised`, `entry_fee`) VALUES
(1, '2009-11-13 13:59:17', '2009-11-16 13:59:22', '2009-11-12 13:59:28', 'Reading, UK', 0, 'Blahhh', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `kills`
--

CREATE TABLE IF NOT EXISTS `kills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weapon` int(11) NOT NULL,
  `description` text NOT NULL,
  `assassin` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `contested` tinyint(4) NOT NULL,
  `contest` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `game` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `kills`
--

INSERT INTO `kills` (`id`, `weapon`, `description`, `assassin`, `target`, `contested`, `contest`, `timestamp`, `game`) VALUES
(1, 8, 'Blah bla', 2, 1, 0, 'Asdf', '2009-11-13 17:09:09', 1),
(2, 2, 'Bleop', 1, 2, 1, 'Hasn''t actually shot me.', '2009-11-04 17:09:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sid` varchar(64) NOT NULL,
  `tok` varchar(64) NOT NULL,
  `ipv4` varchar(12) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

CREATE TABLE IF NOT EXISTS `weapons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`id`, `name`, `class`, `order`) VALUES
(1, 'Nerf N-Strike Maverick', 'Ranged', 0),
(2, 'Nerf N-Strike Longshot CS6', 'Ranged', 0),
(3, 'Nerf N-Strike Buzzsaw', 'Ranged', 0),
(4, 'Nerf N-Strike Secret Strike AS-1', 'Ranged', 0),
(5, 'Nerf N-Strike Raider Rapid Fire CS-35', 'Ranged', 0),
(6, 'Nerf N-Strike Firefly Blaster', 'Ranged', 0),
(7, 'Nerf N-Strike Nite Finder EX-3', 'Ranged', 0),
(8, 'Nerf N-Strike Vulcan EBF-25', 'Ranged', 0),
(9, 'Nerf N-Strike Switch Shot EX-3', 'Ranged', 0),
(10, 'Nerf N-Strike Recon CS-6', 'Ranged', 0),
(11, 'Other Ranged', 'Ranged', 1),
(12, 'Other Melee', 'Melee', 1),
(13, 'Poisoned (Food)', 'Poison', 0),
(14, 'Poisoned (Drink)', 'Poison', 0),
(15, 'Other Special', 'Special', 1),
(16, 'Poisoned (Contact)', 'Poison', 0),
(17, 'Nerf N-ForceThunder Fury', 'Melee', 0),
(18, 'Nerf (Other)', 'Ranged', 0),
(19, 'Fridge', 'Special', 0),
(20, 'Garrotting', 'Melee', 0),
(21, 'Sword', 'Melee', 0),
(22, 'Dagger', 'Melee', 0),
(23, 'Stiletto', 'Melee', 0),
(24, 'Hammer', 'Melee', 0),
(25, 'Grenade', 'Special', 0),
(26, 'Broken Neck', 'Melee', 0),
(27, 'Kiss of Death', 'Poison', 0),
(28, 'Beating', 'Melee', 0),
(29, 'Drive By', 'Special', 0),
(30, 'Letter Bomb', 'Special', 0),
(31, 'Electrocution', 'Special', 0),
(32, 'AIDS', 'Special', 0);
