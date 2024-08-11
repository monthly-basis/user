CREATE TABLE `reset_password` (
    `reset_password_id` int(10) unsigned auto_increment,
    `user_id` int(10) unsigned not null,
    `code` varchar(32) not null,
    `created` datetime not null,
    `accessed` datetime DEFAULT NULL,
    `used` datetime DEFAULT NULL,
    PRIMARY KEY (`reset_password_id`),
    KEY (`code`),
    KEY (`user_id`, `code`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
