<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;

class Logout
{
    public function logout(UserEntity\User $userEntity): void
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
