<?php
namespace LeoGalleguillos\User\Model\Service\Password;

use Exception;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;

class Reset
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService
    ) {
        $this->flashService       = $flashService;
        $this->validService       = $validService;
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
