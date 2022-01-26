<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class ResetPasswordAccessLogTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('reset_password_access_log');

        $this->resetPasswordAccessLogTable = new UserTable\ResetPasswordAccessLog(
            $this->getAdapter()
        );
    }

    public function test_insert()
    {
        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '1.2.3.4',
            1,
        );

        $this->assertSame(
            $resetPasswordAccessLogId,
            1,
        );

        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '5.6.7.8',
            0,
        );

        $this->assertSame(
            $resetPasswordAccessLogId,
            2,
        );
    }

    public function test_selectCountWhereIpAndValidAndCreatedGreaterThan()
    {
        $dateTimeString = date('Y-m-d H:i:s', strtotime('-7 days'));

        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            0,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            0,
        );

        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '1.2.3.4',
            1,
        );
        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            0,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            0,
        );

        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '1.2.3.4',
            0,
        );
        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            0,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            1,
        );

        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '1.2.3.4',
            0,
        );
        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            0,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            2,
        );

        $resetPasswordAccessLogId = $this->resetPasswordAccessLogTable->insert(
            '5.6.7.8',
            0,
        );
        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            0,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            2,
        );

        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            1,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            1,
        );

        $dateTimeString = date('Y-m-d H:i:s', strtotime('now'));
        $count = $this->resetPasswordAccessLogTable->selectCountWhereIpAndValidAndCreatedGreaterThan(
            '1.2.3.4',
            1,
            $dateTimeString,
        );

        $this->assertSame(
            $count,
            0,
        );
    }
}
