<?php
namespace MonthlyBasis\User\Model\Service\Password;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class Reset
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        SimpleEmailServiceService\Send\Conditionally $conditionallySendService,
        string $emailAddress,
        string $websiteName,
        StringService\Random $randomService,
        UserFactory\User $userFactory,
        UserService\Password\Reset\Url $urlService,
        UserTable\ResetPassword $resetPasswordTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->flashService             = $flashService;
        $this->validService             = $validService;
        $this->conditionallySendService = $conditionallySendService;
        $this->emailAddress             = $emailAddress;
        $this->websiteName              = $websiteName;
        $this->randomService            = $randomService;
        $this->userFactory              = $userFactory;
        $this->urlService               = $urlService;
        $this->resetPasswordTable       = $resetPasswordTable;
        $this->userEmailTable           = $userEmailTable;
    }

    public function reset()
    {
        $this->flashService->set(
            'email',
            $_POST['email'] ?? null
        );

        if (false != ($errors = $this->getErrors())) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Errors with form.');
        }

        $result = $this->userEmailTable->selectWhereAddress(
            $_POST['email']
        );
        if (false == ($array = $result->current())) {
            return;
        }

        $userId = $array['user_id'];

        $count = $this->resetPasswordTable->selectCountWhereUserIdAndCreatedGreaterThan(
            $userId,
            date('Y-m-d H:i:s', strtotime('-1 day'))
        );
        if ($count >= 3) {
            return;
        }

        $code = $this->randomService->getRandomString(32);
        $this->resetPasswordTable->insert(
            $userId,
            $code
        );

        $url = $this->urlService->getUrl($userId, $code);

        $this->conditionallySendService->conditionallySend(
            $_POST['email'],
            "{$this->websiteName} <{$this->emailAddress}>",
            "{$this->websiteName} - Reset Password",
            $this->getEmailBodyText($url),
        );
    }

    protected function getErrors(): array
    {
        $errors = [];

        if (empty($_POST['email'])
            || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            $errors[] = 'Invalid email address.';
            return $errors;
        }

        if (!$this->validService->isValid()) {
            $errors[] = 'Invalid reCAPTCHA.';
        }

        return $errors;
    }

    protected function getEmailBodyText(string $url)
    {
        return "To reset your password, please go to:\n\n"
             . $url
             . "\n\n"
             . "If you did not request this email, you may ignore it.";
    }
}
