<?php
namespace MonthlyBasis\User\Model\Service;

use ArrayObject;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

/**
 * @deprecated Use MonthlyBasis\Post module instead
 */
class Posts
{
    public function __construct(
        UserFactory\Post $postFactory,
        UserTable\Post $postTable
    ) {
        $this->postFactory = $postFactory;
        $this->postTable   = $postTable;
    }

    /**
     * Get posts.
     *
     * @return ArrayObject
     */
    public function getPosts(UserEntity\User $toUserEntity) : ArrayObject
    {
        $posts = new ArrayObject();

        $arrayObjects = $this->postTable->selectWhereToUserId(
            $toUserEntity->getUserId()
        );
        foreach ($arrayObjects as $arrayObject) {
            $posts[] = $this->postFactory->buildFromArrayObject($arrayObject);
        }

        return $posts;
    }
}
