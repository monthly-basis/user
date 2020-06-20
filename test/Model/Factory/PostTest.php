<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use ArrayObject;
use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        $this->postFactory = new UserFactory\Post();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserFactory\Post::class,
            $this->postFactory
        );
    }

    public function testBuildFromArrayObject()
    {
        $arrayObject = new ArrayObject([
            'post_id'            => 123,
            'from_user_user_id'  => 456,
            'from_user_username' => 'username1',
            'to_user_user_id'    => 789,
            'to_user_username'   => 'username2',
            'message'            => 'this is the message',
            'created'            => '2018-01-29 19:46:03',
        ]);

        $fromUserEntity = new UserEntity\User();
        $fromUserEntity->setUserId($arrayObject['from_user_user_id']);
        $fromUserEntity->username = $arrayObject['from_user_username'];

        $toUserEntity = new UserEntity\User();
        $toUserEntity->setUserId($arrayObject['to_user_user_id']);
        $toUserEntity->username = $arrayObject['to_user_username'];

        $created = new DateTime($arrayObject['created']);

        $postEntity = new UserEntity\Post();
        $postEntity->setCreated($created)
                   ->setFromUser($fromUserEntity)
                   ->setMessage($arrayObject['message'])
                   ->setPostId($arrayObject['post_id'])
                   ->setToUser($toUserEntity);

        $this->assertEquals(
            $postEntity,
            $this->postFactory->buildFromArrayObject($arrayObject)
        );
    }
}
