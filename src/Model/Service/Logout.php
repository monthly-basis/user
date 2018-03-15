<?php
namespace LeoGalleguillos\User\Model\Service;

class Logout
{
    /**
     * Logout
     */
    public function logout()
    {
        $name   = 'userId';
        $value  = 0;
        $expire = -1;
        $path   = '/';
        $domain = $_SERVER['HTTP_HOST'];
        $secure = true;
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name   = 'loginHash';
        $value  = 'login-hash';
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name  = 'loginIp';
        $value = 'login-ip';
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );
    }
}
