<?php
namespace LeoGalleguillos\UserTest\Model\Factory\User;

use ArrayObject;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
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
