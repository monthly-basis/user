# Changelog

## Unreleased

### Added

- `user`.`open_ai_role` column
```
ALTER TABLE `user` ADD COLUMN `open_ai_role` varchar(255) DEFAULT NULL AFTER `welcome_message`;
```

## v3.5.0

### Added

- Followers service
- Following service

## v3.3.0

### Added

- `user_follow` table

        CREATE TABLE `user_follow` (
            `user_id_1` int(10) unsigned NOT NULL,
            `user_id_2` int(10) unsigned NOT NULL,
            `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`user_id_1`, `user_id_2`),
            KEY `user_id_2` (`user_id_2`),
            KEY `created` (`created`)
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

## v3.0.3

```
ALTER TABLE `user` ADD COLUMN `emoji_12_id` int(10) unsigned DEFAULT NULL AFTER `user_id`;
```

## v3.0.0

- Remove deprecated classes, methods, and routes

## v2.12.0

- Deprecate Controller classes
- Deprecate `isUserLoggedIn()` view helper
- Deprecate Post classes
- Deprecate routes

## v2.11.5

```
ALTER TABLE `user` DROP COLUMN `login_hash`;
ALTER TABLE `user` DROP COLUMN `https_token`;
```

## v2.11.0

```
ALTER TABLE `user` DROP COLUMN `login_ip`;
```

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

```
ALTER TABLE `user` ADD COLUMN `https_token` varchar(64) DEFAULT NULL AFTER `login_ip`;
```

## v2.8.0

- Replace calls to `$this->isUserLoggedIn()` view helper with `$this->isVisitorLoggedIn()`

## v2.7.8

- Create `activate_log` table using `sql/test/activate_log/create.sql` file.

## v2.7.6

```
ALTER TABLE `register` CHANGE `activation_code` `activation_code` varchar(31) NOT NULL;
```
