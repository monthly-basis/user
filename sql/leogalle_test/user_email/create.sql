CREATE TABLE `user_email` (
      `user_id` int(10) unsigned NOT NULL,
      `address` varchar(255) NOT NULL,
      PRIMARY KEY (`user_id`,`address`),
      UNIQUE KEY `address` (`address`)
) ENGINE=InnoDB CHARSET=utf8 
