<?php
namespace LeoGalleguillos\User\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;

class LoggedInUser
{
    public function __construct(
        UserFactory\User $userFactory,
        UserTable\User $userTable
    ) {
        $this->userFactory = $userFactory;
        $this->userTable   = $userTable;
    }

    /**
     * Get logged in user.
     *
     * @throws Exception
     * @return UserEntity\User
     */
    public function getLoggedInUser() : UserEntity\User
    {
        if (empty($_COOKIE['userId'])
            || empty($_COOKIE['loginHash'])
            || empty($_COOKIE['loginIp'])
        ) {
            throw new Exception('User is not logged in (cookies are not set).');
        }

        try {
            $array = $this->userTable->selectWhereUserIdLoginHashLoginIp(
                $_COOKIE['userId'],
                $_COOKIE['loginHash'],
                $_COOKIE['loginIp']
            );
        } catch (Exception $expcetion) {
            throw new Exception('User is not logged in (could not find row).');
        }

        return $this->userFactory->buildFromArray(
            $array
        );
    }
}
