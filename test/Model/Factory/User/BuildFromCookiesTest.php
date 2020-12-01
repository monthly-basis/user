<?php
namespace MonthlyBasis\UserTest\Model\Factory\User;

use ArrayObject;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class BuildFromCookiesTest extends TestCase
{
    protected function setUp(): void
    {
        $this->logginUserUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loginHashTableMock = $this->createMock(
            UserTable\User\LoginHash::class
        );
        $this->loginIpTableMock = $this->createMock(
            UserTable\User\LoginIp::class
        );
        $this->buildFromCookiesFactory = new UserFactory\User\BuildFromCookies(
            $this->logginUserUserServiceMock,
            $this->userTableMock,
            $this->loginHashTableMock,
            $this->loginIpTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserFactory\User\BuildFromCookies::class,
            $this->buildFromCookiesFactory
        );
    }
}
