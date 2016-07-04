# RNDLabs
Smiggle messaging platform

Installation:

1. Put all the file into your web directory
2. Install php5 or ensure that php5 works
3. Install a mysql-server on your machine
4. Assign this password to the root user(created by default when you install mysql-server); weakpassword
5. Log into mysql server with the username; root and password; weakpassword
6. Create a database titled; smiggle_chat
7. After creating the database, run this command on the mysql server cli; use smiggle_chat;
8. Then run these commands to create the necessary; CREATE TABLE `smiggle_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;

CREATE TABLE `smiggle_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `dest_id` int(11) NOT NULL,
  `message_content` varchar(255) NOT NULL,
  `message_sent_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `smiggle_messages_fk1` (`source_id`),
  KEY `smiggle_messages_fk2` (`dest_id`),
  CONSTRAINT `smiggle_messages_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `smiggle_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `smiggle_messages_ibfk_2` FOREIGN KEY (`dest_id`) REFERENCES `smiggle_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

9. You will then have all the necessary resources to operate the platform
10. Finally to access the platform; run the index.php file in the directory where you stored the files. Ideally; http://localhost/smiggle_chat/index.php
11. For any questions or support email me at; glenton92@gmail.com
