<?php
namespace LeoGalleguillos\User\Model\Service\Login;

use LeoGalleguillos\User\Model\Table as UserTable;

class ShouldRedirectToReferer
{
    /**
     * Should redirect to referer
     *
     * @param bool $wasLoginSuccessful
     * @return bool
     */
    public function shouldRedirectToReferer(
        bool $wasLoginSuccessful
    ) : bool {
        return false;
    }
}
