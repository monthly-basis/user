<?php
namespace MonthlyBasis\User\View\Helper\Login;

use MonthlyBasis\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

class ReCaptchaRequired extends AbstractHelper
{
    public function __construct(
        UserService\Login\ReCaptchaRequired $reCaptchaRequiredService
    ) {
        $this->reCaptchaRequiredService = $reCaptchaRequiredService;
    }

    public function __invoke()
    {
        return $this->reCaptchaRequiredService->isReCaptchaRequired();
    }
}
