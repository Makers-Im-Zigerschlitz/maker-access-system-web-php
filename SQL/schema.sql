<<<<<<< HEAD
-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
<<<<<<< HEAD
-- Erstellungszeit: 16. Jan 2018 um 17:53
=======
-- Erstellungszeit: 16. Jan 2018 um 12:55
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
-- Server Version: 5.5.58-0+deb8u1
-- PHP-Version: 5.6.30-0+deb8u1

=======
>>>>>>> parent of ffaefd7... Another Update
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

<<<<<<< HEAD
--
<<<<<<< HEAD
-- Datenbank: `mas_schema`
=======
-- Datenbank: `access_Schema`
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblDevices`
--
=======
>>>>>>> parent of ffaefd7... Another Update

CREATE TABLE IF NOT EXISTS `tblDevices` (
`deviceID` int(10) unsigned NOT NULL,
  `deviceName` text,
  `deviceDesc` text
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblNews`
--

CREATE TABLE IF NOT EXISTS `tblNews` (
  `nid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `text` longtext NOT NULL,
  `author` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblPermissions` (
<<<<<<< HEAD
`permID` int(10) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `uid` text NOT NULL
<<<<<<< HEAD
=======
`permissionID` int(10) unsigned NOT NULL,
  `tblRoles_RoleID` int(10) unsigned NOT NULL,
  `tblDevices_deviceID` int(10) unsigned NOT NULL,
  `memberID` int(10) unsigned DEFAULT NULL,
  `deviceID` int(10) unsigned DEFAULT NULL,
  `Role` int(10) unsigned DEFAULT NULL
>>>>>>> parent of ffaefd7... Another Update
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
=======
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tblRoles`
--
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1

CREATE TABLE IF NOT EXISTS `tblRoles` (
`RoleID` int(10) unsigned NOT NULL,
  `RoleName` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblUploads` (
`upid` int(11) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `title` varchar(40) NOT NULL,
  `uploader` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblUsers` (
`uid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` int(1) NOT NULL
<<<<<<< HEAD
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
=======
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1

--
-- Daten für Tabelle `tblUsers`
--
=======
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
>>>>>>> parent of ffaefd7... Another Update

INSERT INTO `tblUsers` (`uid`, `username`, `password`, `level`) VALUES
(0, 'admin', '21232f297a57a5a743894a0e4a801fc3', 4);


ALTER TABLE `tblDevices`
 ADD PRIMARY KEY (`deviceID`);

ALTER TABLE `tblMembers`
 ADD PRIMARY KEY (`memberID`,`uid`), ADD KEY `uid` (`uid`);

ALTER TABLE `tblNews`
 ADD PRIMARY KEY (`nid`);

ALTER TABLE `tblPermissions`
 ADD PRIMARY KEY (`permissionID`,`tblRoles_RoleID`,`tblDevices_deviceID`), ADD KEY `tblPermissions_FKIndex1` (`tblRoles_RoleID`), ADD KEY `tblPermissions_FKIndex2` (`tblDevices_deviceID`);

ALTER TABLE `tblRoles`
 ADD PRIMARY KEY (`RoleID`);

ALTER TABLE `tblUploads`
 ADD PRIMARY KEY (`upid`);

ALTER TABLE `tblUsers`
 ADD PRIMARY KEY (`uid`), ADD KEY `username` (`username`);


ALTER TABLE `tblDevices`
<<<<<<< HEAD
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT;
<<<<<<< HEAD
=======
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
--
-- AUTO_INCREMENT für Tabelle `tblMembers`
--
=======
>>>>>>> parent of ffaefd7... Another Update
ALTER TABLE `tblMembers`
<<<<<<< HEAD
MODIFY `memberID` int(10) NOT NULL AUTO_INCREMENT;
<<<<<<< HEAD
--
-- AUTO_INCREMENT für Tabelle `tblNews`
--
ALTER TABLE `tblNews`
MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;
=======
MODIFY `memberID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
--
-- AUTO_INCREMENT für Tabelle `tblPermissions`
--
ALTER TABLE `tblPermissions`
<<<<<<< HEAD
MODIFY `permID` int(10) NOT NULL AUTO_INCREMENT;
=======
MODIFY `permID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
--
-- AUTO_INCREMENT für Tabelle `tblRoles`
--
=======
ALTER TABLE `tblPermissions`
MODIFY `permissionID` int(10) unsigned NOT NULL AUTO_INCREMENT;
>>>>>>> parent of ffaefd7... Another Update
ALTER TABLE `tblRoles`
MODIFY `RoleID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUploads`
MODIFY `upid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUsers`
<<<<<<< HEAD
<<<<<<< HEAD
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
=======
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
>>>>>>> 64b4e169643060b871332e2ea4e9cd15eef2faf1
=======
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
>>>>>>> parent of ffaefd7... Another Update
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
