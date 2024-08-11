<?php
namespace LeoGalleguillos\User\Model\Service\Login;

use LeoGalleguillos\User\Model\Table as UserTable;

class ReCaptchaRequired
{
    /**
     * Construct.
     *
     * @param UserTable\LoginLog $loginLogTable
     */
    public function __construct(
        UserTable\LoginLog $loginLogTable
    ) {
        $this->loginLogTable = $loginLogTable;
    }

    /**
     * Is reCAPTCHA required.
     *
     * @return bool
     */
    public function isReCaptchaRequired() : bool
    {
        return false;
    }
}
