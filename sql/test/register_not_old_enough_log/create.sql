CREATE TABLE `register_not_old_enough_log` (
    `register_not_old_enough_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(45) NOT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`register_not_old_enough_log_id`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
