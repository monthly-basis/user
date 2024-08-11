<?php
namespace MonthlyBasis\User\Model\Factory\User;

use DateTime;
use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

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
            // Do nothing.
        }

        $userEntity = new UserEntity\User();

        $userId    = $this->userTable->insert();
        $userEntity->setUserId($userId);

        $loginHash = password_hash($userId . time(), PASSWORD_DEFAULT);
        $loginIp   = $_SERVER['REMOTE_ADDR'];

        $this->loginHashTable->updateWhereUserId(
            $loginHash,
            $userId
        );
        $this->loginIpTable->updateWhereUserId(
            $loginIp,
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

        $name  = 'loginIp';
        $value = $loginIp;
        @setcookie(
            $name,
            $value,
            $expire,
            $path,
            $domain,
            $secure
        );

        return $userEntity;
    }
}
