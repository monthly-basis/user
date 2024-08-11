<?php
namespace MonthlyBasis\User\Model\Service;

class Logout
{
    public function logout(): void
    {
        $options = [
            'expires'  => time() - 3600,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ];

        $name   = 'user-id';
        $value  = '';
        setcookie(
            $name,
            $value,
            $options,
        );

        $name   = 'login-hash';
        $value  = '';
        setcookie(
            $name,
            $value,
            $options,
        );
    }
}
