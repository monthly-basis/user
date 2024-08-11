<?php
namespace LeoGalleguillos\User\Model\Service;

use DateTime;
use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Service as UserService;

class Register
{
    public function __construct(
        array $config,
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        UserService\Register\FlashValues $flashValuesService
    ) {
        $this->config             = $config;
        $this->flashService       = $flashService;
        $this->validService       = $validService;
        $this->flashValuesService = $flashValuesService;
    }

    /**
     * Get form errors.
     *
     * @return array
     */
    public function getErrors()
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

    public function isFormValidIfNotSetFlashErrors()
    {
        $errors = $this->getErrors();
        $isFormValid = empty($errors);

        if (!$isFormValid) {
            $this->flashService->set('errors', $errors);
        }

        return $isFormValid;
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        $this->flashValuesService->setFlashValues();
        $errors = $this->getErrors();

        if (!empty($errors)) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Invalid registration.');
        }

        if (isset($this->config['require-activation-via-email'])
            && $this->config['require-activation-via-email']
        ) {
            return;
        }

        // Activate account immediately.
    }
}
