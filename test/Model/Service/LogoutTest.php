<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    protected function setUp(): void
    {
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );

        $this->logoutService = new UserService\Logout(
            $this->loggedInUserServiceMock
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
