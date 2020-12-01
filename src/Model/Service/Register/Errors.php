<?php
namespace MonthlyBasis\User\Model\Service\Register;

use DateTime;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\User\Model\Table as UserTable;

class Errors
{
    public function __construct(
        array $config,
        ReCaptchaService\Valid $validService,
        UserTable\User\Username $usernameTable
    ) {
        $this->config        = $config;
        $this->validService  = $validService;
        $this->usernameTable = $usernameTable;
    }

    public function getErrors(): array
    {
        $errors = [];

        if (!isset($this->config['required']['email'])
            || ($this->config['required']['email'])
        ) {
            if (empty($_POST['email'])
                || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
            ) {
                $errors[] = 'Invalid email address.';
            }
        }

        if (empty($_POST['username'])
            || preg_match('/\W/', $_POST['username'])) {
            $errors[] = 'Invalid username.';
        } else {
            $count = $this->usernameTable->selectCountWhereUsernameEquals(
                $_POST['username']
            );
            if ($count > 0) {
                $errors[] = 'Username already exists.';
            }
        }
        if (empty($_POST['password'])) {
            $errors[] = 'Invalid password.';
        }
        if (empty($_POST['confirm-password'])) {
            $errors[] = 'Invalid confirm password.';
        }

        if (!empty($_POST['password'])
            && !empty($_POST['confirm-password'])
            && ($_POST['password'] != $_POST['confirm-password'])
        ) {
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

        if (!isset($this->config['required']['gender'])
            || ($this->config['required']['gender'])
        ) {
            if (empty($_POST['gender'])
                || (!in_array($_POST['gender'], ['M', 'F']))
            ) {
                $errors[] = 'Invalid gender.';
            }
        }

        if (!isset($this->config['required']['re-captcha'])
            || ($this->config['required']['re-captcha'])
        ) {
            if (!$this->validService->isValid()) {
                $errors[] = 'Invalid reCAPTCHA.';
            }
        }

        return $errors;
    }
}
