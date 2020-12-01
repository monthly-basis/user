<?php
namespace MonthlyBasis\User\Model\Service\Username;

use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class Change
{
    public function __construct(
        UserTable\User\Username $usernameTable
    ) {
        $this->usernameTable = $usernameTable;
    }

    public function change(
        UserEntity\User $userEntity,
        string $newUsername
    ) {
        if (empty($newUsername)
            || preg_match('/\W/', $newUsername)) {
            throw new Exception('Invalid username.');
        }

        return $this->usernameTable->updateWhereUserId(
            $newUsername,
            $userEntity->getUserId()
        );
    }
}
