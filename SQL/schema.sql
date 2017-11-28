CREATE TABLE tblRoles (
  RoleID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  RoleName TEXT  NULL    ,
PRIMARY KEY(RoleID));



CREATE TABLE tblDevices (
  deviceID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  deviceName TEXT  NULL  ,
  deviceDesc TEXT  NULL    ,
PRIMARY KEY(deviceID));



CREATE TABLE tblPermissions (
  permissionID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  tblRoles_RoleID INTEGER UNSIGNED  NOT NULL  ,
  tblDevices_deviceID INTEGER UNSIGNED  NOT NULL  ,
  memberID INTEGER UNSIGNED  NULL  ,
  deviceID INTEGER UNSIGNED  NULL  ,
  Role INTEGER UNSIGNED  NULL    ,
PRIMARY KEY(permissionID, tblRoles_RoleID, tblDevices_deviceID)  ,
INDEX tblPermissions_FKIndex1(tblRoles_RoleID)  ,
INDEX tblPermissions_FKIndex2(tblDevices_deviceID),
  FOREIGN KEY(tblRoles_RoleID)
    REFERENCES tblRoles(RoleID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(tblDevices_deviceID)
    REFERENCES tblDevices(deviceID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE tblMembers (
  memberID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  tblPermissions_tblDevices_deviceID INTEGER UNSIGNED  NOT NULL  ,
  tblPermissions_tblRoles_RoleID INTEGER UNSIGNED  NOT NULL  ,
  tblPermissions_permissionID INTEGER UNSIGNED  NOT NULL  ,
  Surname TEXT  NULL  ,
  Lastname TEXT  NULL  ,
  Birthday DATE  NULL  ,
  Phone VARCHAR  NULL  ,
  Street TEXT  NULL  ,
  City TEXT  NULL  ,
  Country TEXT  NULL    ,
PRIMARY KEY(memberID, tblPermissions_tblDevices_deviceID, tblPermissions_tblRoles_RoleID, tblPermissions_permissionID)  ,
INDEX tblMembers_FKIndex1(tblPermissions_permissionID, tblPermissions_tblRoles_RoleID, tblPermissions_tblDevices_deviceID),
  FOREIGN KEY(tblPermissions_permissionID, tblPermissions_tblRoles_RoleID, tblPermissions_tblDevices_deviceID)
    REFERENCES tblPermissions(permissionID, tblRoles_RoleID, tblDevices_deviceID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);




