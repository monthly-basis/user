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

    public function test_getErrors_birthdayErrorNotOldEnough_nonEmptyArray()
    {
        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn([UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH])
             ;

        $this->toxicServiceMock
             ->expects($this->exactly(0))
             ->method('isIpAddressToxic')
             ;

        $this->emailExistsServiceMock
             ->expects($this->exactly(0))
             ->method('doesEmailExist')
             ;

        $this->usernameExistsServiceMock
             ->expects($this->exactly(0))
             ->method('doesUsernameExist')
             ;

        $this->validServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ;

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_birthdayErrorInvalidBirthday_nonEmptyArray()
    {
        $_POST                     = [];
        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username123';
        $_POST['password']         = 'password';
        $_POST['confirm-password'] = 'password';

        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn([UserService\Register\Errors\Birthday::ERROR_INVALID_BIRTHDAY])
             ;

        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(false)
             ;

        $this->emailExistsServiceMock
             ->expects($this->once())
             ->method('doesEmailExist')
             ->with('test@example.com')
             ->willReturn(false)
             ;

        $this->usernameExistsServiceMock
             ->expects($this->once())
             ->method('doesUsernameExist')
             ->with('username123')
             ->willReturn(false)
             ;

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_INVALID_BIRTHDAY],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_toxicIp_nonEmptyArray()
    {
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn([])
             ;

        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(true)
             ;

        $this->emailExistsServiceMock
             ->expects($this->exactly(0))
             ->method('doesEmailExist')
             ;

        $this->usernameExistsServiceMock
             ->expects($this->exactly(0))
             ->method('doesUsernameExist')
             ;

        $this->validServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertSame(
            [
                 'Registration is currently unavailable.'
            ],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_multipleErrors_nonEmptyArray()
    {
        $_POST                     = [];
        $_POST['email']            = 'invalid-email';
        $_POST['username']         = 'username123';
        $_POST['password']         = 'password';
        $_POST['confirm-password'] = 'password';
        $_POST['birthday-month']   = '08';
        $_POST['birthday-day']     = '03';
        $_POST['birthday-year']    = '2005';

        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn(['A birthday error.'])
             ;

        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(false)
             ;

        $this->emailExistsServiceMock
             ->expects($this->exactly(0))
             ->method('doesEmailExist')
             ;

        $this->usernameExistsServiceMock
             ->expects($this->once())
             ->method('doesUsernameExist')
             ->with('username123')
             ->willReturn(true)
             ;

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(false)
             ;

        $this->assertSame(
            [
                'Invalid email address.',
                'Username already exists.',
                'A birthday error.',
                'Invalid reCAPTCHA.',
            ],
            $this->errorsService->getErrors()
        );
    }

    public function test_getErrors_allDataValid_emptyArray()
    {
        $_POST                     = [];
        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username123';
        $_POST['password']         = 'password';
        $_POST['confirm-password'] = 'password';
        $_POST['birthday-month']   = '08';
        $_POST['birthday-day']     = '03';
        $_POST['birthday-year']    = '2005';
        $_POST['gender']           = 'F';

        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->birthdayErrorsServiceMock
             ->expects($this->once())
             ->method('getBirthdayErrors')
             ->willReturn([])
             ;

        $this->toxicServiceMock
             ->expects($this->once())
             ->method('isIpAddressToxic')
             ->with('1.2.3.4')
             ->willReturn(false)
             ;

        $this->emailExistsServiceMock
             ->expects($this->once())
             ->method('doesEmailExist')
             ->with('test@example.com')
             ->willReturn(false)
             ;

        $this->usernameExistsServiceMock
             ->expects($this->once())
             ->method('doesUsernameExist')
             ->with('username123')
             ->willReturn(false)
             ;

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
}
