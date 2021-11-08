<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class Login
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validReCaptchaService,
        StringService\Random $randomService,
        UserService\Password\Valid $validService,
        UserTable\User $userTable,
        UserTable\User\LoginDateTime $loginDateTimeTable,
        UserTable\User\UserId $userIdTable,
        UserTable\UserToken $userTokenTable
    ) {
        $this->flashService          = $flashService;
        $this->validReCaptchaService = $validReCaptchaService;
        $this->randomService         = $randomService;
        $this->validService          = $validService;
        $this->userTable             = $userTable;
        $this->loginDateTimeTable    = $loginDateTimeTable;
        $this->userIdTable           = $userIdTable;
        $this->userTokenTable        = $userTokenTable;
    }

    public function login(): bool
    {
        if (empty($_POST['username'])
            || empty($_POST['password'])) {
            return false;
        }

        if (!$this->validReCaptchaService->isValid()) {
            return false;
        }

        $result = $this->userTable->selectWhereUsername($_POST['username']);
        if (false == ($userArray = $result->current())) {
            return false;
        }

        $userId       = $userArray['user_id'];
        $passwordHash = $userArray['password_hash'];

        if (!$this->validService->isValid($_POST['password'], $passwordHash)) {
            return false;
        }

        /*
         * Credentials are valid. Update tables and set cookies.
         */

        $loginHash  = $this->randomService->getRandomString(64);
        $loginIp    = $_SERVER['REMOTE_ADDR'];
        $httpsToken = $this->randomService->getRandomString(64);

        $this->loginDateTimeTable->updateSetToNowWhereUserId(
            $userId
        );
        $this->userIdTable->updateSetLoginHashHttpsTokenWhereUserId(
            $loginHash,
            $httpsToken,
            $userId
        );
        $this->userTokenTable->insert(
            $userId,
            $loginHash,
            $httpsToken,
            (new \DateTime())->modify('+30 days'),
        );

        $this->setCookies(
            $userId,
            $loginHash,
            $httpsToken,
        );

        return true;
    }

    protected function setCookies(
        int $userId,
        string $loginHash,
        string $httpsToken
    ) {
        $namesAndValues = [
            'user-id'     => $userId,
            'login-hash'  => $loginHash,
            'login-token' => $loginHash,
            'https-token' => $httpsToken,
        ];

        $options = [
            'expires'  => empty($_POST['keep']) ? 0 : time() + 30 * 24 * 60 * 60,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ];

        foreach ($namesAndValues as $name => $value) {
            setcookie(
                $name,
                $value,
                $options,
            );
        }
    }
}
