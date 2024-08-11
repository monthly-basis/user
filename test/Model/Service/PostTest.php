<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp()
    {
        $this->postTableMock = $this->createMock(
            UserTable\Post::class
        );
        $this->postService = new UserService\Post(
            $this->postTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Post::class,
            $this->postService
        );
    }

    public function testSubmitPost()
    {
        $this->postTableMock->method('insert')->willReturn(789);

        $this->assertTrue(
            $this->postService->submitPost(
                (new UserEntity\User())->setUserId(123),
                (new UserEntity\User())->setUserId(456),
                'this is the message'
            )
        );
    }
}
