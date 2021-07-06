<?php
namespace MonthlyBasis\User\Model\Service;

use DateTime;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class Register
{
    public function __construct(
        array $config,
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        SimpleEmailServiceService\Send\Conditionally $conditionallySendService,
        UserService\Register\FlashValues $flashValuesService,
        UserTable\Register $registerTable
    ) {
        $this->config                   = $config;
        $this->flashService             = $flashService;
        $this->validService             = $validService;
        $this->conditionallySendService = $conditionallySendService;
        $this->flashValuesService       = $flashValuesService;
        $this->registerTable            = $registerTable;
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

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }

    /**
     * @throws UserException
     */
    public function register()
    {
        $this->flashValuesService->setFlashValues();
        $errors = $this->getErrors();

        if (!empty($errors)) {
            $this->flashService->set('errors', $errors);
            throw new UserException('Invalid registration.');
        }

        $activationCode = rand(1, 999999999);
        $passwordHash   = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $dateTime = DateTime::createFromFormat(
            'Y-m-d',
            $_POST['birthday-year'] . '-' . $_POST['birthday-month'] . '-' . $_POST['birthday-day']
        );

        $registerId = $this->registerTable->insert(
            $activationCode,
            $_POST['username'],
            $_POST['email'],
            $passwordHash,
            $dateTime->format('Y-m-d 00:00:00'),
        );

        $this->conditionallySendService->conditionallySend(
            $_POST['email'],
            "{$this->config['website-name']} <{$this->config['email-address']}>",
            "{$this->config['website-name']} - Activate Your Account",
            $this->getEmailBodyText($registerId, $activationCode),
        );
    }

    protected function getEmailBodyText($registerId, $activationCode): string
    {
        $text = "Thank you for signing up with {$this->config['website-name']}.\n\n";
        $text .= "To activate your account, please click on the following link:\n\n";
        $text .= 'https://' . $_SERVER['HTTP_HOST'] . '/activate/'
               . urlencode($registerId)
               . '/'
               . urlencode($activationCode)
               . "\n\n";
        return $text;
    }
}
