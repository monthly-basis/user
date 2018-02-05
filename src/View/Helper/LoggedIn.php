<?php
namespace LeoGalleguillos\User\View\Helper;

use LeoGalleguillos\User\Model\Service as UserService;
use Zend\View\Helper\AbstractHelper;

class LoggedIn extends AbstractHelper
{
    public function __construct(
        UserService\LoggedIn $loggedInService
    ) {
        $this->loggedInService = $loggedInService;
    }

    public function __invoke()
    {
        return $this->loggedInService->isLoggedIn();
    }
}
