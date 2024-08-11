CREATE TABLE `activate_log` (
    `activate_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(45) NOT NULL,
    `success` tinyint(1) unsigned NOT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`activate_log_id`),
    KEY `ip_address_success` (`ip_address`, `success`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
