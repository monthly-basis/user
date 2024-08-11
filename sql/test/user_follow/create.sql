CREATE TABLE `user_follow` (
    `user_id_1` int(10) unsigned NOT NULL,
    `user_id_2` int(10) unsigned NOT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id_1`, `user_id_2`),
    KEY `user_id_2` (`user_id_2`),
    KEY `created` (`created`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
