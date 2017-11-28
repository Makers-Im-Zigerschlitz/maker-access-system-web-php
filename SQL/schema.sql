CREATE TABLE tblUsers (
  uid INTEGER(11)  NOT NULL   AUTO_INCREMENT,
  username VARCHAR(40)  NOT NULL  ,
  password VARCHAR(50)  NOT NULL  ,
  level INTEGER(1)  NOT NULL  ,
  memberID INTEGER UNSIGNED  NULL    ,
PRIMARY KEY(uid)  ,
INDEX username(username));



CREATE TABLE tblRoles (
  RoleID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  RoleName TEXT  NULL    ,
PRIMARY KEY(RoleID));



CREATE TABLE tblUploads (
  upid INTEGER(11)  NOT NULL  ,
  filename VARCHAR(150)  NOT NULL  ,
  title VARCHAR(40)  NOT NULL  ,
  uploader VARCHAR(40)  NOT NULL    ,
PRIMARY KEY(upid));



CREATE TABLE tblDevices (
  deviceID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  deviceName TEXT  NULL  ,
  deviceDesc TEXT  NULL    ,
PRIMARY KEY(deviceID));



CREATE TABLE tblNews (
  nid INTEGER(11)  NOT NULL  ,
  title VARCHAR(40)  NOT NULL  ,
  text LONGTEXT  NOT NULL  ,
  author VARCHAR(40)  NULL    ,
PRIMARY KEY(nid));



CREATE TABLE tblMembers (
  memberID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT,
  uid INTEGER(11)  NOT NULL  ,
  Firstname TEXT  NULL  ,
  Lastname TEXT  NULL  ,
  Birthday DATE  NULL  ,
  Phone TEXT  NULL  ,
  Street TEXT  NULL  ,
  City TEXT  NULL  ,
  Country TEXT  NULL    ,
PRIMARY KEY(memberID, uid),
  FOREIGN KEY(uid)
    REFERENCES tblUsers(uid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



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




