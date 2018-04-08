<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected function setUp()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REMOTE_ADDR'] = '123.123.123.123';

        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->validReCaptchaServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->reCaptchaRequiredServiceMock = $this->createMock(
            UserService\Login\ReCaptchaRequired::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loginDateTimeTableMock = $this->createMock(
            UserTable\User\LoginDateTime::class
        );
        $this->loginHashTableMock = $this->createMock(
            UserTable\User\LoginHash::class
        );
        $this->loginIpTableMock = $this->createMock(
            UserTable\User\LoginIp::class
        );
        $this->loginService = new UserService\Login(
            $this->flashServiceMock,
            $this->validReCaptchaServiceMock,
            $this->userFactoryMock,
            $this->reCaptchaRequiredServiceMock,
            $this->userTableMock,
            $this->loginDateTimeTableMock,
            $this->loginHashTableMock,
            $this->loginIpTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Login::class,
            $this->loginService
        );
    }

    public function testLogin()
    {
        unset($_POST['username']);
        unset($_POST['password']);
        $this->assertFalse(
            $this->loginService->login()
        );

        $_POST['username'] = 'username';
        $_POST['password'] = 'incorrect password';
        $this->assertFalse(
            $this->loginService->login()
        );

        $array = [
            'username'      => 'username',
            'password_hash' => '$2y$10$/O2EsOXRypBlEEuEVNHBa.Zd2p6jM3K3IkG3HzfaulFxArpbZC2y2',
        ];
        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $array
        );
        $this->assertFalse(
            $this->loginService->login()
        );

        $userEntity = new UserEntity\User();
        $userEntity->setUserId(123)
                   ->setUsername('username');
        $this->userFactoryMock->method('buildFromUsername')->willReturn(
            $userEntity
        );
        $_POST['password']    = 'correct password';
        $this->assertTrue(
            $this->loginService->login()
        );
    }
}
