Maker Access System
====================
Idea
----
The idea of this project is to create a management system for makerspaces.
So it should be possible to manage the members and their permissions.
The members can learn the needed stuff in courses. After they did the course, they will be able to use the device.

So on example if you want to use the 3D-Printer, you have to do a 3D-Printer-Course. After you did that the admin can allow you to use the 3D-Printer.
Everytime you want to use the printer, you have to authorize yourself with an RFID-Tag. The Arduino will check your permissions and allow the usage if you did the course.
***

Installation
----
The installation of the MAS is pretty easy. All you have to do is to import the SQL-Schema into your MySQL-Server.
After that, you have to copy all elements from the WebService/portal folder to your webroot.
Then you rename the config.inc.php.example to config.inc.php and set the parameters.

You're done!
The admin credentials are: Username: admin & Password: admin
***
If you have any greate ideas how to extend the system, let me know!
