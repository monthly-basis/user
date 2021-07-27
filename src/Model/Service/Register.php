<?php
namespace MonthlyBasis\User\Model\Service;

use DateTime;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class Register
{
    public function __construct(
        array $config,
        FlashService\Flash $flashService,
        SimpleEmailServiceService\Send\Conditionally $conditionallySendService,
        StringService\Random $randomService,
        UserService\Register\Errors $errorsService,
        UserService\Register\FlashValues $flashValuesService,
        UserTable\Register $registerTable
    ) {
        $this->config                   = $config;
        $this->flashService             = $flashService;
        $this->conditionallySendService = $conditionallySendService;
        $this->randomService            = $randomService;
        $this->errorsService            = $errorsService;
        $this->flashValuesService       = $flashValuesService;
        $this->registerTable            = $registerTable;
    }

    /**
     * @throws UserException
     */
    public function register()
    {
        $this->flashValuesService->setFlashValues();
        $errors = $this->errorsService->getErrors();

        if (!empty($errors)) {
            $this->flashService->set('errors', $errors);
            throw new UserException('Invalid registration.');
        }

        $activationCode = $this->randomService->getRandomString(31);
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
