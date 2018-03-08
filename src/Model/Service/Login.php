<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Table as UserTable;

class Login
{
    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }

    /**
     * Login
     *
     * @return bool
     */
    public function login() : bool
    {
        if (empty($_POST['username'])
            || empty($_POST['password'])) {
            return false;
        }

        $userArray = $this->userTable->selectRow($_POST['username']);
        if (empty($userArray)) {
            return false;
        }

        $username     = $userArray['username'];
        $passwordHash = $userArray['password_hash'];

        if (!password_verify($_POST['password'], $passwordHash)) {
            return false;
        }

        $_SESSION['username'] = $username;
        return true;
    }
}
