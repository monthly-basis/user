<?php
use LeoGalleguillos\User\Model\Entity\User as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
        $this->helloWorldEntity = new UserEntity();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserEntity::class, $this->helloWorldEntity);
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('userId', $this->helloWorldEntity);
        $this->assertObjectHasAttribute('username', $this->helloWorldEntity);
        $this->assertObjectHasAttribute('firstName', $this->helloWorldEntity);
        $this->assertObjectHasAttribute('lastName', $this->helloWorldEntity);
    }
}
