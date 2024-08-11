<?php
namespace LeoGalleguillos\LoginLogTest\Model\Table;

use LeoGalleguillos\User\Model\Table as UserTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class LoginLogTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->loginLogTable = new UserTable\LoginLog($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropTable('login_log');
        $this->createTable('login_log');
        $this->setForeignKeyChecks1();
    }

    public function testInsertAndSelectCount()
    {
        $this->assertSame(
            0,
            $this->loginLogTable->selectCount()
        );
        $this->assertSame(
            1,
            $this->loginLogTable->insert('255.255.255.255', 0)
        );
        $this->assertSame(
            2,
            $this->loginLogTable->insert('123.123.123.123', 1)
        );
        $this->assertSame(
            2,
            $this->loginLogTable->selectCount()
        );
    }

    public function testselectCountWhereIpSuccessCreated()
    {
        $this->assertSame(
            0,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 1);
        $this->assertSame(
            0,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->assertSame(
            3,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 1);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->assertSame(
            4,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
    }
}
