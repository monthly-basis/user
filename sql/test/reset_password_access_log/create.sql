CREATE TABLE `reset_password_access_log` (
    `reset_password_access_log_id` int(10) unsigned auto_increment,
    `ip` varchar(45) not null,
    `valid` tinyint(1) not null,
    `created` datetime not null,
    PRIMARY KEY (`reset_password_access_log_id`)
) charset=utf8;
