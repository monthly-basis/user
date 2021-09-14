<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Change;

use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->reCaptchaValidServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->passwordValidServiceMock = $this->createMock(
            UserService\Password\Valid::class
        );

        $this->errorsService = new UserService\Password\Change\Errors(
            $this->reCaptchaValidServiceMock,
            $this->loggedInUserServiceMock,
            $this->passwordValidServiceMock
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_missingFields_errors()
    {
        $_POST = [];

        $this->loggedInUserServiceMock
             ->expects($this->exactly(0))
             ->method('getLoggedInUser')
             ;
        $this->passwordValidServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ;
        $this->reCaptchaValidServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ;

        $this->assertSame(
            [
                'Missing fields.',
            ],
            $this->errorsService->getErrors(),
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_invalidReCaptcha_errors()
    {
        $_POST['current-password']     = 'correct current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';
        $_POST['https-token']          = 'the https token';

        $this->reCaptchaValidServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(false)
             ;
        $this->passwordValidServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ;

        $this->assertSame(
            [
                'Invalid reCAPTCHA.',
            ],
            $this->errorsService->getErrors(),
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_invalidHttpsToken_errors()
    {
        $_POST['current-password']     = 'correct current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';
        $_POST['https-token']          = 'invalid https token';

        $this->reCaptchaValidServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertSame(
            [
                'Invalid credentials.',
            ],
            $this->errorsService->getErrors(),
        );

        $this->passwordValidServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
             ;
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_invalidPassword_errors()
    {
        $_POST['current-password']     = 'invalid current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';
        $_POST['https-token']          = 'the https token';

        $this->reCaptchaValidServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->willReturn(true)
             ;
        $this->loggedInUserServiceMock
             ->expects($this->exactly(1))
             ->method('getLoggedInUser')
             ->willReturn(
                 (new UserEntity\User())
                     ->setHttpsToken('the https token')
                     ->setPasswordHash('the password hash')
             )
             ;
        $this->passwordValidServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->with(
                 'invalid current password',
                 'the password hash'
             )
             ->willReturn(false)
             ;

        $this->assertSame(
            [
                'Current password is invalid.',
            ],
            $this->errorsService->getErrors(),
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_newPasswordAndConfirmNewPasswordDoNotMatch_errors()
    {
        $_POST['current-password']     = 'correct current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'a different new password';
        $_POST['https-token']          = 'the https token';

        $this->reCaptchaValidServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->willReturn(true)
             ;
        $this->loggedInUserServiceMock
             ->expects($this->exactly(1))
             ->method('getLoggedInUser')
             ->willReturn(
                 (new UserEntity\User())
                     ->setHttpsToken('the https token')
                     ->setPasswordHash('the password hash')
             )
             ;
        $this->passwordValidServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->with(
                 'correct current password',
                 'the password hash'
             )
             ->willReturn(true)
             ;

        $this->assertSame(
            [
                'New password and confirm new password do not match.',
            ],
            $this->errorsService->getErrors(),
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_everythingValid_emptyArray()
    {
        $_POST['current-password']     = 'correct current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';
        $_POST['https-token']          = 'the https token';

        $this->loggedInUserServiceMock
             ->expects($this->exactly(1))
             ->method('getLoggedInUser')
             ->willReturn(
                 (new UserEntity\User())
                     ->setHttpsToken('the https token')
                     ->setPasswordHash('the password hash')
             )
             ;
        $this->passwordValidServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->with(
                 'correct current password',
                 'the password hash'
             )
             ->willReturn(true)
             ;
        $this->reCaptchaValidServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertEmpty(
            $this->errorsService->getErrors()
        );
    }
}
