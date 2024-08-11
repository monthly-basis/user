<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    protected function setUp(): void
    {
        $this->logoutService = new UserService\Logout();
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
