Maker Access System
====================

![](https://img.shields.io/badge/build-passed-green.svg) ![](https://img.shields.io/badge/made%20by-miz-blue.svg)

Idea
----
The idea of this project is to create a management system for makerspaces.
So it should be possible to manage the members and their permissions.
The members can learn the needed stuff in courses. After they did the course, they will be able to use the device.

So on example if you want to use the 3D-Printer, you have to do a 3D-Printer-Course. After you did that the admin can allow you to use the 3D-Printer.
Everytime you want to use the printer, you have to authorize yourself with an RFID-Tag. The Arduino will check your permissions and allow the usage if you did the course.
___

Installation
----
1. Navigate to the server + /installer
2. Now the dependency-checks will be done
3. If everything is ok, you will be able to continue and enter your page informations
4. After you have set everything, the sql connection will be tested
5. If everything is fine, you can continue and the php will install the schema and create the first user
6. After everything is done please ***DELETE*** the installer folder.
7. Now you can continue by clicking the button. You will be redirected to the loginpage.
8. Now you can login using the username "admin" and the password you have choosen before.

*Warning:* The admin user has no membership, so it won't be listed as a member. There are some other restrictions.

***You're done!***
___
If you have any greate ideas how to extend the system, let me know!
