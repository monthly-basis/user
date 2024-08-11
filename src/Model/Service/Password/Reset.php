<?php
namespace LeoGalleguillos\User\Model\Service\Password;

use Exception;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use TypeError;

class Reset
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        UserFactory\User $userFactory,
        UserService\Password\Reset\GenerateCode $generateCodeService,
        UserTable\ResetPassword $resetPasswordTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->flashService        = $flashService;
        $this->validService        = $validService;
        $this->userFactory         = $userFactory;
        $this->generateCodeService = $generateCodeService;
        $this->resetPasswordTable  = $resetPasswordTable;
        $this->userEmailTable      = $userEmailTable;
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

        $count = $this->resetPasswordTable->selectCountWhereUserIdAndCreatedGreaterThan(
            $userId,
            date('Y-m-d H:i:s', strtotime('+3 day'))
        );
        if ($count >= 3) {
            return;
        }

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
