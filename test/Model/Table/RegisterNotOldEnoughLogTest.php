<?php
namespace MonthlyBasis\UserTest\Model\Table;

use DateTime;
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

        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            '1.2.3.4',
            (new DateTime())->modify('-1 day')
        );
        $array = $result->current();
        $this->assertSame(
            [
                'register_not_old_enough_log_id' => '1',
                'ip_address'                     => '1.2.3.4',
            ],
            [
                'register_not_old_enough_log_id' => $array['register_not_old_enough_log_id'],
                'ip_address'                     => $array['ip_address']
            ]
        );
    }

    public function test_selectWhereIpAddressAndCreatedGreaterThan()
    {
        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            '5.6.7.8',
            (new DateTime())->modify('-1 day')
        );
        $this->assertEmpty($result);

        $this->registerNotOldEnoughLogTable->insert('5.6.7.8');

        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            '5.6.7.8',
            (new DateTime())->modify('-1 day')
        );
        $array = $result->current();
        $this->assertSame(
            [
                'register_not_old_enough_log_id' => '1',
                'ip_address'                     => '5.6.7.8',
            ],
            [
                'register_not_old_enough_log_id' => $array['register_not_old_enough_log_id'],
                'ip_address'                     => $array['ip_address']
            ]
        );

        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            '1.2.3.4',
            (new DateTime())->modify('-1 day')
        );
        $this->assertEmpty($result);

        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            '5.6.7.8',
            (new DateTime())->modify('+1 day')
        );
        $this->assertEmpty($result);
    }
}
