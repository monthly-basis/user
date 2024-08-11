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

    public function test_getErrors_invalidReCaptcha_errors()
    {
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
