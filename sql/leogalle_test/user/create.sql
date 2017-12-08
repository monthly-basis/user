CREATE TABLE `user` (
    `user_id` int(10) unsigned auto_increment,
    `username` varchar(255) not null,
    `first_name` varchar(255) default null,
    `last_name` varchar(255) default null,
    primary key (`user_id`)
) charset=utf8;
