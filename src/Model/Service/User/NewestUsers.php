<?php
namespace MonthlyBasis\User\Model\Service\User;

use Generator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;

class NewestUsers
{
    /**
     * Construct.
     *
     * @param UserFactory\User $userFactory
     * @param UserTable\User $userTable
     */
    public function __construct(
        UserFactory\User $userFactory,
        UserTable\User $userTable
    ) {
        $this->userFactory = $userFactory;
        $this->userTable   = $userTable;
    }

    /**
     * Get newest users
     *
     * @return Generator
     * @yield UserEntity\User
     */
    public function getNewestUsers(): Generator
    {
        foreach ($this->userTable->selectOrderByCreatedDesc() as $array) {
            yield $this->userFactory->buildFromArray($array);
        }
    }
}
