<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class LoginIpTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userTable = new UserTable\User($this->getAdapter());
        $this->loginIpTable = new UserTable\User\LoginIp($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }

    public function testUpdateWhereUsername()
    {
        $this->assertFalse(
            $this->loginIpTable->updateWhereUsername('123.456.789.012', 'username')
        );

        $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->assertTrue(
            $this->loginIpTable->updateWhereUsername('123.456.789.012', 'username')
        );
    }
}
