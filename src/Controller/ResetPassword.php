<?php
namespace MonthlyBasis\User\Controller;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Model\Service as UserService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;

class ResetPassword extends AbstractActionController
{
    public function __construct(
        FlashService\Flash $flashService,
        UserService\Password\Reset $resetService
    ) {
        $this->flashService = $flashService;
        $this->resetService = $resetService;
    }

    public function onDispatch(MvcEvent $mvcEvent)
    {
        $this->layout()->setVariables([
            'showAds' => false,
        ]);

        return parent::onDispatch($mvcEvent);
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

    public function emailSentAction()
    {
        $email = $this->flashService->get('email');
        if (empty($email)) {
            return $this->redirect()->toRoute('reset-password')->setStatusCode(303);
        }

        return [
            'email' => $email,
        ];
    }

    public function successAction()
    {

    }

    protected function postAction()
    {
        try {
            $this->resetService->reset();
        } catch (Exception $exception) {
            return $this->redirect()
                        ->toRoute('reset-password')
                        ->setStatusCode(303);
        }

        return $this->redirect()
                    ->toRoute('reset-password/email-sent')
                    ->setStatusCode(303);
    }
}
