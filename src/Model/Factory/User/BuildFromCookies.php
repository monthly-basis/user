<?php
namespace LeoGalleguillos\User\Model\Factory\User;

use DateTime;
use Exception;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

class BuildFromCookies
{
    public function __construct(
        UserService\LoggedInUser $loggedInUserService,
        UserTable\User $userTable,
        UserTable\User\LoginHash $loginHashTable,
        UserTable\User\LoginIp $loginIpTable
    ) {
        $this->loggedInUserService = $loggedInUserService;
        $this->userTable           = $userTable;
        $this->loginHashTable      = $loginHashTable;
        $this->loginIpTable        = $loginIpTable;
    }

    public function buildFromCookies()
    {
        try {
            $userEntity = $this->loggedInUserService->getLoggedInUser();
            return $userEntity;
        } catch (Exception $exception) {
            $userId = $this->userTable->insert();
            $this->loginHashTable->updateWhereUserId(
                password_hash($userId . time(), PASSWORD_DEFAULT),
                $userId
            );
            $this->loginIpTable->updateWhereUserId(
                $_SERVER['REMOTE_ADDR'],
                $userId
            );

            $expire = time() + 30 * 24 * 60 * 60;
            $path   = '/';
            $domain = $_SERVER['HTTP_HOST'];
            $secure = true;

            $name   = 'userId';
            $value  = $userId;
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
        }
    }
}
