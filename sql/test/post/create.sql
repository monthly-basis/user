CREATE TABLE `post` (
    `post_id` int(10) unsigned auto_increment,
    `from_user_id` int(10) unsigned not null,
    `to_user_id` int(10) unsigned not null,
    `message` text not null,
    `created` datetime not null,
    PRIMARY KEY (`post_id`),
    KEY `to_user_id` (`to_user_id`),
    CONSTRAINT `from_user_id` FOREIGN KEY (`from_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `to_user_id` FOREIGN KEY (`to_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
