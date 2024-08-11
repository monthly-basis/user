CREATE TABLE `user` (
    `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `emoji_12_id` int(10) unsigned DEFAULT NULL,
    `username` varchar(255) DEFAULT NULL,
    `password_hash` varchar(255) DEFAULT NULL,
    `birthday` datetime DEFAULT NULL,
    `gender` char(1) DEFAULT NULL,
    `display_name` varchar(255) DEFAULT NULL,
    `welcome_message` text DEFAULT NULL,
    `login_datetime` datetime DEFAULT NULL,
    `views` int unsigned NOT NULL default 0,
    `created` datetime NOT NULL,
    `deleted_datetime` datetime DEFAULT NULL,
    `deleted_user_id` int(10) DEFAULT NULL,
    `deleted_reason` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`),
    KEY `created` (`created`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
