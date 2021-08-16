<?php
namespace MonthlyBasis\User\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\User\Model\Service as UserService;

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
