CREATE TABLE `user` (
    `user_id` int(10) unsigned auto_increment,
    `username` varchar(255) not null,
    `password_hash` varchar(255) not null,
    `welcome_message` varchar(255) default null,
    `created` datetime not null,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) charset=utf8;
