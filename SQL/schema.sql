-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Jan 2018 um 17:53
-- Server Version: 5.5.58-0+deb8u1
-- PHP-Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `mas_schema`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblDevices`
--

CREATE TABLE IF NOT EXISTS `tblDevices` (
`deviceID` int(10) unsigned NOT NULL,
  `deviceName` text,
  `deviceDesc` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblMembers`
--

CREATE TABLE IF NOT EXISTS `tblMembers` (
`memberID` int(10) NOT NULL,
  `uid` int(11) NOT NULL,
  `Firstname` text,
  `Lastname` text,
  `Birthday` date DEFAULT NULL,
  `Phone` text,
  `Mail` text NOT NULL,
  `Street` text,
  `City` text,
  `Country` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblNews`
--

CREATE TABLE IF NOT EXISTS `tblNews` (
`nid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `text` longtext NOT NULL,
  `author` varchar(40) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblPermissions`
--

CREATE TABLE IF NOT EXISTS `tblPermissions` (
`permID` int(10) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `uid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblRoles`
--

CREATE TABLE IF NOT EXISTS `tblRoles` (
`RoleID` int(10) unsigned NOT NULL,
  `RoleName` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblTags`
--

CREATE TABLE IF NOT EXISTS `tblTags` (
  `tagID` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblUploads`
--

CREATE TABLE IF NOT EXISTS `tblUploads` (
`upid` int(11) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `title` varchar(40) NOT NULL,
  `uploader` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblUsers`
--

CREATE TABLE IF NOT EXISTS `tblUsers` (
`uid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tblUsers`
--

INSERT INTO `tblUsers` (`uid`, `username`, `password`, `level`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 4);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tblDevices`
--
ALTER TABLE `tblDevices`
 ADD PRIMARY KEY (`deviceID`);

--
-- Indizes für die Tabelle `tblMembers`
--
ALTER TABLE `tblMembers`
 ADD PRIMARY KEY (`memberID`,`uid`), ADD KEY `uid` (`uid`);

--
-- Indizes für die Tabelle `tblNews`
--
ALTER TABLE `tblNews`
 ADD PRIMARY KEY (`nid`);

--
-- Indizes für die Tabelle `tblPermissions`
--
ALTER TABLE `tblPermissions`
 ADD PRIMARY KEY (`permID`), ADD UNIQUE KEY `Role` (`permID`);

--
-- Indizes für die Tabelle `tblRoles`
--
ALTER TABLE `tblRoles`
 ADD PRIMARY KEY (`RoleID`);

--
-- Indizes für die Tabelle `tblUploads`
--
ALTER TABLE `tblUploads`
 ADD PRIMARY KEY (`upid`);

--
-- Indizes für die Tabelle `tblUsers`
--
ALTER TABLE `tblUsers`
 ADD PRIMARY KEY (`uid`), ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tblDevices`
--
ALTER TABLE `tblDevices`
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblMembers`
--
ALTER TABLE `tblMembers`
MODIFY `memberID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblNews`
--
ALTER TABLE `tblNews`
MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblPermissions`
--
ALTER TABLE `tblPermissions`
MODIFY `permID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblRoles`
--
ALTER TABLE `tblRoles`
MODIFY `RoleID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblUploads`
--
ALTER TABLE `tblUploads`
MODIFY `upid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblUsers`
--
ALTER TABLE `tblUsers`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
