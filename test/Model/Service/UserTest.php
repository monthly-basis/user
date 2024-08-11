<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userService = new UserService\User(
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\User::class,
            $this->userService
        );
    }

    public function testIncrementViews()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(123);

        $this->userTableMock->method('updateViewsWhereUserId')->willReturn(true);

        $this->assertTrue(
            $this->userService->incrementViews($userEntity)
        );
    }
}
