<?php
namespace LeoGalleguillos\User\Model\Service;

class LoggedIn
{
    /**
     * Is logged in.
     *
     * @return bool
     */
    public function isLoggedIn() : bool
    {
        return !empty($_SESSION['username']);
    }
}
