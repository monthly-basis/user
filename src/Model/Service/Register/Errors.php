<?php
namespace LeoGalleguillos\User\Model\Service\Register;

use DateTime;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;

class Errors
{
    public function __construct(
        array $config,
        ReCaptchaService\Valid $validService
    ) {
        $this->config       = $config;
        $this->validService = $validService;
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

        if (empty($_POST['gender'])
            || (!in_array($_POST['gender'], ['M', 'F']))
        ) {
            $errors[] = 'Invalid gender.';
        }

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }
}
