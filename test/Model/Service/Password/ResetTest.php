<?php
namespace MonthlyBasis\UserTest\Model\Service\Password;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
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
        $this->emailAddress = 'test@example.com';
        $this->websiteName = 'My Test Website';
        $this->randomServiceMock = $this->createMock(
            StringService\Random::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->urlServiceMock = $this->createMock(
            UserService\Password\Reset\Url::class
        );
        $this->resetPasswordTableMock = $this->createMock(
            UserTable\ResetPassword::class
        );
        $this->userEmailTableMock = $this->createMock(
            UserTable\UserEmail::class
        );

        $this->resetService = new UserService\Password\Reset(
            $this->flashServiceMock,
            $this->validServiceMock,
            $this->conditionallySendServiceMock,
            $this->emailAddress,
            $this->websiteName,
            $this->randomServiceMock,
            $this->userFactoryMock,
            $this->urlServiceMock,
            $this->resetPasswordTableMock,
            $this->userEmailTableMock
        );
    }

    public function test_reset_invalidEmail_errorMessage()
    {
        try {
            $this->resetService->reset();
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Errors with form.',
                $exception->getMessage()
            );
        }
    }
}
