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

    public function test_updateWhereUserId()
    {
        $this->assertFalse(
            $this->loginHashTable->updateWhereUserId(
                'the-login-hash',
                1,
            )
        );

        $this->userTable->insert(
            'username',
            'password-hash',
            '1983-01-01 00:00:00',
        );

        $this->assertTrue(
            $this->loginHashTable->updateWhereUserId(
                'the-login-hash',
                1,
            )
        );

        $array = $this->userTable->selectWhereUserId(1);
        $this->assertSame(
            'the-login-hash',
            $array['login_hash'],
        );
    }
}
