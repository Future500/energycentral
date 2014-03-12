-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 10 mrt 2014 om 09:36
-- Serverversie: 5.5.35
-- PHP-Versie: 5.4.4-14+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `future500`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `daydata`
--

CREATE TABLE IF NOT EXISTS `daydata` (
  `datetime` datetime NOT NULL,
  `deviceid` int(5) NOT NULL,
  `kWh` decimal(12,3) DEFAULT NULL,
  `kW` decimal(12,3) DEFAULT NULL,
  PRIMARY KEY (`datetime`),
  KEY `deviceid` (`deviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `daydata`
--

INSERT INTO `daydata` (`datetime`, `deviceid`, `kWh`, `kW`) VALUES
('2013-04-19 00:00:00', 1, 2321.015, 0.000),
('2013-04-19 00:05:00', 1, 2321.015, 0.000),
('2013-04-19 00:10:00', 1, 2321.015, 0.000),
('2013-04-19 00:15:00', 1, 2321.015, 0.000),
('2013-04-19 00:20:00', 1, 2321.015, 0.000),
('2013-04-19 00:25:00', 1, 2321.015, 0.000),
('2013-04-19 00:30:00', 1, 2321.015, 0.000),
('2013-04-19 00:35:00', 1, 2321.015, 0.000),
('2013-04-19 00:40:00', 1, 2321.015, 0.000),
('2013-04-19 00:45:00', 1, 2321.015, 0.000),
('2013-04-19 00:50:00', 1, 2321.015, 0.000),
('2013-04-19 00:55:00', 1, 2321.015, 0.000),
('2013-04-19 01:00:00', 1, 2321.015, 0.000),
('2013-04-19 01:05:00', 1, 2321.015, 0.000),
('2013-04-19 01:10:00', 1, 2321.015, 0.000),
('2013-04-19 01:15:00', 1, 2321.015, 0.000),
('2013-04-19 01:20:00', 1, 2321.015, 0.000),
('2013-04-19 01:25:00', 1, 2321.015, 0.000),
('2013-04-19 01:30:00', 1, 2321.015, 0.000),
('2013-04-19 01:35:00', 1, 2321.015, 0.000),
('2013-04-19 01:40:00', 1, 2321.015, 0.000),
('2013-04-19 01:45:00', 1, 2321.015, 0.000),
('2013-04-19 01:50:00', 1, 2321.015, 0.000),
('2013-04-19 01:55:00', 1, 2321.015, 0.000),
('2013-04-19 02:00:00', 1, 2321.015, 0.000),
('2013-04-19 02:05:00', 1, 2321.015, 0.000),
('2013-04-19 02:10:00', 1, 2321.015, 0.000),
('2013-04-19 02:15:00', 1, 2321.015, 0.000),
('2013-04-19 02:20:00', 1, 2321.015, 0.000),
('2013-04-19 02:25:00', 1, 2321.015, 0.000),
('2013-04-19 02:30:00', 1, 2321.015, 0.000),
('2013-04-19 02:35:00', 1, 2321.015, 0.000),
('2013-04-19 02:40:00', 1, 2321.015, 0.000),
('2013-04-19 02:45:00', 1, 2321.015, 0.000),
('2013-04-19 02:50:00', 1, 2321.015, 0.000),
('2013-04-19 02:55:00', 1, 2321.015, 0.000),
('2013-04-19 03:00:00', 1, 2321.015, 0.000),
('2013-04-19 03:05:00', 1, 2321.015, 0.000),
('2013-04-19 03:10:00', 1, 2321.015, 0.000),
('2013-04-19 03:15:00', 1, 2321.015, 0.000),
('2013-04-19 03:20:00', 1, 2321.015, 0.000),
('2013-04-19 03:25:00', 1, 2321.015, 0.000),
('2013-04-19 03:30:00', 1, 2321.015, 0.000),
('2013-04-19 03:35:00', 1, 2321.015, 0.000),
('2013-04-19 03:40:00', 1, 2321.015, 0.000),
('2013-04-19 03:45:00', 1, 2321.015, 0.000),
('2013-04-19 03:50:00', 1, 2321.015, 0.000),
('2013-04-19 03:55:00', 1, 2321.015, 0.000),
('2013-04-19 04:00:00', 1, 2321.015, 0.000),
('2013-04-19 04:05:00', 1, 2321.015, 0.000),
('2013-04-19 04:10:00', 1, 2321.015, 0.000),
('2013-04-19 04:15:00', 1, 2321.015, 0.000),
('2013-04-19 04:20:00', 1, 2321.015, 0.000),
('2013-04-19 04:25:00', 1, 2321.015, 0.000),
('2013-04-19 04:30:00', 1, 2321.015, 0.000),
('2013-04-19 04:35:00', 1, 2321.015, 0.000),
('2013-04-19 04:40:00', 1, 2321.015, 0.000),
('2013-04-19 04:45:00', 1, 2321.015, 0.000),
('2013-04-19 04:50:00', 1, 2321.015, 0.000),
('2013-04-19 04:55:00', 1, 2321.015, 0.000),
('2013-04-19 05:00:00', 1, 2321.015, 0.000),
('2013-04-19 05:05:00', 1, 2321.015, 0.000),
('2013-04-19 05:10:00', 1, 2321.015, 0.000),
('2013-04-19 05:15:00', 1, 2321.015, 0.000),
('2013-04-19 05:20:00', 1, 2321.015, 0.000),
('2013-04-19 05:25:00', 1, 2321.015, 0.000),
('2013-04-19 05:30:00', 1, 2321.015, 0.000),
('2013-04-19 05:35:00', 1, 2321.015, 0.000),
('2013-04-19 05:40:00', 1, 2321.015, 0.000),
('2013-04-19 05:45:00', 1, 2321.015, 0.000),
('2013-04-19 05:50:00', 1, 2321.015, 0.000),
('2013-04-19 05:55:00', 1, 2321.015, 0.000),
('2013-04-19 06:00:00', 1, 2321.015, 0.000),
('2013-04-19 06:05:00', 1, 2321.015, 0.000),
('2013-04-19 06:10:00', 1, 2321.015, 0.000),
('2013-04-19 06:15:00', 1, 2321.015, 0.000),
('2013-04-19 06:20:00', 1, 2321.015, 0.000),
('2013-04-19 06:25:00', 1, 2321.015, 0.000),
('2013-04-19 06:30:00', 1, 2321.015, 0.000),
('2013-04-19 06:35:00', 1, 2321.015, 0.000),
('2013-04-19 06:40:00', 1, 2321.015, 0.000),
('2013-04-19 06:45:00', 1, 2321.015, 0.000),
('2013-04-19 06:50:00', 1, 2321.015, 0.000),
('2013-04-19 06:55:00', 1, 2321.015, 0.000),
('2013-04-19 07:00:00', 1, 2321.019, 0.048),
('2013-04-19 07:05:00', 1, 2321.026, 0.084),
('2013-04-19 07:10:00', 1, 2321.037, 0.132),
('2013-04-19 07:15:00', 1, 2321.052, 0.180),
('2013-04-19 07:20:00', 1, 2321.073, 0.252),
('2013-04-19 07:25:00', 1, 2321.100, 0.324),
('2013-04-19 07:30:00', 1, 2321.133, 0.396),
('2013-04-19 07:35:00', 1, 2321.170, 0.444),
('2013-04-19 07:40:00', 1, 2321.215, 0.540),
('2013-04-19 07:45:00', 1, 2321.266, 0.612),
('2013-04-19 07:50:00', 1, 2321.324, 0.696),
('2013-04-19 07:55:00', 1, 2321.387, 0.756),
('2013-04-19 08:00:00', 1, 2321.456, 0.828),
('2013-04-19 08:05:00', 1, 2321.532, 0.912),
('2013-04-19 08:10:00', 1, 2321.612, 0.960),
('2013-04-19 08:15:00', 1, 2321.697, 1.020),
('2013-04-19 08:20:00', 1, 2321.789, 1.104),
('2013-04-19 08:25:00', 1, 2321.886, 1.164),
('2013-04-19 08:30:00', 1, 2321.990, 1.248),
('2013-04-19 08:35:00', 1, 2322.046, 0.672),
('2013-04-19 08:40:00', 1, 2322.087, 0.492),
('2013-04-19 08:45:00', 1, 2322.204, 1.404),
('2013-04-19 08:50:00', 1, 2322.332, 1.536),
('2013-04-19 08:55:00', 1, 2322.462, 1.560),
('2013-04-19 09:00:00', 1, 2322.597, 1.620),
('2013-04-19 09:05:00', 1, 2322.735, 1.656),
('2013-04-19 09:10:00', 1, 2322.866, 1.572),
('2013-04-19 09:15:00', 1, 2322.961, 1.140),
('2013-04-19 09:20:00', 1, 2323.029, 0.816),
('2013-04-19 09:25:00', 1, 2323.072, 0.516),
('2013-04-19 09:30:00', 1, 2323.098, 0.312),
('2013-04-19 09:35:00', 1, 2323.119, 0.252),
('2013-04-19 09:40:00', 1, 2323.142, 0.276),
('2013-04-19 09:45:00', 1, 2323.164, 0.264),
('2013-04-19 09:50:00', 1, 2323.188, 0.288),
('2013-04-19 09:55:00', 1, 2323.213, 0.300),
('2013-04-19 10:00:00', 1, 2323.241, 0.336),
('2013-04-19 10:05:00', 1, 2323.273, 0.384),
('2013-04-19 10:10:00', 1, 2323.301, 0.336),
('2013-04-19 10:15:00', 1, 2323.322, 0.252),
('2013-04-19 10:20:00', 1, 2323.346, 0.288),
('2013-04-19 10:25:00', 1, 2323.375, 0.348),
('2013-04-19 10:30:00', 1, 2323.406, 0.372),
('2013-04-19 10:35:00', 1, 2323.436, 0.360),
('2013-04-19 10:40:00', 1, 2323.460, 0.288),
('2013-04-19 10:45:00', 1, 2323.483, 0.276),
('2013-04-19 10:50:00', 1, 2323.513, 0.360),
('2013-04-19 10:55:00', 1, 2323.553, 0.480),
('2013-04-19 11:00:00', 1, 2323.600, 0.564),
('2013-04-19 11:05:00', 1, 2323.654, 0.648),
('2013-04-19 11:10:00', 1, 2323.702, 0.576),
('2013-04-19 11:15:00', 1, 2323.739, 0.444),
('2013-04-19 11:20:00', 1, 2323.773, 0.408),
('2013-04-19 11:25:00', 1, 2323.827, 0.648),
('2013-04-19 11:30:00', 1, 2323.886, 0.708),
('2013-04-19 11:35:00', 1, 2323.978, 1.104),
('2013-04-19 11:40:00', 1, 2324.109, 1.572),
('2013-04-19 11:45:00', 1, 2324.266, 1.884),
('2013-04-19 11:50:00', 1, 2324.338, 0.864),
('2013-04-19 11:55:00', 1, 2324.398, 0.720),
('2013-04-19 12:00:00', 1, 2324.457, 0.708),
('2013-04-19 12:05:00', 1, 2324.493, 0.432),
('2013-04-19 12:10:00', 1, 2324.531, 0.456),
('2013-04-19 12:15:00', 1, 2324.577, 0.552),
('2013-04-19 12:20:00', 1, 2324.645, 0.816),
('2013-04-19 12:25:00', 1, 2324.735, 1.080),
('2013-04-19 12:30:00', 1, 2324.810, 0.900),
('2013-04-19 12:35:00', 1, 2324.873, 0.756),
('2013-04-19 12:40:00', 1, 2324.920, 0.564),
('2013-04-19 12:45:00', 1, 2324.984, 0.768),
('2013-04-19 12:50:00', 1, 2325.054, 0.840),
('2013-04-19 12:55:00', 1, 2325.102, 0.576),
('2013-04-19 13:00:00', 1, 2325.159, 0.684),
('2013-04-19 13:05:00', 1, 2325.222, 0.756),
('2013-04-19 13:10:00', 1, 2325.291, 0.828),
('2013-04-19 13:15:00', 1, 2325.339, 0.576),
('2013-04-19 13:20:00', 1, 2325.397, 0.696),
('2013-04-19 13:25:00', 1, 2325.444, 0.564),
('2013-04-19 13:30:00', 1, 2325.477, 0.396),
('2013-04-19 13:35:00', 1, 2325.504, 0.324),
('2013-04-19 13:40:00', 1, 2325.551, 0.564),
('2013-04-19 13:45:00', 1, 2325.586, 0.420),
('2013-04-19 13:50:00', 1, 2325.610, 0.288),
('2013-04-19 13:55:00', 1, 2325.648, 0.456),
('2013-04-19 14:00:00', 1, 2325.699, 0.612),
('2013-04-19 14:05:00', 1, 2325.738, 0.468),
('2013-04-19 14:10:00', 1, 2325.796, 0.696),
('2013-04-19 14:15:00', 1, 2325.871, 0.900),
('2013-04-19 14:20:00', 1, 2325.939, 0.816),
('2013-04-19 14:25:00', 1, 2326.031, 1.104),
('2013-04-19 14:30:00', 1, 2326.108, 0.924),
('2013-04-19 14:35:00', 1, 2326.251, 1.716),
('2013-04-19 14:40:00', 1, 2326.351, 1.200),
('2013-04-19 14:45:00', 1, 2326.408, 0.684),
('2013-04-19 14:50:00', 1, 2326.484, 0.912),
('2013-04-19 14:55:00', 1, 2326.580, 1.152),
('2013-04-19 15:00:00', 1, 2326.702, 1.464),
('2013-04-19 15:05:00', 1, 2326.853, 1.812),
('2013-04-19 15:10:00', 1, 2326.964, 1.332),
('2013-04-19 15:15:00', 1, 2327.043, 0.948),
('2013-04-19 15:20:00', 1, 2327.133, 1.080),
('2013-04-19 15:25:00', 1, 2327.243, 1.320),
('2013-04-19 15:30:00', 1, 2327.355, 1.344),
('2013-04-19 15:35:00', 1, 2327.479, 1.488),
('2013-04-19 15:40:00', 1, 2327.564, 1.020),
('2013-04-19 15:45:00', 1, 2327.653, 1.068),
('2013-04-19 15:50:00', 1, 2327.774, 1.452),
('2013-04-19 15:55:00', 1, 2327.888, 1.368),
('2013-04-19 16:00:00', 1, 2327.972, 1.008),
('2013-04-19 16:05:00', 1, 2328.083, 1.332),
('2013-04-19 16:10:00', 1, 2328.179, 1.152),
('2013-04-19 16:15:00', 1, 2328.265, 1.032),
('2013-04-19 16:20:00', 1, 2328.346, 0.972),
('2013-04-19 16:25:00', 1, 2328.423, 0.924),
('2013-04-19 16:30:00', 1, 2328.496, 0.876),
('2013-04-19 16:35:00', 1, 2328.565, 0.828),
('2013-04-19 16:40:00', 1, 2328.640, 0.900),
('2013-04-19 16:45:00', 1, 2328.704, 0.768),
('2013-04-19 16:50:00', 1, 2328.761, 0.684),
('2013-04-19 16:55:00', 1, 2328.811, 0.600),
('2013-04-19 17:00:00', 1, 2328.857, 0.552),
('2013-04-19 17:05:00', 1, 2328.894, 0.444),
('2013-04-19 17:10:00', 1, 2328.931, 0.444),
('2013-04-19 17:15:00', 1, 2328.963, 0.384),
('2013-04-19 17:20:00', 1, 2328.990, 0.324),
('2013-04-19 17:25:00', 1, 2329.015, 0.300),
('2013-04-19 17:30:00', 1, 2329.034, 0.228),
('2013-04-19 17:35:00', 1, 2329.053, 0.228),
('2013-04-19 17:40:00', 1, 2329.069, 0.192),
('2013-04-19 17:45:00', 1, 2329.088, 0.228),
('2013-04-19 17:50:00', 1, 2329.103, 0.180),
('2013-04-19 17:55:00', 1, 2329.117, 0.168),
('2013-04-19 18:00:00', 1, 2329.131, 0.168),
('2013-04-19 18:05:00', 1, 2329.145, 0.168),
('2013-04-19 18:10:00', 1, 2329.159, 0.168),
('2013-04-19 18:15:00', 1, 2329.171, 0.144),
('2013-04-19 18:20:00', 1, 2329.185, 0.168),
('2013-04-19 18:25:00', 1, 2329.198, 0.156),
('2013-04-19 18:30:00', 1, 2329.211, 0.156),
('2013-04-19 18:35:00', 1, 2329.225, 0.168),
('2013-04-19 18:40:00', 1, 2329.238, 0.156),
('2013-04-19 18:45:00', 1, 2329.250, 0.144),
('2013-04-19 18:50:00', 1, 2329.262, 0.144),
('2013-04-19 18:55:00', 1, 2329.274, 0.144),
('2013-04-19 19:00:00', 1, 2329.285, 0.132),
('2013-04-19 19:05:00', 1, 2329.295, 0.120),
('2013-04-19 19:10:00', 1, 2329.306, 0.132),
('2013-04-19 19:15:00', 1, 2329.315, 0.108),
('2013-04-19 19:20:00', 1, 2329.325, 0.120),
('2013-04-19 19:25:00', 1, 2329.336, 0.132),
('2013-04-19 19:30:00', 1, 2329.344, 0.096),
('2013-04-19 19:35:00', 1, 2329.351, 0.084),
('2013-04-19 19:40:00', 1, 2329.357, 0.072),
('2013-04-19 19:45:00', 1, 2329.362, 0.060),
('2013-04-19 19:50:00', 1, 2329.366, 0.048),
('2013-04-19 19:55:00', 1, 2329.370, 0.048),
('2013-04-19 20:00:00', 1, 2329.374, 0.048),
('2013-04-19 20:05:00', 1, 2329.377, 0.036),
('2013-04-19 20:10:00', 1, 2329.380, 0.036),
('2013-04-19 20:15:00', 1, 2329.382, 0.024),
('2013-04-19 20:20:00', 1, 2329.383, 0.012),
('2013-04-19 20:25:00', 1, 2329.383, 0.000),
('2013-04-19 20:30:00', 1, 2329.383, 0.000),
('2013-04-19 20:35:00', 1, 2329.383, 0.000),
('2013-04-19 20:40:00', 1, 2329.383, 0.000),
('2013-04-19 20:45:00', 1, 2329.383, 0.000),
('2013-04-19 20:50:00', 1, 2329.383, 0.000),
('2013-04-19 20:55:00', 1, 2329.383, 0.000),
('2013-04-19 21:00:00', 1, 2329.383, 0.000),
('2013-04-19 21:05:00', 1, 2329.383, 0.000),
('2013-04-19 21:10:00', 1, 2329.383, 0.000),
('2013-04-19 21:15:00', 1, 2329.383, 0.000),
('2013-04-19 21:20:00', 1, 2329.383, 0.000),
('2013-04-19 21:25:00', 1, 2329.383, 0.000),
('2013-04-19 21:30:00', 1, 2329.383, 0.000),
('2013-04-19 21:35:00', 1, 2329.383, 0.000),
('2013-04-19 21:40:00', 1, 2329.383, 0.000),
('2013-04-19 21:45:00', 1, 2329.383, 0.000),
('2013-04-19 21:50:00', 1, 2329.383, 0.000),
('2013-04-19 21:55:00', 1, 2329.383, 0.000),
('2013-04-19 22:00:00', 1, 2329.383, 0.000),
('2013-04-19 22:05:00', 1, 2329.383, 0.000),
('2013-04-19 22:10:00', 1, 2329.383, 0.000),
('2013-04-19 22:15:00', 1, 2329.383, 0.000),
('2013-04-19 22:20:00', 1, 2329.383, 0.000),
('2013-04-19 22:25:00', 1, 2329.383, 0.000),
('2013-04-19 22:30:00', 1, 2329.383, 0.000),
('2013-04-19 22:35:00', 1, 2329.383, 0.000),
('2013-04-19 22:40:00', 1, 2329.383, 0.000),
('2013-04-19 22:45:00', 1, 2329.383, 0.000),
('2013-04-19 22:50:00', 1, 2329.383, 0.000),
('2013-04-19 22:55:00', 1, 2329.383, 0.000),
('2013-04-19 23:00:00', 1, 2329.383, 0.000),
('2013-04-19 23:05:00', 1, 2329.383, 0.000),
('2013-04-19 23:10:00', 1, 2329.383, 0.000),
('2013-04-19 23:15:00', 1, 2329.383, 0.000),
('2013-04-19 23:20:00', 1, 2329.383, 0.000),
('2013-04-19 23:25:00', 1, 2329.383, 0.000),
('2013-04-19 23:30:00', 1, 2329.383, 0.000),
('2013-04-19 23:35:00', 1, 2329.383, 0.000),
('2013-04-19 23:40:00', 1, 2329.383, 0.000),
('2013-04-19 23:45:00', 1, 2329.383, 0.000),
('2013-04-19 23:50:00', 1, 2329.383, 0.000),
('2013-04-19 23:55:00', 1, 2329.383, 0.000),
('2013-04-20 00:00:00', 1, 2329.383, 0.000),
('2013-04-20 00:05:00', 1, 2329.383, 0.000),
('2013-04-20 00:10:00', 1, 2329.383, 0.000),
('2013-04-20 00:15:00', 1, 2329.383, 0.000),
('2013-04-20 00:20:00', 1, 2329.383, 0.000),
('2013-04-20 00:25:00', 1, 2329.383, 0.000),
('2013-04-20 00:30:00', 1, 2329.383, 0.000),
('2013-04-20 00:35:00', 1, 2329.383, 0.000),
('2013-04-20 00:40:00', 1, 2329.383, 0.000),
('2013-04-20 00:45:00', 1, 2329.383, 0.000),
('2013-04-20 00:50:00', 1, 2329.383, 0.000),
('2013-04-20 00:55:00', 1, 2329.383, 0.000),
('2013-04-20 01:00:00', 1, 2329.383, 0.000),
('2013-04-20 01:05:00', 1, 2329.383, 0.000),
('2013-04-20 01:10:00', 1, 2329.383, 0.000),
('2013-04-20 01:15:00', 1, 2329.383, 0.000),
('2013-04-20 01:20:00', 1, 2329.383, 0.000),
('2013-04-20 01:25:00', 1, 2329.383, 0.000),
('2013-04-20 01:30:00', 1, 2329.383, 0.000),
('2013-04-20 01:35:00', 1, 2329.383, 0.000),
('2013-04-20 01:40:00', 1, 2329.383, 0.000),
('2013-04-20 01:45:00', 1, 2329.383, 0.000),
('2013-04-20 01:50:00', 1, 2329.383, 0.000),
('2013-04-20 01:55:00', 1, 2329.383, 0.000),
('2013-04-20 02:00:00', 1, 2329.383, 0.000),
('2013-04-20 02:05:00', 1, 2329.383, 0.000),
('2013-04-20 02:10:00', 1, 2329.383, 0.000),
('2013-04-20 02:15:00', 1, 2329.383, 0.000),
('2013-04-20 02:20:00', 1, 2329.383, 0.000),
('2013-04-20 02:25:00', 1, 2329.383, 0.000),
('2013-04-20 02:30:00', 1, 2329.383, 0.000),
('2013-04-20 02:35:00', 1, 2329.383, 0.000),
('2013-04-20 02:40:00', 1, 2329.383, 0.000),
('2013-04-20 02:45:00', 1, 2329.383, 0.000),
('2013-04-20 02:50:00', 1, 2329.383, 0.000),
('2013-04-20 02:55:00', 1, 2329.383, 0.000),
('2013-04-20 03:00:00', 1, 2329.383, 0.000),
('2013-04-20 03:05:00', 1, 2329.383, 0.000),
('2013-04-20 03:10:00', 1, 2329.383, 0.000),
('2013-04-20 03:15:00', 1, 2329.383, 0.000),
('2013-04-20 03:20:00', 1, 2329.383, 0.000),
('2013-04-20 03:25:00', 1, 2329.383, 0.000),
('2013-04-20 03:30:00', 1, 2329.383, 0.000),
('2013-04-20 03:35:00', 1, 2329.383, 0.000),
('2013-04-20 03:40:00', 1, 2329.383, 0.000),
('2013-04-20 03:45:00', 1, 2329.383, 0.000),
('2013-04-20 03:50:00', 1, 2329.383, 0.000),
('2013-04-20 03:55:00', 1, 2329.383, 0.000),
('2013-04-20 04:00:00', 1, 2329.383, 0.000),
('2013-04-20 04:05:00', 1, 2329.383, 0.000),
('2013-04-20 04:10:00', 1, 2329.383, 0.000),
('2013-04-20 04:15:00', 1, 2329.383, 0.000),
('2013-04-20 04:20:00', 1, 2329.383, 0.000),
('2013-04-20 04:25:00', 1, 2329.383, 0.000),
('2013-04-20 04:30:00', 1, 2329.383, 0.000),
('2013-04-20 04:35:00', 1, 2329.383, 0.000),
('2013-04-20 04:40:00', 1, 2329.383, 0.000),
('2013-04-20 04:45:00', 1, 2329.383, 0.000),
('2013-04-20 04:50:00', 1, 2329.383, 0.000),
('2013-04-20 04:55:00', 1, 2329.383, 0.000),
('2013-04-20 05:00:00', 1, 2329.383, 0.000),
('2013-04-20 05:05:00', 1, 2329.383, 0.000),
('2013-04-20 05:10:00', 1, 2329.383, 0.000),
('2013-04-20 05:15:00', 1, 2329.383, 0.000),
('2013-04-20 05:20:00', 1, 2329.383, 0.000),
('2013-04-20 05:25:00', 1, 2329.383, 0.000),
('2013-04-20 05:30:00', 1, 2329.383, 0.000),
('2013-04-20 05:35:00', 1, 2329.383, 0.000),
('2013-04-20 05:40:00', 1, 2329.383, 0.000),
('2013-04-20 05:45:00', 1, 2329.383, 0.000),
('2013-04-20 05:50:00', 1, 2329.383, 0.000),
('2013-04-20 05:55:00', 1, 2329.383, 0.000),
('2013-04-20 06:00:00', 1, 2329.383, 0.000),
('2013-04-20 06:05:00', 1, 2329.383, 0.000),
('2013-04-20 06:10:00', 1, 2329.383, 0.000),
('2013-04-20 06:15:00', 1, 2329.383, 0.000),
('2013-04-20 06:20:00', 1, 2329.383, 0.000),
('2013-04-20 06:25:00', 1, 2329.383, 0.000),
('2013-04-20 06:30:00', 1, 2329.383, 0.000),
('2013-04-20 06:35:00', 1, 2329.383, 0.000),
('2013-04-20 06:40:00', 1, 2329.383, 0.000),
('2013-04-20 06:45:00', 1, 2329.383, 0.000),
('2013-04-20 06:50:00', 1, 2329.383, 0.000),
('2013-04-20 06:55:00', 1, 2329.383, 0.000),
('2013-04-20 07:00:00', 1, 2329.383, 0.000),
('2013-04-20 07:05:00', 1, 2329.385, 0.024),
('2013-04-20 07:10:00', 1, 2329.388, 0.036),
('2013-04-20 07:15:00', 1, 2329.391, 0.036),
('2013-04-20 07:20:00', 1, 2329.395, 0.048),
('2013-04-20 07:25:00', 1, 2329.403, 0.096),
('2013-04-20 07:30:00', 1, 2329.411, 0.096),
('2013-04-20 07:35:00', 1, 2329.421, 0.120),
('2013-04-20 07:40:00', 1, 2329.431, 0.120),
('2013-04-20 07:45:00', 1, 2329.442, 0.132),
('2013-04-20 07:50:00', 1, 2329.453, 0.132),
('2013-04-20 07:55:00', 1, 2329.464, 0.132),
('2013-04-20 08:00:00', 1, 2329.476, 0.144),
('2013-04-20 08:05:00', 1, 2329.487, 0.132),
('2013-04-20 08:10:00', 1, 2329.502, 0.180),
('2013-04-20 08:15:00', 1, 2329.521, 0.228),
('2013-04-20 08:20:00', 1, 2329.543, 0.264),
('2013-04-20 08:25:00', 1, 2329.594, 0.612),
('2013-04-20 08:30:00', 1, 2329.682, 1.056),
('2013-04-20 08:35:00', 1, 2329.774, 1.104),
('2013-04-20 08:40:00', 1, 2329.897, 1.476),
('2013-04-20 08:45:00', 1, 2330.026, 1.548),
('2013-04-20 08:50:00', 1, 2330.159, 1.596),
('2013-04-20 08:55:00', 1, 2330.296, 1.644),
('2013-04-20 09:00:00', 1, 2330.438, 1.704),
('2013-04-20 09:05:00', 1, 2330.583, 1.740),
('2013-04-20 09:10:00', 1, 2330.729, 1.752),
('2013-04-20 09:15:00', 1, 2330.880, 1.812),
('2013-04-20 09:20:00', 1, 2331.035, 1.860),
('2013-04-20 09:25:00', 1, 2331.194, 1.908),
('2013-04-20 09:30:00', 1, 2331.358, 1.968),
('2013-04-20 09:35:00', 1, 2331.525, 2.004),
('2013-04-20 09:40:00', 1, 2331.696, 2.052),
('2013-04-20 09:45:00', 1, 2331.867, 2.052),
('2013-04-20 09:50:00', 1, 2332.011, 1.728),
('2013-04-20 09:55:00', 1, 2332.116, 1.260),
('2013-04-20 10:00:00', 1, 2332.162, 0.552),
('2013-04-20 10:05:00', 1, 2332.307, 1.740),
('2013-04-20 10:10:00', 1, 2332.501, 2.328),
('2013-04-20 10:15:00', 1, 2332.695, 2.328),
('2013-04-20 10:20:00', 1, 2332.809, 1.368),
('2013-04-20 10:25:00', 1, 2332.859, 0.600),
('2013-04-20 10:30:00', 1, 2332.920, 0.732),
('2013-04-20 10:35:00', 1, 2333.048, 1.536),
('2013-04-20 10:40:00', 1, 2333.254, 2.472),
('2013-04-20 10:45:00', 1, 2333.460, 2.472),
('2013-04-20 10:50:00', 1, 2333.665, 2.460),
('2013-04-20 10:55:00', 1, 2333.870, 2.460),
('2013-04-20 11:00:00', 1, 2334.074, 2.448),
('2013-04-20 11:05:00', 1, 2334.275, 2.412),
('2013-04-20 11:10:00', 1, 2334.476, 2.412),
('2013-04-20 11:15:00', 1, 2334.686, 2.520),
('2013-04-20 11:20:00', 1, 2334.895, 2.508),
('2013-04-20 11:25:00', 1, 2335.106, 2.532),
('2013-04-20 11:30:00', 1, 2335.317, 2.532),
('2013-04-20 11:35:00', 1, 2335.527, 2.520),
('2013-04-20 11:40:00', 1, 2335.737, 2.520),
('2013-04-20 11:45:00', 1, 2335.948, 2.532),
('2013-04-20 11:50:00', 1, 2336.161, 2.556),
('2013-04-20 11:55:00', 1, 2336.372, 2.532),
('2013-04-20 12:00:00', 1, 2336.584, 2.544),
('2013-04-20 12:05:00', 1, 2336.797, 2.556),
('2013-04-20 12:10:00', 1, 2337.004, 2.484),
('2013-04-20 12:15:00', 1, 2337.211, 2.484),
('2013-04-20 12:20:00', 1, 2337.420, 2.508),
('2013-04-20 12:25:00', 1, 2337.629, 2.508),
('2013-04-20 12:30:00', 1, 2337.836, 2.484),
('2013-04-20 12:35:00', 1, 2338.043, 2.484),
('2013-04-20 12:40:00', 1, 2338.250, 2.484),
('2013-04-20 12:45:00', 1, 2338.456, 2.472),
('2013-04-20 12:50:00', 1, 2338.660, 2.448),
('2013-04-20 12:55:00', 1, 2338.864, 2.448),
('2013-04-20 13:00:00', 1, 2339.069, 2.460),
('2013-04-20 13:05:00', 1, 2339.274, 2.460),
('2013-04-20 13:10:00', 1, 2339.476, 2.424),
('2013-04-20 13:15:00', 1, 2339.679, 2.436),
('2013-04-20 13:20:00', 1, 2339.880, 2.412),
('2013-04-20 13:25:00', 1, 2340.080, 2.400),
('2013-04-20 13:30:00', 1, 2340.278, 2.376),
('2013-04-20 13:35:00', 1, 2340.474, 2.352),
('2013-04-20 13:40:00', 1, 2340.669, 2.340),
('2013-04-20 13:45:00', 1, 2340.863, 2.328),
('2013-04-20 13:50:00', 1, 2341.057, 2.328),
('2013-04-20 13:55:00', 1, 2341.244, 2.244),
('2013-04-20 14:00:00', 1, 2341.430, 2.232),
('2013-04-20 14:05:00', 1, 2341.616, 2.232),
('2013-04-20 14:10:00', 1, 2341.801, 2.220),
('2013-04-20 14:15:00', 1, 2341.982, 2.172),
('2013-04-20 14:20:00', 1, 2342.159, 2.124),
('2013-04-20 14:25:00', 1, 2342.332, 2.076),
('2013-04-20 14:30:00', 1, 2342.501, 2.028),
('2013-04-20 14:35:00', 1, 2342.659, 1.896),
('2013-04-20 14:40:00', 1, 2342.824, 1.980),
('2013-04-20 14:45:00', 1, 2342.980, 1.872),
('2013-04-20 14:50:00', 1, 2343.138, 1.896),
('2013-04-20 14:55:00', 1, 2343.294, 1.872),
('2013-04-20 15:00:00', 1, 2343.446, 1.824),
('2013-04-20 15:05:00', 1, 2343.595, 1.788),
('2013-04-20 15:10:00', 1, 2343.734, 1.668),
('2013-04-20 15:15:00', 1, 2343.875, 1.692),
('2013-04-20 15:20:00', 1, 2344.012, 1.644),
('2013-04-20 15:25:00', 1, 2344.146, 1.608),
('2013-04-20 15:30:00', 1, 2344.275, 1.548),
('2013-04-20 15:35:00', 1, 2344.401, 1.512),
('2013-04-20 15:40:00', 1, 2344.522, 1.452),
('2013-04-20 15:45:00', 1, 2344.639, 1.404),
('2013-04-20 15:50:00', 1, 2344.751, 1.344),
('2013-04-20 15:55:00', 1, 2344.859, 1.296),
('2013-04-20 16:00:00', 1, 2344.963, 1.248),
('2013-04-20 16:05:00', 1, 2345.061, 1.176),
('2013-04-20 16:10:00', 1, 2345.155, 1.128),
('2013-04-20 16:15:00', 1, 2345.243, 1.056),
('2013-04-20 16:20:00', 1, 2345.327, 1.008),
('2013-04-20 16:25:00', 1, 2345.406, 0.948),
('2013-04-20 16:30:00', 1, 2345.479, 0.876),
('2013-04-20 16:35:00', 1, 2345.548, 0.828),
('2013-04-20 16:40:00', 1, 2345.612, 0.768),
('2013-04-20 16:45:00', 1, 2345.671, 0.708),
('2013-04-20 16:50:00', 1, 2345.725, 0.648),
('2013-04-20 16:55:00', 1, 2345.774, 0.588),
('2013-04-20 17:00:00', 1, 2345.819, 0.540),
('2013-04-20 17:05:00', 1, 2345.858, 0.468),
('2013-04-20 17:10:00', 1, 2345.893, 0.420),
('2013-04-20 17:15:00', 1, 2345.923, 0.360),
('2013-04-20 17:20:00', 1, 2345.949, 0.312),
('2013-04-20 17:25:00', 1, 2345.972, 0.276),
('2013-04-20 17:30:00', 1, 2345.991, 0.228),
('2013-04-20 17:35:00', 1, 2346.008, 0.204),
('2013-04-20 17:40:00', 1, 2346.022, 0.168),
('2013-04-20 17:45:00', 1, 2346.035, 0.156),
('2013-04-20 17:50:00', 1, 2346.047, 0.144),
('2013-04-20 17:55:00', 1, 2346.059, 0.144),
('2013-04-20 18:00:00', 1, 2346.071, 0.144),
('2013-04-20 18:05:00', 1, 2346.082, 0.132),
('2013-04-20 18:10:00', 1, 2346.094, 0.144),
('2013-04-20 18:15:00', 1, 2346.105, 0.132),
('2013-04-20 18:20:00', 1, 2346.116, 0.132),
('2013-04-20 18:25:00', 1, 2346.126, 0.120),
('2013-04-20 18:30:00', 1, 2346.137, 0.132),
('2013-04-20 18:35:00', 1, 2346.147, 0.120),
('2013-04-20 18:40:00', 1, 2346.157, 0.120),
('2013-04-20 18:45:00', 1, 2346.166, 0.108),
('2013-04-20 18:50:00', 1, 2346.176, 0.120),
('2013-04-20 18:55:00', 1, 2346.185, 0.108),
('2013-04-20 19:00:00', 1, 2346.194, 0.108),
('2013-04-20 19:05:00', 1, 2346.203, 0.108),
('2013-04-20 19:10:00', 1, 2346.211, 0.096),
('2013-04-20 19:15:00', 1, 2346.219, 0.096),
('2013-04-20 19:20:00', 1, 2346.226, 0.084),
('2013-04-20 19:25:00', 1, 2346.233, 0.084),
('2013-04-20 19:30:00', 1, 2346.240, 0.084),
('2013-04-20 19:35:00', 1, 2346.246, 0.072),
('2013-04-20 19:40:00', 1, 2346.251, 0.060),
('2013-04-20 19:45:00', 1, 2346.257, 0.072),
('2013-04-20 19:50:00', 1, 2346.261, 0.048),
('2013-04-20 19:55:00', 1, 2346.266, 0.060),
('2013-04-20 20:00:00', 1, 2346.270, 0.048),
('2013-04-20 20:05:00', 1, 2346.273, 0.036),
('2013-04-20 20:10:00', 1, 2346.276, 0.036),
('2013-04-20 20:15:00', 1, 2346.278, 0.024),
('2013-04-20 20:20:00', 1, 2346.280, 0.024),
('2013-04-20 20:25:00', 1, 2346.281, 0.012),
('2013-04-20 20:30:00', 1, 2346.281, 0.000),
('2013-04-20 20:35:00', 1, 2346.281, 0.000),
('2013-04-20 20:40:00', 1, 2346.281, 0.000),
('2013-04-20 20:45:00', 1, 2346.281, 0.000),
('2013-04-20 20:50:00', 1, 2346.281, 0.000),
('2013-04-20 20:55:00', 1, 2346.281, 0.000),
('2013-04-20 21:00:00', 1, 2346.281, 0.000),
('2013-04-20 21:05:00', 1, 2346.281, 0.000),
('2013-04-20 21:10:00', 1, 2346.281, 0.000),
('2013-04-20 21:15:00', 1, 2346.281, 0.000),
('2013-04-20 21:20:00', 1, 2346.281, 0.000),
('2013-04-20 21:25:00', 1, 2346.281, 0.000),
('2013-04-20 21:30:00', 1, 2346.281, 0.000),
('2013-04-20 21:35:00', 1, 2346.281, 0.000),
('2013-04-20 21:40:00', 1, 2346.281, 0.000),
('2013-04-20 21:45:00', 1, 2346.281, 0.000),
('2013-04-20 21:50:00', 1, 2346.281, 0.000),
('2013-04-20 21:55:00', 1, 2346.281, 0.000),
('2013-04-20 22:00:00', 1, 2346.281, 0.000),
('2013-04-20 22:05:00', 1, 2346.281, 0.000),
('2013-04-20 22:10:00', 1, 2346.281, 0.000),
('2013-04-20 22:15:00', 1, 2346.281, 0.000),
('2013-04-20 22:20:00', 1, 2346.281, 0.000),
('2013-04-20 22:25:00', 1, 2346.281, 0.000),
('2013-04-20 22:30:00', 1, 2346.281, 0.000),
('2013-04-20 22:35:00', 1, 2346.281, 0.000),
('2013-04-20 22:40:00', 1, 2346.281, 0.000),
('2013-04-20 22:45:00', 1, 2346.281, 0.000),
('2013-04-20 22:50:00', 1, 2346.281, 0.000),
('2013-04-20 22:55:00', 1, 2346.281, 0.000),
('2013-04-20 23:00:00', 1, 2346.281, 0.000),
('2013-04-20 23:05:00', 1, 2346.281, 0.000),
('2013-04-20 23:10:00', 1, 2346.281, 0.000),
('2013-04-20 23:15:00', 1, 2346.281, 0.000),
('2013-04-20 23:20:00', 1, 2346.281, 0.000),
('2013-04-20 23:25:00', 1, 2346.281, 0.000),
('2013-04-20 23:30:00', 1, 2346.281, 0.000),
('2013-04-20 23:35:00', 1, 2346.281, 0.000),
('2013-04-20 23:40:00', 1, 2346.281, 0.000),
('2013-04-20 23:45:00', 1, 2346.281, 0.000),
('2013-04-20 23:50:00', 1, 2346.281, 0.000),
('2013-04-20 23:55:00', 1, 2346.281, 0.000);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `devaccess`
--

CREATE TABLE IF NOT EXISTS `devaccess` (
  `deviceid` int(5) NOT NULL AUTO_INCREMENT,
  `userid` int(4) NOT NULL,
  PRIMARY KEY (`deviceid`,`userid`),
  KEY `userid_idx` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `devaccess`
--

INSERT INTO `devaccess` (`deviceid`, `userid`) VALUES
(1, 1),
(2, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `deviceid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  PRIMARY KEY (`deviceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `device`
--

INSERT INTO `device` (`deviceid`, `name`, `accepted`) VALUES
(1, 'Spot', 1),
(2, 'Spot2', 0),
(3, '2805GG', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `monthdata`
--

CREATE TABLE IF NOT EXISTS `monthdata` (
  `date` date NOT NULL,
  `deviceid` int(5) NOT NULL,
  `kWh` decimal(12,3) DEFAULT NULL,
  `kW` decimal(12,3) DEFAULT NULL,
  PRIMARY KEY (`date`),
  KEY `deviceid` (`deviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `monthdata`
--

INSERT INTO `monthdata` (`date`, `deviceid`, `kWh`, `kW`) VALUES
('2013-01-01', 1, 1683.204, 2.729),
('2013-01-02', 1, 1687.326, 4.122),
('2013-01-03', 1, 1688.143, 0.817),
('2013-01-04', 1, 1688.383, 0.240),
('2013-01-05', 1, 1688.414, 0.031),
('2013-01-06', 1, 1694.130, 5.716),
('2013-01-07', 1, 1694.247, 0.117),
('2013-01-08', 1, 1694.483, 0.236),
('2013-01-09', 1, 1694.703, 0.220),
('2013-01-10', 1, 1699.604, 4.901),
('2013-01-11', 1, 1707.268, 7.664),
('2013-01-12', 1, 1713.811, 6.543),
('2013-01-13', 1, 1724.057, 10.246),
('2013-01-14', 1, 1727.475, 3.418),
('2013-01-15', 1, 1730.225, 2.750),
('2013-01-16', 1, 1739.914, 9.689),
('2013-01-17', 1, 1741.230, 1.316),
('2013-01-18', 1, 1743.134, 1.904),
('2013-01-19', 1, 1745.031, 1.897),
('2013-01-20', 1, 1745.889, 0.858),
('2013-01-21', 1, 1746.177, 0.288),
('2013-01-22', 1, 1752.834, 6.657),
('2013-01-23', 1, 1758.787, 5.953),
('2013-01-24', 1, 1766.160, 7.373),
('2013-01-25', 1, 1775.322, 9.162),
('2013-01-26', 1, 1776.166, 0.844),
('2013-01-27', 1, 1777.508, 1.342),
('2013-01-28', 1, 1786.691, 9.183),
('2013-01-29', 1, 1786.933, 0.242),
('2013-01-30', 1, 1790.479, 3.546),
('2013-01-31', 1, 1794.696, 4.217),
('2013-02-01', 1, 1795.127, 0.431),
('2013-02-02', 1, 1803.980, 8.853),
('2013-02-03', 1, 1805.496, 1.516),
('2013-02-04', 1, 1813.619, 8.123),
('2013-02-05', 1, 1818.454, 4.835),
('2013-02-06', 1, 1826.966, 8.512),
('2013-02-07', 1, 1833.079, 6.113),
('2013-02-08', 1, 1841.745, 8.666),
('2013-02-09', 1, 1846.830, 5.085),
('2013-02-10', 1, 1856.444, 9.614),
('2013-02-11', 1, 1858.195, 1.751),
('2013-02-12', 1, 1859.597, 1.402),
('2013-02-13', 1, 1867.930, 8.333),
('2013-02-14', 1, 1868.427, 0.497),
('2013-02-15', 1, 1873.297, 4.870),
('2013-02-16', 1, 1875.052, 1.755),
('2013-02-17', 1, 1887.028, 11.976),
('2013-02-18', 1, 1898.140, 11.112),
('2013-02-19', 1, 1899.475, 1.335),
('2013-02-20', 1, 1911.208, 11.733),
('2013-02-21', 1, 1921.601, 10.393),
('2013-02-22', 1, 1929.669, 8.068),
('2013-02-23', 1, 1933.094, 3.425),
('2013-02-24', 1, 1933.683, 0.589),
('2013-02-25', 1, 1934.489, 0.806),
('2013-02-26', 1, 1936.115, 1.626),
('2013-02-27', 1, 1937.520, 1.405),
('2013-02-28', 1, 1941.570, 4.050),
('2013-03-01', 1, 1943.004, 1.434),
('2013-03-02', 1, 1945.025, 2.021),
('2013-03-03', 1, 1946.191, 1.166),
('2013-03-04', 1, 1961.793, 15.602),
('2013-03-05', 1, 1976.103, 14.310),
('2013-03-06', 1, 1980.865, 4.762),
('2013-03-07', 1, 1985.091, 4.226),
('2013-03-08', 1, 1991.447, 6.356),
('2013-03-09', 1, 1992.201, 0.754),
('2013-03-10', 1, 1992.972, 0.771),
('2013-03-11', 1, 1996.146, 3.174),
('2013-03-12', 1, 2004.250, 8.104),
('2013-03-13', 1, 2015.246, 10.996),
('2013-03-14', 1, 2025.674, 10.428),
('2013-03-15', 1, 2028.063, 2.389),
('2013-03-16', 1, 2031.744, 3.681),
('2013-03-17', 1, 2035.383, 3.639),
('2013-03-18', 1, 2044.843, 9.460),
('2013-03-19', 1, 2048.127, 3.284),
('2013-03-20', 1, 2051.787, 3.660),
('2013-03-21', 1, 2057.058, 5.271),
('2013-03-22', 1, 2068.820, 11.762),
('2013-03-23', 1, 2074.926, 6.106),
('2013-03-24', 1, 2078.588, 3.662),
('2013-03-25', 1, 2090.991, 12.403),
('2013-03-26', 1, 2106.812, 15.821),
('2013-03-27', 1, 2124.292, 17.480),
('2013-03-28', 1, 2137.081, 12.789),
('2013-03-29', 1, 2143.895, 6.814),
('2013-03-30', 1, 2150.806, 6.911),
('2013-03-31', 1, 2159.195, 8.389),
('2013-04-01', 1, 2174.768, 15.573),
('2013-04-02', 1, 2192.636, 17.868),
('2013-04-03', 1, 2201.970, 9.334),
('2013-04-04', 1, 2205.374, 3.404),
('2013-04-05', 1, 2212.636, 7.262),
('2013-04-06', 1, 2225.389, 12.753),
('2013-04-07', 1, 2237.634, 12.245),
('2013-04-08', 1, 2250.234, 12.600),
('2013-04-09', 1, 2256.955, 6.721),
('2013-04-10', 1, 2259.647, 2.692),
('2013-04-11', 1, 2261.023, 1.376),
('2013-04-12', 1, 2264.498, 3.475),
('2013-04-13', 1, 2272.992, 8.494),
('2013-04-14', 1, 2280.511, 7.519),
('2013-04-15', 1, 2288.839, 8.328),
('2013-04-16', 1, 2295.867, 7.028),
('2013-04-17', 1, 2303.203, 7.336),
('2013-04-18', 1, 2321.015, 17.812),
('2013-04-19', 1, 2329.383, 8.368),
('2013-04-20', 1, 2346.281, 16.898),
('2013-04-21', 1, 2363.522, 17.241),
('2013-04-22', 1, 2380.035, 16.513),
('2013-04-23', 1, 2385.867, 5.832),
('2013-04-24', 1, 2401.759, 15.892),
('2013-04-25', 1, 2409.967, 8.208),
('2013-04-26', 1, 2411.596, 1.629),
('2013-04-27', 1, 2427.367, 15.771),
('2013-04-28', 1, 2442.380, 15.013),
('2013-04-29', 1, 2452.318, 9.938),
('2013-04-30', 1, 2462.571, 10.253),
('2013-05-01', 1, 2480.240, 17.669),
('2013-05-02', 1, 2485.065, 4.825),
('2013-05-03', 1, 2499.810, 14.745),
('2013-05-04', 1, 2518.050, 18.240),
('2013-05-05', 1, 2535.337, 17.287),
('2013-05-06', 1, 2551.848, 16.511),
('2013-05-07', 1, 2564.210, 12.362),
('2013-05-08', 1, 2573.557, 9.347),
('2013-05-09', 1, 2586.362, 12.805),
('2013-05-10', 1, 2594.766, 8.404),
('2013-05-11', 1, 2601.019, 6.253),
('2013-05-12', 1, 2610.212, 9.193),
('2013-05-13', 1, 2616.929, 6.717),
('2013-05-14', 1, 2623.447, 6.518),
('2013-05-15', 1, 2633.281, 9.834),
('2013-05-16', 1, 2635.608, 2.327),
('2013-05-17', 1, 2637.357, 1.749),
('2013-05-18', 1, 2641.078, 3.721),
('2013-05-19', 1, 2656.820, 15.742),
('2013-05-20', 1, 2658.230, 1.410),
('2013-05-21', 1, 2659.971, 1.741),
('2013-05-22', 1, 2669.717, 9.746),
('2013-05-23', 1, 2680.707, 10.990),
('2013-05-24', 1, 2686.823, 6.116),
('2013-05-25', 1, 2696.967, 10.144),
('2013-05-26', 1, 2706.002, 9.035),
('2013-05-27', 1, 2723.233, 17.231),
('2013-05-28', 1, 2739.955, 16.722),
('2013-05-29', 1, 2742.431, 2.476),
('2013-05-30', 1, 2748.641, 6.210),
('2013-05-31', 1, 2762.385, 13.744),
('2013-06-01', 1, 2767.018, 4.633),
('2013-06-02', 1, 2784.394, 17.376),
('2013-06-03', 1, 2793.660, 9.266),
('2013-06-04', 1, 2809.657, 15.997),
('2013-06-05', 1, 2826.600, 16.943),
('2013-06-06', 1, 2842.466, 15.866),
('2013-06-07', 1, 2858.564, 16.098),
('2013-06-08', 1, 2875.022, 16.458),
('2013-06-09', 1, 2882.130, 7.108),
('2013-06-10', 1, 2888.400, 6.270),
('2013-06-11', 1, 2899.731, 11.331),
('2013-06-12', 1, 2905.442, 5.711),
('2013-06-13', 1, 2912.838, 7.396),
('2013-06-14', 1, 2925.154, 12.316),
('2013-06-15', 1, 2937.263, 12.109),
('2013-06-16', 1, 2951.081, 13.818),
('2013-06-17', 1, 2962.934, 11.853),
('2013-06-18', 1, 2976.469, 13.535),
('2013-06-19', 1, 2983.125, 6.656),
('2013-06-20', 1, 2989.238, 6.113),
('2013-06-21', 1, 2991.440, 2.202),
('2013-06-22', 1, 2997.244, 5.804),
('2013-06-23', 1, 3005.773, 8.529),
('2013-06-24', 1, 3011.029, 5.256),
('2013-06-25', 1, 3025.083, 14.054),
('2013-06-26', 1, 3035.046, 9.963),
('2013-06-27', 1, 3044.215, 9.169),
('2013-06-28', 1, 3049.667, 5.452),
('2013-06-29', 1, 3059.867, 10.200),
('2013-06-30', 1, 3071.940, 12.073),
('2013-07-01', 1, 3080.113, 8.173),
('2013-07-02', 1, 3092.755, 12.642),
('2013-07-03', 1, 3095.607, 2.852),
('2013-07-04', 1, 3106.292, 10.685),
('2013-07-05', 1, 3120.051, 13.759),
('2013-07-06', 1, 3134.813, 14.762),
('2013-07-07', 1, 3151.152, 16.339),
('2013-07-08', 1, 3167.223, 16.071),
('2013-07-09', 1, 3183.561, 16.338),
('2013-07-10', 1, 3193.432, 9.871),
('2013-07-11', 1, 3202.469, 9.037),
('2013-07-12', 1, 3208.827, 6.358),
('2013-07-13', 1, 3222.108, 13.281),
('2013-07-14', 1, 3230.605, 8.497),
('2013-07-15', 1, 3246.362, 15.757);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `roles` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `salt`, `roles`) VALUES
(1, 'future500', '1mcK4fAbZK/bn5hsP9j7MJZUpYbHPIdaG+idZzGtDLc0OjMxZ6n2rlxpSZ852fGVdYzk18bSLyZwhriYA8sOVg==', 'pxvP8/++XGplhYjTR95/w+NOFJnU/t4NiA==', 'ROLE_ADMIN'),
(2, 'testuser', 'R+ml5cl9cWk7XTEUVQ3U6ULRZbW8vGrlzX579X1tYxBFfas5yU//0qvzSWJuvhn7L58odXYs3lDEMiaT66oucQ==', '3bMwOBKkT68V8Z+JFArMFDhJeoaCtzij3w==', 'ROLE_USER');

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `daydata`
--
ALTER TABLE `daydata`
  ADD CONSTRAINT `daydata_ibfk_1` FOREIGN KEY (`deviceid`) REFERENCES `device` (`deviceid`);

--
-- Beperkingen voor tabel `devaccess`
--
ALTER TABLE `devaccess`
  ADD CONSTRAINT `deviceid` FOREIGN KEY (`deviceid`) REFERENCES `device` (`deviceid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`);

--
-- Beperkingen voor tabel `monthdata`
--
ALTER TABLE `monthdata`
  ADD CONSTRAINT `monthdata_ibfk_1` FOREIGN KEY (`deviceid`) REFERENCES `device` (`deviceid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;