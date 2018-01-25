<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userFactory = new UserFactory\User(
            $this->userTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserFactory\User::class,
            $this->userFactory
        );
    }
}
