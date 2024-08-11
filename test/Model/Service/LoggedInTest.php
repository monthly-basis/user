<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class LoggedInTest extends TestCase
{
    protected function setUp()
    {
        $this->loggedInService = new UserService\LoggedIn();
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

        $_SESSION['username'] = 'Testing123';

        $this->assertTrue(
            $this->loggedInService->isLoggedIn()
        );
    }
}
