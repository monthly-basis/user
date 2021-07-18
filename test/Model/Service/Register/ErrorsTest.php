<?php
namespace MonthlyBasis\UserTest\Model\Service\Register;

use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\StopForumSpam\Model\Service as StopForumSpamService;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->validServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->toxicServiceMock = $this->createMock(
            StopForumSpamService\IpAddress\Toxic::class
        );
        $this->emailExistsServiceMock = $this->createMock(
            UserService\Email\Exists::class
        );
        $this->birthdayErrorsServiceMock = $this->createMock(
            UserService\Register\Errors\Birthday::class
        );
        $this->usernameExistsServiceMock = $this->createMock(
            UserService\Username\Exists::class
        );

        $this->errorsService = new UserService\Register\Errors(
            $this->validServiceMock,
            $this->toxicServiceMock,
            $this->emailExistsServiceMock,
            $this->birthdayErrorsServiceMock,
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

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertSame(
            [],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_toxicIp_nonEmptyArray()
    {
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(true)
             ;

        $this->assertSame(
            [
                 'Registration is currently unavailable.'
            ],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_birthdayError_nonEmptyArray()
    {
        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(false)
             ;

        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn(['A birthday error.'])
             ;

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertSame(
            ['A birthday error.'],
            $this->errorsService->getErrors()
        );
    }
}
