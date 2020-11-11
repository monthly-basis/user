<?php
namespace LeoGalleguillos\UserTest\Controller\ResetPassword;

use MonthlyBasis\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Controller as UserController;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->logoutServiceMock = $this->createMock(
            UserService\Logout::class
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

        $this->codeController = new UserController\ResetPassword\Code(
            $this->flashServiceMock,
            $this->logoutServiceMock,
            $this->resetPasswordTableMock,
            $this->resetPasswordAccessLogTableMock,
            $this->passwordHashTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserController\ResetPassword\Code::class,
            $this->codeController
        );
    }
}
