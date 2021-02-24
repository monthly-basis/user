<?php
namespace MonthlyBasis\User\Model\Service\Password\Reset;

class Url
{
    /**
     * @TODO URL should include user ID
     */
    public function getUrl(string $code): string
    {
        return 'https://'
             . $_SERVER['HTTP_HOST']
             . '/reset-password/'
             . urlencode($code);
    }
}
