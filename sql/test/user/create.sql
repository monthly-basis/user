CREATE TABLE `user` (
    `user_id` bigint unsigned auto_increment,
    `username` varchar(255) default null,
    `password_hash` varchar(255) default null,
    `birthday` DATETIME default null,
    `display_name` varchar(255) default null,
    `welcome_message` text default null,
    `login_datetime` datetime default null,
    `login_hash` varchar(255) default null,
    `login_ip` varchar(45) default null,
    `views` int unsigned not null default 0,
    `created` datetime not null,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
