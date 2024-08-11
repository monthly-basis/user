<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        $config = [
            'email-address' => 'test@example.com',
            'website-name'  => 'My Website Name',
            'register'      => [
                'required' => [
                    'email' => false,
                    're-captcha' => true,
                ],
            ],
        ];
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->conditionallySendServiceMock = $this->createMock(
            SimpleEmailServiceService\Send\Conditionally::class
        );
        $this->randomServiceMock = $this->createMock(
            StringService\Random::class
        );
        $this->errorsServiceMock = $this->createMock(
            UserService\Register\Errors::class
        );
        $this->flashValuesServiceMock = $this->createMock(
            UserService\Register\FlashValues::class
        );
        $this->registerTableMock = $this->createMock(
            UserTable\Register::class
        );
        $this->registerService = new UserService\Register(
            $config,
            $this->flashServiceMock,
            $this->conditionallySendServiceMock,
            $this->randomServiceMock,
            $this->errorsServiceMock,
            $this->flashValuesServiceMock,
            $this->registerTableMock,
        );
    }

    public function test_register()
    {
        $_POST = [];

        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username';
        $_POST['password']         = 'password';
        $_POST['confirm-password'] = 'password';
        $_POST['birthday-month']   = '08';
        $_POST['birthday-day']     = '03';
        $_POST['birthday-year']    = '2005';
        $_POST['gender']           = 'F';

        $this->flashValuesServiceMock
             ->expects($this->once())
             ->method('setFlashValues')
             ;
        $this->errorsServiceMock
             ->expects($this->once())
             ->method('getErrors')
             ->willReturn([])
             ;
        $this->randomServiceMock
             ->expects($this->once())
             ->method('getRandomString')
             ->with(31)
             ->willReturn('abcdefghijklmnopqrstuvwxzy123456')
             ;

        $this->registerService->register();
    }
}
