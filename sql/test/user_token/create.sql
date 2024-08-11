CREATE TABLE `user_token` (
    `user_token_id` int unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int unsigned NOT NULL,
    `login_token` varchar(256) NOT NULL,
    `https_token` varchar(256) NOT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires` datetime NOT NULL,
    `deleted` datetime DEFAULT NULL,
    PRIMARY KEY (`user_token_id`),
    KEY `user_id_login_token_expires_deleted` (`user_id`, `login_token`, `expires`, `deleted`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
