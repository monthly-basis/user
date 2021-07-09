<?php
namespace MonthlyBasis\UserTest\Model\Table;

use Exception;
use Laminas\Db\Adapter\Adapter;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('user_email');

        $this->userEmailTable = new UserTable\UserEmail($this->getAdapter());
    }

    public function testInsert()
    {
        $this->userEmailTable->insert(
            '1',
            'test@example.com'
        );

        $this->userEmailTable->insert(
            '2',
            'test2@example.com'
        );

        $this->assertSame(
            2,
            $this->userEmailTable->selectCount()
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->userEmailTable->selectCount()
        );
    }

    public function test_selectWhereAddress()
    {
        $result = $this->userEmailTable->selectWhereAddress('user@example.com');
        $this->assertEmpty($result);

        $this->userEmailTable->insert(
            '12345',
            'user@example.com'
        );
        $this->assertSame(
            '12345',
            $this->userEmailTable->selectWhereAddress('user@example.com')->current()['user_id']
        );
    }
}
