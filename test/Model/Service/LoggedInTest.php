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

    public function testIsLoggedInFalse()
    {
        unset($_COOKIE['user-id']);
        unset($_COOKIE['login-hash']);

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );

        $_COOKIE['user-id']    = '123';
        $_COOKIE['login-hash'] = 'login-hash';

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
        $_COOKIE['user-id']    = '123';
        $_COOKIE['login-hash'] = 'login-hash';

        $this->userTableMock->method('selectWhereUserIdLoginHash')->willReturn(
            []
        );

        $this->assertTrue(
            $this->loggedInService->isLoggedIn()
        );
    }
}
