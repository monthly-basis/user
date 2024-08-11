<?php
namespace MonthlyBasis\User\Model\Service\Password\Reset\Accessed;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class ConditionallyUpdate
{
    public function __construct(
        UserTable\ResetPassword $resetPasswordTable
    ) {
        $this->resetPasswordTable = $resetPasswordTable;
    }

    public function conditionallyUpdateAccessed(UserEntity\Password\Reset $resetEntity)
    {
        try {
            $accessed = $resetEntity->getAccessed();
        } catch (\Error $error) {
            $this->resetPasswordTable->updateSetAccessedToUtcTimestampWhereUserIdAndCode(
                $resetEntity->getUserId(),
                $resetEntity->getCode(),
            );
        }
    }
}
