<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Change;

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

        $this->errorsService = new UserService\Password\Change\Errors(
            $this->validServiceMock,
        );
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function test_getErrors_missingFields_errors()
    {
        $_POST = [];

        $this->validServiceMock
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
    public function test_getErrors_newPasswordAndConfirmNewPasswordDoNotMatch_errors()
    {
        $_POST['current-password']     = 'current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'a different new password';

        $this->validServiceMock
             ->expects($this->exactly(0))
             ->method('isValid')
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
    public function test_getErrors_invalidReCaptcha_errors()
    {
        $_POST['current-password']     = 'current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(false)
             ;

        $this->assertSame(
            [
                'Invalid reCAPTCHA.',
            ],
            $this->errorsService->getErrors(),
        );
    }

    public function test_getErrors_everythingValid_emptyArray()
    {
        $_POST['current-password']     = 'current password';
        $_POST['new-password']         = 'the new password';
        $_POST['confirm-new-password'] = 'the new password';

        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $this->assertEmpty(
            $this->errorsService->getErrors()
        );
    }
}
