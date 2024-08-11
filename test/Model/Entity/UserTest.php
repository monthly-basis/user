<?php
use LeoGalleguillos\User\Model\Entity\User as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
    }

    public function testInitialize()
    {
        $helloWorldEntity = new UserEntity();
        $this->assertInstanceOf(UserEntity::class, $helloWorldEntity);
    }
}
