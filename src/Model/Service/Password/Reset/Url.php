<?php
namespace LeoGalleguillos\User\Model\Service\Password\Reset;

class Url
{
    public function getUrl(string $code)
    {
        return 'https://'
             . $_SERVER['HTTP_HOST']
             . '/reset-password/'
             . urlencode($code);
    }
}
