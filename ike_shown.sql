-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 13 Jan 2012 om 15:55
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
-- Tabelstructuur voor tabel `ike_shown`
--

CREATE TABLE IF NOT EXISTS `ike_shown` (
  `user_id` int(11) NOT NULL,
  `mbid` varchar(64) NOT NULL,
  `koppel_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`koppel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Gegevens worden uitgevoerd voor tabel `ike_shown`
--

INSERT INTO `ike_shown` (`user_id`, `mbid`, `koppel_id`) VALUES
(2, 'a74b1b7f-71a5-4011-9441-d0b5e4122711', 1),
(2, 'cc197bad-dc9c-440d-a5b5-d52ba2e14234', 2),
(2, 'c3aeb863-7b26-4388-94e8-5a240f2be21b', 3),
(2, 'd15721d8-56b4-453d-b506-fc915b14cba2', 4),
(2, 'd43d12a1-2dc9-4257-a2fd-0a3bb1081b86', 5),
(2, '20244d07-534f-4eff-b4d4-930878889970', 6),
(2, '9c9f1380-2516-4fc9-a3e6-f9f61941d090', 7),
(2, 'ada7a83c-e3e1-40f1-93f9-3e73dbc9298a', 8),
(2, 'f181961b-20f7-459e-89de-920ef03c7ed0', 9),
(2, '8bfac288-ccc5-448d-9573-c33ea2aa5c30', 10),
(2, 'b10bbbfc-cf9e-42e0-be17-e2c3e1d2600d', 11),
(2, 'b38225b8-8e5f-42aa-bcdc-7bae5b5bdab3', 12),
(2, 'b892f72d-05e2-4ff7-b863-3d5dec6331fd', 13),
(2, '592a3b6d-c42b-4567-99c9-ecf63bd66499', 14),
(2, '01809552-4f87-45b0-afff-2c6f0730a3be', 15),
(2, '39ab1aed-75e0-4140-bd47-540276886b60', 16),
(2, 'ba853904-ae25-4ebb-89d6-c44cfbd71bd2', 17),
(2, '5bdb38bd-5c5b-4d0f-8901-8bbe1d7062d8', 18),
(2, 'b23e8a63-8f47-4882-b55b-df2c92ef400e', 19),
(2, '66c662b6-6e2f-4930-8610-912e24c63ed1', 20),
(2, 'eeb1195b-f213-4ce1-b28c-8565211f8e43', 21),
(2, 'c33627c6-ef0d-49de-9ef0-c4804190040f', 22),
(2, 'c296e10c-110a-4103-9e77-47bfebb7fb2e', 23),
(2, '83107f93-2d1a-474b-8f73-1147c13391cb', 24),
(2, '43d2c08b-f80a-4261-ab04-242fbd3c1e7a', 25),
(2, '0c751690-c784-4a4f-b1e4-c1de27d47581', 26),
(2, 'fa591ef7-690b-4389-8898-7d22ab48af51', 27),
(2, '277e21a9-2d64-452d-96c4-2d23a7af5891', 28),
(2, '0f000cd4-9edc-40cd-9bb4-4009ee4c56e6', 29),
(2, '69ee3720-a7cb-4402-b48d-a02c366f2bcf', 30),
(2, 'f443a331-2623-4d50-8797-bfb204850253', 31),
(2, '83d91898-7763-47d7-b03b-b92132375c47', 32),
(2, '5e7ccd92-6277-451a-aab9-1efd587c50f3', 33),
(2, '29762c82-bb92-4acd-b1fb-09cc4da250d2', 34),
(2, '616469a8-5e3c-4db7-bf1f-e32b01eeaf66', 35),
(2, '2ce02909-598b-44ef-a456-151ba0a3bd70', 36),
(2, 'ef9e7489-8a25-4044-97f2-d72bf5250cc0', 63),
(2, '79491354-3d83-40e3-9d8e-7592d58d790a', 64),
(2, '82b1f5fd-cd31-41a9-b5d4-7e33f0eb9751', 66),
(2, 'dcb03ce3-67a5-4eb3-b2d1-2a12d93a38f3', 67),
(2, '8fcac535-8f39-44b6-be5e-61e80b4b3d63', 68),
(2, '4cf47aee-146e-45be-9b35-52baf0231922', 69),
(2, '704acdbb-1415-4782-b0b6-0596b8c55e46', 70),
(2, '66ebc271-6a26-4fe7-848b-f217da119d92', 71),
(2, 'e8993e9d-9313-4447-ad23-791459a3790d', 72),
(2, '8de4a831-7c25-4d34-90cf-254de0c36e49', 73),
(2, '664c3e0e-42d8-48c1-b209-1efca19c0325', 74),
(2, 'a96ac800-bfcb-412a-8a63-0a98df600700', 75),
(2, '10f89195-3af1-46f5-aa36-504aa3309f5a', 76),
(2, 'e1f1e33e-2e4c-4d43-b91b-7064068d3283', 77),
(2, 'f795c501-1c41-4be2-bc2a-875eba75aa31', 78),
(2, '76b2e842-5e85-4c97-ab62-d5bc315595b5', 79),
(2, '7113aab7-628f-4050-ae49-dbecac110ca8', 80),
(2, 'c3cceeed-3332-4cf0-8c4c-bbde425147b6', 81),
(2, '6ffb8ea9-2370-44d8-b678-e9237bbd347b', 82),
(2, '95c2339b-8277-49a6-9aaf-08d8eeeaa0be', 83),
(2, '46b6c6e1-81ca-4fa8-b264-8f0fe49dff1a', 84),
(2, 'c7020c6d-cae9-4db3-92a7-e5c561cbad50', 85),
(2, 'a9044915-8be3-4c7e-b11f-9e2d2ea0a91e', 86),
(2, '67f66c07-6e61-4026-ade5-7e782fad3a5d', 87),
(2, '7fa7fc04-1011-4876-8095-ecd232edea87', 91),
(2, '45a663b5-b1cb-4a91-bff6-2bef7bbfdd76', 92),
(2, '81b9963b-7ff7-47f7-9afb-fe454d8db43c', 93),
(2, '8475297d-fb78-4630-8d74-9b87b6bb7cc8', 94),
(2, '4661d98a-95bd-4cf1-a693-4f9f25691ada', 95),
(2, '8c538f11-c141-4588-8ecb-931083524186', 96),
(2, 'e5c7b94f-e264-473c-bb0f-37c85d4d5c70', 97),
(2, '5c6d6ede-b64e-44c1-bc62-d78012a95fec', 98),
(2, 'b59ac58d-036e-4bba-8112-c32a34c4575b', 99),
(2, 'cbfb9bcd-c5a0-4d7c-865f-2c641c171e1c', 100),
(2, '984f8239-8fe1-4683-9c54-10ffb14439e9', 101);
