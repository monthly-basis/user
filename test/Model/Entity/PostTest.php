<?php
namespace MonthlyBasis\UserTest\Model\Entity;

use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        $this->postEntity = new UserEntity\Post();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserEntity\Post::class, $this->postEntity);
    }
}
