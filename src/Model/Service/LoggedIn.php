<?php
namespace MonthlyBasis\User\Model\Service;

use Exception;
use MonthlyBasis\User\Model\Table as UserTable;

class LoggedIn
{
    protected $isLoggedIn;

    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }

    public function isLoggedIn() : bool
    {
        if (isset($this->isLoggedIn)) {
            return $this->isLoggedIn;
        }

        if (empty($_COOKIE['user-id'])
            || empty($_COOKIE['login-hash'])
        ) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        try {
            $this->userTable->selectWhereUserIdLoginHash(
                $_COOKIE['user-id'],
                $_COOKIE['login-hash']
            );
        } catch (Exception $exception) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        $this->isLoggedIn = true;
        return $this->isLoggedIn;
    }
}
