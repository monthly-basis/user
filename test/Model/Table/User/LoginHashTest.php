<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use Laminas\Db\Adapter\Adapter;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class LoginHashTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userTable = new UserTable\User($this->getAdapter());
        $this->loginHashTable = new UserTable\User\LoginHash($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }

    public function testUpdateWhereUsername()
    {
        $this->assertFalse(
            $this->loginHashTable->updateWhereUsername('the-login-hash', 'username')
        );

        $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->assertTrue(
            $this->loginHashTable->updateWhereUsername('the-login-hash', 'username')
        );
    }
}
