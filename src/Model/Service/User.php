<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class User
{
    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }

    /**
     * Increment views.
     *
     * @param UserEntity\User $userEntity
     * @return bool
     */
    public function incrementViews(UserEntity\User $userEntity)
    {
        return $this->userTable->updateViewsWhereUserId($userEntity->getUserId());
    }
}
