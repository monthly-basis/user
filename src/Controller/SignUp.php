<?php
namespace MonthlyBasis\User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class SignUp extends AbstractActionController
{
    public function __construct(
        FlashService\Flash $flashService,
        UserService\Register $registerService
    ) {
        $this->flashService    = $flashService;
        $this->registerService = $registerService;
    }

    public function indexAction()
    {
        if (!empty($_POST)) {
            return $this->postAction();
        }

        return [
            'errors' => $this->flashService->get('errors'),
        ];
    }

    public function successAction()
    {
    }

    protected function postAction()
    {
        try {
            $this->registerService->register();
        } catch (UserException $userException) {
            return $this->redirect()->toRoute('sign-up')->setStatusCode(303);
        }

        $params = [
            'action' => 'success',
        ];
        return $this->redirect()
                    ->toRoute('sign-up', $params)
                    ->setStatusCode(303);
    }
}
