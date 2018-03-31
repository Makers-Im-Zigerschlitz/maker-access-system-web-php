SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `tblBookings` (
`evtID` int(11) NOT NULL,
  `resourceId` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `description` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tblDevices` (
`deviceID` int(10) unsigned NOT NULL,
  `deviceName` text,
  `deviceDesc` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblLogs` (
`logID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  `uid` text COLLATE utf8_unicode_ci NOT NULL,
  `deviceID` text COLLATE utf8_unicode_ci,
  `r_host` text COLLATE utf8_unicode_ci,
  `logCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `tblNews` (
`nid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `text` longtext NOT NULL,
  `author` varchar(40) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblPermissions` (
`permID` int(10) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `uid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblRoles` (
`RoleID` int(10) unsigned NOT NULL,
  `RoleName` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblSettings` (
`setID` int(11) NOT NULL,
  `settingName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `settingValue` varchar(80) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tblTags` (
  `tagID` text COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `tblBookings`
 ADD PRIMARY KEY (`evtID`);

ALTER TABLE `tblDevices`
 ADD PRIMARY KEY (`deviceID`);

ALTER TABLE `tblLogs`
 ADD PRIMARY KEY (`logID`);

ALTER TABLE `tblMembers`
 ADD PRIMARY KEY (`memberID`,`uid`), ADD KEY `uid` (`uid`);

ALTER TABLE `tblNews`
 ADD PRIMARY KEY (`nid`);

ALTER TABLE `tblPermissions`
 ADD PRIMARY KEY (`permID`), ADD UNIQUE KEY `Role` (`permID`);

ALTER TABLE `tblRoles`
 ADD PRIMARY KEY (`RoleID`);

ALTER TABLE `tblSettings`
 ADD PRIMARY KEY (`setID`), ADD UNIQUE KEY `settingName` (`settingName`);

ALTER TABLE `tblUploads`
 ADD PRIMARY KEY (`upid`);

ALTER TABLE `tblUsers`
 ADD PRIMARY KEY (`uid`), ADD KEY `username` (`username`);


ALTER TABLE `tblBookings`
MODIFY `evtID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblDevices`
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblLogs`
MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblMembers`
MODIFY `memberID` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblNews`
MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblPermissions`
MODIFY `permID` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblRoles`
MODIFY `RoleID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblSettings`
MODIFY `setID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUploads`
MODIFY `upid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUsers`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
