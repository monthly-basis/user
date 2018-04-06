CREATE TABLE `login_log` (
    `login_log_id` int(10) unsigned auto_increment,
    `ip` varchar(45) not null,
    `success` int unsigned not null,
    `created` datetime not null,
    PRIMARY KEY (`login_log_id`),
    KEY `created` (`created`)
) charset=utf8;
