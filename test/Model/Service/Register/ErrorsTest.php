<?php
namespace MonthlyBasis\UserTest\Model\Service\Register;

use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->validServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->emailExistsServiceMock = $this->createMock(
            UserService\Email\Exists::class
        );
        $this->usernameExistsServiceMock = $this->createMock(
            UserService\Username\Exists::class
        );

        $this->errorsService = new UserService\Register\Errors(
            $this->validServiceMock,
            $this->emailExistsServiceMock,
            $this->usernameExistsServiceMock,
        );
    }

    public function test_getErrors()
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
            $this->errorsService->getErrors()
        );
    }
}
