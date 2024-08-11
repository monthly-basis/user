<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    protected function setUp(): void
    {
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->userTokenTableMock = $this->createMock(
            UserTable\UserToken::class
        );

        $this->logoutService = new UserService\Logout(
            $this->loggedInUserServiceMock,
            $this->userTokenTableMock
        );
    }

    /**
      * @runInSeparateProcess
      */
    public function testLogout()
    {
        $_SERVER['HTTP_HOST'] = 'example.com';

        $this->assertNull(
            $this->logoutService->logout()
        );
    }
}
