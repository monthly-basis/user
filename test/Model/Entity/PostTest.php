<?php
namespace LeoGalleguillos\UserTest\Model\Entity;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp()
    {
        $this->postEntity = new UserEntity\Post();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserEntity\Post::class, $this->postEntity);
    }
}
