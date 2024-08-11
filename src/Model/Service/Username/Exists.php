<?php
namespace MonthlyBasis\User\Model\Service\Username;

use MonthlyBasis\User\Model\Table as UserTable;

class Exists
{
    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }

    public function doesUsernameExist(string $username): bool
    {
        return !empty(
            $this->userTable->selectWhereUsername($username)->current()
        );
    }
}
