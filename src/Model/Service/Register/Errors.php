<?php
namespace MonthlyBasis\User\Model\Service\Register;

use DateTime;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\StopForumSpam\Model\Service as StopForumSpamService;
use MonthlyBasis\User\Model\Service as UserService;

class Errors
{
    public function __construct(
        ReCaptchaService\Valid $validService,
        StopForumSpamService\IpAddress\Toxic $toxicService,
        UserService\Email\Exists $emailExistsService,
        UserService\Register\Errors\Birthday $birthdayErrorsService,
        UserService\Username\Exists $usernameExistsService
    ) {
        $this->validService          = $validService;
        $this->toxicService          = $toxicService;
        $this->emailExistsService    = $emailExistsService;
        $this->birthdayErrorsService = $birthdayErrorsService;
        $this->usernameExistsService = $usernameExistsService;
    }

    public function getErrors(): array
    {
        $errors = [];

        $birthdayErrors = $this->birthdayErrorsService->getBirthdayErrors();
        if ($birthdayErrors == [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH]) {
            return $birthdayErrors;
        }

        if (isset($_SERVER['REMOTE_ADDR'])
            && $this->toxicService->isIpAddressToxic($_SERVER['REMOTE_ADDR'])
        ) {
            $errors[] = 'Registration is currently unavailable.';
            return $errors;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        } elseif ($this->emailExistsService->doesEmailExist($_POST['email'])) {
            $errors[] = 'Email already exists.';
        }

        if (empty($_POST['username'])
            || preg_match('/\W/', $_POST['username'])) {
            $errors[] = 'Invalid username.';
        } elseif ($this->usernameExistsService->doesUsernameExist($_POST['username'])) {
            $errors[] = 'Username already exists.';
        }

        if (empty($_POST['password'])) {
            $errors[] = 'Invalid password.';
        }

        if ($_POST['password'] != $_POST['confirm-password']) {
            $errors[] = 'Password and confirm password do not match.';
        }

        $errors = array_merge(
            $errors,
            $birthdayErrors,
        );

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
