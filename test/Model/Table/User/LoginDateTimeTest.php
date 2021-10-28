<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class LoginDateTimeTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );
        $this->userTable = new UserTable\User($this->sql);
        $this->loginDateTimeTable = new UserTable\User\LoginDateTime($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }

    public function testUpdateSetToNowWhereUsername()
    {
        $this->assertFalse(
            $this->loginDateTimeTable->updateSetToNowWhereUserId(1)
        );

        $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->assertTrue(
            $this->loginDateTimeTable->updateSetToNowWhereUserId(1)
        );
    }
}
