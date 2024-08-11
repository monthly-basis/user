<?php
namespace MonthlyBasis\User\Controller\Account;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use MonthlyBasis\User\Model\Service as UserService;

class ChangePassword extends AbstractActionController
{
    public function __construct(
        UserService\LoggedIn $loggedInService,
        UserService\LoggedInUser $loggedInUserService
    ) {
        $this->loggedInService     = $loggedInService;
        $this->loggedInUserService = $loggedInUserService;
    }

    public function onDispatch(MvcEvent $mvcEvent)
    {
        if (!$this->loggedInService->isLoggedIn()) {
            return $this->redirect()->toRoute('login')->setStatusCode(303);
        }

        return parent::onDispatch($mvcEvent);
    }

    public function changePasswordAction()
    {
        if (!empty($_POST)) {
            return $this->postAction();
        }

        return [
            'user' => $this->loggedInUserService->getLoggedInUser(),
        ];
    }

    protected function postAction()
    {
        exit('post detected');
    }
}
