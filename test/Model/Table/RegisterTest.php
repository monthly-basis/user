<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class RegisterTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('register');

        $this->registerTable = new UserTable\Register($this->getAdapter());
    }

    public function testInsert()
    {
        $this->registerTable->insert(
            '1',
            'username',
            'test@example.com',
            password_hash('123', PASSWORD_DEFAULT),
            '2018-10-22',
            'M'
        );

        $this->registerTable->insert(
            '2',
            'username2',
            'test2@example.com',
            password_hash('123', PASSWORD_DEFAULT),
            '2018-10-22',
            'F'
        );

        $this->assertSame(
            2,
            $this->registerTable->selectCount()
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->registerTable->selectCount()
        );
    }
}
