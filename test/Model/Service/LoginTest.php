<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected function setUp()
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loginHashTableMock = $this->createMock(
            UserTable\User\LoginHash::class
        );
        $this->loginService = new UserService\Login(
            $this->userTableMock,
            $this->loginHashTableMock
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

        $_POST['password'] = 'correct password';
        $this->assertTrue(
            $this->loginService->login()
        );
    }
}
