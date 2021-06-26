<?php
namespace MonthlyBasis\UserTest\Controller\ResetPassword\UserId;

use Laminas\Mvc\MvcEvent;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Controller as UserController;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->fromUserIdAndCodeFactoryMock = $this->createMock(
            UserFactory\Password\Reset\FromUserIdAndCode::class
        );
        $this->logoutServiceMock = $this->createMock(
            UserService\Logout::class
        );
        $this->expiredServiceMock = $this->createMock(
            UserService\Password\Reset\Expired::class
        );
        $this->resetPasswordTableMock = $this->createMock(
            UserTable\ResetPassword::class
        );
        $this->resetPasswordAccessLogTableMock = $this->createMock(
            UserTable\ResetPasswordAccessLog::class
        );
        $this->passwordHashTableMock = $this->createMock(
            UserTable\User\PasswordHash::class
        );

        $this->codeController = new UserController\ResetPassword\UserId\Code(
            $this->flashServiceMock,
            $this->fromUserIdAndCodeFactoryMock,
            $this->logoutServiceMock,
            $this->expiredServiceMock,
            $this->resetPasswordTableMock,
            $this->resetPasswordAccessLogTableMock,
            $this->passwordHashTableMock,
        );
    }

    public function test_onDispatch()
    {
        $mvcEventMock = $this->createMock(
            MvcEvent::class
        );
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        try {
            $this->codeController->onDispatch($mvcEventMock);
            $this->fail();
        } catch (\Laminas\Mvc\Exception\DomainException $domainException) {
            $this->assertSame(
                $domainException->getMessage(),
                'Missing route matches; unsure how to retrieve action',
            );
        }
    }
}
