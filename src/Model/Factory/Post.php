<?php
namespace LeoGalleguillos\User\Model\Factory;

use ArrayObject;
use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;

class Post
{
    /**
     * Build from array object.
     *
     * @param ArrayObject $arrayObject
     * @return UserEntity\Post
     */
    public function buildFromArrayObject(
        ArrayObject $arrayObject
    ) : UserEntity\Post {
        $fromUserEntity = new UserEntity\User();
        $fromUserEntity->userId = $arrayObject['from_user_user_id'];
        $fromUserEntity->username = $arrayObject['from_user_username'];

        $toUserEntity = new UserEntity\User();
        $toUserEntity->userId = $arrayObject['to_user_user_id'];
        $toUserEntity->username = $arrayObject['to_user_username'];

        $created = new DateTime($arrayObject['created']);

        $postEntity = new UserEntity\Post();
        $postEntity->setCreated($created)
                   ->setFromUser($fromUserEntity)
                   ->setMessage($arrayObject['message'])
                   ->setPostId($arrayObject['post_id'])
                   ->setToUser($toUserEntity);

        return $postEntity;
    }
}
