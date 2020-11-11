<?php
namespace LeoGalleguillos\User\Model\Service\Password;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

class Reset
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validService,
        string $emailAddress,
        string $websiteName,
        UserFactory\User $userFactory,
        UserService\Password\Reset\GenerateCode $generateCodeService,
        UserService\Password\Reset\Url $urlService,
        UserTable\ResetPassword $resetPasswordTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->flashService        = $flashService;
        $this->validService        = $validService;
        $this->emailAddress        = $emailAddress;
        $this->websiteName         = $websiteName;
        $this->userFactory         = $userFactory;
        $this->generateCodeService = $generateCodeService;
        $this->urlService          = $urlService;
        $this->resetPasswordTable  = $resetPasswordTable;
        $this->userEmailTable      = $userEmailTable;
    }

    /**
     *  Reset password.
     */
    public function reset()
    {
        $this->flashService->set(
            'email',
            $_POST['email'] ?? null
        );

        if (!empty($errors = $this->getErrors())) {
            $this->flashService->set('errors', $errors);
            throw new Exception('Errors with form.');
        }

        try {
            $userId = $this->userEmailTable->selectUserIdWhereAddress(
                $_POST['email']
            );
        } catch (Exception $exception) {
            return;
        }

        $count = $this->resetPasswordTable->selectCountWhereUserIdAndCreatedGreaterThan(
            $userId,
            date('Y-m-d H:i:s', strtotime('-1 day'))
        );
        if ($count >= 3) {
            return;
        }

        $code = $this->generateCodeService->generateCode();
        $this->resetPasswordTable->insert(
            $userId,
            $code
        );

        $url = $this->urlService->getUrl($code);

        $headers ="From: {$this->websiteName} <{$this->emailAddress}>\r\n";
        mail(
            $_POST['email'],
            "{$this->websiteName} - Reset Password",
            $this->getEmailBodyText($url),
            $headers
        );
    }

    /**
     * Get errors.
     *
     * @return array
     */
    protected function getErrors()
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
