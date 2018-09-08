<?php
namespace LeoGalleguillos\User\Model\Service\Password;

use Exception;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;
use TypeError;

class Reset
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        UserFactory\User $userFactory,
        UserTable\ResetPassword $resetPasswordTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->flashService       = $flashService;
        $this->validService       = $validService;
        $this->userFactory        = $userFactory;
        $this->resetPasswordTable = $resetPasswordTable;
        $this->userEmailTable     = $userEmailTable;
    }

    /**
     *  Reset password.
     */
    public function reset()
    {
        $this->flashService->set(
            'email',
            $_POST['email'] ?? null
        );

        if (!empty($errors = $this->getErrors())) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Errors with form.');
        }

        try {
            $userId = $this->userEmailTable->selectUserIdWhereAddress(
                $_POST['email']
            );
        } catch (TypeError $typeError) {
            return;
        }

        // Build user from user id
        // If reset password email should be sent
            // Generate and store code
            // Email link
    }

    /**
     * Get errors.
     *
     * @return array
     */
    protected function getErrors()
    {
        $errors = [];

        if (empty($_POST['email'])
            || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            $errors[] = 'Invalid email address.';
            return $errors;
        }

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
