CREATE TABLE `user` (
    `user_id` int(10) unsigned auto_increment,
    `username` varchar(255) not null,
    `password_hash` varchar(255) not null,
    `full_name` varchar(255) default null,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) charset=utf8;
