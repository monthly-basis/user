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
        $this->setCookies();

        $_SESSION['username'] = $username;
        return true;
    }

    protected function setCookies()
    {
        $name   = 'username';
        $value  = $_POST['username'];
        $expire = empty($_POST['keep']) ? 0 : time() + 30 * 24 * 60 * 60;
        $path   = '/';
        $domain = $_SERVER['HTTP_HOST'];
        $secure = true;

        @setcookie(
            'username',
            $_POST['username'],
            $expire,
            $path,
            $domain,
            $secure
        );
    }
}
