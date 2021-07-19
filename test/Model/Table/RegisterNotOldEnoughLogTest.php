<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class RegisterNotOldEnoughLogTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('register_not_old_enough_log');

        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->registerNotOldEnoughLogTable = new UserTable\RegisterNotOldEnoughLog(
            $this->sql
        );
    }

    public function test_insert()
    {
        $result = $this->registerNotOldEnoughLogTable->insert('1.2.3.4');
        $this->assertSame(
            '1',
            $result->getGeneratedValue()
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
