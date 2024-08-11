CREATE TABLE `photo` (
    `photo_id` int(10) unsigned auto_increment,
    `user_id` int(10) unsigned,
    `extension` varchar(4) not null,
    `title` varchar(255) not null,
    `description` text not null,
    `views` int unsigned not null default 0,
    `created` datetime NOT NULL,
    PRIMARY KEY (`photo_id`)
) charset=utf8;
