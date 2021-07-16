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
        UserService\Username\Exists $usernameExistsService
    ) {
        $this->validService          = $validService;
        $this->toxicService          = $toxicService;
        $this->emailExistsService    = $emailExistsService;
        $this->usernameExistsService = $usernameExistsService;
    }

    public function getErrors(): array
    {
        $errors = [];

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

        if (empty($_POST['birthday-month'])
            || empty($_POST['birthday-day'])
            || empty($_POST['birthday-year'])) {
            $errors[] = 'Invalid birthday.';
        } else {
            $dateTime = DateTime::createFromFormat(
                'Y-m-d',
                $_POST['birthday-year'] . '-' . $_POST['birthday-month'] . '-' . $_POST['birthday-day']
            );
            if (!$dateTime) {
                $errors[] = 'Invalid birthday.';
            } else {
                $dateInterval = $dateTime->diff(new DateTime());
                if ($dateInterval->format('%Y') < 13) {
                    $errors[] = 'Must be at least 13 years old to sign up.';
                }
            }
        }

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
