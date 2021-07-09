<?php
namespace MonthlyBasis\UserTest\Model\Service\Email;

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

        $this->userEmailTableMock = $this->createMock(
            UserTable\UserEmail::class
        );
        $this->existsService = new UserService\Email\Exists(
            $this->userEmailTableMock
        );
    }

    public function test_doesEmailExist_emptyResult_false()
    {
        $this->assertFalse(
            $this->existsService->doesEmailExist('test@example.com')
        );

        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [],
        );
        $this->userEmailTableMock
             ->expects($this->once())
             ->method('selectWhereAddress')
             ->willReturn($resultMock)
        ;

        $this->assertFalse(
            $this->existsService->doesEmailExist('username')
        );
    }

    public function test_doesEmailExist_nonemptyResult_true()
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
        $this->userEmailTableMock
             ->expects($this->once())
             ->method('selectWhereAddress')
             ->willReturn($resultMock)
        ;

        $this->assertTrue(
            $this->existsService->doesEmailExist('test@example.com')
        );
    }
}
