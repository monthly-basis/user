CREATE TABLE `user` (
    `user_id` int(10) unsigned auto_increment,
    `username` varchar(255) default null,
    `password_hash` varchar(255) default null,
    `birthday` DATETIME default null,
    `gender` CHAR(1) DEFAULT NULL,
    `display_name` varchar(255) default null,
    `welcome_message` text default null,
    `login_datetime` datetime default null,
    `views` int unsigned not null default 0,
    `created` datetime not null,
    `deleted_datetime` datetime DEFAULT NULL,
    `deleted_user_id` int(10) DEFAULT NULL,
    `deleted_reason` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`),
    KEY `created` (`created`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
