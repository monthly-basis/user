<?php
namespace LeoGalleguillos\User\Model\Service;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

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
