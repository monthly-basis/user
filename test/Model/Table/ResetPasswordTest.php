<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class ResetPasswordTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('reset_password');
        $this->resetPasswordTable = new UserTable\ResetPassword(
            $this->getAdapter()
        );
    }

    public function test_selectWhereUserIdAndCode()
    {
        $result = $this->resetPasswordTable->selectWhereUserIdAndCode(
            12345,
            'the-code',
        );
        $this->assertEmpty($result);

        $this->resetPasswordTable->insert(
            12345,
            'the-code',
        );
        $result = $this->resetPasswordTable->selectWhereUserIdAndCode(
            12345,
            'the-code',
        );
        $array = $result->current();
        $this->assertSame('1', $array['reset_password_id']);
        $this->assertSame('12345', $array['user_id']);
        $this->assertSame('the-code', $array['code']);
        $this->assertNotNull($array['created']);
        $this->assertNull($array['accessed']);
        $this->assertNull($array['used']);
    }

    public function test_updateSetAccessedToUtcTimestampWhereUserIdAndCode()
    {
        $result = $this->resetPasswordTable->updateSetAccessedToUtcTimestampWhereUserIdAndCode(
            12345,
            'the-code',
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->resetPasswordTable->insert(
            12345,
            'the-code',
        );
        $result = $this->resetPasswordTable->updateSetAccessedToUtcTimestampWhereUserIdAndCode(
            12345,
            'the-code',
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->resetPasswordTable->selectWhereUserIdAndCode(
            12345,
            'the-code',
        );
        $array = $result->current();
        $this->assertNotNull($array['accessed']);
    }
}
