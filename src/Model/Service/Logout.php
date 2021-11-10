<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use Throwable;

class Logout
{
    public function __construct(
        UserService\LoggedInUser $loggedInUserService,
        UserTable\UserToken $userTokenTable
    ) {
        $this->loggedInUserService = $loggedInUserService;
        $this->userTokenTable      = $userTokenTable;
    }

    public function logout(): void
    {
        $names = [
            'https-token',
            'login-token',
            'user-id',
        ];
        $options = [
            'expires'  => time() - 3600,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ];
        $value  = '';

        foreach ($names as $name) {
            setcookie(
                $name,
                $value,
                $options,
            );
        }

        try {
            $userEntity = $this->loggedInUserService->getLoggedInUser();
            $this->userTokenTable->updateSetDeletedWhereUserIdLoginToken(
                $userEntity->getUserId(),
                $userEntity->getLoginToken(),
            );
        } catch (Throwable $throwable) {
            // Do nothing.
        }
    }
}
