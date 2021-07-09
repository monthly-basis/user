<?php
namespace MonthlyBasis\User\Model\Service\Email;

use MonthlyBasis\User\Model\Table as UserTable;

class Exists
{
    public function __construct(
        UserTable\UserEmail $userEmailTable
    ) {
        $this->userEmailTable = $userEmailTable;
    }

    public function doesEmailExist(string $email): bool
    {
        return !empty(
            $this->userEmailTable->selectWhereAddress($email)->current()
        );
    }
}
