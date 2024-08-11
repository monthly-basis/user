# Changelog

## v2.10.3

```
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
```

## v2.8.5

- ALTER TABLE `user` ADD COLUMN `https_token` varchar(64) DEFAULT NULL AFTER `login_ip`;

## v2.8.0

- Replace calls to $this->isUserLoggedIn() view helper with $this->isVisitorLoggedIn()

## v2.7.8

- Create `activate_log` table using `sql/test/activate_log/create.sql` file.

## v2.7.6

- ALTER TABLE `register` CHANGE `activation_code` `activation_code` varchar(31) NOT NULL;
