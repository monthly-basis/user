<?php
namespace LeoGalleguillos\User\Model\Service;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

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
