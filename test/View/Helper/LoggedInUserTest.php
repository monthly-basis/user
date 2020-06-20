<?php
namespace LeoGalleguillos\UserTest\View\Helper;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\View\Helper as UserHelper;
use PHPUnit\Framework\TestCase;

class LoggedInUserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->loggedInUserHelper = new UserHelper\LoggedInUser(
            $this->loggedInUserServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserHelper\LoggedInUser::class,
            $this->loggedInUserHelper
        );
    }

    public function testInvoke()
    {
        $userEntity = new UserEntity\User();
        $this->loggedInUserServiceMock->method('getLoggedInUser')->willReturn(
            $userEntity
        );

        $this->assertSame(
            $userEntity,
            $this->loggedInUserHelper->__invoke()
        );
    }
}
