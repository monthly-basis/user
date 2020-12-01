<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Exception;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInTest extends TestCase
{
    protected function setUp(): void
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

    public function testIsLoggedInFalse()
    {
        unset($_COOKIE['userId']);
        unset($_COOKIE['loginHash']);
        unset($_COOKIE['loginIp']);

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );

        $_COOKIE['userId']    = 123;
        $_COOKIE['loginHash'] = 'login-hash';
        $_COOKIE['loginIp']   = 'login-ip';

        $this->userTableMock->method('selectWhereUserIdLoginHash')->will(
            $this->onConsecutiveCalls(
                $this->throwException(new Exception()),
                [],
                $this->throwException(new Exception())
            )
        );

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );

        /*
         * Should be false even though table mock returns array
         * since result is cached as false in service.
         */
        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
    }

    public function testIsLoggedInTrue()
    {
        $_COOKIE['userId']    = 123;
        $_COOKIE['loginHash'] = 'login-hash';
        $_COOKIE['loginIp']   = 'login-ip';

        $this->userTableMock->method('selectWhereUserIdLoginHash')->willReturn(
            []
        );

        $this->assertTrue(
            $this->loggedInService->isLoggedIn()
        );
    }
}
