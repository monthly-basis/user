<?php
namespace MonthlyBasis\User\Model\Service\Password\Change;

use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\User\Model\Service as UserService;

class Errors
{
    public function __construct(
        ReCaptchaService\Valid $reCaptchaValid,
        UserService\LoggedInUser $loggedInUserService,
        UserService\Password\Valid $passwordValidService
    ) {
        $this->reCaptchaValid       = $reCaptchaValid;
        $this->loggedInUserService  = $loggedInUserService;
        $this->passwordValidService = $passwordValidService;
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

        $isPasswordValid = $this->passwordValidService->isValid(
            $_POST['current-password'],
            $this->loggedInUserService->getLoggedInUser()->getPasswordHash()
        );
        if (!$isPasswordValid) {
            $errors[] = 'Current password is invalid.';
            return $errors;
        }

        if ($_POST['new-password'] != $_POST['confirm-new-password']) {
            $errors[] = 'New password and confirm new password do not match.';
            return $errors;
        }

        if (!$this->reCaptchaValid->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
