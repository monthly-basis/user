<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp()
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->validServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->flashValuesServiceMock = $this->createMock(
            UserService\Register\FlashValues::class
        );
        $this->registerService = new UserService\Register(
            $this->flashServiceMock,
            $this->validServiceMock,
            $this->flashValuesServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Register::class,
            $this->registerService
        );
    }

    public function testGetErrors()
    {
        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username';
        $_POST['password']         = 'password';
        $_POST['confirm_password'] = 'password';

        $this->validServiceMock->method('isValid')->willReturn(true);

        $this->assertSame(
            [],
            $this->registerService->getErrors()
        );
    }

    public function testIsFormValidIfNotSetFlashErrors()
    {
        $_POST['email']            = 'bad email';
        $_POST['username']         = 'username';
        $_POST['password']         = 'password';
        $_POST['confirm_password'] = 'password2';

        $this->assertFalse(
            $this->registerService->isFormValidIfNotSetFlashErrors()
        );
    }
}
