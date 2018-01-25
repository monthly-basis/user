CREATE TABLE `register` (
      `register_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `activation_code` int(10) NOT NULL,
      `username` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `password_hash` varchar(255) NOT NULL,
      `activated` tinyint(1) unsigned NOT NULL DEFAULT '0',
      PRIMARY KEY (`register_id`),
      UNIQUE KEY `register_id_activation_code` (`register_id`,`activation_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 CHARSET=utf8
