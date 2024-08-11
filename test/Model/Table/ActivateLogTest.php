<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class ActivateLogTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('activate_log');

        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->activateLogTable = new UserTable\ActivateLog(
            $this->sql
        );
    }

    public function test_insert()
    {
        $result = $this->activateLogTable->insert('1.2.3.4', true);
        $this->assertSame(
            '1',
            $result->getGeneratedValue()
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '1.2.3.4',
            true,
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '1',
            ],
            $array,
        );
    }

    public function test_selectCountWhereIpAddressAndSuccess()
    {
        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '5.6.7.8',
            false,
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '0',
            ],
            $array,
        );

        $this->activateLogTable->insert('5.6.7.8', false);

        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '5.6.7.8',
            true,
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '0',
            ],
            $array,
        );

        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '5.6.7.8',
            false,
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '1',
            ],
            $array,
        );

        $this->activateLogTable->insert('1.2.3.4', true);
        $this->activateLogTable->insert('1.2.3.4', true);
        $this->activateLogTable->insert('1.2.3.4', true);
        $this->activateLogTable->insert('1.2.3.4', false);
        $this->activateLogTable->insert('1.2.3.4', true);
        $this->activateLogTable->insert('1.2.3.4', true);
        $this->activateLogTable->insert('1.2.3.4', false);

        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '1.2.3.4',
            true,
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '5',
            ],
            $array,
        );

        $result = $this->activateLogTable->selectCountWhereIpAddressAndSuccess(
            '1.2.3.4',
            false
        );
        $array = $result->current();
        $this->assertSame(
            [
                'COUNT(*)' => '2',
            ],
            $array,
        );
    }
}
