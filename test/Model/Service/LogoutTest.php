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

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Logout::class,
            $this->logoutService
        );
    }

    public function testLogout()
    {
        $this->assertNull(
            $this->logoutService->logout()
        );
    }
}
