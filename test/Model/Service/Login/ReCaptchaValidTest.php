<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ReCaptchaValidTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '123.123.123.123';

        $this->loginLogTableMock = $this->createMock(
            UserTable\LoginLog::class
        );
        $this->reCaptchaRequiredService = new UserService\Login\ReCaptchaRequired(
            $this->loginLogTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Login\ReCaptchaRequired::class,
            $this->reCaptchaRequiredService
        );
    }

    public function testIsReCaptchaRequired()
    {
        $this->loginLogTableMock->method('selectCountWhereIpSuccessCreated')->will(
            $this->onConsecutiveCalls(1, 2, 3, 4)
        );
        $this->assertFalse(
            $this->reCaptchaRequiredService->isReCaptchaRequired()
        );
        $this->assertFalse(
            $this->reCaptchaRequiredService->isReCaptchaRequired()
        );
        $this->assertTrue(
            $this->reCaptchaRequiredService->isReCaptchaRequired()
        );
        $this->assertTrue(
            $this->reCaptchaRequiredService->isReCaptchaRequired()
        );
    }
}
