<?php
namespace MonthlyBasis\User\Model\Service\Password\Change;

use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\User\Model\Service as UserService;

class Errors
{
    public function __construct(
        ReCaptchaService\Valid $validService
    ) {
        $this->validService = $validService;
    }

    public function getErrors(): array
    {
        $errors = [];

        if (
            empty($_POST['current-password'])
            || empty($_POST['new-password'])
            || empty($_POST['confirm-new-password'])
        ) {
            $errors[] = 'Missing fields.';
            return $errors;
        }

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
