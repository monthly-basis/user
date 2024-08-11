<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class LoginDateTimeTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userTable = new UserTable\User($this->getAdapter());
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
