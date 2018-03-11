<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInTest extends TestCase
{
    protected function setUp()
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loggedInService = new UserService\LoggedIn(
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\LoggedIn::class,
            $this->loggedInService
        );
    }

    public function testIsLoggedIn()
    {
        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );

        $_COOKIE['userId']    = 123;
        $_COOKIE['loginHash'] = 'login-hash';
        $_COOKIE['loginIp']   = 'login-ip';

        $this->userTableMock->method('selectWhereUserIdLoginHashLoginIp')->will(
            $this->onConsecutiveCalls(
                $this->throwException(new Exception()),
                [],
                $this->throwException(new Exception())
            )
        );

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
        $this->assertTrue(
            $this->loggedInService->isLoggedIn()
        );
        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
    }
}
