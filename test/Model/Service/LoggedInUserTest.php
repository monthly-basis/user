<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInUserTest extends TestCase
{
    protected function setUp()
    {
        $_SESSION['username'] = 'username';
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loggedInUserService = new UserService\LoggedInUser(
            $this->userFactoryMock,
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\LoggedInUser::class,
            $this->loggedInUserService
        );
    }

    public function testGetLoggedInUser()
    {
        unset($_COOKIE['userId']);
        unset($_COOKIE['loginHash']);
        unset($_COOKIE['loginIp']);

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (Exception $exception) {
            $this->assertSame(
                'User is not logged in (cookies are not set).',
                $exception->getMessage()
            );
        }

        $_COOKIE['userId']    = 123;
        $_COOKIE['loginHash'] = 'login-hash';
        $_COOKIE['loginIp']   = 'login-ip';

        $this->userTableMock->method('selectWhereUserIdLoginHash')->will(
            $this->onConsecutiveCalls(
                $this->throwException(new Exception()),
                []
            )
        );

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (Exception $exception) {
            $this->assertSame(
                'User is not logged in (could not find row).',
                $exception->getMessage()
            );
        }

        $this->assertInstanceOf(
            UserEntity\User::class,
            $this->loggedInUserService->getLoggedInUser()
        );
    }
}
