<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class UserIdTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->userTable   = new UserTable\User($this->sql);
        $this->userIdTable = new UserTable\User\UserId($this->sql);

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }

    public function test_updateSetPasswordHashWhereUserId()
    {
        $result = $this->userIdTable->updateSetPasswordHashWhereUserId(
            'the-new-password-hash',
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->userTable->insert(
            'username',
            'password-hash',
            '1983-01-01 00:00:00',
        );

        $result = $this->userIdTable->updateSetPasswordHashWhereUserId(
            'the-new-password-hash',
            1,
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $array = $this->userTable->selectWhereUserId(1)->current();
        $this->assertSame(
            [
                'password_hash' => 'the-new-password-hash',
            ],
            [
                'password_hash'  => $array['password_hash'],
            ]
        );
    }
}
