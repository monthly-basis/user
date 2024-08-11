# Changelog

## v2.8.5

- ALTER TABLE `user` ADD COLUMN `https_token` varchar(64) DEFAULT NULL AFTER `login_ip`;

## v2.8.0

- Replace calls to $this->isUserLoggedIn() view helper with $this->isVisitorLoggedIn()

## v2.7.8

- Create `activate_log` table using `sql/test/activate_log/create.sql` file.

## v2.7.6

- ALTER TABLE `register` CHANGE `activation_code` `activation_code` varchar(31) NOT NULL;
