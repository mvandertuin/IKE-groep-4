-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 25 Nov 2011 om 17:40
-- Serverversie: 5.5.8
-- PHP-Versie: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `musicdat`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `artistrating`
--

DROP TABLE IF EXISTS `artistrating`;
CREATE TABLE IF NOT EXISTS `artistrating` (
  `incr` int(11) NOT NULL AUTO_INCREMENT,
  `id` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `artistname` text NOT NULL,
  PRIMARY KEY (`incr`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;
