<?php
namespace LeoGalleguillos\UserTest\Model\Service\User;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class NewestUsersTest extends TestCase
{
    protected function setUp()
    {
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->newestUsersService = new UserService\User\NewestUsers(
            $this->userFactoryMock,
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\User\NewestUsers::class,
            $this->newestUsersService
        );
    }
}
