<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Service as UserService;

class Register
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        UserService\Register\FlashValues $flashValuesService
    ) {
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

    public function register()
    {
        $this->flashValuesService->setFlashValues();

        $errors = $this->getErrors();
        if (!empty($errors)) {
            $this->flashService->set('errors', $errors);
        }
    }
}
