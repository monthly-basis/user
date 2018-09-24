<?php
namespace LeoGalleguillos\UserTest\Controller;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Controller as UserController;
use LeoGalleguillos\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ResetPasswordTest extends TestCase
{
    protected function setUp()
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->resetServiceMock = $this->createMock(
            UserService\Password\Reset::class
        );

        $this->resetPasswordController = new UserController\ResetPassword(
            $this->flashServiceMock,
            $this->resetServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserController\ResetPassword::class,
            $this->resetPasswordController
        );
    }
}
