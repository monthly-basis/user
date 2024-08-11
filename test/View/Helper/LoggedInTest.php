<?php
namespace LeoGalleguillos\UserTest\View\Helper;

use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\View\Helper as UserHelper;
use PHPUnit\Framework\TestCase;

class EscapeTest extends TestCase
{
    protected function setUp()
    {
        $this->loggedInServiceMock = $this->createMock(
            UserService\LoggedIn::class
        );
        $this->loggedInHelper = new UserHelper\LoggedIn(
            $this->loggedInServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserHelper\LoggedIn::class,
            $this->loggedInHelper
        );
    }

    public function testInvoke()
    {
        $this->assertFalse(
            $this->loggedInHelper->__invoke()
        );

        $this->loggedInServiceMock
             ->method('isLoggedIn')
             ->willReturn(true);

        $this->assertTrue(
            $this->loggedInHelper->__invoke()
        );
    }
}
