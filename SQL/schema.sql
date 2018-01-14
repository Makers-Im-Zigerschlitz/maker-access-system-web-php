SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `tblDevices` (
`deviceID` int(10) unsigned NOT NULL,
  `deviceName` text,
  `deviceDesc` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `tblNews` (
  `nid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `text` longtext NOT NULL,
  `author` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tblPermissions` (
`permissionID` int(10) unsigned NOT NULL,
  `tblRoles_RoleID` int(10) unsigned NOT NULL,
  `tblDevices_deviceID` int(10) unsigned NOT NULL,
  `memberID` int(10) unsigned DEFAULT NULL,
  `deviceID` int(10) unsigned DEFAULT NULL,
  `Role` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
MODIFY `deviceID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblMembers`
MODIFY `memberID` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblPermissions`
MODIFY `permissionID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblRoles`
MODIFY `RoleID` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUploads`
MODIFY `upid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tblUsers`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
