<?php
namespace MonthlyBasis\User\Model\Service\Password\Reset;

class Url
{
    public function getUrl(int $userId, string $code): string
    {
        return 'https://'
            . $_SERVER['HTTP_HOST']
            . '/reset-password/'
            . $userId
            . '/'
            . urlencode($code);
    }
}
