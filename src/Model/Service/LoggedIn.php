<?php
namespace LeoGalleguillos\User\Model\Service;

class LoggedIn
{
    /**
     * Is logged in.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return !empty($_SESSION['username']);
    }
}
