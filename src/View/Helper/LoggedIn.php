<?php
namespace MonthlyBasis\User\View\Helper;

use MonthlyBasis\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

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
