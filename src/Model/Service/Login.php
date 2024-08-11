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
        UserFactory\User $userFactory,
        UserService\Login\ReCaptchaRequired $reCaptchaRequiredService,
        UserTable\User $userTable,
        UserTable\User\LoginDateTime $loginDateTimeTable,
        UserTable\User\LoginHash $loginHashTable,
        UserTable\User\LoginIp $loginIpTable
    ) {
        $this->flashService             = $flashService;
        $this->validReCaptchaService    = $validReCaptchaService;
        $this->randomService            = $randomService;
        $this->userFactory              = $userFactory;
        $this->reCaptchaRequiredService = $reCaptchaRequiredService;
        $this->userTable                = $userTable;
        $this->loginDateTimeTable       = $loginDateTimeTable;
        $this->loginHashTable           = $loginHashTable;
        $this->loginIpTable             = $loginIpTable;
    }

    public function login(): bool
    {
        if (empty($_POST['username'])
            || empty($_POST['password'])) {
            return false;
        }

        if ($this->reCaptchaRequiredService->isReCaptchaRequired()
            && !$this->validReCaptchaService->isValid()) {
            return false;
        }

        $result = $this->userTable->selectWhereUsername($_POST['username']);
        if (false == ($userArray = $result->current())) {
            return false;
        }

        $username     = $userArray['username'];
        $passwordHash = $userArray['password_hash'];

        if (!password_verify($_POST['password'], $passwordHash)) {
            return false;
        }

        /*
         * Credentials are valid. Update tables and set cookies.
         */

        $userEntity = $this->userFactory->buildFromUsername($username);
        $loginHash  = $this->randomService->getRandomString(64);
        $loginIp    = $_SERVER['REMOTE_ADDR'];

        $this->loginDateTimeTable->updateSetToNowWhereUserId(
            $userEntity->getUserId()
        );
        $this->loginHashTable->updateWhereUserId(
            $loginHash,
            $userEntity->getUserId()
        );
        $this->loginIpTable->updateWhereUserId(
            $loginIp,
            $userEntity->getUserId()
        );

        $this->setCookies(
            $userEntity,
            $loginHash
        );

        return true;
    }

    protected function setCookies(
        UserEntity\User $userEntity,
        string $loginHash
    ) {
        $name   = 'userId';
        $value  = $userEntity->getUserId();
        $expire = empty($_POST['keep']) ? 0 : time() + 30 * 24 * 60 * 60;
        $path   = '/';
        $domain = $_SERVER['HTTP_HOST'];
        $secure = true;
        setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name   = 'loginHash';
        $value  = $loginHash;
        setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name  = 'loginIp';
        $value = $_SERVER['REMOTE_ADDR'];
        setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );
    }
}
