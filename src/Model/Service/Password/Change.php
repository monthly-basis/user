<?php
namespace MonthlyBasis\User\Model\Service\Password;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class Change
{
    public function __construct(
        UserTable\User\UserId $userIdTable
    ) {
        $this->userIdTable = $userIdTable;
    }

    public function changePassword(
        UserEntity\User $userEntity,
        string $newPassword
    ) {
        $this->userIdTable->updateSetPasswordHashWhereUserId(
            password_hash($newPassword, PASSWORD_DEFAULT),
            $userEntity->getUserId()
        );
    }
}
