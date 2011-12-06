-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 06 Dec 2011 om 18:01
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
-- Tabelstructuur voor tabel `user_album_rating`
--

CREATE TABLE IF NOT EXISTS `user_album_rating` (
  `user_id` int(11) NOT NULL,
  `album_id` varchar(36) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `user_album_rating`
--

INSERT INTO `user_album_rating` (`user_id`, `album_id`, `rating`) VALUES
(2, 'd43d12a1-2dc9-4257-a2fd-0a3bb1081b86', -1),
(2, 'd15721d8-56b4-453d-b506-fc915b14cba2', 1),
(2, 'd15721d8-56b4-453d-b506-fc915b14cba2', 1),
(2, 'cc197bad-dc9c-440d-a5b5-d52ba2e14234', 1),
(2, '20244d07-534f-4eff-b4d4-930878889970', -1),
(2, 'a74b1b7f-71a5-4011-9441-d0b5e4122711', -1);
