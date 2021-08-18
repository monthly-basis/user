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

    public function test_updateWhereUserId()
    {
        $this->assertFalse(
            $this->loginIpTable->updateWhereUserId(
                '1.2.3.4',
                1,
            )
        );

        $this->userTable->insert(
            'username',
            'password-hash',
            '1983-01-01 00:00:00',
        );

        $this->assertTrue(
            $this->loginIpTable->updateWhereUserId(
                '1.2.3.4',
                1,
            )
        );

        $array = $this->userTable->selectWhereUserId(1);
        $this->assertSame(
            '1.2.3.4',
            $array['login_ip'],
        );
    }
}
