<?php
namespace MonthlyBasis\UserTest\Model\Service;

use DateTime;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;
use Laminas\Db\Adapter\Adapter;

class CreateTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->adapterMock = $this->getAdapter();
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
        $birthday = new DateTime();

        $this->userTableMock
            ->expects($this->once())
            ->method('insertDeprecated')
            ->with(
                'username',
                'passwordHash',
                $birthday->format('Y-m-d H:i:s'),
                'gender',
            )
            ->willReturn(12345);
        $this->userEmailTableMock
            ->expects($this->once())
            ->method('insert')
            ->with(
                12345,
                'email',
            );

        $this->createService->create(
            $birthday,
            'email',
            'gender',
            'passwordHash',
            'username'
        );

    }
}
