<?php
namespace MonthlyBasis\User\Controller\ResetPassword;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;

class Code extends AbstractActionController
{
    protected string $code;
    protected int $userId;

    public function __construct(
        FlashService\Flash $flashService,
        UserService\Logout $logoutService,
        UserTable\ResetPassword $resetPasswordTable,
        UserTable\ResetPasswordAccessLog $resetPasswordAccessLogTable,
        UserTable\User\PasswordHash $passwordHashTable
    ) {
        $this->flashService                = $flashService;
        $this->logoutService               = $logoutService;
        $this->resetPasswordTable          = $resetPasswordTable;
        $this->resetPasswordAccessLogTable = $resetPasswordAccessLogTable;
        $this->passwordHashTable           = $passwordHashTable;
    }

    public function onDispatch(MvcEvent $mvcEvent)
    {
        $count = $this->resetPasswordAccessLogTable
            ->selectCountWhereIpAndValidAndCreatedGreaterThan(
                $_SERVER['REMOTE_ADDR'],
                0,
                date('Y-m-d H:i:s', strtotime('-1 day'))
            );
        if ($count >= 3) {
            return $this->redirect()->toRoute('reset-password')->setStatusCode(303);
        }

        $this->layout()->setVariables([
            'showAds' => false,
        ]);

        return parent::onDispatch($mvcEvent);
    }

    public function indexAction()
    {
        $this->code = $this->params()->fromRoute('code');

        try {
            $this->userId = $this->resetPasswordTable->selectUserIdWhereCodeAndCreatedGreaterThan(
                $this->code,
                date('Y-m-d H:i:s', strtotime('-1 day'))
            );
        } catch (Exception $exception) {
            $this->resetPasswordAccessLogTable->insert(
                $_SERVER['REMOTE_ADDR'],
                0
            );
            return $this->redirect()->toRoute('reset-password')->setStatusCode(303);
        }

        // At this point, code is valid.

        if (!empty($_POST)) {
            return $this->postAction();
        }

        $this->resetPasswordAccessLogTable->insert(
            $_SERVER['REMOTE_ADDR'],
            1
        );

        return [
            'errors' => $this->flashService->get('errors'),
        ];
    }

    protected function postAction()
    {
        $errors = [];
        if (empty($_POST['new_password'])) {
            $errors[] = 'Invalid new password.';
        }
        if ($_POST['new_password'] != $_POST['confirm_new_password']) {
            $errors[] = 'New password and confirm new password do not match.';
        }

        if ($errors) {
            $this->flashService->set('errors', $errors);
            $parameters = [
                'code' => $this->code,
            ];
            return $this->redirect()->toRoute('reset-password/code', $parameters)->setStatusCode(303);
        }

        $this->logoutService->logout();

        $this->passwordHashTable->updateWhereUserId(
            password_hash($_POST['new_password'], PASSWORD_DEFAULT),
            $this->userId
        );

        $viewModel = new ViewModel();
        $viewModel->setTemplate('monthly-basis/user/reset-password/code/success');
        return $viewModel;
    }
}
