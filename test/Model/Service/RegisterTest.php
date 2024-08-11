<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->validServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->conditionallySendServiceMock = $this->createMock(
            SimpleEmailServiceService\Send\Conditionally::class
        );
        $this->usernameExistsServiceMock = $this->createMock(
            UserService\Username\Exists::class
        );
        $this->flashValuesServiceMock = $this->createMock(
            UserService\Register\FlashValues::class
        );
        $this->registerTableMock = $this->createMock(
            UserTable\Register::class
        );
        $this->registerService = new UserService\Register(
            [],
            $this->flashServiceMock,
            $this->validServiceMock,
            $this->conditionallySendServiceMock,
            $this->usernameExistsServiceMock,
            $this->flashValuesServiceMock,
            $this->registerTableMock,
        );
    }

    public function testGetErrors()
    {
        $_POST = [];

        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username';
        $_POST['password']         = 'password';
        $_POST['confirm-password'] = 'password';
        $_POST['birthday-month']   = '08';
        $_POST['birthday-day']     = '03';
        $_POST['birthday-year']    = '2005';
        $_POST['gender']           = 'F';

        $this->validServiceMock->method('isValid')->willReturn(true);

        $this->assertSame(
            [],
            $this->registerService->getErrors()
        );
    }
}
