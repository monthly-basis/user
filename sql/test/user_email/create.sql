CREATE TABLE `user_email` (
    `user_id` int(10) unsigned NOT NULL,
    `address` varchar(255) NOT NULL,
    PRIMARY KEY (`user_id`,`address`),
    UNIQUE KEY `address` (`address`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
