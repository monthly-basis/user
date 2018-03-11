<?php
namespace LeoGalleguillos\User\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Table as UserTable;

class LoggedIn
{
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
        if (empty($_COOKIE['userId'])
            || empty($_COOKIE['loginHash'])
            || empty($_COOKIE['loginIp'])
        ) {
            return false;
        }

        try {
            $this->userTable->selectWhereUserIdLoginHashLoginIp(
                $_COOKIE['userId'],
                $_COOKIE['loginHash'],
                $_COOKIE['loginIp']
            );
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
