<?php
namespace LeoGalleguillos\UserTest\Model\Entity;

use LeoGalleguillos\User\Model\Entity\User as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
        $this->userEntity = new UserEntity();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserEntity::class, $this->userEntity);
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('userId', $this->userEntity);
        $this->assertObjectHasAttribute('username', $this->userEntity);
        $this->assertObjectHasAttribute('firstName', $this->userEntity);
        $this->assertObjectHasAttribute('lastName', $this->userEntity);
    }

    public function testGettersAndSetters()
    {
        $userId = 123;
        $this->userEntity->setUserId($userId);
        $this->assertSame(
            $userId,
            $this->userEntity->getUserId()
        );

        $username = 'myusername';
        $this->userEntity->setUsername($username);
        $this->assertSame(
            $username,
            $this->userEntity->getUsername()
        );

        $welcomeMessage = 'Welcome to my page.';
        $this->userEntity->setWelcomeMessage($welcomeMessage);
        $this->assertSame(
            $welcomeMessage,
            $this->userEntity->getWelcomeMessage()
        );
    }
}
