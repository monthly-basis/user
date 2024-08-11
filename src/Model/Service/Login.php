<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use TypeError;

class Login
{
    public function __construct(
        FlashService\Flash $flashService,
        ReCaptchaService\Valid $validReCaptchaService,
        UserFactory\User $userFactory,
        UserService\Login\ReCaptchaRequired $reCaptchaRequiredService,
        UserTable\User $userTable,
        UserTable\User\LoginDateTime $loginDateTimeTable,
        UserTable\User\LoginHash $loginHashTable,
        UserTable\User\LoginIp $loginIpTable
    ) {
        $this->flashService             = $flashService;
        $this->validReCaptchaService    = $validReCaptchaService;
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

        try {
            $userArray = $this->userTable->selectWhereUsername($_POST['username']);
        } catch (TypeError $typeError) {
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
        $loginHash  = password_hash($userEntity->getUserId() . time(), PASSWORD_DEFAULT);
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

    /**
     * Set cookies.
     *
     * @param UserEntity\User $userEntity
     * @param string $loginHash
     */
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
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name   = 'loginHash';
        $value  = $loginHash;
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        $name  = 'loginIp';
        $value = $_SERVER['REMOTE_ADDR'];
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );
    }
}
