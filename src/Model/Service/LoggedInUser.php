<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;

class LoggedInUser
{
    protected array $cache = [];

    public function __construct(
        UserFactory\User $userFactory,
        UserTable\UserUserToken $userUserTokenTable
    ) {
        $this->userFactory        = $userFactory;
        $this->userUserTokenTable = $userUserTokenTable;
    }

    /**
     * @throws UserException
     */
    public function getLoggedInUser(): UserEntity\User
    {
        if (isset($this->cache['userEntity'])) {
            return $this->cache['userEntity'];
        }

        if (empty($_COOKIE['user-id'])
            || empty($_COOKIE['login-token'])
        ) {
            throw new UserException('User is not logged in (cookies are not set).');
        }

        $result = $this->userUserTokenTable->selectWhereUserIdLoginTokenExpiresDeleted(
            $_COOKIE['user-id'],
            $_COOKIE['login-token']
        );

        if (false == ($array = $result->current())) {
            throw new UserException('User is not logged in (could not find row).');
        }

        $this->cache['userEntity'] = $this->userFactory->buildFromArray(
            $array
        );
        return $this->cache['userEntity'];
    }
}
