<?php
namespace MonthlyBasis\User\Model\Service;

class Logout
{
    /**
     * Logout
     */
    public function logout()
    {
        $options = [
            'expires'  => time() - 3600,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ];

        $name   = 'userId';
        $value  = 0;
        setcookie(
            $name,
            $value,
            $options,
        );

        $name   = 'loginHash';
        $value  = 'login-hash';
        setcookie(
            $name,
            $value,
            $options,
        );

        $name  = 'loginIp';
        $value = 'login-ip';
        setcookie(
            $name,
            $value,
            $options,
        );
    }
}
