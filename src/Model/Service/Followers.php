<?php
namespace MonthlyBasis\User\Model\Service;

use Generator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;

class Followers
{
    public function __construct(
        protected UserFactory\User $userFactory,
        protected UserTable\UserFollow $userFollowTable,
    ) {
    }

    public function getFollowers(
        UserEntity\User $userEntity,
        int $limit = 100,
    ): Generator {
        $result = $this->userFollowTable->select(
            columns: [
                'user_id_1',
            ],
            where: [
                'user_id_2' => $userEntity->userId,
            ],
            limit: $limit,
        );
        foreach ($result as $array) {
            yield $this->userFactory->buildFromUserId(
                $array['user_id_1']
            );
        }
    }
}
