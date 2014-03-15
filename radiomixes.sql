-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2014 at 12:04 AM
-- Server version: 5.5.32-cll-lve
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `strenbum_stredm`
--

-- --------------------------------------------------------

--
-- Table structure for table `radiomixes`
--

CREATE TABLE IF NOT EXISTS `radiomixes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_radiomix` tinyint(1) NOT NULL DEFAULT '1',
  `radiomix` varchar(255) CHARACTER SET utf8 NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `radiomix` (`radiomix`),
  KEY `image_id` (`image_id`),
  KEY `image_id_2` (`image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `radiomixes`
--

INSERT INTO `radiomixes` (`id`, `is_radiomix`, `radiomix`, `image_id`) VALUES
(1, 1, 'Above & Beyond: Group Therapy', 0),
(2, 1, 'Chuckie: Dirty Dutch Radio', 0),
(3, 1, 'Fedde Le Grand: Dark Light Sessions', 0),
(4, 1, 'Afrojack: Jacked', 0),
(5, 1, 'BBC Radio 1 Essential Mix', 0),
(6, 1, 'Dillon Francis - "GET LOW" Triple j Mix For Triple J Mix Ups', 0),
(7, 1, 'Diplo & Friends', 0),
(8, 1, 'Hardwell On Air', 0),
(10, 1, 'Tiesto: Club Life', 0),
(11, 1, 'W & W: Mainstage Mix', 0),
(12, 1, 'Radio Mix: Group Therapy 050 Live from Alexandria Palace', 0),
(13, 1, 'Radio Mix: Trance Around The World Guest Mix', 0),
(14, 1, 'The HOT Sh*t', 0),
(15, 1, 'Global Mix', 0),
(16, 1, 'Troll Mix', 0),
(23, 1, 'Holiday Mix', 0),
(22, 1, 'Simon Patterson: Open Up Mix', 0),
(24, 1, 'Spring Mix', 0),
(25, 1, 'Jambalaya Mix', 0),
(26, 1, 'Mini Mix', 0),
(28, 1, 'Laidback Luke: Mix Mash Radio', 0);
(29, 1, 'EDX: No Xcuses', 0);
(30, 1, 'Morgan Page: In The Air', 0);
(31, 1, 'Myon & Shane 54: International Departure', 0);
(32, 1, 'Gareth Emery: Podcast', 0);
(33, 1, 'Paul Oakenfold: Planet Perfecto', 0);
(34, 1, 'Aly & Fila: Future Sound of Egypt', 0);
(35, 1, 'EDX: Dance Department', 0);
(36, 1, 'Pete Tong: The Essential Selection', 0);
(37, 1, 'Ferry Corsten: Corstens Countdown', 0);
(38, 1, 'Ferry Corsten: Ferrys Fix', 0);
(39, 1, 'Markus Schultz: Global DJ Broadcast', 0);
(40, 1, 'Eric Prydz: EPIC Radio', 0);
(41, 1, 'Carnage: Incredible', 0);
(42, 1, 'Shadow Child: Rinse', 0);
(43, 1, 'Sander Van Doorn: Identity', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
