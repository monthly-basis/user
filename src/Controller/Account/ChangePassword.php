<?php
namespace MonthlyBasis\User\Controller\Account;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Model\Service as UserService;

class ChangePassword extends AbstractActionController
{
    public function __construct(
        FlashService\Flash $flashService,
        UserService\LoggedIn $loggedInService,
        UserService\LoggedInUser $loggedInUserService,
        UserService\Password\Change\Errors $errorsService
    ) {
        $this->flashService        = $flashService;
        $this->loggedInService     = $loggedInService;
        $this->loggedInUserService = $loggedInUserService;
        $this->errorsService       = $errorsService;
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
        if (false != ($errors = $this->errorsService->getErrors())) {
            $this->flashService->set(
                'errors',
                $errors
            );
            return $this->redirect()->toRoute('account/change-password')->setStatusCode(303);
        }

        // return success view model

        exit;
    }
}
