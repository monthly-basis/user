<?php
namespace MonthlyBasis\User\Model\Service;

use Exception;
use MonthlyBasis\User\Model\Table as UserTable;

class LoggedIn
{
    protected $isLoggedIn;

    /**
     * Construct
     *
     * @param UserTable\User $userTable
     */
    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }

    /**
     * Is logged in.
     *
     * @return bool
     */
    public function isLoggedIn() : bool
    {
        if (isset($this->isLoggedIn)) {
            return $this->isLoggedIn;
        }

        if (empty($_COOKIE['userId'])
            || empty($_COOKIE['loginHash'])
            || empty($_COOKIE['loginIp'])
        ) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        try {
            $this->userTable->selectWhereUserIdLoginHash(
                $_COOKIE['userId'],
                $_COOKIE['loginHash']
            );
        } catch (Exception $exception) {
            $this->isLoggedIn = false;
            return $this->isLoggedIn;
        }

        $this->isLoggedIn = true;
        return $this->isLoggedIn;
    }
}
