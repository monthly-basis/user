<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use DateTime;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;
use Laminas\Db\Adapter\Adapter;

class CreateTest extends TestCase
{
    protected function setUp(): void
    {
        $this->adapterMock = $this->createMock(
            Adapter::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userEmailTableMock = $this->createMock(
            UserTable\UserEmail::class
        );

        $this->createService = new UserService\Create(
            $this->adapterMock,
            $this->userFactoryMock,
            $this->userTableMock,
            $this->userEmailTableMock
        );
    }

    public function testCreate()
    {
        // I'll have to complete this unit test later.
        $this->assertTrue(true);

        /*
        $this->createService->create(
            new DateTime(),
            'email',
            'gender',
            'passwordHash',
            'username'
        );
         */
    }
}
