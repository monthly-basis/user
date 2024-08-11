<?php
namespace MonthlyBasis\UserTest\Model\Service;

use ArrayObject;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->postFactoryMock = $this->createMock(
            UserFactory\Post::class
        );
        $this->postTableMock = $this->createMock(
            UserTable\Post::class
        );
        $this->postsService = new UserService\Posts(
            $this->postFactoryMock,
            $this->postTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Posts::class,
            $this->postsService
        );
    }

    public function testGetPosts()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(123);

        $arrayObjects1 = new ArrayObject([
            new ArrayObject([
                'post_id' => '1',
                'from_user_user_id' => '1',
                'from_user_username' => 'username',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'message',
            ]),
            new ArrayObject([
                'post_id' => '2',
                'from_user_user_id' => '3',
                'from_user_username' => 'username3',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'another message',
            ]),
        ]);

        $arrayObjects2 = new ArrayObject([]);

        $this->postTableMock->method('selectWhereToUserId')->will(
            $this->onConsecutiveCalls(
                $arrayObjects1, $arrayObjects2
            )
        );

        $this->assertSame(
            2,
            count($this->postsService->getPosts($userEntity))
        );

        $this->assertSame(
            0,
            count($this->postsService->getPosts($userEntity))
        );
    }
}
