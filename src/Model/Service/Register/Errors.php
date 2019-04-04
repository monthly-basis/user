<?php
namespace LeoGalleguillos\User\Model\Service\Register;

use DateTime;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;

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

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        if (empty($_POST['username'])
            || preg_match('/\W/', $_POST['username'])) {
            $errors[] = 'Invalid username.';
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
