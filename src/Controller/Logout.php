<?php
namespace MonthlyBasis\User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\User\Model\Service as UserService;

class Logout extends AbstractActionController
{
    public function __construct(
        UserService\Logout $logoutService
    ) {
        $this->logoutService = $logoutService;
    }

    public function indexAction()
    {
        $this->logoutService->logout();

        return $this->redirect()->toRoute('monthly-basis/user/index')->setStatusCode(303);
    }
}
