<?php
namespace MonthlyBasis\UserTest\Model\Service\Username;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ExistsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();

        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->existsService = new UserService\Username\Exists(
            $this->userTableMock
        );
    }

    public function test_doesUsernameExist_emptyResult_false()
    {
        $this->assertFalse(
            $this->existsService->doesUsernameExist('username')
        );

        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [],
        );
        $this->userTableMock
             ->method('selectWhereUsername')
             ->willReturn($resultMock)
        ;

        $this->assertFalse(
            $this->existsService->doesUsernameExist('username')
        );
    }

    public function test_doesUsernameExist_nonemptyResult_true()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'user_id'  => '123456',
                    'username' => 'username',
                ],
            ],
        );
        $this->userTableMock
             ->method('selectWhereUsername')
             ->willReturn($resultMock)
        ;

        $this->assertTrue(
            $this->existsService->doesUsernameExist('username')
        );
    }
}
