-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2015 at 05:01 AM
-- Server version: 5.5.44-MariaDB-1ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mls`
--

-- --------------------------------------------------------

--
-- Table structure for table `cat`
--

CREATE TABLE IF NOT EXISTS `cat` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(6) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` int(6) NOT NULL,
  `photo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cat`
--

INSERT INTO `cat` (`id`, `cid`, `name`, `price`, `photo`) VALUES
(1, 1, 'Mineral Water 20L Jar', 60, '1.jpg'),
(2, 1, 'Pepsi 600 ml', 30, '2.jpg'),
(3, 2, 'Arhar Dal 1kg', 120, '3.jpg'),
(4, 2, 'Basmati Rice 1kg', 55, '4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(6) unsigned NOT NULL,
  `qty` int(2) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `pid`, `qty`, `time`) VALUES
(1, 1, 1, '2015-08-17 21:15:58'),
(2, 2, 1, '2015-08-17 21:15:58'),
(3, 3, 1, '2015-08-17 21:15:58'),
(4, 3, 1, '2015-08-17 21:15:58');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE IF NOT EXISTS `shops` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `name`, `latitude`, `longitude`) VALUES
(1, 'Dhingra Grocers', '28.541369', '77.249781'),
(2, 'Savers', '28.541372', '77.249782'),
(3, 'Chutney', '28.541367', '77.249784'),
(4, 'Sri Balaji', '28.541368', '77.249779');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
