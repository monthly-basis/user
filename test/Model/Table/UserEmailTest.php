<?php
namespace MonthlyBasis\UserTest\Model\Table;

use Exception;
use Laminas\Db\Adapter\Adapter;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['user', 'user_email']);
        $this->setForeignKeyChecks(1);

        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->userTable      = new UserTable\User($this->sql);
        $this->userEmailTable = new UserTable\UserEmail($this->getAdapter());
    }

    public function testInsert()
    {
        $this->userTable->insert(
            'username1',
            'password-hash',
            '2021-07-14 17:51:23',
        );
        $this->userEmailTable->insert(
            '1',
            'test@example.com'
        );

        $this->userTable->insert(
            'username2',
            'password-hash',
            '2021-07-14 17:51:23',
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

        $this->userTable->insert(
            'username',
            'password-hash',
            '2021-07-14 17:51:23',
        );
        $this->userEmailTable->insert(
            '1',
            'user@example.com'
        );
        $this->assertSame(
            '1',
            $this->userEmailTable->selectWhereAddress('user@example.com')->current()['user_id']
        );
    }
}
