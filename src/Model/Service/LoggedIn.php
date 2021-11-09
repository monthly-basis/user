<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Table as UserTable;

class LoggedIn
{
    protected $isLoggedIn;

    public function __construct(
        UserTable\UserUserToken $userUserTokenTable
    ) {
        $this->userUserTokenTable = $userUserTokenTable;
    }

    public function isLoggedIn(): bool
    {
        if (isset($this->isLoggedIn)) {
            return $this->isLoggedIn;
        }

        if (
            empty($_COOKIE['user-id'])
            || empty($_COOKIE['login-token'])
        ) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        $result = $this->userUserTokenTable->selectWhereUserIdLoginTokenExpiresDeleted(
            $_COOKIE['user-id'],
            $_COOKIE['login-token']
        );

        if (empty($result->current())) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        $this->isLoggedIn = true;
        return $this->isLoggedIn;
    }
}
