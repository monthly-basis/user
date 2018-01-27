<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use ArrayObject;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userFactory = new UserFactory\User(
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserFactory\User::class,
            $this->userFactory
        );
    }

    public function testBuildFromArrayObject()
    {
        $arrayObject = new ArrayObject([
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ]);

        $userEntity           = new UserEntity\User();
        $userEntity->userId   = 1;
        $userEntity->username = 'Testing123';
        $userEntity->setWelcomeMessage('Welcome to my page.');

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromArrayObject($arrayObject)
        );
    }

    public function testBuildFromUsername()
    {
        $arrayObject = new ArrayObject([
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ]);

        $userEntity           = new UserEntity\User();
        $userEntity->userId   = 1;
        $userEntity->username = 'Testing123';
        $userEntity->setWelcomeMessage('Welcome to my page.');

        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $arrayObject
        );

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromUsername('Testing123')
        );
    }
}
