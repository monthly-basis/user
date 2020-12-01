<?php
namespace MonthlyBasis\UserTest\Model\Service\User;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class NewestUsersTest extends TestCase
{
    protected function setUp(): void
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
