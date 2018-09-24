<?php
namespace LeoGalleguillos\User\Controller;

use Exception;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Service as UserService;
use Zend\Mvc\Controller\AbstractActionController;

class ResetPassword extends AbstractActionController
{
    public function __construct(
        FlashService\Flash $flashService,
        UserService\Password\Reset $resetService
    ) {
        $this->flashService = $flashService;
        $this->resetService = $resetService;
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
