-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 06 Dec 2011 om 16:10
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
-- Tabelstructuur voor tabel `ike_voorkeur`
--

CREATE TABLE IF NOT EXISTS `ike_voorkeur` (
  `uID` int(11) NOT NULL,
  `genre1` varchar(20) NOT NULL,
  `genre2` varchar(20) NOT NULL,
  `genre3` varchar(20) NOT NULL,
  `genre4` varchar(20) NOT NULL,
  `genre5` varchar(20) NOT NULL,
  `artiesten` tinytext NOT NULL,
  PRIMARY KEY (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
