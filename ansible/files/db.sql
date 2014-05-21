-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 17 mrt 2014 om 14:11
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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `devaccess`
--

CREATE TABLE IF NOT EXISTS `devaccess` (
  `deviceid` int(5) NOT NULL AUTO_INCREMENT,
  `userid` int(4) NOT NULL,
  PRIMARY KEY (`deviceid`,`userid`),
  KEY `userid_idx` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `deviceid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  PRIMARY KEY (`deviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;


INSERT INTO `user` (`userid`, `username`, `password`, `salt`, `roles`) VALUES
  (1, 'future500', 'ao78rDM1T3k9ton+ByZUuJekp5lepF4xeY1P746NC2IARSDDVKgV8mf8QUscgCN/zRjpJgjw88I6W2yyw/OxMw==', 'Za0i95SzMo9BcaTiHzBKJjTR/QqO1gmX8A==', 'ROLE_ADMIN');

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
