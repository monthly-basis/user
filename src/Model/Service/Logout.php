<?php
namespace MonthlyBasis\User\Model\Service;

class Logout
{
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
