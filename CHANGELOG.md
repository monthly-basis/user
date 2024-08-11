# Changelog

## Unreleased

- Add `https_token` column to `user` table

## v2.8.0

- Replace calls to $this->isUserLoggedIn() view helper with $this->isVisitorLoggedIn()

## v2.7.8

- Create `activate_log` table using `sql/test/activate_log/create.sql` file.

## v2.7.6

- ALTER TABLE `register` CHANGE `activation_code` `activation_code` varchar(31) NOT NULL;
