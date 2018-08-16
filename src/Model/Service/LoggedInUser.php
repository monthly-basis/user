<?php
namespace LeoGalleguillos\User\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;

class LoggedInUser
{
    protected $cache = [];

    /**
     * Construct
     *
     * @param UserFactory\User $userFactory
     * @param UserTable\User $userTable
     */
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
        if (isset($this->cache['userEntity'])) {
            return $this->cache['userEntity'];
        }

        if (empty($_COOKIE['userId'])
            || empty($_COOKIE['loginHash'])
            || empty($_COOKIE['loginIp'])
        ) {
            throw new Exception('User is not logged in (cookies are not set).');
        }

        try {
            $array = $this->userTable->selectWhereUserIdLoginHash(
                $_COOKIE['userId'],
                $_COOKIE['loginHash']
            );
        } catch (Exception $expcetion) {
            throw new Exception('User is not logged in (could not find row).');
        }

        $this->cache['userEntity'] = $this->userFactory->buildFromArray(
            $array
        );
        return $this->cache['userEntity'];
    }
}
