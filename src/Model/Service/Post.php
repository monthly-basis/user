<?php
namespace MonthlyBasis\User\Model\Service;

use ArrayObject;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;

class Post
{
    public function __construct(
        UserTable\Post $postTable
    ) {
        $this->postTable   = $postTable;
    }

    /**
     * Submit post.
     *
     * @return ArrayObject
     */
    public function submitPost(
        UserEntity\User $fromUserEntity,
        UserEntity\User $toUserEntity,
        string $message
    ) {
        return (bool) $this->postTable->insert(
            $fromUserEntity->getUserId(),
            $toUserEntity->getUserId(),
            $message
        );
    }
}
