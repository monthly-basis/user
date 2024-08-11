<?php
namespace MonthlyBasis\UserTest\Model\Service\Password;

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
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
        $this->emailAddress = 'test@example.com';
        $this->websiteName = 'My Test Website';
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->generateCodeServiceMock = $this->createMock(
            UserService\Password\Reset\GenerateCode::class
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
            $this->emailAddress,
            $this->websiteName,
            $this->userFactoryMock,
            $this->generateCodeServiceMock,
            $this->urlServiceMock,
            $this->resetPasswordTableMock,
            $this->userEmailTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Password\Reset::class,
            $this->resetService
        );
    }
}
