CREATE TABLE `login_token` (
    `login_token_id` int unsigned NOT NULL AUTO_INCREMENT,
    `login_token` varchar(256) DEFAULT NULL,
    `login_ip` varchar(45) NOT NULL,
    `user_id` int unsigned NOT NULL,
    `created` datetime NOT NULL,
    `expires` datetime NOT NULL,
    `deleted` datetime DEFAULT NULL,
    PRIMARY KEY (`login_token_id`),
    KEY `user_id_login_token_expires_deleted` (`user_id`, `login_token`, `expires`, `deleted`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
