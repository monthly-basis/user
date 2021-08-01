<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class RegisterTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('register');

        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->registerTable = new UserTable\Register($this->sql);
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

    public function test_selectWhereRegisterIdAndActivationCode()
    {
        $result = $this->registerTable->selectWhereRegisterIdAndActivationCode(
            1,
            'the-activation-code',
        );
        $this->assertEmpty($result);

        $this->registerTable->insert(
            'the-activation-code',
            'username',
            'test@example.com',
            'the-password-hash',
            '1920-07-29',
        );

        $result = $this->registerTable->selectWhereRegisterIdAndActivationCode(
            1,
            'the-activation-code',
        );
        $this->assertSame(
            [
                'username'      => 'username',
                'email'         => 'test@example.com',
                'password_hash' => 'the-password-hash',
                'birthday'      => '1920-07-29 00:00:00',
                'gender'        => null,
            ],
            $result->current(),
        );
    }

    public function test_updateSetActivatedWhereRegisterId()
    {
        $result = $this->registerTable->updateSetActivatedWhereRegisterId(
            1,
            true,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows(),
        );

        $this->registerTable->insert(
            'the-activation-code',
            'username',
            'test@example.com',
            'the-password-hash',
            '1920-07-29',
        );
        $result = $this->registerTable->updateSetActivatedWhereRegisterId(
            1,
            false,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows(),
        );
        $result = $this->registerTable->updateSetActivatedWhereRegisterId(
            1,
            true,
        );
        $this->assertSame(
            1,
            $result->getAffectedRows(),
        );
    }
}
