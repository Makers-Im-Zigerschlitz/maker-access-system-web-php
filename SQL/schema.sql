CREATE DATABASE IF NOT EXISTS `mas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mas`;

CREATE TABLE `tblDevices` (
  `deviceID` int(10) UNSIGNED NOT NULL,
  `deviceName` text,
  `deviceDesc` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblMembers` (
  `memberID` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `Firstname` text,
  `Lastname` text,
  `Birthday` date DEFAULT NULL,
  `Mail` text,
  `Phone` text,
  `Street` text,
  `PLZ` int(11) DEFAULT NULL,
  `City` text,
  `Country` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblNews` (
  `nid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `text` longtext NOT NULL,
  `author` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblPermissions` (
  `permissionID` int(10) UNSIGNED NOT NULL,
  `tblRoles_RoleID` int(10) UNSIGNED NOT NULL,
  `tblDevices_deviceID` int(10) UNSIGNED NOT NULL,
  `memberID` int(10) UNSIGNED DEFAULT NULL,
  `deviceID` int(10) UNSIGNED DEFAULT NULL,
  `Role` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblRoles` (
  `RoleID` int(10) UNSIGNED NOT NULL,
  `RoleName` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblUploads` (
  `upid` int(11) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `title` varchar(40) NOT NULL,
  `uploader` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tblUsers` (
  `uid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` int(1) NOT NULL,
  `memberID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `tblDevices`
  ADD PRIMARY KEY (`deviceID`);

ALTER TABLE `tblMembers`
  ADD PRIMARY KEY (`memberID`,`uid`),
  ADD KEY `uid` (`uid`);

ALTER TABLE `tblNews`
  ADD PRIMARY KEY (`nid`);

ALTER TABLE `tblPermissions`
  ADD PRIMARY KEY (`permissionID`,`tblRoles_RoleID`,`tblDevices_deviceID`),
  ADD KEY `tblPermissions_FKIndex1` (`tblRoles_RoleID`),
  ADD KEY `tblPermissions_FKIndex2` (`tblDevices_deviceID`);

ALTER TABLE `tblRoles`
  ADD PRIMARY KEY (`RoleID`);

ALTER TABLE `tblUploads`
  ADD PRIMARY KEY (`upid`);

ALTER TABLE `tblUsers`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `username` (`username`);


ALTER TABLE `tblDevices`
  MODIFY `deviceID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tblMembers`
  MODIFY `memberID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `tblPermissions`
  MODIFY `permissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tblRoles`
  MODIFY `RoleID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `tblUsers`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `tblMembers`
  ADD CONSTRAINT `tblMembers_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tblUsers` (`uid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `tblPermissions`
  ADD CONSTRAINT `tblPermissions_ibfk_1` FOREIGN KEY (`tblRoles_RoleID`) REFERENCES `tblRoles` (`RoleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tblPermissions_ibfk_2` FOREIGN KEY (`tblDevices_deviceID`) REFERENCES `tblDevices` (`deviceID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
