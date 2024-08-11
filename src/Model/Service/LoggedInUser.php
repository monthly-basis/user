<?php
namespace MonthlyBasis\User\Model\Service;

use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;

class LoggedInUser
{
    protected $cache = [];

    public function __construct(
        UserFactory\User $userFactory,
        UserTable\User $userTable
    ) {
        $this->userFactory = $userFactory;
        $this->userTable   = $userTable;
    }

    /**
     * @throws UserException
     */
    public function getLoggedInUser(): UserEntity\User
    {
        if (isset($this->cache['userEntity'])) {
            return $this->cache['userEntity'];
        }

        if (empty($_COOKIE['userId'])
            || empty($_COOKIE['loginHash'])
            || empty($_COOKIE['loginIp'])
        ) {
            throw new UserException('User is not logged in (cookies are not set).');
        }

        try {
            $array = $this->userTable->selectWhereUserIdLoginHash(
                $_COOKIE['userId'],
                $_COOKIE['loginHash']
            );
        } catch (Exception $exception) {
            throw new UserException('User is not logged in (could not find row).');
        }

        $this->cache['userEntity'] = $this->userFactory->buildFromArray(
            $array
        );
        return $this->cache['userEntity'];
    }
}
