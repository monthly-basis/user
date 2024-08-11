<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp()
    {
        $this->registerService = new UserService\Register();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserService\Register::class, $this->registerService);
    }

    public function testGetErrors()
    {
        $_POST['email']            = 'test@example.com';
        $_POST['username']         = 'username';
        $_POST['password']         = 'password';
        $_POST['confirm_password'] = 'password';

        $this->assertSame(
            [],
            $this->registerService->getErrors()
        );
    }
}
