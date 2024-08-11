<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Service as UserService;

class Logout
{
    public function __construct(
        UserService\LoggedInUser $loggedInUserService
    ) {
        $this->loggedInUserService = $loggedInUserService;
    }

    public function logout(): void
    {
        $names = [
            'https-token',
            'login-hash',
            'user-id',
        ];
        $options = [
            'expires'  => time() - 3600,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ];
        $value  = '';

        foreach ($names as $name) {
            setcookie(
                $name,
                $value,
                $options,
            );
        }
    }
}
