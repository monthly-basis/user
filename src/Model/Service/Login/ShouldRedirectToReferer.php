<?php
namespace LeoGalleguillos\User\Model\Service\Login;

use LeoGalleguillos\String\Model\Service as StringService;

class ShouldRedirectToReferer
{
    public function __construct(
        StringService\StartsWith $startsWithService
    ) {
        $this->startsWithService = $startsWithService;
    }

    /**
     * Should redirect to referer
     *
     * @param bool $wasLoginSuccessful
     * @return bool
     */
    public function shouldRedirectToReferer(
        bool $wasLoginSuccessful
    ) : bool {
        if (!$wasLoginSuccessful
            || empty($_POST['referer'])) {
            return false;
        }

        $haystack = 'https://' . $_SERVER['HTTP_HOST'];

        return $this->startsWithService->startsWith($_POST['referer'], $haystack);
    }
}
