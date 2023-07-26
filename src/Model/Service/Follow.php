<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class Follow
{
    public function __construct(
        protected UserTable\UserFollow $userFollowTable
    ) {
    }

    public function follow(
        UserEntity\User $userEntity1,
        UserEntity\User $userEntity2,
    ): bool {
        $result = $this->userFollowTable->insertIgnore(
            $userEntity1->userId,
            $userEntity2->userId,
        );

        return boolval($result->getAffectedRows());
    }
}
