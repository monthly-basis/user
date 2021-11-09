<?php
namespace MonthlyBasis\UserTest\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\User\Model\Db as UserDb;

class UserTokenTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->userTokenTable = new UserTable\UserToken($this->sql);

        $this->dropAndCreateTable('user_token');
    }

    public function test_getColumns_result()
    {
        $select = $this->sql
             ->select()
             ->columns($this->userTokenTable->getColumns())
             ->from('user_token');
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        $this->assertInstanceOf(
            Result::class,
            $result
        );
    }

    public function test_insert()
    {
        $result = $this->userTokenTable->insert(
            12345,
            'the-login-token',
            'the-https-token',
            new DateTime('2021-01-19 07:30:13'),
        );

        $this->assertSame(
            '1',
            $result->getGeneratedValue()
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->userTokenTable->insert(
            12345,
            'another-login-token',
            'another-https-token',
            new DateTime('2021-01-19 07:30:13'),
        );

        $this->assertSame(
            '2',
            $result->getGeneratedValue()
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
