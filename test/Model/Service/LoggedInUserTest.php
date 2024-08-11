<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInUserTest extends TestCase
{
    protected function setUp(): void
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

    public function testGetLoggedInUser()
    {
        unset($_COOKIE['user-id']);
        unset($_COOKIE['login-hash']);

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (Exception $exception) {
            $this->assertSame(
                'User is not logged in (cookies are not set).',
                $exception->getMessage()
            );
        }

        $_COOKIE['user-id']     = 123;
        $_COOKIE['login-hash'] = 'login-hash';

        $this->userTableMock->method('selectWhereUserIdLoginHash')->will(
            $this->onConsecutiveCalls(
                $this->throwException(new Exception()),
                []
            )
        );

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (UserException $exception) {
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
