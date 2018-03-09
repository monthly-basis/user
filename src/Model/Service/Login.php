<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Table as UserTable;

class Login
{
    /**
     * Construct.
     *
     * @param UserTable\User $userTable
     * @param UserTable\User\LoginHash $loginHashTable
     */
    public function __construct(
        UserTable\User $userTable,
        UserTable\User\LoginHash $loginHashTable
    ) {
        $this->userTable = $userTable;
        $this->loginHashTable = $loginHashTable;
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

        $userArray = $this->userTable->selectWhereUsername($_POST['username']);
        if (empty($userArray)) {
            return false;
        }

        $username     = $userArray['username'];
        $passwordHash = $userArray['password_hash'];

        if (!password_verify($_POST['password'], $passwordHash)) {
            return false;
        }

        $loginHash = password_hash($_POST['username'] . time(), PASSWORD_DEFAULT);
        $this->loginHashTable->updateWhereUsername(
            $loginHash,
            $_POST['username']
        );

        $_SESSION['username'] = $username;
        return true;
    }
}
