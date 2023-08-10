<?php
namespace MonthlyBasis\User\Model\Service\Follow;

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
                'user_id_1' => $userEntity1->userId,
                'user_id_2' => $userEntity2->userId,
            ],
        );

        return boolval($result->current());
    }
}
