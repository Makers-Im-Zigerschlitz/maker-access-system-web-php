-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 27. Mrz 2018 um 09:41
-- Server Version: 5.5.58-0+deb8u1
-- PHP-Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `n15c_makers_auth`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblBookings`
--

CREATE TABLE IF NOT EXISTS `tblBookings` (
`evtID` int(11) NOT NULL,
  `resourceId` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `description` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Tabellenstruktur für Tabelle `tblLogs`
--

CREATE TABLE IF NOT EXISTS `tblLogs` (
`logID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  `uid` text COLLATE utf8_unicode_ci NOT NULL,
  `deviceID` text COLLATE utf8_unicode_ci,
  `r_host` text COLLATE utf8_unicode_ci,
  `logCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblMembers`
--

CREATE TABLE IF NOT EXISTS `tblMembers` (
`memberID` int(10) NOT NULL,
  `uid` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `Firstname` text,
  `Lastname` text,
  `Birthday` date DEFAULT NULL,
  `Phone` text,
  `Mail` text NOT NULL,
  `Street` text,
  `ZIP` text,
  `City` text,
  `Country` text,
  `Membership_Start` date DEFAULT NULL,
  `Membership_End` date DEFAULT NULL
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
  `tagID` text COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tblBookings`
--
ALTER TABLE `tblBookings`
 ADD PRIMARY KEY (`evtID`);

--
-- Indizes für die Tabelle `tblDevices`
--
ALTER TABLE `tblDevices`
 ADD PRIMARY KEY (`deviceID`);

--
-- Indizes für die Tabelle `tblLogs`
--
ALTER TABLE `tblLogs`
 ADD PRIMARY KEY (`logID`);

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
-- AUTO_INCREMENT für Tabelle `tblBookings`
--
ALTER TABLE `tblBookings`
MODIFY `evtID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblDevices`
--
ALTER TABLE `tblDevices`
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tblLogs`
--
ALTER TABLE `tblLogs`
MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT;
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
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
