<?php
namespace MonthlyBasis\User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\User\Model\Service as UserService;

class Logout extends AbstractActionController
{
    public function __construct(
        UserService\LoggedInUser $loggedInUserService,
        UserService\Logout $logoutService
    ) {
        $this->loggedInUserService = $loggedInUserService;
        $this->logoutService       = $logoutService;
    }

    public function indexAction()
    {
        $this->logoutService->logout(
            $this->loggedInUserService->getLoggedInUser()
        );

        return $this->redirect()->toRoute('index')->setStatusCode(303);
    }
}
