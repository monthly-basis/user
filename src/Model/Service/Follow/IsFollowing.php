<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class IsFollowing
{
    public function __construct(
        protected UserTable\UserFollow $userFollowTable
    ) {
    }

    public function isFollowing(
        UserEntity\User $userEntity1,
        UserEntity\User $userEntity2,
    ): bool {
        $result = $this->userFollowTable->select(
            columns: [
                'created'
            ],
            where: [
                $userEntity1->userId,
                $userEntity2->userId,
            ],
        );

        return boolval($result->current());
    }
}
