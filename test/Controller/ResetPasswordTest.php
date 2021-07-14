<?php
namespace MonthlyBasis\UserTest\Controller;

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\User\Controller as UserController;
use MonthlyBasis\User\Model\Service as UserService;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ResetPasswordTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->resetServiceMock = $this->createMock(
            UserService\Password\Reset::class
        );
        $this->urlServiceMock = $this->createMock(
            UserService\Url::class
        );

        $this->resetPasswordController = new UserController\ResetPassword(
            $this->flashServiceMock,
            $this->loggedInUserServiceMock,
            $this->resetServiceMock,
            $this->urlServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserController\ResetPassword::class,
            $this->resetPasswordController
        );
    }
}
