CREATE TABLE `user` (
    `user_id` int(10) unsigned auto_increment,
    `username` varchar(255) not null,
    `password_hash` varchar(255) not null,
    `welcome_message` text default null,
    `login_hash` varchar(255) default null,
    `login_ip` varchar(45) default null,
    `views` int unsigned not null default 0,
    `created` datetime not null,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) charset=utf8;
